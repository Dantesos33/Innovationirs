<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeavyDutyTool;
use App\Models\HeavyDutyToolImage;
use App\Models\MediaLibrary;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminHeavyDutyToolsController extends Controller
{
    public function __construct(
        protected MediaUploadService $mediaService
    ) {}

    // ─── Index ────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = HeavyDutyTool::with('primaryImage');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        $tools  = $query->latest()->paginate(25)->withQueryString();
        $brands = HeavyDutyTool::whereNotNull('brand')->distinct()->orderBy('brand')->pluck('brand');

        return view('admin.heavy-duty-tools.index', compact('tools', 'brands'));
    }

    // ─── Create ───────────────────────────────────────────────────────

    public function create()
    {
        $tool = null;
        return view('admin.heavy-duty-tools.form', compact('tool'));
    }

    // ─── Store ────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $this->validateTool($request);

        // Auto-generate slug
        if (empty($validated['slug'])) {
            $validated['slug'] = $this->uniqueSlug($validated['name']);
        }

        // Primary image upload
        if ($request->hasFile('primary_image')) {
            $media = $this->mediaService->upload($request->file('primary_image'), 'tools');
            $validated['primary_image_id'] = $media->id;
        } elseif ($request->filled('primary_image_id')) {
            $validated['primary_image_id'] = $request->primary_image_id;
        }

        $tool = HeavyDutyTool::create($validated);

        // Additional gallery images
        $this->syncGalleryImages($tool, $request);

        return redirect()
            ->route('admin.heavy-duty-tools.index')
            ->with('success', "Tool \"{$tool->name}\" created successfully.");
    }

    // ─── Edit ─────────────────────────────────────────────────────────

    public function edit(HeavyDutyTool $heavyDutyTool)
    {
        $tool = $heavyDutyTool;
        $tool->load(['primaryImage', 'images.media']);
        return view('admin.heavy-duty-tools.form', compact('tool'));
    }

    // ─── Update ───────────────────────────────────────────────────────

    public function update(Request $request, HeavyDutyTool $heavyDutyTool)
    {
        $tool      = $heavyDutyTool;
        $validated = $this->validateTool($request, $tool->id);

        if (empty($validated['slug'])) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], $tool->id);
        }

        if ($request->hasFile('primary_image')) {
            $media = $this->mediaService->upload($request->file('primary_image'), 'tools');
            $validated['primary_image_id'] = $media->id;
        } elseif ($request->filled('primary_image_id')) {
            $validated['primary_image_id'] = $request->primary_image_id;
        }

        $tool->update($validated);

        $this->syncGalleryImages($tool, $request);

        return redirect()
            ->route('admin.heavy-duty-tools.index')
            ->with('success', "Tool \"{$tool->name}\" updated successfully.");
    }

    // ─── Destroy ──────────────────────────────────────────────────────

    public function destroy(HeavyDutyTool $heavyDutyTool)
    {
        $name = $heavyDutyTool->name;
        $heavyDutyTool->images()->delete();
        $heavyDutyTool->delete();

        return redirect()
            ->route('admin.heavy-duty-tools.index')
            ->with('success', "Tool \"{$name}\" deleted.");
    }

    // ─── Toggle Status ────────────────────────────────────────────────

    public function toggle(HeavyDutyTool $heavyDutyTool)
    {
        $heavyDutyTool->update([
            'status' => $heavyDutyTool->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', "Tool status updated.");
    }

    // ─── Bulk Action ──────────────────────────────────────────────────

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids'    => 'required|array',
            'ids.*'  => 'integer|exists:heavy_duty_tools,id',
        ]);

        $tools = HeavyDutyTool::whereIn('id', $request->ids);

        match ($request->action) {
            'activate'   => $tools->update(['status' => 'active']),
            'deactivate' => $tools->update(['status' => 'inactive']),
            'delete'     => $tools->get()->each(fn($t) => $t->images()->delete()) && $tools->delete(),
        };

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }

    // ─── Export ───────────────────────────────────────────────────────

    public function export(): StreamedResponse
    {
        $tools = HeavyDutyTool::with('primaryImage')->latest()->lazy(200);

        return response()->streamDownload(function () use ($tools) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID', 'Name', 'SKU', 'Part Number', 'Brand',
                'Price', 'Sale Price', 'Stock Qty', 'Stock Status', 'Status', 'Created',
            ]);
            foreach ($tools as $tool) {
                fputcsv($handle, [
                    $tool->id,
                    $tool->name,
                    $tool->sku,
                    $tool->part_number,
                    $tool->brand,
                    $tool->price,
                    $tool->sale_price,
                    $tool->stock_quantity,
                    $tool->stock_status,
                    $tool->status,
                    $tool->created_at->format('Y-m-d'),
                ]);
            }
            fclose($handle);
        }, 'heavy-duty-tools-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    // ─── Remove Gallery Image (AJAX) ─────────────────────────────────

    public function removeImage(Request $request, HeavyDutyTool $heavyDutyTool)
    {
        $request->validate(['image_id' => 'required|integer']);

        $image = HeavyDutyToolImage::where('tool_id', $heavyDutyTool->id)
            ->where('id', $request->image_id)
            ->firstOrFail();

        $image->delete();

        return response()->json(['success' => true]);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    private function validateTool(Request $request, ?int $excludeId = null): array
    {
        return $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:heavy_duty_tools,slug' . ($excludeId ? ",{$excludeId}" : ''),
            'sku'              => 'nullable|string|max:100|unique:heavy_duty_tools,sku' . ($excludeId ? ",{$excludeId}" : ''),
            'part_number'      => 'nullable|string|max:100',
            'brand'            => 'nullable|string|max:100',
            'model_number'     => 'nullable|string|max:100',
            'short_description'=> 'nullable|string|max:1000',
            'description'      => 'nullable|string',
            'specifications'   => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'sale_price'       => 'nullable|numeric|min:0|lt:price',
            'stock_quantity'   => 'nullable|integer|min:0',
            'stock_status'     => 'required|in:in_stock,out_of_stock,on_order',
            'status'           => 'required|in:active,inactive,draft',
            'weight_lbs'       => 'nullable|numeric|min:0',
            'dimensions'       => 'nullable|string|max:100',
            'is_featured'      => 'nullable|boolean',
            'ships_worldwide'  => 'nullable|boolean',
            'sort_order'       => 'nullable|integer|min:0',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'primary_image_id' => 'nullable|integer|exists:media_library,id',
        ]);
    }

    private function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base  = Str::slug($name);
        $slug  = $base;
        $count = 1;

        while (HeavyDutyTool::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()
        ) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }

    private function syncGalleryImages(HeavyDutyTool $tool, Request $request): void
    {
        // Handle newly uploaded gallery images
        if ($request->hasFile('gallery_images')) {
            $sort = $tool->images()->max('sort_order') ?? 0;
            foreach ($request->file('gallery_images') as $file) {
                $media = $this->mediaService->upload($file, 'tools/gallery');
                HeavyDutyToolImage::create([
                    'tool_id'    => $tool->id,
                    'media_id'   => $media->id,
                    'sort_order' => ++$sort,
                ]);
            }
        }

        // Handle images selected from media library picker
        if ($request->filled('gallery_media_ids')) {
            $sort = $tool->images()->max('sort_order') ?? 0;
            foreach (explode(',', $request->gallery_media_ids) as $mediaId) {
                $mediaId = (int) trim($mediaId);
                if (!$mediaId) continue;
                // Don't duplicate
                if ($tool->images()->where('media_id', $mediaId)->exists()) continue;
                HeavyDutyToolImage::create([
                    'tool_id'    => $tool->id,
                    'media_id'   => $mediaId,
                    'sort_order' => ++$sort,
                ]);
            }
        }
    }
}
