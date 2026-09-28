<footer class="bg-gray-800 text-yellow-600 mt-auto">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- Colonne 1 : Logo et description -->
            <div class="md:col-span-1">
                <div class="flex items-center mb-2">
                    <x-application-logo class="h-8 w-auto"/>
                </div>
                <p class="text-white text-xs">
                    Accompagnement des étudiants dans leurs démarches Campus France.
                </p>
            </div>

            <!-- Colonne 2 : Liens rapides selon le rôle -->
            <div class="md:col-span-1">
                <h4 class="text-sm font-semibold mb-2">Navigation</h4>
                <ul class="space-y-1">
                    @auth
                        @if(Auth::user()->role === 'etudiant')
                            <li><a href="{{ route('etudiant.dashboard') }}" class="text-gray-900 hover:text-white transition text-xs">Tableau de bord</a></li>
                            <li><a href="{{ route('etudiant.dossiers.index') }}" class="text-gray-900 hover:text-white transition text-xs">Mes dossiers</a></li>
                            <li><a href="{{ route('etudiant.dossiers.create') }}" class="text-gray-900 hover:text-white transition text-xs">Nouveau dossier</a></li>
                        @elseif(Auth::user()->role === 'conseiller')
                            <li><a href="{{ route('conseiller.dashboard') }}" class="text-gray-900 hover:text-white transition text-xs">Tableau de bord</a></li>
                            <li><a href="{{ route('conseiller.dossiers.index') }}" class="text-gray-900 hover:text-white transition text-xs">Dossiers à traiter</a></li>
                            <li><a href="#" class="text-gray-900 hover:text-white transition text-xs">Mes étudiants</a></li>
                        @elseif(Auth::user()->role === 'admin')
                            <li><a href="{{ route('admin.dashboard') }}" class="text-gray-900 hover:text-white transition text-xs">Tableau de bord</a></li>
                            <li><a href="{{ route('admin.utilisateurs.index') }}" class="text-gray-900 hover:text-white transition text-xs">Gestion utilisateurs</a></li>
                            <li><a href="#" class="text-gray-900 hover:text-white transition text-xs">Statistiques</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('login') }}" class="text-gray-900 hover:text-white transition text-xs">Connexion</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-900 hover:text-white transition text-xs">Inscription</a></li>
                    @endauth
                </ul>
            </div>

            <!-- Colonne 3 : Liens communs -->
            <div class="md:col-span-1">
                <h4 class="text-sm font-semibold mb-2">Liens utiles</h4>
                <ul class="space-y-1">
                    <li><a href="{{ route('welcome') }}" class="text-gray-900 hover:text-white transition text-xs">À propos</a></li>
                    <li><a href="{{ route('welcome') }}" class="text-gray-900 hover:text-white transition text-xs">Services</a></li>
                </ul>
            </div>

            <!-- Colonne 4 : Contact -->
            <div class="md:col-span-1">
                <h4 class="text-sm font-semibold mb-2">Contact</h4>
                <div class="space-y-2 text-gray-400">
                    <div class="flex items-start">
                        <span class="mr-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 text-white">
                                <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span class="text-white text-xs">
                            +228 90 90 90 53
                            <br>
                            +228 93 72 34 96
                            <br>
                            +33 7 53 84 47 75
                        </span>
                    </div>
                    <div class="flex items-start">
                        <span class="mr-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 text-white">
                                <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                                <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                            </svg>
                        </span>
                        <span class="text-white text-xs">cecvt99@gmail.com</span>
                    </div>
                    <div class="flex items-start">
                        <span class="mr-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 text-white">
                                <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span class="text-white text-xs">
                            Lomé, TOGO
                            <br>
                            Agoe demakpoe,
                            <br>
                            non loin du marché agoé assiyéyé
                            <br>
                            et à 100m de l'hôtel la base 228.
                        </span>
                    </div>
                    <div class="flex items-start">
                        <span class="mr-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 text-gray-800">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span class="text-white text-xs">Lundi-Samedi: 8h-18h</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-yellow-600 mt-4 pt-4 text-center text-yellow-600 text-xs">
            <p>&copy; {{ date('Y') }} CECVT - Cabinet d'Étude, de Conseils et de Voyages de Mr Traoré.</p>
            <p class="mt-1 text-xs">Tous droits réservés.</p>
        </div>
    </div>
</footer>
