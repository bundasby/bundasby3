<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Mail\ComplaintNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ComplaintController extends Controller
{
    /**
     * Store a new complaint (Public)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $validated['status'] = Complaint::STATUS_PENDING;

        $complaint = Complaint::create($validated);

        // Send email notification to admin
        try {
            $adminEmail = config('mail.admin_email', 'bundapaud@surabaya.go.id');
            Mail::to($adminEmail)->send(new ComplaintNotification($complaint, 'new'));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::warning('Failed to send complaint notification email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Complaint submitted successfully. We will contact you soon.',
            'data' => [
                'id' => $complaint->id,
                'status' => $complaint->status,
            ],
        ], 201);
    }

    /**
     * Get all complaints (Admin only)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Complaint::query()
            ->orderBy('created_at', 'desc');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $complaints = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $complaints,
        ]);
    }

    /**
     * Get single complaint (Admin only)
     */
    public function show(Complaint $complaint): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $complaint,
        ]);
    }

    /**
     * Update complaint status (Admin only)
     */
    public function update(Request $request, Complaint $complaint): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,in_progress,resolved',
            'admin_notes' => 'nullable|string',
        ]);

        if ($validated['status'] === Complaint::STATUS_RESOLVED && !$complaint->resolved_at) {
            $validated['resolved_at'] = now();
        }

        $complaint->update($validated);

        // Send status update email to complainant
        try {
            Mail::to($complaint->email)->send(new ComplaintNotification($complaint, 'status_update'));
        } catch (\Exception $e) {
            \Log::warning('Failed to send complaint status update email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Complaint updated successfully',
            'data' => $complaint,
        ]);
    }
}
