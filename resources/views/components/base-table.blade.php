@props([
    'columns' => [],
    'emptyMessage' => 'No items found',
    'emptyDescription' => 'No items found in this status.'
])

<div class="overflow-x-auto">
    @if(trim($slot))
        <table class="min-w-full w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @foreach($columns as $col)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider {{ $col['class'] ?? '' }}">
                            {{ $col['label'] }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{ $slot }}
            </tbody>
        </table>
    @else
        <div class="px-6 py-12 text-center">
            <div class="text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ $emptyMessage }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ $emptyDescription }}</p>
            </div>
        </div>
    @endif
</div> 