<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return Inertia::render('Admin/Items/Index', [
            'items' => Item::with('category')->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render('Admin/Items/Create', [
            'categories' => Category::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:available,in_use,maintenance,retired',
            'serial_number' => 'nullable|string|max:255',
        ]);

         Item::create($validated);

          return redirect()->route('admin.items.index')->with('success', 'Item created successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
       return Inertia::render('Admin/Items/Show', [
            'item' => $item->load('category', 'requestItems.request.user')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
       return Inertia::render('Admin/Items/Edit', [
            'item' => $item,
            'categories' => Category::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:available,in_use,maintenance,retired',
            'serial_number' => 'nullable|string|max:255',
        ]);

        $item->update($validated);

        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully');
    }
}
