<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('menuItems')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:50',
            'icon'       => 'nullable|string|max:10',
            'sort_order' => 'nullable|integer',
        ]);
        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active');
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category created!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'       => 'required|string|max:50',
            'icon'       => 'nullable|string|max:10',
            'sort_order' => 'nullable|integer',
        ]);
        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category updated!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted!');
    }
}