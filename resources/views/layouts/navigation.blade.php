<nav x-data="{open: false }" class="bg-transparent fixed w-full z-50 top-0 left-0 backdrop-blur-sm bg-white/10 border-b border-white/10">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}">
                        @if(file_exists(public_path('logo.png')))
                            <img src="{{ asset('logo.png') }}" alt="CECVT" class="block h-10 w-auto">
                        @else
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                        {{ __('Accueil') }}
                    </x-nav-link>

                    @auth
                        @if(Auth::user()->role === 'etudiant')
                            <x-nav-link :href="route('etudiant.dashboard')" :active="request()->routeIs('etudiant.dashboard')">
                                {{ __('Tableau de bord') }}
                            </x-nav-link>
                        @elseif(Auth::user()->role === 'conseiller')
                            <x-nav-link :href="route('conseiller.dashboard')" :active="request()->routeIs('conseiller.dashboard')">
                                {{ __('Tableau de bord') }}
                            </x-nav-link>
                        @elseif(Auth::user()->role === 'admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Tableau de bord') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-white bg-white/10 hover:bg-white/20 focus:bg-white/20 rounded-full px-3 py-2 transition ease-in-out duration-150">
                                <div class="flex items-center">
                                    <!-- Avatar -->
                                    @if(Auth::user())

                                        <!-- Nom et prénom -->
                                        <div class="ml-3 text-left">
                                            <div class="text-sm font-medium text-black">
                                                {{ Auth::user()->prenom ?? '' }} {{ Auth::user()->name ?? '' }}
                                            </div>
                                        </div>
                                    @else
                                        Utilisateur
                                    @endif


                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                <div class="flex items-center">

                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ Auth::user()->prenom ?? '' }} {{ Auth::user()->name ?? '' }}
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="px-4 py-2 text-xs text-gray-400">
                                {{ __('Gérer le compte') }}
                            </div>

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profil') }}
                            </x-dropdown-link>

                            <!-- Liens selon le rôle -->
                            @if(Auth::user()->role === 'admin')
                                <x-dropdown-link :href="route('admin.dashboard')">
                                    {{ __('Administration') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.utilisateurs.index')">
                                    {{ __('Gestion des utilisateurs') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.dossiers.index')">
                                    {{ __('Gestion des dossiers') }}
                                </x-dropdown-link>
                            @elseif(Auth::user()->role === 'conseiller')
                                <x-dropdown-link :href="route('conseiller.dashboard')">
                                    {{ __('Conseiller') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('conseiller.dossiers.index')">
                                    {{ __('Mes dossiers') }}
                                </x-dropdown-link>
                            @elseif(Auth::user()->role === 'etudiant')
                                <x-dropdown-link :href="route('etudiant.dashboard')">
                                    {{ __('Étudiant') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('etudiant.dossiers.index')">
                                    {{ __('Mes dossiers') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Déconnexion') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- Guest Navigation -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                            {{ __('Connexion') }}
                        </x-nav-link>

                        <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                            {{ __('Inscription') }}
                        </x-nav-link>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-200 hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-gray-200 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                {{ __('Accueil') }}
            </x-responsive-nav-link>

            @auth
                @if(Auth::user()->role === 'etudiant')
                    <x-responsive-nav-link :href="route('etudiant.dashboard')" :active="request()->routeIs('etudiant.dashboard')">
                        {{ __('Tableau de bord') }}
                    </x-responsive-nav-link>
                @elseif(Auth::user()->role === 'conseiller')
                    <x-responsive-nav-link :href="route('conseiller.dashboard')" :active="request()->routeIs('conseiller.dashboard')">
                        {{ __('Tableau de bord') }}
                    </x-responsive-nav-link>
                @elseif(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Tableau de bord') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-white/10">
                <div class="px-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-white/20 flex items-center justify-center">
                            <span class="text-white font-medium">
                                {{ substr(Auth::user()->prenom ?? 'U', 0, 1) }}{{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                            </span>
                        </div>
                        <div class="ml-3">
                            <div class="font-medium text-base text-white">
                                {{ Auth::user()->prenom ?? '' }} {{ Auth::user()->name ?? '' }}
                            </div>
                            <div class="font-medium text-sm text-white/80">
                                {{ Auth::user()->email }}
                            </div>
                            <div class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if(Auth::user()->role === 'admin') bg-red-100 text-red-800
                                    @elseif(Auth::user()->role === 'conseiller') bg-purple-100 text-purple-800
                                    @elseif(Auth::user()->role === 'etudiant') bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profil') }}
                    </x-responsive-nav-link>

                    <!-- Liens selon le rôle (responsive) -->
                    @if(Auth::user()->role === 'admin')
                        <x-responsive-nav-link :href="route('admin.dashboard')">
                            {{ __('Administration') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.utilisateurs.index')">
                            {{ __('Gestion des utilisateurs') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.dossiers.index')">
                            {{ __('Gestion des dossiers') }}
                        </x-responsive-nav-link>
                    @elseif(Auth::user()->role === 'conseiller')
                        <x-responsive-nav-link :href="route('conseiller.dashboard')">
                            {{ __('Conseiller') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('conseiller.dossiers.index')">
                            {{ __('Mes dossiers') }}
                        </x-responsive-nav-link>
                    @elseif(Auth::user()->role === 'etudiant')
                        <x-responsive-nav-link :href="route('etudiant.dashboard')">
                            {{ __('Étudiant') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('etudiant.dossiers.index')">
                            {{ __('Mes dossiers') }}
                        </x-responsive-nav-link>
                    @endif

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Déconnexion') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-white/10">
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Connexion') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Inscription') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>
