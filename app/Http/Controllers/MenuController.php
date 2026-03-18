<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menu.create');
    }

   
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'url' => 'required|string',
        'icon' => 'nullable|string',
    ]);

    // Get the maximum order value and add 1 to set the new order
    $maxOrder = Menu::max('order');
    $order = $maxOrder + 1;

    Menu::create([
        'name' => $request->input('name'),
        'url' => $request->input('url'),
        'icon' => $request->input('icon'),
        'order' => $order,
    ]);

    return redirect()->route('menus.index')->with('success', 'Menu created successfully');
}
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.menu.edit', compact('menu'));
    }

   public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string',
        'url' => 'required|string',
        'icon' => 'nullable|string',
        'order' => 'required|integer', // Add validation for the order field
    ]);

    $menu = Menu::findOrFail($id);
    $menu->update([
        'name' => $request->input('name'),
        'url' => $request->input('url'),
        'icon' => $request->input('icon'),
        'order' => $request->input('order'), // Update the order field
    ]);

    return redirect()->route('menus.index')->with('success', 'Menu updated successfully');
}public function updateOrder(Request $request)
{
    $newOrder = $request->input('sortOrder');
    
    // Get the existing order from the database
    $existingOrder = Menu::orderBy('order')->pluck('id')->toArray();

    // Make sure both the existing and new order arrays have the same elements
    if (count($existingOrder) != count($newOrder) || array_diff($existingOrder, $newOrder)) {
        return response()->json(['error' => 'Invalid order data'], 400);
    }

    DB::beginTransaction();

    try {
        foreach ($newOrder as $key => $menuId) {
            $menu = Menu::find($menuId);

            // Check if the menu is found before attempting to update
            if ($menu) {
                // Update the order based on the index in the new order array
                $menu->update(['order' => $key + 1]);
            }
        }

        DB::commit();

        if ($request->ajax()) {
            return response()->json(['message' => 'Menu order updated successfully']);
        }

        return redirect()->route('menus.index')->with('success', 'Menu order updated successfully');
    } catch (\Exception $e) {
        DB::rollBack();

        if ($request->ajax()) {
            return response()->json(['error' => 'Failed to update menu order'], 500);
        }

        return redirect()->route('menus.index')->with('error', 'Failed to update menu order');
    }
}

    

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully');
    }
}
