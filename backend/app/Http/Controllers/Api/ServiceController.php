<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $services = Service::active()
            ->ordered()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $services
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'image' => 'nullable|string',
            'sort_order' => 'integer',
            'is_active' => 'boolean'
        ]);

        $service = Service::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Service created successfully',
            'data' => $service
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $service
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service): JsonResponse
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'icon' => 'nullable|string',
            'image' => 'nullable|string',
            'sort_order' => 'integer',
            'is_active' => 'boolean'
        ]);

        $service->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Service updated successfully',
            'data' => $service
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service): JsonResponse
    {
        $service->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Service deleted successfully'
        ]);
    }
}
