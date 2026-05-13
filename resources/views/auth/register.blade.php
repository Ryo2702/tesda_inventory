@extends('layouts.auth')
@section('title', 'Create Account')
@section('meta_description', 'Create a new account')

@section('content')
<div class="max-w-[440px] mx-auto">
    <x-card animate="true">
        <div class="text-center mb-8">
            <div class="w-[52px] h-[52px] mx-auto mb-4 bg-indigo-600 rounded-[14px] flex items-center justify-center shadow-[0_8px_32px_rgba(79,70,229,0.15)] animate-iconPulse">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[26px] h-[26px] text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-100">Create account</h1>
            <p class="text-slate-500 text-sm mt-1.5 leading-relaxed">Get started with your free account</p>
        </div>

        <div x-data="registerForm()">
            <x-alert />

            <form @submit.prevent="submit" novalidate>
                <x-input type="text" id="name" label="Full name" model="form.name" placeholder="John Doe" autocomplete="name" required="true" />

                <x-input type="email" id="email" label="Email address" model="form.email" placeholder="you@example.com" autocomplete="email" required="true" />

                <x-input type="password" id="password" label="Password" model="form.password" placeholder="Min. 8 characters" autocomplete="new-password" required="true" />

                <x-input type="password" id="password_confirmation" label="Confirm password" model="form.password_confirmation" placeholder="Repeat your password" autocomplete="new-password" required="true" />

                <x-primary-button type="submit" x-bind:disabled="loading" x-bind:class="{ 'opacity-60 pointer-events-none': loading }" class="w-full mt-2">
                    <span class="relative btn-text" x-show="!loading">Create Account</span>
                    <span class="spinner w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin mx-auto" x-show="loading" style="display: none;"></span>
                </x-primary-button>
            </form>
        </div>

        <div class="text-center mt-6 pt-6 border-t border-white/10">
            <p class="text-[13px] text-slate-500">Already have an account? <a href="/login" class="text-indigo-500 font-medium hover:text-violet-500 hover:underline underline-offset-4 transition-all">Sign in</a></p>
        </div>
    </x-card>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('registerForm', () => ({
        form: {
            name: '',
            email: '',
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
                const res = await fetch('/api/auth/register', {
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
                    localStorage.setItem('auth_token', r.token);
                    this.alert = { show: true, type: 'success', message: 'Account created! Redirecting...' };
                    setTimeout(() => window.location.href = '/', 1000);
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
