@extends('layouts.auth')
@section('title', 'Reset Password')
@section('meta_description', 'Set a new password for your account')

@section('content')
<div class="max-w-[440px] mx-auto">
    <x-card animate="true">
        <div class="text-center mb-8">
            <div class="w-[52px] h-[52px] mx-auto mb-4 bg-indigo-600 rounded-[14px] flex items-center justify-center shadow-[0_8px_32px_rgba(79,70,229,0.15)] animate-iconPulse">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[26px] h-[26px] text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-100">Reset password</h1>
            <p class="text-slate-500 text-sm mt-1.5 leading-relaxed">Enter your new password below</p>
        </div>

        <div x-data="resetForm()">
            <x-alert />

            <form @submit.prevent="submit" novalidate>
                <x-input type="email" id="email" label="Email address" model="form.email" placeholder="you@example.com" autocomplete="email" required="true" />

                <x-input type="password" id="password" label="New password" model="form.password" placeholder="Min. 8 characters" autocomplete="new-password" required="true" />

                <x-input type="password" id="password_confirmation" label="Confirm new password" model="form.password_confirmation" placeholder="Repeat your password" autocomplete="new-password" required="true" />

                <x-primary-button type="submit" x-bind:disabled="loading" x-bind:class="{ 'opacity-60 pointer-events-none': loading }" class="w-full mt-2">
                    <span class="relative btn-text" x-show="!loading">Reset Password</span>
                    <span class="spinner w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin mx-auto" x-show="loading" style="display: none;"></span>
                </x-primary-button>
            </form>
        </div>

        <div class="text-center mt-6 pt-6 border-t border-white/10">
            <p class="text-[13px] text-slate-500">Remember your password? <a href="/login" class="text-indigo-500 font-medium hover:text-violet-500 hover:underline underline-offset-4 transition-all">Back to sign in</a></p>
        </div>
    </x-card>
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
