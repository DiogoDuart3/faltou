<?php

namespace App\Http\Controllers;

use App\Models\OutageComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OutageCommentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:power,water'],
        ]);

        $comments = OutageComment::query()
            ->where('type', $validated['type'])
            ->where('created_at', '>=', now()->subDay())
            ->latest()
            ->limit(200)
            ->get();

        return response()->json($comments);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:power,water'],
            'text' => ['required', 'string', 'max:140'],
        ]);

        $comment = OutageComment::create([
            'type' => $validated['type'],
            'text' => trim($validated['text']),
        ]);

        return response()->json($comment, 201);
    }
}
