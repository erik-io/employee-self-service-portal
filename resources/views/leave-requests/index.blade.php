@php use Carbon\Carbon; @endphp
<x-app-layout>
    <x-slot name="title">
        {{ __('leave-requests.my_requests') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('leave-requests.my_requests') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('leave-requests.overview_current_year') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-management.header-card
                :title="__('leave-requests.my_absences_title')"
                :subtitle="__('leave-requests.my_absences_summary', ['total' => $leaveRequests->total()])"
                :action-href="route('leave-requests.create')"
                :action-label="__('leave-requests.create')"
            >
                <x-slot:icon>
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </x-slot:icon>
                <x-slot:actionIcon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </x-slot:actionIcon>
            </x-management.header-card>

            @if (session('success'))
                <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                     role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="p-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-900"
                     role="alert">
                    {{ session('warning') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 flex-wrap space-y-4 sm:space-y-0 sm:space-x-4">
                        @php
                            $statusOptions = [
                                'all' => __('All'),
                                'pending' => __('Pending'),
                                'approved' => __('Approved'),
                                'rejected' => __('Rejected'),
                            ];
                        @endphp
                        <form method="GET" action="{{ route('leave-requests.index') }}"
                              class="flex items-center space-x-2">
                            <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                            <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'desc') }}">
                            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Filter') }}
                            </label>
                            <select name="status" id="status"
                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-300 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800 focus:ring-opacity-50 rounded-md shadow-sm"
                                    onchange="this.form.submit()">
                                @foreach ($statusOptions as $key => $label)
                                    <option value="{{ $key }}" {{ request('status', 'all') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        <form method="GET" action="{{ route('leave-requests.index') }}"
                              class="flex items-center space-x-2">
                            <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                            <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'desc') }}">
                            <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                            <label for="per_page" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Showing') }}
                            </label>
                            <select name="per_page" id="per_page"
                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-300 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800 focus:ring-opacity-50 rounded-md shadow-sm"
                                    onchange="this.form.submit()">
                                @foreach ([10, 25, 50, 100] as $value)
                                    <option value="{{ $value }}" {{ request('per_page', 10) == $value ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="per_page" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('per page') }}
                            </label>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('ID') }}
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <x-sortable-link sortBy="absence_type_id" label="{{ __('leave-requests.type') }}"/>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <x-sortable-link sortBy="start_date" label="{{ __('leave-requests.period') }}"/>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <x-sortable-link sortBy="created_at" label="{{ __('leave-requests.submitted_on') }}"/>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <x-sortable-link sortBy="status" label="{{ __('leave-requests.status') }}"/>
                                </th>
                                <th class="px-2 py-3"></th>
                            </tr>
                            </thead>
                            @forelse ($leaveRequests as $leaveRequest)
                                @php
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
                                <x-management.rejection-row
                                    :reason="$leaveRequest->rejection_reason"
                                    :colspan="6"
                                    :title="__('leave-requests.rejection_reason')"
                                >
                                    <x-slot:cells>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $leaveRequest->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ __($leaveRequest->absenceType->name) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ Carbon::parse($leaveRequest->start_date)->locale(app()->getLocale())->isoFormat('L') }}
                                            - {{ Carbon::parse($leaveRequest->end_date)->locale(app()->getLocale())->isoFormat('L') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ Carbon::parse($leaveRequest->created_at)->locale(app()->getLocale())->isoFormat('L') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                    </x-slot:cells>
                                </x-management.rejection-row>
                            @empty
                                <tbody class="bg-white dark:bg-gray-800">
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-10 text-sm text-gray-500 dark:text-gray-400 text-center">
                                        {{ __('leave-requests.no_leave_requests_yet') }}
                                    </td>
                                </tr>
                                </tbody>
                            @endforelse
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $leaveRequests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

