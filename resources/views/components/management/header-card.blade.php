@props(['title', 'subtitle' => null, 'actionHref' => null, 'actionLabel' => null])

<div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-100 dark:border-none">
    <div class="px-6 py-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center space-x-3">
                @isset($icon)
                    <div class="flex items-center justify-center w-12 h-12 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                        {{ $icon }}
                    </div>
                @endisset
                <div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                        {{ $title }}
                    </h3>
                    @if ($subtitle)
                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>
            </div>
            @if ($actionHref && $actionLabel)
                <a href="{{ $actionHref }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-semibold rounded-lg shadow-md transition duration-150">
                    @isset($actionIcon)
                        <span class="mr-2">
                            {{ $actionIcon }}
                        </span>
                    @endisset
                    {{ $actionLabel }}
                </a>
            @elseif(isset($action))
                {{ $action }}
            @endif
        </div>
    </div>
</div>

