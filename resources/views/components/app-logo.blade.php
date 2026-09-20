@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Kitch’o" {{ $attributes }}>
        <x-slot name="logo">
            <img
                src="{{ asset('img/logo/kitcho-logo.svg') }}"
                alt="Kitch’o"
                class="h-8 w-auto"
            >
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:sidebar.brand name="Kitch’o" {{ $attributes }}>
        <x-slot name="logo">
            <img
                src="{{ asset('img/logo/kitcho-logo.svg') }}"
                alt="Kitch’o"
                class="h-8 w-auto"
            >
        </x-slot>
    </flux:brand>
@endif