@props(['disabled' => false])

<input type="checkbox" @disabled($disabled)
    {{ $attributes->merge(['class' => 'shrink-0 border-gray-200 rounded-sm text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800']) }}>
