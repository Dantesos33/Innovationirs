<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Make;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminMakesController extends Controller
{
    public function __construct(protected MediaUploadService $mediaService)
    {}

    public function index(Request $request)
    {
        $query = Make::withCount('parts');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $makes = $query->orderBy('sort_order')->orderBy('name')->paginate(25)->withQueryString();

        return view('admin.makes.index', compact('makes'));
    }

    public function create()
    {
        return view('admin.makes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:150|unique:makes,name',
            'slug'             => 'nullable|string|max:150|unique:makes,slug',
            'description'      => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active'        => 'boolean',
            'sort_order'       => 'nullable|integer|min:0',
            'logo'             => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:1024',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $media                 = $this->mediaService->upload($request->file('logo'), 'makes');
            $data['logo_media_id'] = $media->id;
        }

        Make::create($data);

        return redirect()->route('admin.makes.index')->with('success', 'Make created successfully.');
    }

    public function edit(Make $make)
    {
        return view('admin.makes.edit', compact('make'));
    }

    public function update(Request $request, Make $make)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:150|unique:makes,name,' . $make->id,
            'slug'             => 'nullable|string|max:150|unique:makes,slug,' . $make->id,
            'description'      => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active'        => 'boolean',
            'sort_order'       => 'nullable|integer|min:0',
            'logo'             => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:1024',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            $media                 = $this->mediaService->upload($request->file('logo'), 'makes');
            $data['logo_media_id'] = $media->id;
        }

        $make->update($data);

        return redirect()->route('admin.makes.index')->with('success', 'Make updated.');
    }

    public function destroy(Make $make)
    {
        $make->delete();
        return redirect()->route('admin.makes.index')->with('success', 'Make deleted.');
    }
}
