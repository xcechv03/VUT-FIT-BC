{{-- blue_input.blade.php --}}
{{----}}
{{-- autor: Jan Procházka (xproch0g) --}}
{{----}}

@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-blue-900 rounded-md shadow-sm border-white focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50']) !!}>
