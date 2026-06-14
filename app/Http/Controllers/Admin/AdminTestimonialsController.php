<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class AdminTestimonialsController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::latest();

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('reviewer_name', 'like', "%{$term}%")
                    ->orWhere('company', 'like', "%{$term}%")
                    ->orWhere('content', 'like', "%{$term}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $testimonials = $query->orderBy('sort_order')->paginate(25)->withQueryString();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reviewer_name'  => 'required|string|max:150',
            'reviewer_title' => 'nullable|string|max:150',
            'company'        => 'nullable|string|max:200',
            'location'       => 'nullable|string|max:200',
            'content'        => 'required|string',
            'rating'         => 'required|integer|min:1|max:5',
            'source'         => 'nullable|string|max:100',
            'is_active'      => 'boolean',
            'is_featured'    => 'boolean',
            'sort_order'     => 'nullable|integer|min:0',
        ]);

        $data['is_active']   = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured');

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $request->validate([
            'reviewer_name'  => 'required|string|max:150',
            'reviewer_title' => 'nullable|string|max:150',
            'company'        => 'nullable|string|max:200',
            'location'       => 'nullable|string|max:200',
            'content'        => 'required|string',
            'rating'         => 'required|integer|min:1|max:5',
            'source'         => 'nullable|string|max:100',
            'is_active'      => 'boolean',
            'is_featured'    => 'boolean',
            'sort_order'     => 'nullable|integer|min:0',
        ]);

        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }

    public function toggle(Testimonial $testimonial)
    {
        $testimonial->update(['is_active' => ! $testimonial->is_active]);
        return response()->json(['success' => true, 'is_active' => $testimonial->is_active]);
    }

    public function reorder(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:testimonials,id']);
        foreach ($request->ids as $order => $id) {
            Testimonial::where('id', $id)->update(['sort_order' => $order + 1]);
        }
        return response()->json(['success' => true]);
    }
}
