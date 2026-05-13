@extends('layouts.auth')
@section('title', 'Reset Password')
@section('meta_description', 'Set a new password for your account')

@section('content')
<div class="text-center mb-8">
    <div class="w-[52px] h-[52px] mx-auto mb-4 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-[14px] flex items-center justify-center shadow-[0_8px_32px_rgba(99,102,241,0.15)] animate-iconPulse">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[26px] h-[26px] text-white">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
        </svg>
    </div>
    <h1 class="text-2xl font-bold tracking-tight bg-gradient-to-br from-slate-100 to-slate-400 bg-clip-text text-transparent">Reset password</h1>
    <p class="text-slate-500 text-sm mt-1.5 leading-relaxed">Enter your new password below</p>
</div>

<div x-data="resetForm()">
    <div x-show="alert.show" x-transition.opacity style="display: none;"
         :class="alert.type === 'error' ? 'bg-red-500/10 border border-red-500/20 text-red-400' : 'bg-green-500/10 border border-green-500/20 text-green-400'"
         class="p-3 px-4 rounded-xl text-[13px] mb-5 flex items-center gap-2">
        <span x-text="alert.type === 'error' ? '⚠ ' + alert.message : '✓ ' + alert.message"></span>
    </div>

    <form @submit.prevent="submit" novalidate>
        <x-input type="email" id="email" label="Email address" model="form.email" placeholder="you@example.com" autocomplete="email" required="true" />

        <x-input type="password" id="password" label="New password" model="form.password" placeholder="Min. 8 characters" autocomplete="new-password" required="true" />

        <x-input type="password" id="password_confirmation" label="Confirm new password" model="form.password_confirmation" placeholder="Repeat your password" autocomplete="new-password" required="true" />

        <button type="submit" :disabled="loading" :class="{ 'opacity-60 pointer-events-none': loading }" class="w-full py-3 px-6 bg-gradient-to-br from-indigo-500 to-violet-500 text-white rounded-xl text-[15px] font-semibold cursor-pointer transition-all duration-300 relative overflow-hidden mt-2 hover:-translate-y-[1px] hover:shadow-[0_8px_32px_rgba(99,102,241,0.15),0_4px_12px_rgba(99,102,241,0.3)] active:translate-y-0 active:shadow-[0_4px_16px_rgba(99,102,241,0.15)] group">
            <span class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
            <span class="relative btn-text" x-show="!loading">Reset Password</span>
            <span class="spinner w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin mx-auto" x-show="loading" style="display: none;"></span>
        </button>
    </form>
</div>

<div class="text-center mt-6 pt-6 border-t border-white/10">
    <p class="text-[13px] text-slate-500">Remember your password? <a href="/login" class="text-indigo-500 font-medium hover:text-violet-500 hover:underline underline-offset-4 transition-all">Back to sign in</a></p>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('resetForm', () => ({
        form: {
            token: '{{ request()->query('token', '') }}',
            email: '{{ request()->query('email', '') }}',
            password: '',
            password_confirmation: ''
        },
        errors: {},
        alert: { show: false, type: '', message: '' },
        loading: false,

        async submit() {
            this.errors = {};
            this.alert.show = false;
            this.loading = true;

            try {
                const res = await fetch('/api/auth/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.form)
                });
                
                const r = await res.json();
                
                if (!res.ok) {
                    if (r.errors) {
                        for (let field in r.errors) {
                            this.errors[field] = r.errors[field][0];
                        }
                    } else if (r.message) {
                        this.alert = { show: true, type: 'error', message: r.message };
                    }
                } else {
                    this.alert = { show: true, type: 'success', message: 'Password reset! Redirecting to login...' };
                    setTimeout(() => window.location.href = '/login', 2000);
                }
            } catch (err) {
                this.alert = { show: true, type: 'error', message: 'Network error. Please try again.' };
            } finally {
                this.loading = false;
            }
        }
    }));
});
</script>
@endsection
