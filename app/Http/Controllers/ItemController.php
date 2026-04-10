<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $items = Item::all();
        return redirect('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'category_id' => 'requested|exist|categories,id',
            'name' => 'required|string|max:255',
            'total' => 'requireed|integer|min:0',
            'repair' => 'requireed|integer|min:0',
            'lending' => 'requireed|integer|min:0',

        ]);

        Item::create($request->all());
        return redirect()->route('items.index')->with('success', 'items created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
        $request->validate([
            'category_id' => 'requested|exist|categories,id',
            'name' => 'required|string|max:255',
            'total' => 'requireed|integer|min:0',
            'repair' => 'requireed|integer|min:0',
            'lending' => 'requireed|integer|min:0',

        ]);

        Item::update($request->all());
        return redirect()->route('items.index')->with('success', 'items updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
        $item->delete();
        return redirect('items.index')->with('success', 'items deleted successfuly');
    }
}
