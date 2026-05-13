@extends('layouts.auth')
@section('title', 'Sign In')
@section('meta_description', 'Sign in to your account')

@section('content')
<div class="text-center mb-8">
    <div class="w-[52px] h-[52px] mx-auto mb-4 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-[14px] flex items-center justify-center shadow-[0_8px_32px_rgba(99,102,241,0.15)] animate-iconPulse">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[26px] h-[26px] text-white">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
        </svg>
    </div>
    <h1 class="text-2xl font-bold tracking-tight bg-gradient-to-br from-slate-100 to-slate-400 bg-clip-text text-transparent">Welcome back</h1>
    <p class="text-slate-500 text-sm mt-1.5 leading-relaxed">Sign in to continue to your account</p>
</div>

<div x-data="loginForm()">
    <div x-show="alert.show" x-transition.opacity style="display: none;"
         :class="alert.type === 'error' ? 'bg-red-500/10 border border-red-500/20 text-red-400' : 'bg-green-500/10 border border-green-500/20 text-green-400'"
         class="p-3 px-4 rounded-xl text-[13px] mb-5 flex items-center gap-2">
        <span x-text="alert.type === 'error' ? '⚠ ' + alert.message : '✓ ' + alert.message"></span>
    </div>

    <form @submit.prevent="submit" novalidate>
        <x-input type="email" id="email" label="Email address" model="form.email" placeholder="you@example.com" autocomplete="email" required="true" />

        <x-input type="password" id="password" label="Password" model="form.password" placeholder="Enter your password" autocomplete="current-password" required="true" />

        <a href="/forgot-password" class="block text-right -mt-2 mb-4 text-indigo-500 font-medium text-[13px] hover:text-violet-500 hover:underline underline-offset-4 transition-all">Forgot password?</a>

        <button type="submit" :disabled="loading" :class="{ 'opacity-60 pointer-events-none': loading }" class="w-full py-3 px-6 bg-gradient-to-br from-indigo-500 to-violet-500 text-white rounded-xl text-[15px] font-semibold cursor-pointer transition-all duration-300 relative overflow-hidden mt-2 hover:-translate-y-[1px] hover:shadow-[0_8px_32px_rgba(99,102,241,0.15),0_4px_12px_rgba(99,102,241,0.3)] active:translate-y-0 active:shadow-[0_4px_16px_rgba(99,102,241,0.15)] group">
            <span class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
            <span class="relative btn-text" x-show="!loading">Sign In</span>
            <span class="spinner w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin mx-auto" x-show="loading" style="display: none;"></span>
        </button>
    </form>
</div>

<div class="text-center mt-6 pt-6 border-t border-white/10">
    <p class="text-[13px] text-slate-500">Don't have an account? <a href="/register" class="text-indigo-500 font-medium hover:text-violet-500 hover:underline underline-offset-4 transition-all">Create account</a></p>
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
