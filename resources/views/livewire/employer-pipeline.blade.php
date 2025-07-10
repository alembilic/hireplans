<div class="mt-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div x-data="{ open: false }">
            <div class="flex flex-col flex-row items-center justify-between px-6 py-4 border-b border-gray-200 gap-4">
                <div class="flex items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Employer Pipeline</h2>
                </div>
                <div class="flex">
                    <x-create-employer-button class="w-full md:w-auto" />
                </div>
            </div>

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