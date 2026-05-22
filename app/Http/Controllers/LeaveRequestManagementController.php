<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Services\Leave\LeaveRequestServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LeaveRequestManagementController extends Controller
{
    public function __construct(
        private readonly LeaveRequestServiceInterface $leaveRequestService
    ) {
    }

    public function index(Request $request): View
    {
        $leaveRequests = $this->getPaginatedLeaveRequests($request, 'pending');
        $pendingCount = LeaveRequest::where('status', 'pending')->count();

        return view('leave-requests.management.index', compact('leaveRequests', 'pendingCount'));
    }

    public function history(Request $request): View
    {
        $leaveRequests = $this->getPaginatedLeaveRequests($request, 'all');
        $pendingCount = LeaveRequest::where('status', 'pending')->count();
        $totalCount = LeaveRequest::count();

        return view('leave-requests.management.history', compact('leaveRequests', 'pendingCount', 'totalCount'));
    }

    public function show(LeaveRequest $leaveRequest): View
    {
        $leaveRequest->load(['user', 'absenceType']);

        $startDate = Carbon::parse($leaveRequest->start_date);
        $endDate = Carbon::parse($leaveRequest->end_date);

        $teamOverlaps = $this->leaveRequestService->getTeamOverlappingRequests(
            $startDate,
            $endDate,
            $leaveRequest->user_id
        );

        return view('leave-requests.management.show', compact('leaveRequest', 'teamOverlaps'));
    }

    public function approve(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $this->leaveRequestService->approve($leaveRequest, $request->user()->id);

        return redirect()->route('leave-requests.management.index')
            ->with('success', __('leave-requests.feedback.approved'));
    }

    public function reject(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5', 'max:' . LeaveRequest::MAX_REJECTION_REASON_LENGTH],
        ]);

        $this->leaveRequestService->reject($leaveRequest, $request->user()->id, $validated['rejection_reason']);

        return redirect()->route('leave-requests.management.index')
            ->with('success', __('leave-requests.feedback.rejected'));
    }

    private function getPaginatedLeaveRequests(Request $request, string $statusScope)
    {
        $allowedSortBy = ['created_at', 'start_date', 'end_date', 'status', 'user_id', 'absence_type_id'];
        $allowedPerPage = [10, 25, 50, 100];
        $allowedStatuses = ['pending', 'approved', 'rejected'];

        $defaultSortBy = 'created_at';
        $defaultSortDirection = 'asc';

        if ($statusScope === 'all') {
            $defaultSortDirection = 'desc';
        }

        $sortBy = $request->query('sort_by', $defaultSortBy);
        if (!in_array($sortBy, $allowedSortBy, true)) {
            $sortBy = $defaultSortBy;
        }

        $sortDirection = $request->query('sort_direction', $defaultSortDirection);
        if (!in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = $defaultSortDirection;
        }

        $perPage = (int)$request->query('per_page', 10);
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $query = LeaveRequest::with(['user', 'absenceType']);

        if ($statusScope === 'pending') {
            $query->where('status', 'pending');
        }

        if ($statusScope === 'all') {
            $status = $request->query('status');
            if ($status && in_array($status, $allowedStatuses, true)) {
                $query->where('status', $status);
            }
        }

        return $query->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();
    }
}
