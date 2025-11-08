<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        $testimonials = Testimonial::active()->get();
        return response()->json(['status' => 'success', 'data' => $testimonials]);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'profession' => 'nullable|string|max:255',
            'message' => 'required|string',
            'image' => 'nullable|string',
            'rating' => 'integer|min:1|max:5',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $testimonial = Testimonial::create($validatedData);
        return response()->json(['status' => 'success', 'data' => $testimonial], 201);
    }

    public function show(Testimonial $testimonial): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => $testimonial]);
    }

    public function update(Request $request, Testimonial $testimonial): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'profession' => 'nullable|string|max:255',
            'message' => 'sometimes|required|string',
            'image' => 'nullable|string',
            'rating' => 'integer|min:1|max:5',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $testimonial->update($validatedData);
        return response()->json(['status' => 'success', 'data' => $testimonial]);
    }

    public function destroy(Testimonial $testimonial): JsonResponse
    {
        $testimonial->delete();
        return response()->json(['status' => 'success', 'message' => 'Testimonial deleted']);
    }

    public function featured(): JsonResponse
    {
        $testimonials = Testimonial::featured()->active()->get();
        return response()->json(['status' => 'success', 'data' => $testimonials]);
    }
}
