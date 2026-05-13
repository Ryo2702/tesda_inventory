@extends('layouts.auth')
@section('title', 'Sign In')
@section('meta_description', 'Sign in to your account')

@section('content')
<div class="max-w-[440px] mx-auto">
    <x-card animate="true">
        <div class="text-center mb-8">
            <div class="w-[52px] h-[52px] mx-auto mb-4 bg-indigo-600 rounded-[14px] flex items-center justify-center shadow-[0_8px_32px_rgba(79,70,229,0.15)] animate-iconPulse">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[26px] h-[26px] text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Welcome back</h1>
            <p class="text-gray-500 text-sm mt-1.5 leading-relaxed">Sign in to continue to your account</p>
        </div>

        <div x-data="loginForm()">
            <x-alert />

            <form @submit.prevent="submit" novalidate>
                <x-input type="email" id="email" label="Email address" model="form.email" placeholder="you@example.com" autocomplete="email" required="true" />

                <x-input type="password" id="password" label="Password" model="form.password" placeholder="Enter your password" autocomplete="current-password" required="true" />

                <a href="/forgot-password" class="block text-right -mt-2 mb-4 text-indigo-500 font-medium text-[13px] hover:text-violet-500 hover:underline underline-offset-4 transition-all">Forgot password?</a>

                <x-primary-button type="submit" x-bind:disabled="loading" x-bind:class="{ 'opacity-60 pointer-events-none': loading }" class="w-full mt-2">
                    <span class="relative btn-text" x-show="!loading">Sign In</span>
                    <span class="spinner w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin mx-auto" x-show="loading" style="display: none;"></span>
                </x-primary-button>
            </form>
        </div>

        <div class="text-center mt-6 pt-6 border-t border-white/10">
            <p class="text-[13px] text-slate-500">Don't have an account? <a href="/register" class="text-indigo-500 font-medium hover:text-violet-500 hover:underline underline-offset-4 transition-all">Create account</a></p>
        </div>
    </x-card>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('loginForm', () => ({
        form: {
            email: '',
            password: ''
        },
        errors: {},
        alert: { show: false, type: '', message: '' },
        loading: false,

        async submit() {
            this.errors = {};
            this.alert.show = false;
            this.loading = true;

            try {
                const res = await fetch('/api/auth/login', {
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
                        const firstError = Object.values(r.errors)[0][0];
                        this.alert = { show: true, type: 'error', message: firstError || r.message };
                    } else if (r.message) {
                        this.alert = { show: true, type: 'error', message: r.message };
                    }
                } else {
                    localStorage.setItem('auth_token', r.token);
                    this.alert = { show: true, type: 'success', message: 'Login successful! Redirecting...' };
                    setTimeout(() => window.location.href = '/dashboard', 1000);
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
