<?php

namespace App\Livewire;

use App\Enums\EmployerStatus;
use App\Models\Employer;
use Livewire\Component;
use Livewire\WithPagination;

class EmployerPipeline extends Component
{
    use WithPagination;

    public $selectedStatus = -1;
    public $employers = [];
    public $statusCounts = [];

    protected $queryString = [
        'selectedStatus' => ['except' => -1],
    ];

    public function mount()
    {
        $this->loadEmployers();
    }

    public function updatedSelectedStatus($value)
    {
        $this->resetPage();
    }

    public function loadEmployers()
    {
        $employers = Employer::with(['user', 'jobs'])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->employers = $employers;
        $this->updateStatusCounts($employers);
    }

    public function updateStatusCounts($employers)
    {
        $this->statusCounts = [
            'total' => $employers->count(),
        ];
        
        foreach (EmployerStatus::cases() as $status) {
            $this->statusCounts[$status->value] = $employers->where('status', $status->value)->count();
        }
    }

    public function selectStatus($status)
    {
        $this->selectedStatus = $status;
        $this->resetPage();
    }

    public function updateEmployerStatus($employerId, $newStatus)
    {
        $employer = Employer::findOrFail($employerId);
        $employer->update(['status' => (int)$newStatus]);
        
        $this->loadEmployers();
        
        $this->dispatch('status-updated', [
            'message' => 'Employer status updated successfully!',
            'employerId' => $employerId,
            'newStatus' => $newStatus
        ]);
    }

    public function deleteEmployer($employerId)
    {
        $employer = Employer::findOrFail($employerId);
        // Optionally delete related user and jobs if needed
        if ($employer->user) {
            $employer->user->delete();
        }
        $employer->delete();
        $this->loadEmployers();
        $this->dispatch('status-updated', [
            'message' => 'Employer deleted successfully!',
            'employerId' => $employerId,
        ]);
    }

    public function getFilteredEmployers()
    {
        $employers = $this->employers;
        
        // -1 means 'All' statuses
        if ($this->selectedStatus != -1) {
            $employers = $employers->where('status', $this->selectedStatus);
        }

        return $employers;
    }

    public function getStatusLabel()
    {
        if ($this->selectedStatus == -1) {
            return 'All Employers';
        }

        return EmployerStatus::fromValue($this->selectedStatus)?->label() ?? 'Unknown' . ' Employers';
    }

    public function render()
    {
        return view('livewire.employer-pipeline');
    }
}
