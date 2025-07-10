@props([
    'href' => '#',
    'icon' => null,
    'label',
    'class' => '',
    'target' => null,
    'title' => null,
])
<a href="{{ $href }}" @if($target) target="{{ $target }}" @endif
   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors duration-200 {{ $class }}"
   @if($title) title="{{ $title }}" @endif
>
    @if($icon)
        <i class="bi {{ $icon }} mr-1.5 text-base"></i>
    @endif
    {{ $label }}
</a> 