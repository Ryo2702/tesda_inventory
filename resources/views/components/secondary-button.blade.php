<button {{ $attributes->merge(['class' => 'inline-flex justify-center items-center py-3 px-6 bg-slate-600 text-white rounded-xl text-[15px] font-semibold cursor-pointer transition-all duration-300 relative overflow-hidden hover:-translate-y-[1px] hover:bg-slate-700 hover:shadow-[0_8px_32px_rgba(71,85,105,0.2),0_4px_12px_rgba(71,85,105,0.4)] active:translate-y-0 active:shadow-[0_4px_16px_rgba(71,85,105,0.2)] group']) }}>
    {{ $slot }}
</button>
