<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    public function index(): JsonResponse
    {
        $reservations = Reservation::orderBy('reservation_date', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $reservations]);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required|date_format:H:i',
            'number_of_people' => 'required|integer|min:1|max:20',
            'special_requests' => 'nullable|string'
        ]);

        $reservation = Reservation::create($validatedData);
        return response()->json(['status' => 'success', 'data' => $reservation], 201);
    }

    public function show(Reservation $reservation): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => $reservation]);
    }

    public function update(Request $request, Reservation $reservation): JsonResponse
    {
        $validatedData = $request->validate([
            'status' => 'sometimes|required|in:pending,confirmed,cancelled',
            'special_requests' => 'nullable|string'
        ]);

        $reservation->update($validatedData);
        return response()->json(['status' => 'success', 'data' => $reservation]);
    }

    public function destroy(Reservation $reservation): JsonResponse
    {
        $reservation->delete();
        return response()->json(['status' => 'success', 'message' => 'Reservation deleted']);
    }
}
