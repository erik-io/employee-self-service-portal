@props(['reason', 'colspan', 'title'])

<tbody x-data="{ open: false }" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
        {{ $cells }}
        <td class="px-3 py-4 text-center text-sm font-medium">
            @if(filled($reason))
                <button
                    @click="open = !open"
                    type="button"
                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200 transition duration-150 ease-in-out"
                    title="{{ $title }}">
                    <i class="fa-solid fa-comment-dots fa-lg" :class="{'text-gray-400': open}"></i>
                </button>
            @endif
        </td>
    </tr>
    @if(filled($reason))
        <tr x-show="open" x-transition x-cloak>
            <td colspan="{{ $colspan }}" class="p-0">
                <div class="p-4 bg-gray-50 dark:bg-gray-700 border-l-4 border-red-400 dark:border-red-600">
                    <h4 class="font-bold text-sm text-red-800 dark:text-red-300">{{ $title }}</h4>
                    <p class="mt-1 text-sm text-gray-700 dark:text-gray-300 break-all hyphens-auto">
                        {!! nl2br(e($reason)) !!}
                    </p>
                </div>
            </td>
        </tr>
    @endif
</tbody>

