@props(['active' => false, 'href' => '#'])

@php
    $base = 'flex items-center gap-3 px-3 py-2 rounded-md text-sm transition';
    $classes = $active
        ? $base.' bg-indigo-600 text-white'
        : $base.' text-gray-300 hover:bg-gray-800 hover:text-white';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
