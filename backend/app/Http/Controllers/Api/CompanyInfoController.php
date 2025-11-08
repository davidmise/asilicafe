<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompanyInfoController extends Controller
{
    public function index(): JsonResponse
    {
        $companyInfo = CompanyInfo::all();
        return response()->json(['status' => 'success', 'data' => $companyInfo]);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'key' => 'required|string|unique:company_infos',
            'value' => 'required|string',
            'type' => 'string|in:text,email,phone,address,url,image',
            'description' => 'nullable|string'
        ]);

        $companyInfo = CompanyInfo::create($validatedData);
        return response()->json(['status' => 'success', 'data' => $companyInfo], 201);
    }

    public function show(CompanyInfo $companyInfo): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => $companyInfo]);
    }

    public function update(Request $request, CompanyInfo $companyInfo): JsonResponse
    {
        $validatedData = $request->validate([
            'value' => 'sometimes|required|string',
            'type' => 'string|in:text,email,phone,address,url,image',
            'description' => 'nullable|string'
        ]);

        $companyInfo->update($validatedData);
        return response()->json(['status' => 'success', 'data' => $companyInfo]);
    }

    public function destroy(CompanyInfo $companyInfo): JsonResponse
    {
        $companyInfo->delete();
        return response()->json(['status' => 'success', 'message' => 'Company info deleted']);
    }

    public function getByKey(string $key): JsonResponse
    {
        $companyInfo = CompanyInfo::where('key', $key)->first();

        if (!$companyInfo) {
            return response()->json(['status' => 'error', 'message' => 'Key not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $companyInfo]);
    }
}
