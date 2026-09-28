<x-app-layout>
    <!--Titre de la page -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-600 leading-tight">
            {{ __('Bienvenu au CECVT') }}
        </h2>
    </x-slot>

    <!-- Images d'arrière plan avec les boutton de connexion et d'inscription -->
    <div id="background-slider"
            class="relative h-98 w-full bg-no-repeat bg-cover bg-center transition-all duration-1000 ease-in-out"
            style="min-height: 500px;">

        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
        <div class="relative z-10 py-24 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">
                    Réalisez votre projet Campus France avec Nous
                </h1>
                <p class="text-xl md:text-2xl text-white mb-12 max-w-3xl mx-auto">
                    Nous avons des solutions sur mesure pour vos ambitions. Le Cabinet CECVT vous accompagne dans toutes vos démarches pour étudier en France via Campus France.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Accéder à mon espace
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center px-8 py-4 bg-gray-900 text-yellow-700 font-bold rounded-lg hover:bg-gray-800 transition duration-300 transform hover:scale-105">
                            Commencer maintenant
                        </a>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-lg hover:bg-gray-100 transition duration-300 border-2 border-white hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            J'ai déjà un compte
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!--Section des service proposé -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-yellow-600 mb-4">
                    Nos Services
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Un accompagnement complet de A à Z pour votre projet d'études en France
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-500 text-blue-100 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-yellow-600 mb-2">Accompagnement Dossier</h3>
                        <p class="text-gray-600">
                            Assistance complète dans la constitution et le dépôt de votre dossier Campus France
                        </p>
                    </div>
                </div>

                <!-- Service 2 -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-500 text-green-100 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-yellow-600 mb-2">Suivi Financier</h3>
                        <p class="text-gray-600">
                            Gestion et suivi de vos paiements avec transparence et sécurité
                        </p>
                    </div>
                </div>

                <!-- Service 3 -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-purple-500 text-purple-100 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-8 0 3 3 0 018 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-yellow-600 mb-2">Support Personnalisé</h3>
                        <p class="text-gray-600">
                            Conseils et accompagnement personnalisés tout au long de votre processus
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section de comment ça marche avec nous -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-red-600 mb-4">
                    Comment ça marche ?
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Un processus simple et efficace pour réussir votre candidature
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Étape 1 -->
                <div class="text-center bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow relative bg-cover bg-center bg-no-repeat"
                             style="background-image: url('{{ asset('image/image7.jpeg') }}');">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-900 text-green-100 font-bold mx-auto mb-4">
                        1
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Inscription</h3>
                    <p class="text-sm text-gray-900">
                        Créez votre compte en quelques clics
                    </p>
                </div>

                <!-- Étape 2 -->
                <div class="text-center bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow relative bg-cover bg-center bg-no-repeat"
                    style="background-image: url('{{ asset('image/image9.jpeg') }}'); background-size: cover; background-position: center;">

                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-900 text-blue-500 font-bold mx-auto mb-4">
                        2
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Dossier</h3>
                    <p class="text-sm text-gray-900">
                        Créez et remplissez votre dossier
                    </p>
                </div>

                <!-- Étape 3 -->
                <div class="text-center bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow relative bg-cover bg-center bg-no-repeat"
                    style="background-image: url('{{ asset('image/image5.jpeg') }}');">

                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-900 text-yellow-600 font-bold mx-auto mb-4">
                        3
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Paiement</h3>
                    <p class="text-sm text-gray-900">
                        Effectuez le paiement requis
                    </p>
                </div>

                <!-- Étape 4 -->
                <div class="text-center bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow relative bg-cover bg-center bg-no-repeat"
                    style="background-image: url('{{ asset('image/image8.jpeg') }}');">

                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-900 text-green-600 font-bold mx-auto mb-4">
                        4
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Validation</h3>
                    <p class="text-sm text-gray-900">
                        Suivez votre dossier validé
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    // public/js/background-slider.js
    const images = [
        '{{ asset("image/image2.png") }}',
        '{{ asset("image/preuve20.jpeg") }}',

        '{{ asset("image/preuve1.jpeg") }}',
        '{{ asset("image/preuve2.jpeg") }}',
        '{{ asset("image/preuve3.jpeg") }}',
        '{{ asset("image/preuve4.jpeg") }}',
        '{{ asset("image/preuve5.jpeg") }}',
        '{{ asset("image/preuve6.jpeg") }}',
        '{{ asset("image/preuve7.jpeg") }}',
        '{{ asset("image/preuve8.jpeg") }}',
        '{{ asset("image/preuve9.jpeg") }}',
        '{{ asset("image/preuve10.jpeg") }}',
        '{{ asset("image/preuve11.jpeg") }}',
        '{{ asset("image/preuve12.jpeg") }}',
        '{{ asset("image/preuve13.jpeg") }}',
        '{{ asset("image/preuve14.jpeg") }}',
        '{{ asset("image/preuve15.jpeg") }}',
        '{{ asset("image/preuve16.jpeg") }}',
        '{{ asset("image/preuve17.jpeg") }}',
        '{{ asset("image/preuve18.jpeg") }}',
        '{{ asset("image/preuve19.jpeg") }}',
        '{{ asset("image/preuve18.jpeg") }}',

    ];

    let currentIndex = 0;
    const backgroundSlider = document.getElementById('background-slider');

    function changeBackground() {
        backgroundSlider.style.backgroundImage = `url('${images[currentIndex]}')`;
        currentIndex = (currentIndex + 1) % images.length;
    }

    // Initial background image
    changeBackground();

    // Change background every 5 seconds
    setInterval(changeBackground, 5000);
</script>
