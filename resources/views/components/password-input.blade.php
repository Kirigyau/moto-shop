@props([
    'name' => 'password',
    'label' => 'Пароль',
    'id' => null,
    'required' => true,
    'autocomplete' => 'current-password',
])

@php
    $inputId = $id ?? $name;
@endphp

<label class="password-field" for="{{ $inputId }}">
    {{ $label }}
    <span class="password-field__wrap">
        <input
            type="password"
            name="{{ $name }}"
            id="{{ $inputId }}"
            @if($required) required @endif
            autocomplete="{{ $autocomplete }}"
            {{ $attributes->except(['name', 'label', 'id', 'required', 'autocomplete']) }}
        >
        <button
            type="button"
            class="password-field__toggle"
            data-password-toggle
            aria-controls="{{ $inputId }}"
            aria-label="Показать пароль"
            aria-pressed="false"
        >
            <svg class="password-field__icon password-field__icon--show" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            <svg class="password-field__icon password-field__icon--hide" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true" hidden>
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-10-8-10-8a18.45 18.45 0 0 1 5.06-5.94"/>
                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 10 8 10 8a18.5 18.5 0 0 1-2.16 3.19"/>
                <path d="M1 1l22 22"/>
                <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/>
            </svg>
        </button>
    </span>
</label>

@once
    @push('scripts')
        <script src="{{ public_asset('js/password-toggle.js') }}" defer></script>
    @endpush
@endonce
