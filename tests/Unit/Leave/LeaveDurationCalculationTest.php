<?php

declare(strict_types=1);

namespace Tests\Unit\Leave;

use App\Services\Leave\LeaveDurationService;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Tests\TestCase;

class LeaveDurationCalculationTest extends TestCase
{
    private LeaveDurationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new LeaveDurationService();
    }

    // US5 AC1: Saturdays and Sundays are not counted as vacation days

    public function test_saturday_is_not_counted_as_working_day(): void
    {
        // 2026-07-11 is a Saturday
        $saturday = Carbon::parse('2026-07-11');

        $days = $this->service->calculateNetDays($saturday, $saturday);

        $this->assertEquals(0, $days);
    }

    public function test_sunday_is_not_counted_as_working_day(): void
    {
        // 2026-07-12 is a Sunday
        $sunday = Carbon::parse('2026-07-12');

        $days = $this->service->calculateNetDays($sunday, $sunday);

        $this->assertEquals(0, $days);
    }

    public function test_monday_to_friday_without_holiday_counts_as_five_days(): void
    {
        // 2026-07-06 (Mon) to 2026-07-10 (Fri) — no German public holidays
        $start = Carbon::parse('2026-07-06');
        $end = Carbon::parse('2026-07-10');

        $days = $this->service->calculateNetDays($start, $end);

        $this->assertEquals(5, $days);
    }

    public function test_range_spanning_a_weekend_excludes_both_weekend_days(): void
    {
        // 2026-07-06 (Mon) to 2026-07-13 (Mon) = 6 working days, 2 weekend days excluded
        $start = Carbon::parse('2026-07-06');
        $end = Carbon::parse('2026-07-13');

        $days = $this->service->calculateNetDays($start, $end);

        $this->assertEquals(6, $days);
    }

    // US5 AC2: German public holidays are not counted as vacation days

    public function test_german_new_years_day_is_not_counted_as_working_day(): void
    {
        // 2026-01-01 (Thursday) is Neujahrstag — a nationwide German public holiday
        $newYearsDay = Carbon::parse('2026-01-01');

        $days = $this->service->calculateNetDays($newYearsDay, $newYearsDay);

        $this->assertEquals(0, $days);
    }

    public function test_german_public_holiday_within_range_is_excluded(): void
    {
        // 2026-01-01 (Thursday) is Neujahrstag — a nationwide German public holiday
        // 2025-12-29 (Mon) to 2026-01-02 (Fri): 5 weekdays but one is a holiday → 4 net days
        $start = Carbon::parse('2025-12-29');
        $end = Carbon::parse('2026-01-02');

        $days = $this->service->calculateNetDays($start, $end);

        $this->assertEquals(4, $days);
    }

    // US5 AC3: End date before start date triggers an error

    public function test_end_date_before_start_date_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->service->calculateNetDays(
            Carbon::parse('2026-08-10'),
            Carbon::parse('2026-08-05')
        );
    }

    public function test_same_start_and_end_date_on_working_day_counts_as_one_day(): void
    {
        // 2026-07-06 is a Monday with no holiday
        $monday = Carbon::parse('2026-07-06');

        $days = $this->service->calculateNetDays($monday, $monday);

        $this->assertEquals(1, $days);
    }

    public function test_two_consecutive_working_days_count_as_two(): void
    {
        $monday = Carbon::parse('2026-07-06');
        $tuesday = Carbon::parse('2026-07-07');

        $days = $this->service->calculateNetDays($monday, $tuesday);

        $this->assertEquals(2, $days);
    }
}
