<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Schedule</h2>
                        <p class="text-gray-600">Manage your meetings with candidates</p>
                    </div>
                    <button wire:click="openCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Meeting
                    </button>
                </div>

                <!-- Filters -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input wire:model.live.debounce.300ms="search" type="text" id="search" 
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                               placeholder="Search meetings, candidates, or jobs...">
                    </div>
                    <div>
                        <label for="filterType" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select wire:model.live="filterType" id="filterType" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Types</option>
                            <option value="video">Video</option>
                            <option value="phone">Phone</option>
                        </select>
                    </div>
                    <div>
                        <label for="filterStatus" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model.live="filterStatus" id="filterStatus" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Status</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button wire:click="$set('search', '')" wire:click="$set('filterType', '')" wire:click="$set('filterStatus', '')" 
                                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm">
                            Clear Filters
                        </button>
                    </div>
                </div>

                <!-- Meetings List -->
                <div class="overflow-hidden">
                    @if($meetings->count() > 0)
                        <div class="grid gap-4">
                            @foreach($meetings as $meeting)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $meeting->title }}</h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $meeting->type === 'video' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ ucfirst($meeting->type) }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $meeting->status === 'scheduled' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($meeting->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst($meeting->status) }}
                                                </span>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                                <div>
                                                    <span class="font-medium">Candidate:</span>
                                                    <span>{{ $meeting->candidate->user->name }}</span>
                                                </div>
                                                <div>
                                                    <span class="font-medium">Scheduled:</span>
                                                    <span>{{ $meeting->formatted_scheduled_time }}</span>
                                                </div>
                                                <div>
                                                    <span class="font-medium">Duration:</span>
                                                    <span>{{ $meeting->formatted_duration }}</span>
                                                </div>
                                            </div>
                                            
                                            @if($meeting->job)
                                                <div class="mt-2 text-sm text-gray-600">
                                                    <span class="font-medium">Related Job:</span>
                                                    <span>{{ $meeting->job->title }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($meeting->description)
                                                <div class="mt-2 text-sm text-gray-600">
                                                    <span class="font-medium">Description:</span>
                                                    <span>{{ Str::limit($meeting->description, 100) }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($meeting->type === 'video' && $meeting->meeting_link)
                                                <div class="mt-2">
                                                    <a href="{{ $meeting->meeting_link }}" target="_blank" 
                                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                        Join Meeting →
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center gap-2">
                                            @if($meeting->status === 'scheduled')
                                                <button wire:click="updateStatus({{ $meeting->id }}, 'completed')" 
                                                        class="text-green-600 hover:text-green-800 text-sm font-medium">
                                                    Mark Complete
                                                </button>
                                                <button wire:click="updateStatus({{ $meeting->id }}, 'cancelled')" 
                                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                    Cancel
                                                </button>
                                            @endif
                                            
                                            <button wire:click="openEditModal({{ $meeting->id }})" 
                                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Edit
                                            </button>
                                            
                                            <button wire:click="deleteMeeting({{ $meeting->id }})" 
                                                    wire:confirm="Are you sure you want to delete this meeting?"
                                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $meetings->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No meetings found</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new meeting.</p>
                            <div class="mt-6">
                                <button wire:click="openCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                    Create Meeting
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Meeting Modal -->
    <livewire:components.meeting-modal />

    <!-- Notifications -->
    <div x-data="{ show: false, message: '', type: 'success' }" 
         x-show="show" x-cloak 
         @meeting-created.window="show = true; message = $event.detail.message; type = 'success'; setTimeout(() => show = false, 3000)"
         @meeting-updated.window="show = true; message = $event.detail.message; type = 'success'; setTimeout(() => show = false, 3000)"
         @meeting-deleted.window="show = true; message = $event.detail.message; type = 'success'; setTimeout(() => show = false, 3000)"
         @meeting-status-updated.window="show = true; message = $event.detail.message; type = 'success'; setTimeout(() => show = false, 3000)"
         class="fixed top-4 right-4 z-50">
        <div :class="type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'"
             class="border px-4 py-3 rounded">
            <span x-text="message"></span>
        </div>
    </div>
</div>
