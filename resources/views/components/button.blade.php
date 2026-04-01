@props([
    'href' => null,
    'variant' => 'primary',
    'type' => 'button',
])

@php
    $classes = match ($variant) {
        'secondary' => 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 focus:ring-slate-300',
        default => 'border border-blue-600 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-300',
    };
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2 {$classes}"]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2 {$classes}"]) }}>
        {{ $slot }}
    </button>
@endif
