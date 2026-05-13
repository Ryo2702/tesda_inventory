<div x-show="alert.show" x-transition.opacity style="display: none;"
     :class="alert.type === 'error' ? 'bg-red-500/10 border border-red-500/20 text-red-400' : 'bg-green-500/10 border border-green-500/20 text-green-400'"
     class="p-3 px-4 rounded-xl text-[13px] mb-5 flex items-center gap-2"
     {{ $attributes }}>
    <span x-text="alert.type === 'error' ? '⚠ ' + alert.message : '✓ ' + alert.message"></span>
</div>
