{{-- blue_button.blade.php --}}
{{----}}
{{-- autor: Jan Procházka (xproch0g) --}}
{{----}}

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-blue-900 border-4 border-transparent rounded-lg text-lg text-white font-bold uppercase tracking-widest hover:bg-blue-700 active:bg-gray-900 focus:outline-none focus:border-gray-100 focus:ring ring-blue-800 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
