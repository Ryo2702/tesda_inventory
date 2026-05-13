@props([
    'type' => 'text',
    'id',
    'label',
    'model',
    'error' => null,
    'placeholder' => '',
    'autocomplete' => '',
    'required' => false
])

@php
    $errorField = $error ?? (str_contains($model, '.') ? explode('.', $model)[1] : $model);
@endphp

<div class="mb-5 relative" {{ $type === 'password' ? 'x-data={ showPw: false }' : '' }}>
    <label for="{{ $id }}" class="block text-[13px] font-medium text-slate-400 mb-2 tracking-wide">{{ $label }}</label>
    
    @if($type === 'password')
        <div class="relative">
            <input :type="showPw ? 'text' : 'password'" id="{{ $id }}" x-model="{{ $model }}"
                   :class="errors.{{ $errorField }} ? '!border-red-500 !ring-4 !ring-red-500/20' : ''"
                   class="w-full pl-4 pr-12 py-3 bg-white/5 border border-white/5 rounded-xl text-slate-100 text-[15px] transition-all duration-250 outline-none placeholder:text-slate-500 placeholder:opacity-60 hover:bg-white/10 hover:border-white/10 focus:bg-white/10 focus:border-indigo-500/50 focus:ring-4 focus:ring-indigo-500/20"
                   placeholder="{{ $placeholder }}" autocomplete="{{ $autocomplete }}" {{ $required ? 'required' : '' }}>
            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-400 p-1 flex items-center transition-colors" @click="showPw = !showPw">
                <svg x-show="!showPw" class="w-[18px] h-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                <svg x-show="showPw" style="display: none;" class="w-[18px] h-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
            </button>
        </div>
    @else
        <input type="{{ $type }}" id="{{ $id }}" x-model="{{ $model }}"
               :class="errors.{{ $errorField }} ? '!border-red-500 !ring-4 !ring-red-500/20' : ''"
               class="w-full px-4 py-3 bg-white/5 border border-white/5 rounded-xl text-slate-100 text-[15px] transition-all duration-250 outline-none placeholder:text-slate-500 placeholder:opacity-60 hover:bg-white/10 hover:border-white/10 focus:bg-white/10 focus:border-indigo-500/50 focus:ring-4 focus:ring-indigo-500/20"
               placeholder="{{ $placeholder }}" autocomplete="{{ $autocomplete }}" {{ $required ? 'required' : '' }}>
    @endif

    <div x-show="errors.{{ $errorField }}" x-text="errors.{{ $errorField }}" style="display: none;" class="text-[12px] text-red-500 mt-1.5 flex items-center gap-1 animate-shake-in"></div>
</div>
