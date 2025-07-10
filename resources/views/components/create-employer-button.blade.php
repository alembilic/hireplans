@props([
    'label' => 'Add New Employer',
    'class' => ''
])
<a href="{{ route('platform.employers.create') }}"
   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 {{ $class }}"
   title="Create Employer"
>
    <i class="bi bi-plus-circle mr-1.5"></i>
    {{ $label }}
</a> 