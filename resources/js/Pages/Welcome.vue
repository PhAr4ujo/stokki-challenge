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
    <Head title="Stokki | Desafio Pleno" />
    <div class="relative min-h-screen flex flex-col bg-white overflow-hidden">
        <!-- Navbar -->
        <nav class="flex justify-between items-center px-8 py-4 z-20 relative shadow backdrop-blur bg-white/80 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <!-- Stock Icon -->
                <svg class="h-9 w-auto mr-1" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="7" y="28" width="8" height="13" rx="2" fill="#4ADE80"/>
                    <rect x="20" y="18" width="8" height="23" rx="2" fill="#60A5FA"/>
                    <rect x="33" y="8" width="8" height="33" rx="2" fill="#FBBF24"/>
                    <rect x="2.5" y="2.5" width="43" height="43" rx="6.5" stroke="#CBD5E1" stroke-opacity=".17"/>
                </svg>
                <span class="tracking-wider text-gray-900 font-bold text-xl">Stokki</span>
            </div>
            <div class="flex items-center space-x-4">
                <div v-if="canLogin" class="flex items-center space-x-2 ml-2">
                    <Link
                        v-if="!$page.props.auth.user"
                        :href="route('login')"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md border border-blue-400 text-xs font-semibold hover:bg-blue-800 hover:text-white transition uppercase tracking-wide"
                    >Entrar</Link>
                    <Link
                        v-if="canRegister && !$page.props.auth.user"
                        :href="route('register')"
                        class="px-4 py-2 ml-2 bg-yellow-400 text-black rounded-md border border-yellow-300 text-xs font-semibold hover:bg-yellow-500 hover:text-black transition uppercase tracking-wide"
                    >Registrar</Link>
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="px-4 py-2 bg-green-700 text-white rounded-md border border-green-400 text-xs font-semibold hover:bg-green-800 hover:text-white transition uppercase tracking-wide"
                    >Painel</Link>
                </div>
            </div>
        </nav>

        <!-- Backdrop -->
        <div class="absolute inset-0 pointer-events-none select-none z-0">
            <svg class="w-full h-full object-cover" viewBox="0 0 1440 800" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <polyline points="0,600 140,520 280,680 420,360 560,520 700,440 840,700 980,220 1120,480 1260,380 1440,570" stroke="#22d3ee" stroke-width="3" opacity=".05" fill="none"/>
                <polyline points="0,700 160,600 320,770 480,500 640,620 800,520 960,780 1120,320 1280,540 1440,470" stroke="#fbbf24" stroke-width="2" opacity=".03" fill="none"/>
            </svg>
        </div>

        <!-- Conteúdo Principal -->
        <div class="relative z-10 flex-1 flex flex-col items-center justify-center px-4 pt-20 pb-24">
            <div class="max-w-xl w-full bg-white rounded-2xl shadow-md p-10 border border-gray-100">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 text-center mb-5 tracking-tight">
                    Bem-vindo ao <span class="text-blue-600">Desafio Pleno Stokki</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-700 text-center mb-8 font-normal">
                    Este sistema foi desenvolvido como desafio técnico para uma vaga de desenvolvedor pleno na Stokki.
                </p>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-8">
                    <li class="flex items-center text-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                        Gestão de pedidos
                    </li>
                    <li class="flex items-center text-green-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                        Gestão de transportadoras
                    </li>
                    <li class="flex items-center text-yellow-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                        Dashboard e relatórios inteligentes
                    </li>
                    <li class="flex items-center text-purple-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                        Exportação de dados
                    </li>
                </ul>
                <div class="flex flex-col md:flex-row justify-center md:space-x-4">
                    <Link v-if="canLogin && !$page.props.auth.user"
                          :href="route('login')"
                          class="mb-2 md:mb-0 px-7 py-3 bg-blue-700 hover:bg-blue-900 text-white font-bold rounded transition"
                    >
                        Entrar
                    </Link>
                    <Link v-if="canRegister && !$page.props.auth.user"
                        :href="route('register')"
                        class="px-7 py-3 bg-yellow-400 hover:bg-yellow-500 text-black font-bold rounded transition"
                    >
                        Registrar
                    </Link>
                    <Link v-if="$page.props.auth.user"
                          :href="route('dashboard')"
                          class="px-7 py-3 bg-green-700 hover:bg-green-800 text-white font-bold rounded transition"
                    >
                        Ir para o Painel
                    </Link>
                </div>
            </div>
        </div>

        <!-- Rodapé -->
        <footer class="absolute bottom-0 left-0 right-0 py-3 px-10 text-xs text-gray-500 flex justify-between items-center z-20 bg-white/80 backdrop-blur-sm border-t border-gray-200">
            <div>
                Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
            </div>
            <div>
                &copy; {{ new Date().getFullYear() }} Desafio Técnico - Stokki Pleno
            </div>
        </footer>
    </div>
</template>
