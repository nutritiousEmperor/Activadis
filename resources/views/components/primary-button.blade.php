<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-main-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-main-600 focus:bg-main-600 active:bg-main-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
