@props(['status', 'showLabel' => true])

@php
    $colors = [
        1 => 'bg-blue-100 text-blue-800', // In Progress
        2 => 'bg-green-100 text-green-800', // Active Opportunity
        3 => 'bg-purple-100 text-purple-800', // Current Client
        4 => 'bg-red-100 text-red-800', // Dead Opportunity
        5 => 'bg-gray-100 text-gray-800', // Do Not Prospect
        6 => 'bg-yellow-100 text-yellow-800', // Uncontacted
    ];
    
    $colorClass = $colors[$status] ?? 'bg-gray-100 text-gray-800';
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
    @if($showLabel)
        {{ \App\Enums\EmployerStatus::fromValue($status)?->label() ?? 'Unknown' }}
    @endif
</span> 