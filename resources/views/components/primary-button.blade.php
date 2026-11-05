<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-3 rounded-lg bg-navy border border-transparent font-bold text-xs text-white uppercase tracking-widest hover:bg-navy-600 focus:bg-navy-600 active:bg-navy-600 focus:outline-none focus:ring-1 focus:ring-navy transition ease-in-out duration-150 cursor-pointer']) }}>
    {{ $slot }}
</button>
