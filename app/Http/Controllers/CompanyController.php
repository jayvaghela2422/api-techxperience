<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * PUBLIC GET (Anyone)
     */
    public function publicShow()
    {
        try {
            $company = Company::first();

            if (!$company) {
                return response()->json(['message' => 'Company info not found'], 404);
            }

            return response()->json($company);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }


    /**
     * ADMIN GET (Protected)
     */
    public function show()
    {
        try {
            $company = Company::first();

            if (!$company) {
                return response()->json(['message' => 'Company not found'], 404);
            }

            return response()->json($company);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }


    /**
     * STORE — Create only if missing
     */
    public function store(Request $request)
    {
        try {
            // Check existing record
            if (Company::exists()) {
                return response()->json([
                    'message' => 'Company profile already exists. Use update instead.'
                ], 409);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'address' => 'nullable|string|max:255',
                'phone'   => 'nullable|string|max:30',
                'email'   => 'nullable|email|max:255',
                'bio'     => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            // Create new company record
            $company = Company::create($validator->validated());

            return response()->json([
                'message' => 'Company created successfully',
                'data' => $company
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating company',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * UPDATE — Update existing record
     */
    public function update(Request $request)
    {
        try {
            $company = Company::first();

            if (!$company) {
                return response()->json(['message' => 'No company found. Use store to create first.'], 404);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'address' => 'nullable|string|max:255',
                'phone'   => 'nullable|string|max:30',
                'email'   => 'nullable|email|max:255',
                'bio'     => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            // Update company
            $company->update($validator->validated());

            return response()->json([
                'message' => 'Company updated successfully',
                'data' => $company
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating company',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
