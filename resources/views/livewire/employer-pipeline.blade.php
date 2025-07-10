<div class="mt-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div x-data="{ open: false }">
            

            <!-- Status Tabs and Label Row -->
            <x-status-tabs :statuses="\App\Enums\EmployerStatus::cases()" :selectedStatus="$selectedStatus"
                :statusCounts="$statusCounts" onStatusClick="selectStatus" />


            <!-- Employers Table -->
            <x-base-table :columns="[
        ['label' => 'Employer'],
        ['label' => 'Contact'],
        ['label' => 'Country'],
        ['label' => 'Jobs'],
        ['label' => 'Created'],
        ['label' => 'Status'],
        ['label' => 'Actions'],
    ]" :emptyMessage="'No employers found'" :emptyDescription="'No employers found in this status.'">
                @foreach($this->getFilteredEmployers() as $employer)
                    <x-employer-table-row :employer="$employer" />
                @endforeach
            </x-base-table>
        </div>

        <!-- Success Notification -->
        <div x-data="{ show: false, message: '' }" x-show="show" x-cloak @status-updated.window="
                 show = true;
                 message = $event.detail.message;
                 setTimeout(() => show = false, 3000);
             " class="fixed top-4 right-4 z-50">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <span x-text="message"></span>
            </div>
        </div>
    </div>
</div>