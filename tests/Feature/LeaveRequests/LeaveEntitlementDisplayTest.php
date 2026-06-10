<?php

declare(strict_types=1);

namespace Tests\Feature\LeaveRequests;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Services\Leave\LeaveEntitlementCalculatorInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveEntitlementDisplayTest extends TestCase
{
    use RefreshDatabase;

    // US2 AC1: The system shows remaining vacation days to the employee

    public function test_create_form_passes_remaining_days_to_view(): void
    {
        $employee = User::factory()->create([
            'hire_date' => now()->subYears(2)->toDateString(),
            'weekly_working_days' => 5,
            'has_severe_disability' => false,
        ]);
        $employee->assignRole('employee');

        $response = $this->actingAs($employee)
            ->get(route('leave-requests.create'));

        $response->assertOk();
        $response->assertViewHas('remainingDays');
    }

    public function test_remaining_days_is_shown_on_create_page(): void
    {
        $employee = User::factory()->create([
            'hire_date' => now()->subYears(2)->toDateString(),
            'weekly_working_days' => 5,
            'has_severe_disability' => false,
        ]);
        $employee->assignRole('employee');

        $entitlement = 30; // 5 days/week, no bonus → 30 days

        $response = $this->actingAs($employee)
            ->get(route('leave-requests.create'));

        $response->assertOk();
        $response->assertSee((string) $entitlement);
    }

    public function test_remaining_days_equals_full_entitlement_when_no_requests_exist(): void
    {
        $employee = User::factory()->create([
            'hire_date' => now()->subYears(2)->toDateString(),
            'weekly_working_days' => 5,
            'has_severe_disability' => false,
        ]);
        $employee->assignRole('employee');

        $response = $this->actingAs($employee)
            ->get(route('leave-requests.create'));

        $response->assertOk();

        $remainingDays = $response->viewData('remainingDays');

        $entitlementService = app(LeaveEntitlementCalculatorInterface::class);
        $expectedEntitlement = $entitlementService->calculateAnnualEntitlement($employee, now()->year);

        $this->assertEquals($expectedEntitlement, $remainingDays);
    }

    // US2 AC2: Used and pending vacation days are deducted from the remaining count

    public function test_remaining_days_decreases_after_approved_vacation_request(): void
    {
        $employee = User::factory()->create([
            'hire_date' => now()->subYears(2)->toDateString(),
            'weekly_working_days' => 5,
            'has_severe_disability' => false,
        ]);
        $employee->assignRole('employee');

        $vacationType = \App\Models\AbsenceType::where('name', 'Vacation')->first();

        $before = $this->actingAs($employee)
            ->get(route('leave-requests.create'))
            ->viewData('remainingDays');

        LeaveRequest::create([
            'user_id' => $employee->id,
            'absence_type_id' => $vacationType->id,
            'start_date' => '2026-07-07',
            'end_date' => '2026-07-11',
            'status' => 'approved',
        ]);

        $after = $this->actingAs($employee)
            ->get(route('leave-requests.create'))
            ->viewData('remainingDays');

        $this->assertLessThan($before, $after);
    }

    public function test_remaining_days_decreases_after_pending_vacation_request(): void
    {
        $employee = User::factory()->create([
            'hire_date' => now()->subYears(2)->toDateString(),
            'weekly_working_days' => 5,
            'has_severe_disability' => false,
        ]);
        $employee->assignRole('employee');

        $vacationType = \App\Models\AbsenceType::where('name', 'Vacation')->first();

        $before = $this->actingAs($employee)
            ->get(route('leave-requests.create'))
            ->viewData('remainingDays');

        LeaveRequest::create([
            'user_id' => $employee->id,
            'absence_type_id' => $vacationType->id,
            'start_date' => '2026-07-07',
            'end_date' => '2026-07-11',
            'status' => 'pending',
        ]);

        $after = $this->actingAs($employee)
            ->get(route('leave-requests.create'))
            ->viewData('remainingDays');

        $this->assertLessThan($before, $after);
    }

    public function test_remaining_days_not_affected_by_rejected_vacation_request(): void
    {
        $employee = User::factory()->create([
            'hire_date' => now()->subYears(2)->toDateString(),
            'weekly_working_days' => 5,
            'has_severe_disability' => false,
        ]);
        $employee->assignRole('employee');

        $vacationType = \App\Models\AbsenceType::where('name', 'Vacation')->first();

        $before = $this->actingAs($employee)
            ->get(route('leave-requests.create'))
            ->viewData('remainingDays');

        LeaveRequest::create([
            'user_id' => $employee->id,
            'absence_type_id' => $vacationType->id,
            'start_date' => '2026-07-07',
            'end_date' => '2026-07-11',
            'status' => 'rejected',
        ]);

        $after = $this->actingAs($employee)
            ->get(route('leave-requests.create'))
            ->viewData('remainingDays');

        $this->assertEquals($before, $after);
    }
}
