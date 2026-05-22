<?php

declare(strict_types=1);

return [
    // Page titles
    'manage' => 'Manage Leave Requests',
    'manage_subtitle' => 'Review and approve pending leave requests',
    'my_requests' => 'My Leave Requests',
    'history' => 'Leave Request History',
    'create' => 'New Leave Request',
    'details' => 'Leave Request Details',
    'pending_title' => 'Open Requests',
    'pending_subtitle' => 'Open requests :pending to review',
    'view_history' => 'View History',
    'view_pending' => 'View Pending',
    'history_title' => 'Leave Request Overview',
    'history_subtitle' => 'View and track all leave requests and their approval status',
    'history_summary' => ':total total, :pending to review',
    'history_empty_title' => 'No leave requests yet',
    'history_empty_body' => 'Leave requests will appear here once submitted.',

    // Status / overview
    'your_vacation_status' => 'Your Vacation Status',
    'overview_current_year' => 'Overview of your leave days for the current year.',
    'days_remaining' => 'Days Remaining',
    'submitted_on' => 'Submitted On',
    'reviewer' => 'Reviewer',
    'rejection_reason' => 'Rejection Reason',
    'back_to_requests' => 'Back to Requests',
    'no_leave_requests_yet' => 'You have not submitted any leave requests yet.',

    // Form
    'note' => 'Note:',
    'absence_type' => 'Absence Type',
    'start_date' => 'Start Date',
    'end_date' => 'End Date',
    'submit_request' => 'Submit Request',
    'cancel' => 'Cancel',

    // Table / management
    'employee' => 'Employee',
    'type' => 'Type',
    'period' => 'Period',
    'status' => 'Status',
    'actions' => 'Actions',
    'review' => 'Review',
    'view_details' => 'View Details',
    'no_leave_requests' => 'No leave requests found.',
    // Team capacity / occupancy
    'team_capacity_check' => 'Team Capacity Check',
    'no_overlaps' => 'No overlaps detected. The team is fully staffed during this period.',

    'modal' => [
        'approval' => [
            'title' => 'Confirm Approval',
            'body' => 'Please confirm you want to approve this leave request.',
            'confirm' => 'Yes, Approve',
        ],
        'rejection' => [
            'title' => 'Confirm Rejection',
            'body' => 'Please review the leave request details before rejecting.',
        ],
    ],

    'feedback' => [
        'approved' => 'Leave request approved successfully.',
        'rejected' => 'Leave request rejected successfully.',
    ],

    'pending_summary' => ':pending pending / :total total',
    'my_absences_title' => 'My Absences',
    'my_absences_summary' => ':total total requests',
    'overlap_vacation' => 'The requested period overlaps with an existing vacation from :start to :end.',
];
