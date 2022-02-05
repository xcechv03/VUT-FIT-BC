{{-- button.blade.php --}}
{{----}}
{{-- autor: Tomáš Čechvala (xcechv03) --}}
{{----}}

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border-white rounded-xl text-lg text-white font-bold uppercase tracking-widest hover:bg-blue-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
