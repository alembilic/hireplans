@props(['employer'])
<tr class="hover:bg-gray-50">
    <td class="px-3 py-2 whitespace-nowrap">
        <a href="{{ route('platform.employers.view', $employer->id) }}" 
           class="flex items-center inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 rounded-md hover:bg-gray-200 transition-colors duration-200" 
           title="View Employer Profile">
            <div class="flex-shrink-0 h-10 w-10">
                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                    <span class="text-sm font-medium text-gray-700">{{ strtoupper(substr($employer->name, 0, 2)) }}</span>
                </div>
            </div>
            <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">{{ $employer->name }}</div>
                <div class="text-sm text-gray-500">{{ $employer->city ?? 'No location' }}</div>
            </div>
        </a>
    </td>
    <td class="px-3 py-2 whitespace-nowrap">
        <div class="text-sm text-gray-900">{{ $employer->user->name ?? 'No contact' }}</div>
        <div class="text-sm text-gray-500">{{ $employer->user->email ?? 'No email' }}</div>
    </td>
    <td class="px-3 py-2 whitespace-nowrap">
        <div class="text-sm text-gray-900">{{ $employer->country ?? 'No country' }}</div>
    </td>
    <td class="px-3 py-2 whitespace-nowrap">
        <div class="text-sm text-gray-900">{{ $employer->jobs->count() }}</div>
    </td>
    <td class="px-3 py-2 whitespace-nowrap">
        <div class="text-sm text-gray-900">{{ $employer->created_at->format('M d, Y') }}</div>
        <div class="text-sm text-gray-500">{{ $employer->created_at->format('H:i') }}</div>
    </td>
    <td class="px-3 py-2 whitespace-nowrap">
        <select wire:change="updateEmployerStatus({{ $employer->id }}, $event.target.value)" 
                class="pl-2 pr-7 py-1 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-brand focus:border-brand">
            @foreach(\App\Enums\EmployerStatus::cases() as $status)
                <option value="{{ $status->value }}" {{ $employer->status == $status->value ? 'selected' : '' }}>
                    {{ $status->label() }}
                </option>
            @endforeach
        </select>
    </td>
    <td class="px-3 py-2 whitespace-nowrap">
        <div class="flex items-center space-x-3">
            <x-table-button 
                :href="route('platform.employers.view', $employer->id)"
                icon="bi-eye"
                label="View"
                title="View Employer Details"
            />
            <x-table-button 
                :href="route('platform.employers.edit', $employer->id)"
                icon="bi-pencil"
                label="Edit"
                title="Edit Employer"
            />
            <a href="#" 
               wire:click.prevent="deleteEmployer({{ $employer->id }})"
               onclick="return confirm('Are you sure you want to delete this employer?')"
               class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-md hover:bg-red-200 transition-colors duration-200"
               title="Delete Employer">
                <i class="bi bi-trash mr-1.5 text-base"></i>
                Delete
            </a>
        </div>
    </td>
</tr> 