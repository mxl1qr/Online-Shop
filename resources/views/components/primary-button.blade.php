<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-3 bg-[#002547] border border-transparent font-bold text-xs text-white uppercase tracking-widest hover:bg-[#000f23] focus:bg-[#000f23] active:bg-[#000f23] focus:outline-none focus:ring-1 focus:ring-[#002547] transition ease-in-out duration-150 cursor-pointer']) }}>
    {{ $slot }}
</button>
