<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentType;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminEquipmentTypesController extends Controller
{
    public function __construct(protected MediaUploadService $mediaService)
    {}

    public function index(Request $request)
    {
        $query = EquipmentType::withCount('parts');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // FIXED — matches what the view expects
        $types = $query->orderBy('sort_order')->orderBy('name')->paginate(25)->withQueryString();

        return view('admin.equipment-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.equipment-types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:150|unique:equipment_types,name',
            'slug'             => 'nullable|string|max:150|unique:equipment_types,slug',
            'description'      => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active'        => 'boolean',
            'sort_order'       => 'nullable|integer|min:0',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $media                  = $this->mediaService->upload($request->file('image'), 'equipment-types');
            $data['image_media_id'] = $media->id;
        }

        EquipmentType::create($data);

        return redirect()->route('admin.equipment-types.index')->with('success', 'Equipment type created.');
    }

    public function edit(EquipmentType $equipmentType)
    {
        $type = $equipmentType;
        return view('admin.equipment-types.edit', compact('type'));
    }

    public function update(Request $request, EquipmentType $equipmentType)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:150|unique:equipment_types,name,' . $equipmentType->id,
            'slug'             => 'nullable|string|max:150|unique:equipment_types,slug,' . $equipmentType->id,
            'description'      => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active'        => 'boolean',
            'sort_order'       => 'nullable|integer|min:0',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $media                  = $this->mediaService->upload($request->file('image'), 'equipment-types');
            $data['image_media_id'] = $media->id;
        }

        $equipmentType->update($data);

        return redirect()->route('admin.equipment-types.index')->with('success', 'Equipment type updated.');
    }

    public function destroy(EquipmentType $equipmentType)
    {
        $equipmentType->delete();
        return redirect()->route('admin.equipment-types.index')->with('success', 'Equipment type deleted.');
    }
}
