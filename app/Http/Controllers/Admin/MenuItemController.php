<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index()
    {
        $items = MenuItem::with('category')->latest()->paginate(20);
        return view('admin.menu-items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.menu-items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');
        $data['is_veg']       = $request->boolean('is_veg');
        $data['is_available'] = $request->boolean('is_available');
        $data['is_special']   = $request->boolean('is_special');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu', 'public');
        }

        MenuItem::create($data);
        return redirect()->route('admin.menu-items.index')->with('success', 'Item added!');
    }

    public function edit(MenuItem $menuItem)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.menu-items.edit', compact('menuItem', 'categories'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');
        $data['is_veg']       = $request->boolean('is_veg');
        $data['is_available'] = $request->boolean('is_available');
        $data['is_special']   = $request->boolean('is_special');

        if ($request->hasFile('image')) {
            if ($menuItem->image) Storage::disk('public')->delete($menuItem->image);
            $data['image'] = $request->file('image')->store('menu', 'public');
        }

        $menuItem->update($data);
        return redirect()->route('admin.menu-items.index')->with('success', 'Item updated!');
    }

    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->image) Storage::disk('public')->delete($menuItem->image);
        $menuItem->delete();
        return back()->with('success', 'Item deleted!');
    }
}