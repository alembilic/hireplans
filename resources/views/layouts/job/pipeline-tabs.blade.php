<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Application Pipeline</h3>
    </div>
    
    <div class="px-6 py-4">
        <div class="flex flex-wrap gap-2">
            @foreach($statuses as $status)
                <button 
                    wire:click="selectStatus({{ $status->value }})"
                    class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200
                        {{ $selectedStatus == $status->value 
                            ? 'bg-blue-100 text-blue-700 border-2 border-blue-300' 
                            : 'bg-gray-50 text-gray-700 hover:bg-gray-100 border-2 border-transparent' }}"
                >
                    <span class="mr-2">{{ $status->label() }}</span>
                    <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold rounded-full
                        {{ $selectedStatus == $status->value 
                            ? 'bg-blue-200 text-blue-800' 
                            : 'bg-gray-200 text-gray-600' }}">
                        {{ $statusCounts[$status->value] ?? 0 }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>
</div> 