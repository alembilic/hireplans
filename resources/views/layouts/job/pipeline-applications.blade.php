<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">{{ $statusLabel }} Applications ({{ $applications->count() }})</h3>
    </div>
    
    <div class="overflow-x-auto">
        @if($applications->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Candidate
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Application Ref
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Applied Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($applications as $application)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ strtoupper(substr($application->candidate->user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $application->candidate->user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $application->candidate->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $application->application_ref }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $application->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $status = \App\Enums\JobApplicationStatus::fromValue($application->status);
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $status?->color() ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $status?->label() ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('platform.candidates.view', $application->candidate->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        View Candidate
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('platform.job_application.view', $application->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        View Application
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <button 
                                        onclick="openStatusModal({{ $application->id }}, {{ $application->status }})"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        Update Status
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-12 text-center">
                <div class="text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No applications</h3>
                    <p class="mt-1 text-sm text-gray-500">No applications found in this status.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Application Status</h3>
            <form id="statusForm" method="POST">
                @csrf
                <input type="hidden" name="application_id" id="applicationId">
                <div class="mb-4">
                    <label for="newStatus" class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                    <select name="new_status" id="newStatus" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach(\App\Enums\JobApplicationStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStatusModal(applicationId, currentStatus) {
    document.getElementById('applicationId').value = applicationId;
    document.getElementById('newStatus').value = currentStatus;
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStatusModal();
    }
});

// Handle form submission
document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("platform.jobs.pipeline") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeStatusModal();
            // Reload the page to show updated data
            window.location.reload();
        } else {
            alert('Error updating status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating status');
    });
});
</script> 