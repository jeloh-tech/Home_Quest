<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * Store a new report
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'listing_id' => 'required|exists:listings,id',
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user has already reported this listing (only for authenticated users)
        if (Auth::check()) {
            $existingReport = Report::where('user_id', Auth::id())
                ->where('listing_id', $request->listing_id)
                ->first();

            if ($existingReport) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reported this listing.'
                ], 422);
            }
        }

        $report = Report::create([
            'user_id' => Auth::id(), // This will be null for unauthenticated users
            'listing_id' => $request->listing_id,
            'reason' => $request->reason,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully. We will review it shortly.',
            'report' => $report
        ]);
    }

    /**
     * Get reports for admin (index)
     */
    public function index(Request $request)
    {
        $query = Report::with(['user', 'listing', 'reviewer'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(15);

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Show a specific report
     */
    public function show(Report $report)
    {
        $report->load(['user', 'listing', 'reviewer']);
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Update report status (admin only)
     */
    public function update(Request $request, Report $report): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,reviewed,resolved',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $report->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report updated successfully.',
            'report' => $report->load(['user', 'listing', 'reviewer'])
        ]);
    }

    /**
     * Get report statistics for admin dashboard
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_reports' => Report::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'reviewed_reports' => Report::where('status', 'reviewed')->count(),
            'resolved_reports' => Report::where('status', 'resolved')->count(),
            'recent_reports' => Report::with(['user', 'listing'])
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
        ];

        return response()->json($stats);
    }
}
