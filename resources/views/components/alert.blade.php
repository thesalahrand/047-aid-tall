@props(['variant'])

@php
    $classes = 'text-sm border rounded-lg p-4';

    $classes .= match ($variant) {
        'info'
            => ' bg-blue-100 border-blue-200 text-blue-800 dark:bg-blue-800/10 dark:border-blue-900 dark:text-blue-500',
        default => '',
    };
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} role="alert" tabindex="-1">
    {{ $slot }}
</div>
