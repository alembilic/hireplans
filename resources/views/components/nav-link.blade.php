@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-0.5 border-b-2 border-yellow-600 text-md font-large leading-none text-yellow-950 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-0.5 border-b-2 border-transparent text-md font-medium leading-none text-yellow-950 hover:text-yellow-500 hover:border-gray-300 focus:outline-none focus:text-yellow-500 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
