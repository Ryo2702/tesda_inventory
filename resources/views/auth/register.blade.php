@extends('layouts.auth')
@section('title', 'Create Account')
@section('meta_description', 'Create a new account')

@section('content')
<div class="max-w-[440px] mx-auto">
    <x-card animate="true">
        <div class="text-center mb-8">
            <div class="w-[64px] h-[64px] mx-auto mb-4 bg-white rounded-[14px] flex items-center justify-center shadow-[0_8px_32px_rgba(0,0,0,0.05)] border border-black/5 animate-iconPulse overflow-hidden p-1">
                <img src="{{ asset('images/logo.jpg') }}" alt="TESDA Logo" class="w-full h-full object-contain rounded-[10px]">
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-black">Create account</h1>
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

        <div class="text-center mt-6 pt-6 border-t border-black">
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
