<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqsController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query();

        if ($request->filled('search')) {
            $query->where('question', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $faqs       = $query->orderBy('sort_order')->orderBy('id')->paginate(25)->withQueryString();
        $categories = Faq::distinct()->pluck('category')->filter()->sort()->values();

        return view('admin.faqs.index', compact('faqs', 'categories'));
    }

    public function create()
    {
        $existingCategories = Faq::distinct()->pluck('category')->filter()->sort()->values();
        return view('admin.faqs.create', compact('existingCategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'category'   => 'nullable|string|max:100',
            'is_active'  => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        Faq::create($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created.');
    }

    public function edit(Faq $faq)
    {
        $existingCategories = Faq::distinct()->pluck('category')->filter()->sort()->values();
        return view('admin.faqs.edit', compact('faq', 'existingCategories'));
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'category'   => 'nullable|string|max:100',
            'is_active'  => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $faq->update($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:faqs,id']);
        foreach ($request->ids as $order => $id) {
            Faq::where('id', $id)->update(['sort_order' => $order + 1]);
        }
        return response()->json(['success' => true]);
    }
}
