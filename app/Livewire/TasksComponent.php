<?php

namespace App\Livewire;

use App\Services\GoogleTasksService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class TasksComponent extends Component
{
    use WithPagination;

    public $selectedListId = '@default';
    public $showCompleted = false;
    public $taskLists = [];
    public $tasks = [];
    public $isConnected = false;
    public $connectionInfo = null;

    // New task form
    public $newTaskTitle = '';
    public $newTaskNotes = '';
    public $newTaskDueDate = '';

    protected $queryString = [
        'selectedListId' => ['except' => '@default'],
        'showCompleted' => ['except' => false],
    ];

    public function mount()
    {
        $this->loadGoogleConnection();
        if ($this->isConnected) {
            $this->loadTaskLists();
            $this->loadTasks();
        }
    }

    public function loadGoogleConnection()
    {
        $user = Auth::user();
        $this->isConnected = $user->googleConnection && $user->googleConnection->isValid();
        $this->connectionInfo = $user->googleConnection;
    }

    public function loadTaskLists()
    {
        $user = Auth::user();
        $googleService = new GoogleTasksService($user);
        $rawTaskLists = $googleService->getTaskLists();
        
        // Convert Google API objects to simple arrays for Livewire compatibility
        $this->taskLists = collect($rawTaskLists)->map(function ($list) {
            return [
                'id' => $list->id ?? '',
                'title' => $list->title ?? '',
                'kind' => $list->kind ?? '',
                'etag' => $list->etag ?? '',
                'selfLink' => $list->selfLink ?? '',
                'updated' => $list->updated ?? '',
            ];
        })->toArray();
    }

    public function loadTasks()
    {
        $user = Auth::user();
        $googleService = new GoogleTasksService($user);
        $rawTasks = $googleService->getTasks($this->selectedListId, $this->showCompleted);
        
        // Convert Google API objects to simple arrays for Livewire compatibility
        $this->tasks = collect($rawTasks)->map(function ($task) {
            return [
                'id' => $task->id ?? '',
                'title' => $task->title ?? '',
                'notes' => $task->notes ?? '',
                'status' => $task->status ?? 'needsAction',
                'due' => $task->due ?? null,
                'completed' => $task->completed ?? null,
                'kind' => $task->kind ?? '',
                'etag' => $task->etag ?? '',
                'selfLink' => $task->selfLink ?? '',
                'updated' => $task->updated ?? '',
                'position' => $task->position ?? '',
                'parent' => $task->parent ?? '',
                'links' => $task->links ?? [],
            ];
        })->toArray();
    }

    public function updatedSelectedListId()
    {
        $this->loadTasks();
        $this->resetPage();
    }

    public function updatedShowCompleted()
    {
        $this->loadTasks();
        $this->resetPage();
    }

    public function createTask()
    {
        $this->validate([
            'newTaskTitle' => 'required|string|max:255',
            'newTaskNotes' => 'nullable|string',
            'newTaskDueDate' => 'nullable|date',
        ]);

        $user = Auth::user();
        $googleService = new GoogleTasksService($user);

        $options = [];
        if ($this->newTaskNotes) {
            $options['notes'] = $this->newTaskNotes;
        }
        if ($this->newTaskDueDate) {
            $options['due'] = $this->newTaskDueDate . 'T00:00:00Z';
        }

        $task = $googleService->createTask($this->newTaskTitle, $this->selectedListId, $options);

        if ($task) {
            $this->reset(['newTaskTitle', 'newTaskNotes', 'newTaskDueDate']);
            $this->loadTasks();
            $this->dispatch('task-created', message: 'Task created successfully!');
        } else {
            $this->dispatch('task-error', message: 'Failed to create task. Please try again.');
        }
    }

    public function toggleTaskCompletion($taskId)
    {
        $user = Auth::user();
        $googleService = new GoogleTasksService($user);

        // Find the task to check its current status
        $task = collect($this->tasks)->firstWhere('id', $taskId);
        
        if ($task) {
            $success = $task['status'] === 'completed' 
                ? $googleService->uncompleteTask($taskId, $this->selectedListId)
                : $googleService->completeTask($taskId, $this->selectedListId);

            if ($success) {
                $this->loadTasks();
                $this->dispatch('task-updated', message: 'Task updated successfully!');
            } else {
                $this->dispatch('task-error', message: 'Failed to update task. Please try again.');
            }
        }
    }

    public function deleteTask($taskId)
    {
        $user = Auth::user();
        $googleService = new GoogleTasksService($user);

        $success = $googleService->deleteTask($taskId, $this->selectedListId);

        if ($success) {
            $this->loadTasks();
            $this->dispatch('task-deleted', message: 'Task deleted successfully!');
        } else {
            $this->dispatch('task-error', message: 'Failed to delete task. Please try again.');
        }
    }

    public function refreshTasks()
    {
        $this->loadTasks();
        $this->dispatch('tasks-refreshed', message: 'Tasks refreshed successfully!');
    }

    public function disconnectGoogle()
    {
        $user = Auth::user();
        $googleService = new GoogleTasksService($user);

        $success = $googleService->disconnect();

        if ($success) {
            $this->loadGoogleConnection();
            $this->taskLists = [];
            $this->tasks = [];
            $this->dispatch('google-disconnected', message: 'Google account disconnected successfully!');
        } else {
            $this->dispatch('task-error', message: 'Failed to disconnect Google account. Please try again.');
        }
    }

    public function getFilteredTasks()
    {
        return collect($this->tasks);
    }

    public function getTaskListName($listId)
    {
        $list = collect($this->taskLists)->firstWhere('id', $listId);
        return $list ? $list['title'] : 'Default List';
    }

    public function render()
    {
        return view('livewire.tasks-component');
    }
} 