<?php

namespace App\Http\Controllers;

use App\Models\OutageReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OutageReportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['nullable', 'in:power,water'],
        ]);

        $query = OutageReport::query()
            ->where('created_at', '>=', now()->subDay())
            ->latest();

        if (! empty($validated['type'])) {
            $query->where('type', $validated['type']);
        }

        return response()->json($query->limit(300)->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:power,water'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'locality' => ['nullable', 'string', 'max:120'],
            'note' => ['nullable', 'string', 'max:160'],
            'impact' => ['nullable', 'string', 'max:40'],
            'method' => ['nullable', 'in:gps,map,manual'],
        ]);

        $report = OutageReport::create([
            'type' => $validated['type'],
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
            'locality' => isset($validated['locality']) ? trim($validated['locality']) : null,
            'note' => isset($validated['note']) ? trim($validated['note']) : null,
            'impact' => isset($validated['impact']) ? trim($validated['impact']) : null,
            'method' => $validated['method'] ?? null,
        ]);

        return response()->json($report, 201);
    }
}
