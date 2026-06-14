<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerPosting;
use Illuminate\Http\Request;

class AdminCareersController extends Controller
{
    // ─── Index ──────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = CareerPosting::withCount('applications')->latest('posted_at');

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('department', 'like', "%{$term}%")
                    ->orWhere('location', 'like', "%{$term}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                    });
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('expires_at', '<=', now());
            }
        }

        $careers     = $query->paginate(20)->withQueryString();
        $departments = CareerPosting::select('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('admin.careers.index', compact('careers', 'departments'));
    }

    // ─── Create ─────────────────────────────────────────────────────────────

    public function create()
    {
        return view('admin.careers.create');
    }

    // ─── Store ──────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'department'   => 'required|string|max:150',
            'location'     => 'required|string|max:255',
            'job_type'     => 'required|in:full_time,part_time,contract,internship,temporary',
            'description'  => 'required|string',
            'requirements' => 'nullable|string',
            'benefits'     => 'nullable|string',
            'salary_range' => 'nullable|string|max:150',
            'apply_email'  => 'nullable|email|max:255',
            'is_active'    => 'boolean',
            'posted_at'    => 'nullable|date',
            'expires_at'   => 'nullable|date|after:posted_at',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['posted_at'] = $data['posted_at'] ?? now();

        CareerPosting::create($data);

        return redirect()
            ->route('admin.careers.index')
            ->with('success', 'Job posting created successfully.');
    }

    // ─── Show ────────────────────────────────────────────────────────────────

    public function show(CareerPosting $career)
    {
        return view('admin.careers.show', compact('career'));
    }

    // ─── Edit ────────────────────────────────────────────────────────────────

    public function edit(CareerPosting $career)
    {
        return view('admin.careers.edit', compact('career'));
    }

    // ─── Update ─────────────────────────────────────────────────────────────

    public function update(Request $request, CareerPosting $career)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'department'   => 'required|string|max:150',
            'location'     => 'required|string|max:255',
            'job_type'     => 'required|in:full_time,part_time,contract,internship,temporary',
            'description'  => 'required|string',
            'requirements' => 'nullable|string',
            'benefits'     => 'nullable|string',
            'salary_range' => 'nullable|string|max:150',
            'apply_email'  => 'nullable|email|max:255',
            'is_active'    => 'boolean',
            'posted_at'    => 'nullable|date',
            'expires_at'   => 'nullable|date|after:posted_at',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $career->update($data);

        return redirect()
            ->route('admin.careers.index')
            ->with('success', 'Job posting updated successfully.');
    }

    // ─── Destroy ─────────────────────────────────────────────────────────────

    public function destroy(CareerPosting $career)
    {
        $career->delete();

        return redirect()
            ->route('admin.careers.index')
            ->with('success', 'Job posting deleted.');
    }

    // ─── Toggle Active ────────────────────────────────────────────────────────

    public function toggle(CareerPosting $career)
    {
        $career->update(['is_active' => ! $career->is_active]);

        $status = $career->is_active ? 'activated' : 'deactivated';

        return response()->json([
            'success'   => true,
            'is_active' => $career->is_active,
            'message'   => "Job posting {$status}.",
        ]);
    }
}
