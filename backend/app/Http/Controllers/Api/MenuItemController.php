<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = MenuItem::query();

        // Filter by category if provided
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        // Filter by availability
        if ($request->has('available')) {
            $query->available();
        }

        // Filter by featured
        if ($request->has('featured')) {
            $query->featured();
        }

        $menuItems = $query->orderBy('name')->get();

        return response()->json([
            'status' => 'success',
            'data' => $menuItems
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string',
            'category' => 'required|in:hot,cold,food,dessert',
            'is_featured' => 'boolean',
            'is_available' => 'boolean'
        ]);

        $menuItem = MenuItem::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Menu item created successfully',
            'data' => $menuItem
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuItem $menuItem): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $menuItem
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuItem $menuItem): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'image' => 'nullable|string',
            'category' => 'sometimes|required|in:hot,cold,food,dessert',
            'is_featured' => 'boolean',
            'is_available' => 'boolean'
        ]);

        $menuItem->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Menu item updated successfully',
            'data' => $menuItem
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItem $menuItem): JsonResponse
    {
        $menuItem->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Menu item deleted successfully'
        ]);
    }

    /**
     * Get menu items by category
     */
    public function byCategory(string $category): JsonResponse
    {
        $menuItems = MenuItem::byCategory($category)
            ->available()
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $menuItems,
            'category' => $category
        ]);
    }

    /**
     * Get featured menu items
     */
    public function featured(): JsonResponse
    {
        $menuItems = MenuItem::featured()
            ->available()
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $menuItems
        ]);
    }
}
