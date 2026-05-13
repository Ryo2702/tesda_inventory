@extends('layouts.auth')
@section('title', 'Dashboard')
@section('meta_description', 'Your secure dashboard portal')
@section('container_class', '!max-w-[1200px] w-full mx-auto')
@section('card_class', '!p-8 !max-w-full')

@section('content')
<div x-data="dashboardApp()" x-cloak>
    <div class="w-full" x-show="!loading" style="display: none;">
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 border-b border-white/10 pb-6 gap-6 text-center md:text-left">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-br from-slate-100 to-slate-400 bg-clip-text text-transparent">Overview</h1>
                <p class="text-slate-500 mt-2">Welcome back to your TESDA Inventory Management System</p>
            </div>
            <div class="flex flex-col md:flex-row items-center gap-4">
                <div class="text-center md:text-right">
                    <div class="font-semibold text-slate-100" x-text="user.name">Loading...</div>
                    <div class="text-sm text-slate-500" x-text="user.email">loading@example.com</div>
                </div>
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white font-semibold text-xl shadow-[0_4px_12px_rgba(99,102,241,0.25)]" x-text="user.initial">?</div>
                <button class="px-4 py-2 bg-red-500/10 text-red-400 border border-red-500/20 rounded-lg text-sm font-medium transition-colors hover:bg-red-500/20 md:ml-4" @click="logout" :disabled="loggingOut" :class="{ 'opacity-50 cursor-not-allowed': loggingOut }">
                    <span x-show="!loggingOut">Sign Out</span>
                    <span x-show="loggingOut">...</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-white/20">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 bg-indigo-500/15 text-indigo-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                </div>
                <div class="text-3xl font-bold text-slate-100 mb-1">124</div>
                <div class="text-sm text-slate-500">Total Items in Inventory</div>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-white/20">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 bg-amber-500/15 text-amber-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="text-3xl font-bold text-slate-100 mb-1">8</div>
                <div class="text-sm text-slate-500">Low Stock Alerts</div>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-white/20">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 bg-green-500/15 text-green-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-3xl font-bold text-slate-100 mb-1">45</div>
                <div class="text-sm text-slate-500">Items Distributed This Month</div>
            </div>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-white/10 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-slate-100">Inventory Items</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-400">
                    <thead class="text-xs uppercase bg-white/5 text-slate-300">
                        <tr>
                            <th scope="col" class="px-6 py-4 cursor-pointer hover:text-white transition-colors" @click="sortBy('id')">
                                <div class="flex items-center gap-2 select-none">
                                    ID
                                    <span x-show="sortCol === 'id'" x-text="sortAsc ? '↑' : '↓'" class="text-indigo-400 font-bold"></span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 cursor-pointer hover:text-white transition-colors" @click="sortBy('name')">
                                <div class="flex items-center gap-2 select-none">
                                    Item Name
                                    <span x-show="sortCol === 'name'" x-text="sortAsc ? '↑' : '↓'" class="text-indigo-400 font-bold"></span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 cursor-pointer hover:text-white transition-colors" @click="sortBy('category')">
                                <div class="flex items-center gap-2 select-none">
                                    Category
                                    <span x-show="sortCol === 'category'" x-text="sortAsc ? '↑' : '↓'" class="text-indigo-400 font-bold"></span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 cursor-pointer hover:text-white transition-colors" @click="sortBy('quantity')">
                                <div class="flex items-center gap-2 select-none">
                                    Quantity
                                    <span x-show="sortCol === 'quantity'" x-text="sortAsc ? '↑' : '↓'" class="text-indigo-400 font-bold"></span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 cursor-pointer hover:text-white transition-colors" @click="sortBy('status')">
                                <div class="flex items-center gap-2 select-none">
                                    Status
                                    <span x-show="sortCol === 'status'" x-text="sortAsc ? '↑' : '↓'" class="text-indigo-400 font-bold"></span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="item in sortedItems" :key="item.id">
                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-200" x-text="'#' + item.id"></td>
                                <td class="px-6 py-4 text-slate-200" x-text="item.name"></td>
                                <td class="px-6 py-4" x-text="item.category"></td>
                                <td class="px-6 py-4" x-text="item.quantity"></td>
                                <td class="px-6 py-4">
                                    <span :class="{
                                        'bg-green-500/10 text-green-400 border-green-500/20': item.status === 'In Stock',
                                        'bg-amber-500/10 text-amber-400 border-amber-500/20': item.status === 'Low Stock',
                                        'bg-red-500/10 text-red-400 border-red-500/20': item.status === 'Out of Stock'
                                    }" class="px-2.5 py-1 text-[12px] font-medium rounded-full border" x-text="item.status"></span>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="sortedItems.length === 0">
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">No items found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div class="w-full" x-show="loading">
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 border-b border-white/10 pb-6 gap-6">
            <div class="w-[300px] max-w-full">
                <div class="h-8 w-full mb-2 bg-gradient-to-r from-white/5 via-white/10 to-white/5 bg-[length:200%_100%] animate-skeleton rounded"></div>
                <div class="h-5 w-[60%] bg-gradient-to-r from-white/5 via-white/10 to-white/5 bg-[length:200%_100%] animate-skeleton rounded"></div>
            </div>
            <div class="flex items-center gap-4 w-[200px]">
                <div class="flex-1">
                    <div class="h-5 w-full mb-2 bg-gradient-to-r from-white/5 via-white/10 to-white/5 bg-[length:200%_100%] animate-skeleton rounded"></div>
                    <div class="h-4 w-[80%] ml-auto bg-gradient-to-r from-white/5 via-white/10 to-white/5 bg-[length:200%_100%] animate-skeleton rounded"></div>
                </div>
                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-white/5 via-white/10 to-white/5 bg-[length:200%_100%] animate-skeleton"></div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="h-[150px] bg-gradient-to-r from-white/5 via-white/10 to-white/5 bg-[length:200%_100%] animate-skeleton rounded-2xl"></div>
            <div class="h-[150px] bg-gradient-to-r from-white/5 via-white/10 to-white/5 bg-[length:200%_100%] animate-skeleton rounded-2xl"></div>
            <div class="h-[150px] bg-gradient-to-r from-white/5 via-white/10 to-white/5 bg-[length:200%_100%] animate-skeleton rounded-2xl"></div>
        </div>
        
        <div class="h-[300px] bg-gradient-to-r from-white/5 via-white/10 to-white/5 bg-[length:200%_100%] animate-skeleton rounded-2xl"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardApp', () => ({
        loading: true,
        user: {
            name: 'Loading...',
            email: 'loading@example.com',
            initial: '?'
        },
        loggingOut: false,

        inventoryItems: [
            { id: 1, name: 'Laptops', category: 'Electronics', quantity: 45, status: 'In Stock' },
            { id: 2, name: 'Office Chairs', category: 'Furniture', quantity: 12, status: 'Low Stock' },
            { id: 3, name: 'Projectors', category: 'Electronics', quantity: 5, status: 'Low Stock' },
            { id: 4, name: 'Notebooks', category: 'Supplies', quantity: 150, status: 'In Stock' },
            { id: 5, name: 'Whiteboards', category: 'Furniture', quantity: 0, status: 'Out of Stock' },
            { id: 6, name: 'Desktop PCs', category: 'Electronics', quantity: 30, status: 'In Stock' },
            { id: 7, name: 'Printer Ink', category: 'Supplies', quantity: 2, status: 'Low Stock' },
        ],
        sortCol: 'id',
        sortAsc: true,
        
        get sortedItems() {
            return [...this.inventoryItems].sort((a, b) => {
                let valA = a[this.sortCol];
                let valB = b[this.sortCol];
                
                if (typeof valA === 'string') valA = valA.toLowerCase();
                if (typeof valB === 'string') valB = valB.toLowerCase();
                
                if (valA < valB) return this.sortAsc ? -1 : 1;
                if (valA > valB) return this.sortAsc ? 1 : -1;
                return 0;
            });
        },

        sortBy(col) {
            if (this.sortCol === col) {
                this.sortAsc = !this.sortAsc;
            } else {
                this.sortCol = col;
                this.sortAsc = true;
            }
        },

        async init() {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            try {
                const response = await fetch('/api/user', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Unauthorized');
                }

                const user = await response.json();
                this.user.name = user.name;
                this.user.email = user.email;
                this.user.initial = user.name.charAt(0).toUpperCase();
                
                this.loading = false;
            } catch (error) {
                localStorage.removeItem('auth_token');
                window.location.href = '/login';
            }
        },

        async logout() {
            this.loggingOut = true;
            const token = localStorage.getItem('auth_token');
            
            try {
                await fetch('/api/auth/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
            } catch (e) {}
            
            localStorage.removeItem('auth_token');
            window.location.href = '/login';
        }
    }));
});
</script>
@endsection
