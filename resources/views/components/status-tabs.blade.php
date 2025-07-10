@props(['statuses', 'selectedStatus', 'statusCounts', 'onStatusClick'])

<div class="border-b border-gray-200 bg-gray-50 px-6">
    <nav class="-mb-px flex flex-wrap space-x-4 overflow-x-auto" aria-label="Tabs">
        <!-- All Tab -->
        <button wire:click="{{ $onStatusClick }}(-1)"
            class="whitespace-nowrap py-3 px-2 border-b-2 font-medium text-sm transition-colors {{ $selectedStatus == -1 ? 'border-brand text-brand' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Total
            <span
                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedStatus == -1 ? 'bg-brand/10 text-brand' : 'bg-gray-100 text-gray-800' }}">
                {{ $statusCounts['total'] ?? 0 }}
            </span>
        </button>
        
        @foreach($statuses as $status)
            <button wire:click="{{ $onStatusClick }}({{ $status->value }})"
                class="whitespace-nowrap py-3 px-2 border-b-2 font-medium text-sm transition-colors {{ $selectedStatus == $status->value ? 'border-brand text-brand' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                {{ $status->label() }}
                <span
                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedStatus == $status->value ? 'bg-brand/10 text-brand' : 'bg-gray-100 text-gray-800' }}">
                    {{ $statusCounts[$status->value] ?? 0 }}
                </span>
            </button>
        @endforeach
    </nav>
</div> 