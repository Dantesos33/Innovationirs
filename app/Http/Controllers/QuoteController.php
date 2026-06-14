<?php
namespace App\Http\Controllers;

use App\Mail\NewQuoteNotification;
use App\Models\EquipmentModel;
use App\Models\Make;
use App\Models\QuoteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class QuoteController extends Controller
{
    public function create()
    {
        $makes = Make::active()->orderBy('name')->get();
        return view('pages.quote', compact('makes'));
    }

    public function store(Request $request): mixed
    {
        $validated = $request->validate([
            'first_name'       => 'nullable|string|max:100', // nullable — hero form uses email fallback
            'last_name'        => 'nullable|string|max:100',
            'email'            => 'required|email|max:255',
            'phone'            => 'required|string|max:30',
            'company'          => 'nullable|string|max:150',
            'make_id'          => 'nullable|exists:makes,id',
            'model_id'         => 'nullable|exists:equipment_models,id',
            'year'             => 'nullable|string|max:10',
            'serial_number'    => 'nullable|string|max:100',
            'part_number'      => 'nullable|string|max:100',
            'oem_part_number'  => 'nullable|string|max:100',
            'part_description' => 'required|string|max:2000',
            'quantity'         => 'nullable|integer|min:1|max:9999',
            'condition'        => 'nullable|in:new,used,rebuilt,any',
            'urgency'          => 'nullable|in:standard,urgent,emergency',
            'notes'            => 'nullable|string|max:2000',
            'source'           => 'nullable|string|max:100',
        ]);

        // ── Resolve make_id → make name (text column in DB) ───────────────
        $makeName  = null;
        $modelName = null;

        if (! empty($validated['make_id'])) {
            $make     = Make::find($validated['make_id']);
            $makeName = $make?->name;
        }

        // ── Resolve model_id → model name (text column in DB) ─────────────
        if (! empty($validated['model_id'])) {
            $model     = EquipmentModel::find($validated['model_id']);
            $modelName = $model?->name;
            // Append year to model name if provided
            if ($modelName && ! empty($validated['year'])) {
                $modelName = $validated['year'] . ' ' . $modelName;
            }
        } elseif (! empty($validated['year'])) {
            // Year with no model — store year alone in model field
            $modelName = $validated['year'];
        }

        // ── Build notes field — append urgency, condition, oem#, source ───
        $noteParts = [];

        if (! empty($validated['notes'])) {
            $noteParts[] = $validated['notes'];
        }
        if (! empty($validated['urgency']) && $validated['urgency'] !== 'standard') {
            $urgencyLabel = match ($validated['urgency']) {
                'urgent'    => 'URGENT (48hr response needed)',
                'emergency' => 'EMERGENCY — ASAP response needed',
                default     => ucfirst($validated['urgency']),
            };
            $noteParts[] = 'Urgency: ' . $urgencyLabel;
        }
        if (! empty($validated['condition']) && $validated['condition'] !== 'any') {
            $noteParts[] = 'Preferred condition: ' . ucfirst($validated['condition']);
        }
        if (! empty($validated['oem_part_number'])) {
            $noteParts[] = 'OEM / Cross-reference #: ' . $validated['oem_part_number'];
        }
        if (! empty($validated['source']) && $validated['source'] !== 'quote_page') {
            $noteParts[] = 'Source: ' . $validated['source'];
        }

        $combinedNotes = implode("\n", $noteParts) ?: null;

        // ── Resolve first/last name — hero form may not collect these ─────────
        // Fall back to extracting from email when the quick-quote form is used
        $firstName = ! empty($validated['first_name'])
            ? $validated['first_name']
            : ucfirst(explode('.', explode('@', $validated['email'])[0])[0]);

        $lastName = ! empty($validated['last_name'])
            ? $validated['last_name']
            : (explode('.', explode('@', $validated['email'])[0])[1] ?? '');

        // ── Create the quote with only real DB columns ─────────────────────────
        $quote = QuoteRequest::create([
            'first_name'       => $firstName,
            'last_name'        => $lastName,
            'email'            => $validated['email'],
            'phone'            => $validated['phone'],
            'company'          => $validated['company'] ?? null,
            'make'             => $makeName,  // text column
            'model'            => $modelName, // text column
            'serial_number'    => $validated['serial_number'] ?? null,
            'part_number'      => $validated['part_number'] ?? null,
            'part_description' => $validated['part_description'],
            'quantity'         => $validated['quantity'] ?? 1,
            'notes'            => $combinedNotes,
            'status'           => 'new',
            'ip_address'       => $request->ip(),
            'referrer_url'     => $request->header('referer'),
            'utm_source'       => $request->get('utm_source'),
            'utm_medium'       => $request->get('utm_medium'),
            'utm_campaign'     => $request->get('utm_campaign'),
        ]);

        // ── Send admin notification email ──────────────────────────────────
        // config('amsparts.admin_email') is the correct key — defined in config/amsparts.php
        $notifyEmail = config('amsparts.admin_email');
        if ($notifyEmail) {
            try {
                Mail::to($notifyEmail)->send(new NewQuoteNotification($quote));
            } catch (\Throwable $e) {
                Log::error('Quote notification email failed', [
                    'quote_id' => $quote->id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Your quote request has been submitted. Our team will contact you shortly!',
                'quote_id' => $quote->id,
            ]);
        }

        // redirect()->back() works for both the hero quick-quote form (stays on homepage)
        // and the dedicated /quote page (stays on /quote)
        return redirect()->back()
            ->with('success', 'Your quote request has been submitted! Our parts specialists will contact you within 1 business day.');
    }

    // AJAX: Load models for a given make (used by quote form cascade)
    public function modelsByMake(int $makeId): JsonResponse
    {
        $models = EquipmentModel::where('make_id', $makeId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'year_start', 'year_end']);

        return response()->json(['models' => $models]);
    }
}
