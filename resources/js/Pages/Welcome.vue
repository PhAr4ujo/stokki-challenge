<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Stock System" />
    <div class="relative min-h-screen flex flex-col bg-gray-950 overflow-hidden">
        <!-- Navbar -->
        <nav class="flex justify-between items-center px-8 py-4 z-20 relative shadow backdrop-blur bg-gray-900/85">
            <div class="flex items-center space-x-3">
                <!-- Stock Icon -->
                <svg class="h-9 w-auto mr-1" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="7" y="28" width="8" height="13" rx="2" fill="#4ADE80"/>
                    <rect x="20" y="18" width="8" height="23" rx="2" fill="#60A5FA"/>
                    <rect x="33" y="8" width="8" height="33" rx="2" fill="#FBBF24"/>
                    <rect x="2.5" y="2.5" width="43" height="43" rx="6.5" stroke="#CBD5E1" stroke-opacity=".17"/>
                </svg>
                <span class="tracking-wider text-white font-bold text-xl">Stock System</span>
            </div>
            <div class="flex items-center space-x-6">
                <a href="#" class="text-zinc-300 hover:text-white transition text-sm">Dashboard</a>
                <a href="#" class="text-zinc-300 hover:text-white transition text-sm">Inventory</a>
                <a href="#" class="text-zinc-300 hover:text-white transition text-sm">Reports</a>
                <a href="#" class="text-zinc-300 hover:text-white transition text-sm">About</a>
                <div v-if="canLogin" class="flex items-center space-x-2 ml-2">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="px-4 py-2 bg-green-700 text-zinc-100 rounded-md border border-green-400 text-xs font-semibold hover:bg-white hover:text-green-800 transition uppercase tracking-wide"
                    >Dashboard</Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="px-4 py-2 bg-blue-900 text-zinc-100 rounded-md border border-blue-400 text-xs font-semibold hover:bg-white hover:text-blue-800 transition uppercase tracking-wide"
                        >Log In</Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="px-4 py-2 ml-2 bg-yellow-400 text-black rounded-md border border-yellow-300 text-xs font-semibold hover:bg-gray-900 hover:text-yellow-300 transition uppercase tracking-wide"
                        >Sign Up</Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Backdrop minimal lines like a stock graph background -->
        <div class="absolute inset-0 pointer-events-none select-none z-0">
            <svg class="w-full h-full object-cover" viewBox="0 0 1440 800" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <polyline points="0,600 140,520 280,680 420,360 560,520 700,440 840,700 980,220 1120,480 1260,380 1440,570" stroke="#22d3ee" stroke-width="3" opacity=".11" fill="none"/>
                <polyline points="0,700 160,600 320,770 480,500 640,620 800,520 960,780 1120,320 1280,540 1440,470" stroke="#fbbf24" stroke-width="2" opacity=".08" fill="none"/>
            </svg>
        </div>

        <!-- Main Content -->
        <div class="relative z-10 flex-1 flex flex-col items-center justify-center px-4 pt-20 pb-24">
            <div class="max-w-xl w-full bg-white/5 rounded-2xl shadow-md p-10 backdrop-blur-lg border border-white/10">
                <h1 class="text-4xl md:text-5xl font-extrabold text-white text-center mb-5 tracking-tight">
                    Welcome to <span class="text-blue-400">Stock System</span>
                </h1>
                <p class="text-lg md:text-xl text-zinc-200 text-center mb-8 font-normal">
                    A technical challenge platform for tracking, managing, and analyzing your inventory with real-time insights.
                </p>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-8">
                    <li class="flex items-center text-green-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                        Live Stock Monitoring
                    </li>
                    <li class="flex items-center text-blue-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                        Quick Inventory Search
                    </li>
                    <li class="flex items-center text-yellow-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                        Smart Reporting
                    </li>
                    <li class="flex items-center text-white">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                        Easy User Management
                    </li>
                </ul>
                <div class="flex flex-col md:flex-row justify-center md:space-x-4">
                    <Link v-if="canLogin && !$page.props.auth.user"
                          :href="route('login')"
                          class="mb-2 md:mb-0 px-7 py-3 bg-blue-700 hover:bg-blue-900 text-white font-bold rounded transition"
                    >
                        Log In to Your Account
                    </Link>
                    <Link v-if="canRegister && !$page.props.auth.user"
                          :href="route('register')"
                          class="px-7 py-3 bg-yellow-400 hover:bg-yellow-500 text-black font-bold rounded transition"
                    >
                        Create Account
                    </Link>
                    <Link v-if="$page.props.auth.user"
                          :href="route('dashboard')"
                          class="px-7 py-3 bg-green-700 hover:bg-green-800 text-white font-bold rounded transition"
                    >
                        Go to Dashboard
                    </Link>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="absolute bottom-0 left-0 right-0 py-3 px-10 text-xs text-zinc-400 flex justify-between items-center z-20 bg-gray-900/85 backdrop-blur-sm border-t border-gray-800">
            <div>
                Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
            </div>
            <div>
                &copy; {{ new Date().getFullYear() }} Stock System Technical Challenge
            </div>
        </footer>
    </div>
</template>
