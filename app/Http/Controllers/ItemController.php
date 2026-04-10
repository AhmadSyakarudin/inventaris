<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->get();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'total' => 'required|integer|min:0',
            'repair' => 'nullable|integer|min:0',
            'lending' => 'nullable|integer|min:0',
            'borrowed' => 'nullable|integer|min:0',
        ]);

        Item::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'total' => $request->total,
            'repair' => $request->input('repair', 0),
            'lending' => $request->input('lending', 0),
            'borrowed' => $request->input('borrowed', 0),
        ]);

        return redirect()->route('items.index')
            ->with('success', 'Item created successfully');
    }

    public function edit(Item $item)
{
    $categories = Category::all();
    return view('items.edit', compact('item', 'categories'));
}

    public function update(Request $request, Item $item)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|max:255',
        'total' => 'required|integer|min:0',
        'repair' => 'nullable|integer|min:0',
        'lending' => 'nullable|integer|min:0',
        'borrowed' => 'nullable|integer|min:0',
    ]);

    $repair = $request->input('repair', 0);

    $newTotal = $request->total - $repair;

    $item->update([
        'name' => $request->name,
        'category_id' => $request->category_id,
        'total' => $newTotal,
        'repair' => $repair,
        'lending' => $request->input('lending', 0),
        'borrowed' => $request->input('borrowed', 0),
    ]);

    return redirect()->route('items.index')
        ->with('success', 'Item updated successfully');
}

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item deleted successfully');
    }
}