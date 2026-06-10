<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AbsenceType;
use App\Models\Expense;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $testEmployee = User::where('email', 'employee@example.com')->first();
        $supervisor = User::where('email', 'supervisor@example.com')->first();

        if ($testEmployee) {
            $this->ensureAbsenceProfileFields($testEmployee);
            $this->createExpensesForUser($testEmployee);
            $this->createLeaveRequestsForUser($testEmployee, $supervisor);
        }

        $randomEmployees = User::factory(10)->create();
        foreach ($randomEmployees as $employee) {
            $employee->assignRole('employee');
            $this->ensureAbsenceProfileFields($employee);
            $this->createExpensesForUser($employee);
            $this->createLeaveRequestsForUser($employee, $supervisor);
        }
    }

    private function ensureAbsenceProfileFields(User $user): void
    {
        $updates = [];

        if ($user->hire_date === null) {
            $updates['hire_date'] = Carbon::now()
                ->subYears(rand(1, 15))
                ->subDays(rand(0, 364))
                ->toDateString();
        }

        if ($user->weekly_working_days < 1 || $user->weekly_working_days > 7) {
            $updates['weekly_working_days'] = rand(3, 5);
        }

        if (!array_key_exists('has_severe_disability', $user->getAttributes())) {
            $updates['has_severe_disability'] = rand(1, 100) <= 10;
        }

        if ($updates !== []) {
            $user->update($updates);
        }
    }

    private function createExpensesForUser(User $user): void
    {
        Expense::factory()
            ->count(rand(3, 7))
            ->pending()
            ->randomizeSubmissionDate()
            ->create(['user_id' => $user->id]);

        Expense::factory()
            ->count(rand(5, 15))
            ->approved()
            ->randomizeSubmissionDate(10, 365)
            ->create(['user_id' => $user->id]);

        Expense::factory()
            ->count(rand(3, 7))
            ->rejected()
            ->randomizeSubmissionDate(30, 365)
            ->create(['user_id' => $user->id]);
    }

    private function createLeaveRequestsForUser(User $user, ?User $reviewer): void
    {
        $absenceTypeIds = AbsenceType::pluck('id')->all();

        if ($absenceTypeIds === []) {
            return;
        }

        $this->createLeaveRequestsBatch($user, $reviewer, $absenceTypeIds, rand(2, 5), 'pending', 0, 90);
        $this->createLeaveRequestsBatch($user, $reviewer, $absenceTypeIds, rand(4, 10), 'approved', 30, 365);
        $this->createLeaveRequestsBatch($user, $reviewer, $absenceTypeIds, rand(2, 5), 'rejected', 30, 365);
    }

    private function createLeaveRequestsBatch(
        User $user,
        ?User $reviewer,
        array $absenceTypeIds,
        int $count,
        string $status,
        int $minDaysAgo,
        int $maxDaysAgo
    ): void {
        for ($i = 0; $i < $count; $i++) {
            $start = Carbon::now()->subDays(rand($minDaysAgo, $maxDaysAgo));
            $end = $start->copy()->addDays(rand(0, 10));
            $createdAt = $start->copy()->subDays(rand(1, 20));

            LeaveRequest::create([
                'user_id' => $user->id,
                'absence_type_id' => $absenceTypeIds[array_rand($absenceTypeIds)],
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'status' => $status,
                'rejection_reason' => $status === 'rejected' ? fake()->sentence(10) : null,
                'reviewer_id' => in_array($status, ['approved', 'rejected'], true) ? ($reviewer?->id) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
