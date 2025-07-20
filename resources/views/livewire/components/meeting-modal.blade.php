<div>
    @if($show)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $mode === 'create' ? 'Create New Meeting' : 'Edit Meeting' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="save" class="space-y-4">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Meeting Title *</label>
                            <input wire:model="title" type="text" id="title" required
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   placeholder="Enter meeting title">
                            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Type and Duration -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Meeting Type *</label>
                                <select wire:model="type" id="type" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="video">Video Call</option>
                                    <option value="phone">Phone Call</option>
                                </select>
                                @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes) *</label>
                                <select wire:model="duration_minutes" id="duration_minutes" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="15">15 minutes</option>
                                    <option value="30">30 minutes</option>
                                    <option value="45">45 minutes</option>
                                    <option value="60">1 hour</option>
                                    <option value="90">1.5 hours</option>
                                    <option value="120">2 hours</option>
                                </select>
                                @error('duration_minutes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Date and Time -->
                        <div>
                            <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-1">Date & Time *</label>
                            <input wire:model="scheduled_at" type="datetime-local" id="scheduled_at" required
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('scheduled_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Candidate and Job -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="candidate_id" class="block text-sm font-medium text-gray-700 mb-1">Candidate *</label>
                                <select wire:model="candidate_id" id="candidate_id" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Select a candidate</option>
                                    @foreach($candidates as $candidate)
                                        <option value="{{ $candidate->id }}">{{ $candidate->user->name }} ({{ $candidate->user->email }})</option>
                                    @endforeach
                                </select>
                                @error('candidate_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="job_id" class="block text-sm font-medium text-gray-700 mb-1">Related Job (Optional)</label>
                                <select wire:model="job_id" id="job_id"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Select a job (optional)</option>
                                    @foreach($jobs as $job)
                                        <option value="{{ $job->id }}">{{ $job->title }}</option>
                                    @endforeach
                                </select>
                                @error('job_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Meeting Link or Phone Number -->
                        @if($type === 'video')
                            <div>
                                <label for="meeting_link" class="block text-sm font-medium text-gray-700 mb-1">Meeting Link (Optional)</label>
                                <input wire:model="meeting_link" type="url" id="meeting_link"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                       placeholder="https://meet.google.com/...">
                                @error('meeting_link') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number (Optional)</label>
                                <input wire:model="phone_number" type="tel" id="phone_number"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                       placeholder="+1 (555) 123-4567">
                                @error('phone_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                            <textarea wire:model="description" id="description" rows="3"
                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                      placeholder="Add meeting details, agenda, or notes..."></textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Google Calendar Integration -->
                        @if(auth()->user()->googleConnection)
                            <div class="flex items-center">
                                <input wire:model="createGoogleEvent" type="checkbox" id="createGoogleEvent" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="createGoogleEvent" class="ml-2 block text-sm text-gray-900">
                                    Create Google Calendar event
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">
                                When enabled, this meeting will be automatically added to your Google Calendar with the candidate as an attendee.
                            </p>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">
                                            Google Calendar Integration
                                        </h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>
                                                Connect your Google account to automatically create calendar events for meetings.
                                                <a href="#" class="font-medium underline hover:text-yellow-600">
                                                    Connect Google Account
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" wire:click="closeModal"
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                {{ $mode === 'create' ? 'Create Meeting' : 'Update Meeting' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
