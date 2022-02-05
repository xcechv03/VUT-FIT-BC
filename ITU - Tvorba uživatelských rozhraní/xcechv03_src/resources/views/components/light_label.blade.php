{{-- light_label.blade.php --}}
{{----}}
{{-- autor: Jan Procházka (xproch0g) --}}
{{----}}

@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-100']) }}>
    {{ $value ?? $slot }}
</label>
