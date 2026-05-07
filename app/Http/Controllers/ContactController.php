<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Exception;

class ContactController extends Controller
{
    // PUBLIC SUBMIT CONTACT MESSAGE
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'    => 'required|string',
                'email'   => 'required|email',
                'message' => 'required|string',
            ]);

            ContactMessage::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Message submitted successfully.'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    // ADMIN GET ALL MESSAGES
    public function index()
    {
        try {
            $messages = ContactMessage::latest()->paginate(10);

            return response()->json([
                'status' => true,
                'data'   => $messages
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => $e->getMessage(),
            ], 500);
        }
    }
}
