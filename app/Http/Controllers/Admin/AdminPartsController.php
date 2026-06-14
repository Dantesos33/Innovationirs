<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Parts\StorePartRequest;
use App\Http\Requests\Parts\UpdatePartRequest;
use App\Models\Category;
use App\Models\EquipmentModel;
use App\Models\EquipmentType;
use App\Models\Make;
use App\Models\Part;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminPartsController extends Controller
{
    public function __construct(
        protected MediaUploadService $mediaService
    ) {}

    public function index(Request $request)
    {
        $query = Part::with(['make', 'categories', 'primaryImage']);

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('part_number', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%");
            });
        }

        if ($request->filled('make_id')) {
            $query->where('make_id', $request->make_id);
        }

        if ($request->filled('condition_type')) {
            $query->where('condition_type', $request->condition_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('categories', fn($q) =>
                $q->where('categories.id', $request->category_id));
        }

        $parts      = $query->latest()->paginate(25)->withQueryString();
        $categories = Category::active()->ordered()->get();
        $makes      = Make::active()->ordered()->get();

        return view('admin.parts.index', compact('parts', 'categories', 'makes'));
    }

    public function create()
    {
        $categories     = Category::active()->ordered()->get();
        $makes          = Make::active()->ordered()->get();
        $equipmentTypes = EquipmentType::active()->ordered()->get();
        $models         = EquipmentModel::with('make')->active()->orderBy('name')->get();

        return view('admin.parts.create', compact('categories', 'makes', 'equipmentTypes', 'models'));
    }

    public function store(StorePartRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $media                    = $this->mediaService->upload($request->file('image'), 'parts');
            $data['primary_image_id'] = $media->id;
        }

        $part = Part::create($data);

        if ($request->filled('category_ids')) {
            $categories = collect($request->category_ids)->mapWithKeys(function ($id, $index) {
                return [$id => ['is_primary' => $index === 0]];
            });
            $part->categories()->sync($categories);
        }

        if ($request->filled('model_ids')) {
            $part->fitsModels()->sync($request->model_ids);
        }

        return redirect()
            ->route('admin.parts.index')
            ->with('success', 'Part created successfully.');
    }

    public function edit(Part $part)
    {
        $part->load(['categories', 'fitsModels', 'primaryImage', 'images.media']);

        $categories     = Category::active()->ordered()->get();
        $makes          = Make::active()->ordered()->get();
        $equipmentTypes = EquipmentType::active()->ordered()->get();
        $models         = EquipmentModel::with('make')->active()->orderBy('name')->get();

        return view('admin.parts.edit', compact(
            'part', 'categories', 'makes', 'equipmentTypes', 'models'
        ));
    }

    public function update(UpdatePartRequest $request, Part $part)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $media                    = $this->mediaService->upload($request->file('image'), 'parts');
            $data['primary_image_id'] = $media->id;
        }

        $part->update($data);

        if ($request->has('category_ids')) {
            $categories = collect($request->category_ids ?? [])->mapWithKeys(function ($id, $index) {
                return [$id => ['is_primary' => $index === 0]];
            });
            $part->categories()->sync($categories);
        }

        if ($request->has('model_ids')) {
            $part->fitsModels()->sync($request->model_ids ?? []);
        }

        return redirect()
            ->route('admin.parts.index')
            ->with('success', 'Part updated successfully.');
    }

    public function destroy(Part $part)
    {
        $part->categories()->detach();
        $part->fitsModels()->detach();
        $part->delete();

        return redirect()
            ->route('admin.parts.index')
            ->with('success', 'Part deleted.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids'    => 'required|array',
            'ids.*'  => 'integer|exists:parts,id',
        ]);

        $parts = Part::whereIn('id', $request->ids);

        match ($request->action) {
            'activate'   => $parts->update(['status' => 'active']),
            'deactivate' => $parts->update(['status' => 'inactive']),
            'delete'     => $parts->delete(),
        };

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Part::with(['make', 'primaryCategory']);

        if ($request->filled('make_id')) {
            $query->where('make_id', $request->make_id);
        }

        if ($request->filled('condition_type')) {
            $query->where('condition_type', $request->condition_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $parts = $query->lazy(500);

        return response()->streamDownload(function () use ($parts) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID', 'Name', 'Part Number', 'OEM Number', 'SKU',
                'Make', 'Condition', 'Stock Status', 'Stock Qty',
                'Status', 'Created',
            ]);

            foreach ($parts as $part) {
                fputcsv($handle, [
                    $part->id,
                    $part->name,
                    $part->part_number,
                    $part->oem_part_number,
                    $part->sku,
                    $part->make?->name,
                    $part->condition_type,
                    $part->stock_status,
                    $part->stock_quantity,
                    $part->status,
                    $part->created_at->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        }, 'parts-export-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
