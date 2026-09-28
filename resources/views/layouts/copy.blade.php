<!-- Informations selon le rôle -->
        @auth
            <div class="border-t border-gray-700 mt-8 pt-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-gray-400 text-sm mb-4 md:mb-0">
                        Connecté en tant que
                        <span class="text-cecvt-yellow font-medium">
                            @if(Auth::user()->role === 'etudiant')
                                Étudiant
                            @elseif(Auth::user()->role === 'conseiller')
                                Conseiller
                            @elseif(Auth::user()->role === 'admin')
                                Administrateur
                            @endif
                        </span>
                    </div>
                    <div class="text-gray-400 text-sm">
                        Dernière connexion: {{ Auth::user()->last_login_at ?? 'Jamais' }}
                    </div>
                </div>
            </div>
        @endauth
