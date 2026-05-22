@php
    use App\Models\LeaveRequest;
    use Carbon\Carbon;

    $statusLabel = match ($leaveRequest->status) {
        'approved' => __('Approved'),
        'rejected' => __('Rejected'),
        default => __('Pending'),
    };
    $statusClasses = match ($leaveRequest->status) {
        'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
    };
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('leave-requests.manage') }} #{{ $leaveRequest->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($errors->any() && !$errors->has('rejection_reason'))
                        <div
                            class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 rounded">
                            <strong>{{ __('Error') }}</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4 text-right">
                        <x-primary-button href="{{ route('leave-requests.management.index') }}">
                            {{ __('Back to List') }}
                        </x-primary-button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('leave-requests.details') }}
                            </h3>
                            <dl class="mt-2 space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-base font-medium text-gray-500 dark:text-gray-400 mr-1">{{ __('Employee') }}</dt>
                                    <dd class="text-base text-gray-900 dark:text-gray-100">
                                        {{ $leaveRequest->user?->name ?? __('messages.general.unknown_user') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-base font-medium text-gray-500 dark:text-gray-400 mr-1">{{ __('Email') }}</dt>
                                    <dd class="text-base text-gray-900 dark:text-gray-100">
                                        @if ($leaveRequest->user?->email)
                                            <a href="mailto:{{ $leaveRequest->user->email }}"
                                               class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                                {{ $leaveRequest->user->email }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-base font-medium text-gray-500 dark:text-gray-400 mr-1">{{ __('leave-requests.start_date') }}</dt>
                                    <dd class="text-base text-gray-900 dark:text-gray-100">
                                        {{ Carbon::parse($leaveRequest->start_date)->locale(app()->getLocale())->isoFormat('L') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-base font-medium text-gray-500 dark:text-gray-400 mr-1">{{ __('leave-requests.end_date') }}</dt>
                                    <dd class="text-base text-gray-900 dark:text-gray-100">
                                        {{ Carbon::parse($leaveRequest->end_date)->locale(app()->getLocale())->isoFormat('L') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-base font-medium text-gray-500 dark:text-gray-400 mr-1">{{ __('leave-requests.submitted_on') }}</dt>
                                    <dd class="text-base text-gray-900 dark:text-gray-100">
                                        {{ $leaveRequest->created_at?->isoFormat('LLL') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-base font-medium text-gray-500 dark:text-gray-400 mr-1">{{ __('leave-requests.absence_type') }}</dt>
                                    <dd class="text-base text-gray-900 dark:text-gray-100">
                                        {{ __($leaveRequest->absenceType->name) }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-base font-medium text-gray-500 dark:text-gray-400 mr-1">{{ __('leave-requests.status') }}</dt>
                                    <dd class="text-base text-gray-900 dark:text-gray-100">
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>

                            <div
                                class="mt-6 p-4 bg-white dark:bg-gray-800 border-l-4 {{ $teamOverlaps->isNotEmpty() ? 'border-red-500' : 'border-green-500' }}">
                                <h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    {{ __('Team Capacity Check') }}
                                </h4>

                                @if ($teamOverlaps->isNotEmpty())
                                    <p class="text-sm text-red-600 dark:text-red-400 mb-4 font-semibold">
                                        {{ __('Warning: The following team members have approved or pending leave during this period.') }}
                                    </p>
                                    <ul class="space-y-2">
                                        @foreach ($teamOverlaps as $overlap)
                                            <li class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-2 rounded">
                                                <strong>{{ $overlap->user->name }}</strong>: {{ $overlap->absenceType->name }}
                                                ({{ Carbon::parse($overlap->start_date)->format('Y-m-d') }}
                                                to {{ Carbon::parse($overlap->end_date)->format('Y-m-d') }}) -
                                                <em>{{ ucfirst($overlap->status) }}</em>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-green-600 dark:text-green-400 font-semibold">
                                        {{ __('No overlaps detected. The team is fully staffed during this period.') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div x-data="{
                            showRejectForm: {{ $errors->has('rejection_reason') ? 'true' : 'false' }},
                            maxChars: {{ LeaveRequest::MAX_REJECTION_REASON_LENGTH }},
                            rejectionReason: @js(old('rejection_reason', ''))
                            }">
                            @if ($leaveRequest->status === 'pending')
                                <div x-show="!showRejectForm" x-transition:enter class="space-y-2">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Actions') }}</h3>
                                    <div class="mt-2 grid grid-cols-2 gap-4">
                                        <x-primary-button type="button"
                                                          x-data=""
                                                          x-on:click.prevent="$dispatch('open-modal', 'confirm-approval')"
                                                          class="w-full justify-center bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:ring-green-500">
                                            {{ __('Approve') }}
                                        </x-primary-button>
                                        <x-danger-button type="button" @click="showRejectForm = true"
                                                         class="w-full justify-center">
                                            {{ __('Reject') }}
                                        </x-danger-button>
                                    </div>
                                </div>

                                <form method="POST"
                                      action="{{ route('leave-requests.management.reject', $leaveRequest) }}"
                                      x-show="showRejectForm" x-cloak x-transition
                                      x-ref="rejectForm"
                                      x-on:submit.prevent="$dispatch('open-modal', 'confirm-rejection')"
                                      class="mt-4">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <x-input-label for="rejection_reason" :value="__('leave-requests.rejection_reason')"/>
                                        <textarea id="rejection_reason"
                                                  name="rejection_reason"
                                                  rows="4"
                                                  class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm min-h-32"
                                                  x-model="rejectionReason"
                                                  maxlength="{{ LeaveRequest::MAX_REJECTION_REASON_LENGTH }}"
                                                  required minlength="5">{{ old('rejection_reason') }}</textarea>
                                        <x-input-error class="mt-2" :messages="$errors->get('rejection_reason')"/>

                                        <div class="mt-1 text-base text-gray-500 dark:text-gray-400 text-right">
                                            <span x-text="rejectionReason.length"></span> / <span x-text="maxChars"></span>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex space-x-4">
                                        <x-danger-button type="submit" class="w-full justify-center">
                                            {{ __('Confirm Rejection') }}
                                        </x-danger-button>
                                        <x-secondary-button type="button" @click="showRejectForm = false"
                                                            class="w-full justify-center">
                                            {{ __('Cancel') }}
                                        </x-secondary-button>
                                    </div>

                                    <x-management.confirm-modal
                                        name="confirm-rejection"
                                        :title="__('leave-requests.modal.rejection.title')"
                                        :body="__('leave-requests.modal.rejection.body')"
                                    >
                                        <x-slot:details>
                                            <div class="flex justify-between">
                                                <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('Employee') }}:</span>
                                                <span class="font-bold">
                                                    {{ $leaveRequest->user?->name ?? __('messages.general.unknown_user') }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('leave-requests.absence_type') }}:</span>
                                                <span class="font-bold">{{ __($leaveRequest->absenceType->name) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('leave-requests.period') }}:</span>
                                                <span class="font-bold">
                                                    {{ Carbon::parse($leaveRequest->start_date)->locale(app()->getLocale())->isoFormat('L') }}
                                                    - {{ Carbon::parse($leaveRequest->end_date)->locale(app()->getLocale())->isoFormat('L') }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('leave-requests.submitted_on') }}:</span>
                                                <span class="font-bold">{{ $leaveRequest->created_at?->isoFormat('LLL') }}</span>
                                            </div>
                                        </x-slot:details>
                                        <x-slot:preview>
                                            <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('leave-requests.rejection_reason') }}:</span>
                                            <div
                                                class="mt-1 p-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded text-gray-800 dark:text-gray-200 whitespace-pre-line break-words"
                                                x-text="rejectionReason"></div>
                                        </x-slot:preview>
                                        <x-slot:actions>
                                            <x-secondary-button
                                                x-on:click="$dispatch('close-modal', 'confirm-rejection')">
                                                {{ __('Cancel') }}
                                            </x-secondary-button>
                                            <x-danger-button type="button"
                                                             x-on:click="$refs.rejectForm.submit()"
                                                             class="ms-3">
                                                {{ __('Reject') }}
                                            </x-danger-button>
                                        </x-slot:actions>
                                    </x-management.confirm-modal>
                                </form>

                                <x-management.confirm-modal
                                    name="confirm-approval"
                                    :title="__('leave-requests.modal.approval.title')"
                                    :body="__('leave-requests.modal.approval.body')"
                                >
                                    <x-slot:details>
                                        <div class="flex justify-between">
                                            <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('Employee') }}:</span>
                                            <span class="font-bold">
                                                {{ $leaveRequest->user?->name ?? __('messages.general.unknown_user') }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('leave-requests.absence_type') }}:</span>
                                            <span class="font-bold">{{ __($leaveRequest->absenceType->name) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('leave-requests.period') }}:</span>
                                            <span class="font-bold">
                                                {{ Carbon::parse($leaveRequest->start_date)->locale(app()->getLocale())->isoFormat('L') }}
                                                - {{ Carbon::parse($leaveRequest->end_date)->locale(app()->getLocale())->isoFormat('L') }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('leave-requests.submitted_on') }}:</span>
                                            <span class="font-bold">{{ $leaveRequest->created_at?->isoFormat('LLL') }}</span>
                                        </div>
                                    </x-slot:details>
                                    <x-slot:actions>
                                        <x-secondary-button
                                            x-on:click="$dispatch('close-modal', 'confirm-approval')">
                                            {{ __('Cancel') }}
                                        </x-secondary-button>
                                        <form method="POST" action="{{ route('leave-requests.management.approve', $leaveRequest) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-primary-button
                                                class="ms-3 bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:ring-green-500">
                                                {{ __('leave-requests.modal.approval.confirm') }}
                                            </x-primary-button>
                                        </form>
                                    </x-slot:actions>
                                </x-management.confirm-modal>
                            @elseif ($leaveRequest->status === 'rejected' && filled($leaveRequest->rejection_reason))
                                <h3 class="text-lg font-medium text-red-900 dark:text-red-300">
                                    {{ __('leave-requests.rejection_reason') }}
                                </h3>
                                <div
                                    class="mt-2 p-4 bg-gray-50 dark:bg-gray-700 border-l-4 border-red-400 dark:border-red-600">
                                    <p class="text-base text-gray-700 dark:text-gray-300 break-words hyphens-auto whitespace-pre-line">
                                        {{ $leaveRequest->rejection_reason }}
                                    </p>
                                </div>
                            @elseif ($leaveRequest->status === 'approved')
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Status') }}</h3>
                                <div
                                    class="mt-2 p-4 bg-gray-50 dark:bg-gray-700 border-l-4 border-green-400 dark:border-green-600">
                                    <p class="text-base text-gray-700 dark:text-gray-300">
                                        {{ __('This leave request has already been approved.') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
