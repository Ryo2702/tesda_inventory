@props(['padding' => 'p-8 sm:p-10', 'animate' => false])

<div {{ $attributes->merge(['class' => 'bg-white/[0.03] border border-black rounded-[20px] relative overflow-hidden ' . ($animate ? 'opacity-0 translate-y-5 animate-card-enter' : '') . ' ' . $padding]) }}>
    {{ $slot }}
</div>
