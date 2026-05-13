@extends('layouts.auth')
@section('title', 'Forgot Password')
@section('meta_description', 'Reset your password')

@section('content')
<div class="max-w-[440px] mx-auto">
    <x-card animate="true">
        <div class="text-center mb-8">
            <div class="w-[64px] h-[64px] mx-auto mb-4 bg-white rounded-[14px] flex items-center justify-center shadow-[0_8px_32px_rgba(0,0,0,0.05)] border border-black/5 animate-iconPulse overflow-hidden p-1">
                <img src="{{ asset('images/logo.jpg') }}" alt="TESDA Logo" class="w-full h-full object-contain rounded-[10px]">
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-black">Forgot password?</h1>
            <p class="text-slate-500 text-sm mt-1.5 leading-relaxed">Enter your email and we'll send you a reset link</p>
        </div>

        <div x-data="forgotForm()">
            <x-alert />

            <form @submit.prevent="submit" novalidate>
                <x-input type="email" id="email" label="Email address" model="form.email" placeholder="you@example.com" autocomplete="email" required="true" />

                <x-primary-button type="submit" x-bind:disabled="loading" x-bind:class="{ 'opacity-60 pointer-events-none': loading }" class="w-full mt-2">
                    <span class="relative btn-text" x-show="!loading">Send Reset Link</span>
                    <span class="spinner w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin mx-auto" x-show="loading" style="display: none;"></span>
                </x-primary-button>
            </form>
        </div>

        <div class="text-center mt-6 pt-6 border-t border-black">
            <p class="text-[13px] text-slate-500">Remember your password? <a href="/login" class="text-indigo-500 font-medium hover:text-violet-500 hover:underline underline-offset-4 transition-all">Back to sign in</a></p>
        </div>
    </x-card>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('forgotForm', () => ({
        form: {
            email: ''
        },
        errors: {},
        alert: { show: false, type: '', message: '' },
        loading: false,

        async submit() {
            this.errors = {};
            this.alert.show = false;
            this.loading = true;

            try {
                const res = await fetch('/api/auth/forgot-password', {
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
                    this.alert = { show: true, type: 'success', message: r.message || 'Password reset link sent! Check your email.' };
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
