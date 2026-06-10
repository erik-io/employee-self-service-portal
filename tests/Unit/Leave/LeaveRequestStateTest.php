<?php

declare(strict_types=1);

namespace Tests\Unit\Leave;

use App\Models\LeaveRequest;
use App\Services\Leave\State\ApprovedState;
use App\Services\Leave\State\LeaveRequestStateFactory;
use App\Services\Leave\State\PendingState;
use App\Services\Leave\State\RejectedState;
use DomainException;
use InvalidArgumentException;
use Mockery;
use Tests\TestCase;

class LeaveRequestStateTest extends TestCase
{
    protected function tearDown(): void
    {
        $this->addToAssertionCount(Mockery::getContainer()->mockery_getExpectationCount());
        Mockery::close();
        parent::tearDown();
    }

    // PendingState::approve

    public function test_pending_state_approve_updates_leave_request_to_approved(): void
    {
        $leaveRequest = Mockery::mock(LeaveRequest::class);
        $leaveRequest->shouldReceive('update')
            ->once()
            ->with(['status' => 'approved', 'reviewer_id' => 5]);

        (new PendingState())->approve($leaveRequest, 5);
    }

    public function test_pending_state_approve_throws_for_zero_reviewer_id(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new PendingState())->approve(Mockery::mock(LeaveRequest::class), 0);
    }

    public function test_pending_state_approve_throws_for_negative_reviewer_id(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new PendingState())->approve(Mockery::mock(LeaveRequest::class), -1);
    }

    // PendingState::reject

    public function test_pending_state_reject_updates_leave_request_to_rejected(): void
    {
        $reason = 'Team at full capacity during this period.';
        $leaveRequest = Mockery::mock(LeaveRequest::class);
        $leaveRequest->shouldReceive('update')
            ->once()
            ->with(['status' => 'rejected', 'reviewer_id' => 3, 'rejection_reason' => $reason]);

        (new PendingState())->reject($leaveRequest, 3, $reason);
    }

    public function test_pending_state_reject_throws_for_zero_reviewer_id(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new PendingState())->reject(Mockery::mock(LeaveRequest::class), 0, 'Some valid reason.');
    }

    public function test_pending_state_reject_throws_for_negative_reviewer_id(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new PendingState())->reject(Mockery::mock(LeaveRequest::class), -1, 'Some valid reason.');
    }

    public function test_pending_state_reject_throws_for_empty_reason(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new PendingState())->reject(Mockery::mock(LeaveRequest::class), 1, '');
    }

    public function test_pending_state_reject_throws_for_whitespace_only_reason(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new PendingState())->reject(Mockery::mock(LeaveRequest::class), 1, '   ');
    }

    // ApprovedState

    public function test_approved_state_approve_throws_domain_exception(): void
    {
        $this->expectException(DomainException::class);

        (new ApprovedState())->approve(Mockery::mock(LeaveRequest::class), 1);
    }

    public function test_approved_state_reject_throws_domain_exception(): void
    {
        $this->expectException(DomainException::class);

        (new ApprovedState())->reject(Mockery::mock(LeaveRequest::class), 1, 'reason');
    }

    // RejectedState

    public function test_rejected_state_approve_throws_domain_exception(): void
    {
        $this->expectException(DomainException::class);

        (new RejectedState())->approve(Mockery::mock(LeaveRequest::class), 1);
    }

    public function test_rejected_state_reject_throws_domain_exception(): void
    {
        $this->expectException(DomainException::class);

        (new RejectedState())->reject(Mockery::mock(LeaveRequest::class), 1, 'reason');
    }

    // LeaveRequestStateFactory

    public function test_factory_creates_pending_state(): void
    {
        $this->assertInstanceOf(PendingState::class, LeaveRequestStateFactory::make('pending'));
    }

    public function test_factory_creates_approved_state(): void
    {
        $this->assertInstanceOf(ApprovedState::class, LeaveRequestStateFactory::make('approved'));
    }

    public function test_factory_creates_rejected_state(): void
    {
        $this->assertInstanceOf(RejectedState::class, LeaveRequestStateFactory::make('rejected'));
    }

    public function test_factory_throws_for_invalid_status(): void
    {
        $this->expectException(InvalidArgumentException::class);

        LeaveRequestStateFactory::make('unknown');
    }
}
