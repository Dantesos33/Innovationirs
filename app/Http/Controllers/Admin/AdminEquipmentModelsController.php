<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentModel;
use App\Models\Make;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminEquipmentModelsController extends Controller
{
    public function index(Request $request)
    {
        $query = EquipmentModel::with('make')->withCount('parts');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('make_id')) {
            $query->where('make_id', $request->make_id);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $models = $query->orderBy('make_id')->orderBy('name')->paginate(30)->withQueryString();
        $makes  = Make::active()->ordered()->get();

        return view('admin.equipment-models.index', compact('models', 'makes'));
    }

    public function create()
    {
        $makes = Make::active()->ordered()->get();
        return view('admin.equipment-models.create', compact('makes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'make_id'     => 'required|exists:makes,id',
            'name'        => 'required|string|max:150',
            'slug'        => 'nullable|string|max:150',
            'year_start'  => 'nullable|integer|min:1900|max:2099',
            'year_end'    => 'nullable|integer|min:1900|max:2099|gte:year_start',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['is_active'] = $request->boolean('is_active', true);

        EquipmentModel::create($data);

        return redirect()->route('admin.equipment-models.index')->with('success', 'Equipment model created.');
    }

    public function edit(EquipmentModel $equipmentModel)
    {
        $makes = Make::active()->ordered()->get();
        $model = $equipmentModel;
        return view('admin.equipment-models.edit', compact('model', 'makes'));
    }

    public function update(Request $request, EquipmentModel $equipmentModel)
    {
        $data = $request->validate([
            'make_id'     => 'required|exists:makes,id',
            'name'        => 'required|string|max:150',
            'slug'        => 'nullable|string|max:150',
            'year_start'  => 'nullable|integer|min:1900|max:2099',
            'year_end'    => 'nullable|integer|min:1900|max:2099',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $equipmentModel->update($data);

        return redirect()->route('admin.equipment-models.index')->with('success', 'Equipment model updated.');
    }

    public function destroy(EquipmentModel $equipmentModel)
    {
        $equipmentModel->delete();
        return redirect()->route('admin.equipment-models.index')->with('success', 'Model deleted.');
    }
}
