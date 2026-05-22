@php
    use Carbon\Carbon;

    $statusOptions = [
        'all' => __('All'),
        'pending' => __('Pending'),
        'approved' => __('Approved'),
        'rejected' => __('Rejected'),
    ];
@endphp
<x-app-layout>
    <x-slot name="title">
        {{ __('leave-requests.history') }}
    </x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('leave-requests.history') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('leave-requests.history_subtitle') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-management.header-card
                :title="__('leave-requests.history_title')"
                :subtitle="__('leave-requests.history_summary', ['total' => $totalCount, 'pending' => $pendingCount])"
                :action-href="route('leave-requests.management.index')"
                :action-label="__('leave-requests.view_pending')"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </x-slot:actionIcon>
            </x-management.header-card>

            <x-management.flash-success/>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div
                        class="flex flex-col sm:flex-row justify-between items-center mb-6 flex-wrap space-y-4 sm:space-y-0 sm:space-x-4">
                        <form method="GET" action="{{ route('leave-requests.management.history') }}"
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

                        <form method="GET" action="{{ route('leave-requests.management.history') }}"
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
                                    <x-sortable-link sortBy="user_id" label="{{ __('Employee Name') }}"/>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <x-sortable-link sortBy="created_at" label="{{ __('Submission Date') }}"/>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <x-sortable-link sortBy="start_date" label="{{ __('leave-requests.start_date') }}"/>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <x-sortable-link sortBy="end_date" label="{{ __('leave-requests.end_date') }}"/>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <x-sortable-link sortBy="absence_type_id" label="{{ __('leave-requests.absence_type') }}"/>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <x-sortable-link sortBy="status" label="{{ __('leave-requests.status') }}"/>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Details') }}
                                </th>
                            </tr>
                            </thead>

                            @forelse ($leaveRequests as $request)
                                @php
                                    $statusLabel = match ($request->status) {
                                        'approved' => __('Approved'),
                                        'rejected' => __('Rejected'),
                                        default => __('Pending'),
                                    };
                                    $statusClasses = match ($request->status) {
                                        'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    };
                                    $employeeName = $request->user?->name ?? __('messages.general.unknown_user');
                                @endphp
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $request->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100 w-48">
                                        <span class="block max-w-[9rem] truncate" title="{{ $employeeName }}">
                                            {{ $employeeName }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                        {{ $request->created_at?->isoFormat('L') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                        {{ Carbon::parse($request->start_date)->locale(app()->getLocale())->isoFormat('L') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                        {{ Carbon::parse($request->end_date)->locale(app()->getLocale())->isoFormat('L') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                        {{ __($request->absenceType->name) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-center text-sm font-medium whitespace-nowrap">
                                        <a href="{{ route('leave-requests.management.show', $request) }}"
                                           class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition duration-150"
                                           title="{{ __('leave-requests.view_details') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="size-5 mx-auto">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            @empty
                                <tbody class="bg-white dark:bg-gray-800">
                                <tr>
                                    <td colspan="8" class="px-6 py-16">
                                        <div class="text-center">
                                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">
                                                {{ __('leave-requests.history_empty_title') }}
                                            </h3>
                                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                {{ __('leave-requests.history_empty_body') }}
                                            </p>
                                        </div>
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

