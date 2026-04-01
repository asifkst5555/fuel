@props([
    'variant' => 'neutral',
])

@php
    $classes = match ($variant) {
        'success', 'available' => 'border border-green-200 bg-green-50 text-green-700',
        'danger', 'unavailable' => 'border border-red-200 bg-red-50 text-red-700',
        'primary' => 'border border-blue-200 bg-blue-50 text-blue-700',
        default => 'border border-slate-200 bg-slate-50 text-slate-600',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-md px-2.5 py-1 text-xs font-medium {$classes}"]) }}>
    {{ $slot }}
</span>
