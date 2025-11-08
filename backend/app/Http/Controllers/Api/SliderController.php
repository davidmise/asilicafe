<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SliderController extends Controller
{
    public function index(): JsonResponse
    {
        $sliders = Slider::ordered()->get();
        return response()->json(['status' => 'success', 'data' => $sliders]);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|string',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'sort_order' => 'integer',
            'is_active' => 'boolean'
        ]);

        $slider = Slider::create($validatedData);
        return response()->json(['status' => 'success', 'data' => $slider], 201);
    }

    public function show(Slider $slider): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => $slider]);
    }

    public function update(Request $request, Slider $slider): JsonResponse
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'sometimes|required|string',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'sort_order' => 'integer',
            'is_active' => 'boolean'
        ]);

        $slider->update($validatedData);
        return response()->json(['status' => 'success', 'data' => $slider]);
    }

    public function destroy(Slider $slider): JsonResponse
    {
        $slider->delete();
        return response()->json(['status' => 'success', 'message' => 'Slider deleted']);
    }

    public function active(): JsonResponse
    {
        $sliders = Slider::active()->ordered()->get();
        return response()->json(['status' => 'success', 'data' => $sliders]);
    }
}
