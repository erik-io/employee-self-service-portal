<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\AbsenceType;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsenceTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_leave_requests_relationship_returns_associated_leave_requests(): void
    {
        $absenceType = AbsenceType::where('name', 'Vacation')->first();

        $user = User::factory()->create(['hire_date' => now()->subYear()->toDateString()]);
        $user->assignRole('employee');

        LeaveRequest::create([
            'user_id' => $user->id,
            'absence_type_id' => $absenceType->id,
            'start_date' => '2026-08-04',
            'end_date' => '2026-08-06',
            'status' => 'pending',
        ]);

        $this->assertCount(1, $absenceType->leaveRequests);
        $this->assertEquals($absenceType->id, $absenceType->leaveRequests->first()->absence_type_id);
    }

    public function test_leave_requests_relationship_excludes_other_absence_types(): void
    {
        $vacation = AbsenceType::where('name', 'Vacation')->first();
        $sickLeave = AbsenceType::where('name', 'Sick Leave')->first();

        $user = User::factory()->create(['hire_date' => now()->subYear()->toDateString()]);
        $user->assignRole('employee');

        LeaveRequest::create([
            'user_id' => $user->id,
            'absence_type_id' => $sickLeave->id,
            'start_date' => '2026-08-04',
            'end_date' => '2026-08-06',
            'status' => 'pending',
        ]);

        $this->assertCount(0, $vacation->leaveRequests);
    }
}
