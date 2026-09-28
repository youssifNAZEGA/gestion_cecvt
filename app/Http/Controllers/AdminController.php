<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dossier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }


        //statistique de l'avancement
        $stats = [
            'total_utilisateurs' => User::count(),
            'total_etudiants' => User::where('role', 'etudiant')->count(),
            'total_conseillers' => User::where('role', 'conseiller')->count(),
            'total_dossiers' => Dossier::count(),
            'dossiers_non_assignes' => Dossier::whereNull('assigne_a')->count(),
            'dossiers_en_cours' => Dossier::whereIn('statut', [
                'attente_verification_documents',
                'attente_verification_paiement',
                'traitement_dossier',
                'attente_validation_finale'
            ])->count(),
            'dossiers_termines' => Dossier::where('statut', 'termine')->count(),
        ];

        $dossiersNonAssignes = Dossier::with(['etudiant.user'])
        ->whereNull('assigne_a')
        ->whereHas('etudiant')
        ->latest()
        ->take(5)
        ->get();


        $derniersUtilisateurs = User::with('etudiant')
            ->latest()
            ->take(5)
            ->get();
        $derniersDossiers = Dossier::with(['etudiant.user', 'assigne'])
            ->whereHas('etudiant')
            ->latest()
            ->take(5)
            ->get();


        return view('admin.dashboard', compact('stats', 'derniersUtilisateurs', 'derniersDossiers'));

        //return view('admin.dashboard', compact('stats'));
    }

    //la liste des utilisateurs
    public function listeUtilisateurs()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }

        $utilisateurs = User::with('etudiant')
            ->latest()
            ->paginate(15);
        return view('admin.utilisateurs.index', compact('utilisateurs'));
    }

    //creation d'un utilisateur
    public function creerUtilisateur()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }

        return view('admin.utilisateurs.create');
    }

    public function enregistrerUtilisateur(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }

        $valideted_data=$request->validate([
            'name' => 'required|string',
            'prenom' => 'required|string',
            'sexe' => 'required|in:homme,femme',
            'contact' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'role' => 'required|in:conseiller,admin',
        ]);

        $user=User::create([
            'name' => $valideted_data['name'],
            'prenom' => $valideted_data['prenom'],
            'sexe' => $valideted_data['sexe'],
            'contact' => $valideted_data['contact'],
            'email' => $valideted_data['email'],
            'password' => Hash::make($valideted_data['password']),
            'role' => $valideted_data['role'],
        ]);

        return redirect()->route('admin.utilisateurs.index')
                        ->with('success', 'Utilisateur créé avec succès !');
    }
    //suppression d'un utilisateur
    public function supprimerUtilisateur($userId)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }

        $user = User::findOrFail($userId);

        // Ne pas permettre à un admin de se supprimer lui-même
        if ($user->user_id === Auth::user()->user_id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }

        $user->delete();

        return redirect()->route('admin.utilisateurs.index')
                        ->with('success', 'Utilisateur supprimé avec succès !');
    }


    public function listeDossiers()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }

        $dossiers = Dossier::with(['etudiant.user', 'assigne']);

        if (request()->get('statut')) {
            $dossiers->where('statut',request()->get('statut'));
        }

        if (request()->get('assigne')) {
            if (request()->get('assigne')==='non_assigne') {
                $dossiers->whereNull('assigne_a');
            }else{
                $dossiers->where('assigne_a',request()->get('assigne'));
            }
        }

        if (request()->get('statut')) {
            $dossiers->whereHas('etudiant.user', function($dossier){
                $dossier->where('name','LIKE','%'.request()->get('search'))
                ->orWhere('prenom','like','%'.request()->get('search'))
                ->orWhere('email','like','%'.request()->get('search'));
            });
        }

        $dossiers=$dossiers->latest()->paginate(15);

        /*
        foreach($dossiers as $dossier) {
            echo "Dossier ID: {$dossier->dossier_id} | ";
            echo "etudiant_id: {$dossier->etudiant_id} | ";

            if ($dossier->etudiant) {
                echo "Étudiant trouvé: " . ($dossier->etudiant->user->name ?? 'Pas d\'user');
            } else {
                echo "AUCUN ÉTUDIANT TROUVÉ";
            }
            echo "<br>";
        }
        */


        $conseillers = User::where('role', 'conseiller')->get();
        $statuts = Dossier::select('statut')->distinct()->pluck('statut');

        return view('admin.dossiers.index', compact('dossiers', 'conseillers', 'statuts'));
    }

    public function formulaireAssignation($dossierId)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }

        $dossier = Dossier::with(['etudiant.user'])->findOrFail($dossierId);
        $conseillers = User::where('role', 'conseiller')->get();

        return view('admin.dossiers.assigner', compact('dossier', 'conseillers'));
    }

    public function assignerDossier(Request $request, $dossierId)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }

        $valideted_data=$request->validate([
            'conseiller_id' => 'required|exists:users,user_id',
        ]);

        $dossier = Dossier::findOrFail($dossierId);
        $conseiller = User::findOrFail($valideted_data['conseiller_id']);

        if ($conseiller->role !== 'conseiller') {
            return redirect()->back()->with('error', 'L\'utilisateur sélectionné n\'est pas un conseiller.');
        }

        $dossier->update([
            'assigne_a' => $conseiller->user_id,
        ]);

        return redirect()->route('admin.dossiers.index')
                        ->with('success', 'Dossier assigné à ' . $conseiller->prenom . ' ' . $conseiller->name . ' avec succès !');
    }

    public function voirDossier($dossierId)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }

        $dossier = Dossier::with([
            'etudiant.user',
            'assigne',
            'documents.verifiePar',
            'paiements.verifiePar',
            'documents' => function($document) {
                $document->orderBy('created_at', 'desc');
            },
            'paiements' => function($paiement) {
                $paiement->orderBy('created_at', 'desc');
            }
        ])->findOrFail($dossierId);

        $avancement = $this->calculerAvancement($dossier->statut);
        $couleur_statut = $this->getCouleurStatut($dossier->statut);
        $statut_fr = $this->traduireStatut($dossier->statut);

        return view('admin.dossiers.show', compact(
            'dossier',
            'avancement',
            'couleur_statut',
            'statut_fr'
        ));
    }

    private function calculerAvancement($statut)
    {
        $etapes = [
            'nouveau' => 5,
            'attente_verification_documents' => 10,
            'documents_a_corriger' => 15,
            'documents_acceptes' => 25,
            'attente_paiement' => 30,
            'attente_verification_paiement' => 35,
            'paiement_a_corriger' => 40,
            'paiement_accepte' => 50,
            'traitement_dossier' => 70,
            'attente_validation_finale' => 80,
            'dossier_complet' => 90,
            'soumis_campus_france' => 95,
            'termine' => 100
        ];

        return $etapes[$statut] ?? 0;
    }

    private function getCouleurStatut($statut)
    {
        $couleurs = [
            'nouveau' => 'bg-gray-100 text-gray-800',
            'attente_verification_documents' => 'bg-yellow-100 text-yellow-800',
            'documents_a_corriger' => 'bg-red-100 text-red-800',
            'documents_acceptes' => 'bg-green-100 text-green-800',
            'attente_paiement' => 'bg-orange-100 text-orange-800',
            'attente_verification_paiement' => 'bg-purple-100 text-purple-800',
            'paiement_a_corriger' => 'bg-red-100 text-red-800',
            'paiement_accepte' => 'bg-green-100 text-green-800',
            'traitement_dossier' => 'bg-blue-100 text-blue-800',
            'attente_validation_finale' => 'bg-indigo-100 text-indigo-800',
            'dossier_complet' => 'bg-green-100 text-green-800',
            'soumis_campus_france' => 'bg-blue-200 text-blue-900',
            'termine' => 'bg-gray-200 text-gray-900'
        ];

        return $couleurs[$statut] ?? 'bg-gray-100 text-gray-800';
    }

    private function traduireStatut($statut)
    {
        $traductions = [
            'nouveau' => 'Nouveau',
            'attente_verification_documents' => 'Attente vérification documents',
            'documents_a_corriger' => 'Documents à corriger',
            'documents_acceptes' => 'Documents acceptés',
            'attente_paiement' => 'Attente paiement',
            'attente_verification_paiement' => 'Attente vérification paiement',
            'paiement_a_corriger' => 'Paiement à corriger',
            'paiement_accepte' => 'Paiement accepté',
            'traitement_dossier' => 'Traitement dossier',
            'attente_validation_finale' => 'Attente validation finale',
            'dossier_complet' => 'Dossier complet',
            'soumis_campus_france' => 'Soumis Campus France',
            'termine' => 'Terminé'
        ];

        return $traductions[$statut] ?? $statut;
    }
    //Performance de chaque utilisateurs
    public function tableauBordPerformances()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }
        $performancesConseillers = User::where('role', 'conseiller')
            ->withCount([
                'dossiersAssignes as total_dossiers',
                'dossiersAssignes as dossiers_termines' => function($statut) {
                    $statut->where('statut', 'termine');
                },
                'dossiersAssignes as dossiers_en_cours' => function($statut) {
                    $statut->whereIn('statut', [
                        'attente_verification_documents',
                        'attente_verification_paiement',
                        'traitement_dossier',
                        'attente_validation_finale'
                    ]);
                }
            ])
            ->get()
            ->map(function($conseiller) {
                $total = $conseiller->total_dossiers;
                $termines = $conseiller->dossiers_termines;
                $pourcentage = $total > 0 ? round(($termines / $total) * 100, 1) : 0;

                return [
                    'conseiller' => $conseiller,
                    'total' => $total,
                    'termines' => $termines,
                    'en_cours' => $conseiller->dossiers_en_cours,
                    'pourcentage' => $pourcentage
                ];
            });

        $tendancesMensuelles = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $mois = $date->format('M Y');
            $tendancesMensuelles[$mois] = [
                'nouveaux_dossiers' => Dossier::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'dossiers_termines' => Dossier::where('statut', 'termine')
                    ->whereMonth('termine_a', $date->month)
                    ->whereYear('termine_a', $date->year)
                    ->count(),
            ];
        }

        return view('admin.performances.index', compact('performancesConseillers', 'tendancesMensuelles'));
    }
}
