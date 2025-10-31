<div class="mt-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div x-data="{ open: false }">
            <!-- Google Connection Status -->
            @if(!$isConnected)
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Google Account Not Connected</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Connect your Google account to sync with Google Tasks.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('platform.tasks.connect-google') }}" 
                           data-turbo="false"
                           target="_self"
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 12z"/>
                                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Connect Google Account
                        </a>
                    </div>
                </div>
            @else
                

                <!-- Two Column Layout -->
                <div class="flex h-[650px]">
                    <!-- Left Column - Settings and Form -->
                    <div class="w-1/3 border-r border-gray-200 flex flex-col">
                        <!-- Task List Selector and Filters -->
                        <div class="p-4 border-b border-gray-200 flex-shrink-0">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">List Settings</h3>
                            <div class="pb-4 space-y-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <img class="h-8 w-8 rounded-full" src="{{ $connectionInfo->picture }}" alt="{{ $connectionInfo->name }}">
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Connected as {{ $connectionInfo->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $connectionInfo->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button wire:click.prevent="refreshTasks" 
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Refresh
                                    </button>
                                    <button wire:click="disconnectGoogle" 
                                            class="inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Disconnect
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <!-- Task List Selector -->
                                <div>
                                    <label for="task-list" class="block text-sm font-medium text-gray-700 mb-1">Task List</label>
                                    <select wire:model.live="selectedListId" id="task-list" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        @foreach($taskLists as $list)
                                            <option value="{{ $list['id'] }}">{{ $list['title'] }}</option>
                                        @endforeach
                                        <option value="{{ '@default' }}">Default List</option>
                                    </select>
                                </div>

                                <!-- Show Completed Toggle -->
                                <div class="flex items-center">
                                    <input wire:model.live="showCompleted" id="show-completed" type="checkbox" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="show-completed" class="ml-2 block text-sm text-gray-900">
                                        Show completed tasks
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- New Task Form -->
                        <div class="p-4 flex-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Task</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="task-title" class="block text-sm font-medium text-gray-700 mb-1">Task Title</label>
                                    <input wire:model="newTaskTitle" type="text" id="task-title" required
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                           placeholder="Enter task title...">
                                    @error('newTaskTitle') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="task-due-date" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                                    <input wire:model="newTaskDueDate" type="date" id="task-due-date"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="task-notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <textarea wire:model="newTaskNotes" id="task-notes" rows="3"
                                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                              placeholder="Add notes..."></textarea>
                                </div>
                                <div class="pt-2">
                                    <button type="button" 
                                            wire:click="createTask"
                                            wire:loading.attr="disabled"
                                            wire:loading.class="opacity-50 cursor-not-allowed"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg wire:loading.remove class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        <svg wire:loading class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span wire:loading.remove>Add Task</span>
                                        <span wire:loading>Creating...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Tasks List -->
                    <div class="w-2/3 flex flex-col">
                        <!-- Tasks List -->
                        <div class="flex-1 overflow-y-auto">
                            <div class="divide-y divide-gray-200">
                                @forelse($this->getFilteredTasks() as $task)
                                    <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start space-x-3 flex-1">
                                                <button wire:click="toggleTaskCompletion('{{ $task['id'] }}')" 
                                                        class="flex-shrink-0 mt-0.5">
                                                    @if($task['status'] === 'completed')
                                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-gray-400 border-2 border-gray-300 rounded-full" fill="none" viewBox="0 0 20 20">
                                                            <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="2"/>
                                                        </svg>
                                                    @endif
                                                </button>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-md font-medium text-gray-900 {{ $task['status'] === 'completed' ? 'line-through text-gray-500' : '' }}">
                                                        {{ $task['title'] }}
                                                    </h3>
                                                    @if($task['notes'])
                                                        <p class="mt-1 text-sm text-gray-600 {{ $task['status'] === 'completed' ? 'line-through' : '' }}">
                                                            {{ $task['notes'] }}
                                                        </p>
                                                    @endif
                                                    @if($task['due'])
                                                        <p class="mt-1 text-xs text-gray-500">
                                                            Due: {{ \Carbon\Carbon::parse($task['due'])->format('M j, Y') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <button wire:click="deleteTask('{{ $task['id'] }}')"
                                    class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>                                                    <span class="ml-2">Delete</span>

                            </button>

                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-8 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks found</h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ $showCompleted ? 'No completed tasks in this list.' : 'No pending tasks in this list.' }}
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Notifications -->
        <div x-data="{ show: false, message: '', type: 'success' }" 
             x-show="show" x-cloak 
             @task-created.window="show = true; message = $event.detail.message; type = 'success'; setTimeout(() => show = false, 3000)"
             @task-updated.window="show = true; message = $event.detail.message; type = 'success'; setTimeout(() => show = false, 3000)"
             @task-deleted.window="show = true; message = $event.detail.message; type = 'success'; setTimeout(() => show = false, 3000)"
             @tasks-refreshed.window="show = true; message = $event.detail.message; type = 'success'; setTimeout(() => show = false, 3000)"
             @google-disconnected.window="show = true; message = $event.detail.message; type = 'success'; setTimeout(() => show = false, 3000)"
             @task-error.window="show = true; message = $event.detail.message; type = 'error'; setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 z-50">
            <div :class="type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'"
                 class="border px-4 py-3 rounded">
                <span x-text="message"></span>
            </div>
        </div>
    </div>
</div> 