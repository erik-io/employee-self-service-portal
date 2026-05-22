@props(['name', 'title', 'body' => null])

<x-modal :name="$name">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ $title }}
        </h2>

        @if($body)
            <p class="mt-2 text-base text-gray-600 dark:text-gray-300">
                {{ $body }}
            </p>
        @endif

        @isset($details)
            <div class="mt-4 space-y-2 text-sm text-gray-800 dark:text-gray-200 border-t border-b border-gray-200 dark:border-gray-600 py-4">
                {!! $details !!}
            </div>
        @endisset

        @isset($preview)
            <div class="pt-2">
                {!! $preview !!}
            </div>
        @endisset

        @isset($actions)
            <div class="mt-6 flex justify-end">
                {!! $actions !!}
            </div>
        @endisset
    </div>
</x-modal>

