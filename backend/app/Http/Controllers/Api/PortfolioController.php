<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PortfolioController extends Controller
{
    public function projects(): JsonResponse
    {
        // Replace with database-backed projects when an admin CMS is added.
        return response()->json([
            ['id' => 1, 'name' => 'CarSale Web Application', 'category' => 'Automation'],
            ['id' => 2, 'name' => 'Diakad — Tracer Alumni', 'category' => 'Manual QA'],
            ['id' => 3, 'name' => 'MoneyFlow Finance App', 'category' => 'Manual QA'],
        ]);
    }

    public function testCases(): JsonResponse
    {
        return response()->json([
            ['id' => 'TC-001', 'scenario' => 'Login with valid credentials', 'expected' => 'User enters dashboard', 'status' => 'EXAMPLE'],
            ['id' => 'TC-002', 'scenario' => 'Login with incorrect password', 'expected' => 'Error message is shown', 'status' => 'EXAMPLE'],
        ]);
    }

    public function contact(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $message = ContactMessage::create($validated);

        return response()->json([
            'message' => 'Contact message received.',
            'id' => $message->id,
        ], 201);
    }
}
