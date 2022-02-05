{{-- input.blade.php --}}
{{----}}
{{-- autor: Jan Procházka (xproch0g) --}}
{{----}}

@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 text-black focus:border-indigo-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50']) !!}>
