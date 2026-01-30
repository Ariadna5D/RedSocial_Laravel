@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold transition-all duration-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

    $variants = [
    'primary'   => 'bg-teal-600 text-white hover:bg-teal-700 focus:ring-teal-500 shadow-sm transition-colors',
    'secondary' => 'bg-violet-600 text-white hover:bg-violet-700 focus:ring-violet-500 shadow-sm transition-colors',
    'danger'    => 'bg-rose-600 text-white hover:bg-rose-700 focus:ring-rose-500 shadow-sm transition-colors',
    'pink'      => 'bg-fuchsia-100 text-fuchsia-700 hover:bg-fuchsia-200 focus:ring-fuchsia-400 transition-colors',
    'custom'    => 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500 shadow-sm transition-colors',
    'outline'   => 'border-2 border-teal-600 text-teal-600 hover:bg-teal-50 focus:ring-teal-500 transition-all',
];
    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-8 py-3 text-base',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
