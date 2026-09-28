<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dossier;
use App\Models\Paiement;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class ConseillerController extends Controller
{



    public function dashboard()
    {
        if (Auth::user()->role !== 'conseiller') {
            abort(403, 'Accès non autorisé');
        }
        $conseiller = Auth::user();

        $dossiersAssignes = $conseiller->dossiersAssignes()
            ->with([
                'etudiant.user',
                'paiements',
                'documents',
                ])
            ->latest()
            ->take(10)
            ->get();

        $stats=[
            'total_dossiers'=>$conseiller->dossiersAssignes()->count(),
            'dossiers_en_cours'=>$conseiller->dossiersAssignes()
                ->whereIn('statut', [
                    'nouveau',
                    'documents_acceptes',
                    'paiement_accepte',
                    'traitement_dossier',
                    'soumis_campus_france',
                    'dossier_complet',


                ])
                ->count(),
            'dossiers_termines'=>$conseiller->dossiersAssignes()
                ->where('statut','termine')
                ->count(),
            'dossiers_attente'=>$conseiller->dossiersAssignes()
                ->whereIn('statut',[
                    'attente_verification_documents',
                    'attente_verification_paiement',
                    'attente_validation_finale',
                    'attente_paiement',
                    'document_a_corriger',
                    'paiement_rejeter',

                ])
                ->count(),

        ];

        return view('conseiller.dashboard',compact('dossiersAssignes','stats'));
    }



    public function tousDossiers()
    {
        if (Auth::user()->role !== 'conseiller') {
            abort(403, 'Accès non autorisé');
        }
        $conseiller=Auth::user();
        $dossiers=$conseiller->dossiersAssignes()
            ->with([
                'etudiant.user',
                'paiements',
                'documents'
                ])
            ->latest()
            ->paginate(15);

        if (request()->get('search')) {
            $query->whereHas('etudiant.user', function($q) {
                $q->where('name', 'LIKE', '%' . request()->get('search') . '%')
                ->orWhere('prenom', 'LIKE', '%' . request()->get('search') . '%');
            });
        }
        return view('conseiller.dossiers.index',compact('dossiers'));
    }



    public function voirDossier($dossierId)
    {
        if (Auth::user()->role !== 'conseiller') {
            abort(403, 'Accès non autorisé');
        }
        $conseiller = Auth::user();

        $dossier = $conseiller->dossiersAssignes()
            ->with([
                'etudiant.user',
                'etudiant.dossiers',
                'documents',
                'paiements',
                'documents.verifiePar',
                'paiements.verifiePar'
            ])
            ->findOrFail($dossierId);


        return view('conseiller.dossiers.show', compact('dossier'));
    }



    public function formulaireVerificationDocument($documentId)
    {
        if (Auth::user()->role !== 'conseiller') {
            abort(403, 'Accès non autorisé');
        }
        $conseiller = Auth::user();

        $document = Document::with(['dossier.etudiant.user', 'dossier.assigne'])
            ->findOrFail($documentId);

        if ($document->dossier->assigne_a !== $conseiller->user_id) {
            abort(403, 'Accès non autorisé');
        }

        return view('conseiller.documents.verifier', compact('document'));
    }



    public function verifierDocument(Request $request,$documentId)
    {
        if (Auth::user()->role !== 'conseiller') {
            abort(403, 'Accès non autorisé');
        }
        $valideted_data=$request->validate([
            'action'=>'required|in:valider,corriger',
            'commentaires'=>'nullable|string',
        ]);

        $conseiller=Auth::user();
        $document=Document::findOrFail($documentId);
        $dossier=$document->dossier;
        if ($document->dossier->assigne_a !== $conseiller->user_id) {
            return redirect()->back()->with('error','Accès non autorisé.');
        }

        $dossierController=new DossierController();

        if ($valideted_data['action'] === 'valider') {
            $document->update([
                'statut'=>'valide',
                'commentaires'=>$valideted_data['commentaires'],
                'verifie_par'=>$conseiller->user_id,
                'verifie_a'=>now(),


            ]);

            $tousDocumentsValides = $dossier->documents()->where('statut', '!=', 'valide')->count() ===0;
            //dd($tousDocumentsValides);
            if ($tousDocumentsValides) {
                //$dossierStatut=$dossierController->changerStatut($dossier, 'documents_acceptes');
                $dossierStatut=$dossier->update(['statut'=>'documents_acceptes',]);
                //dd($dossierStatut);
                if ($dossier->statut==='documents_acceptes') {
                    $dossierController->changerStatut($dossier, 'attente_paiement');
                }
                return redirect()->route('conseiller.dossiers.show', $dossier->dossier_id)
                            ->with('success', 'Document validé avec succès ! Tous les documents sont maintenant acceptés. Le dossier passe en attente de paiement.');
            } else {
                return redirect()->route('conseiller.dossiers.show', $dossier->dossier_id)
                            ->with('success', 'Document validé avec succès !');
            }

            return redirect()->route('conseiller.dossiers.show', $dossier->dossier_id)
                            ->with('success','Document validé avec succès');
        }else {
            $document->update([
                'statut'=>'a_corriger',
                'commentaires'=>$valideted_data['commentaires'],
                'verifie_par'=>$conseiller->user_id,
                'verifie_a'=>now(),
            ]);

            $dossier=$document->dossier;
            $tousDocumentsValides = $dossier->documents()->where('statut', '!=', 'valide')->count() === 0;
            if ($tousDocumentsValides) {
                $dossierController->changerStatut($dossier, 'documents_acceptes');

                return redirect()->route('conseiller.dossiers.show', $dossier->dossier_id)
                            ->with('success', 'Tout documents validé maintenant merci.');
            } else {
                $dossierController->changerStatut($dossier, 'documents_a_corriger');

                return redirect()->route('conseiller.dossiers.show', $dossier->dossier_id)
                            ->with('success', 'Document marqué à corriger. Etudiant notifié');
            }


            return redirect()->route('conseiller.dossiers.show', $dossier->dossier_id)
                            ->with('success','Document marqué à corriger. Etudiant notifié');
        }
    }



    public function formulaireVerificationPaiement($dossierId)
    {
        if (Auth::user()->role !== 'conseiller') {
            abort(403, 'Accès non autorisé');
        }

        $conseiller = Auth::user();
        $dossier = $conseiller->dossiersAssignes()
            ->with(['etudiant.user', 'paiements.verifiePar'])
            ->findOrFail($dossierId);

        $paiement = $dossier->paiements()->first();

        if (!$paiement) {
            return redirect()->back()->with('error', 'Aucun paiement trouvé pour ce dossier.');
        }

        return view('conseiller.dossiers.verifier-paiement', compact('dossier', 'paiement'));
    }




    public function verifierPaiement(Request $request,$dossierId)
    {
        if (Auth::user()->role !== 'conseiller') {
            abort(403, 'Accès non autorisé');
        }

        $valideted_data=$request->validate([
            'action'=>'required|in:confirmer,rejeter',
            'motif_rejet'=>'nullable|string',
        ]);

        $conseiller=Auth::user();
        $dossier=$conseiller->dossiersAssignes()->findOrFail($dossierId);
        $paiement=$dossier->paiements()->first();


        if (!$paiement) {
            return redirect()->back()->with('error', 'Aucun paiement trouvé pour ce dossier.');
        }

        $dossierController = new DossierController();


        if ($paiement) {
            if ($valideted_data['action'] === 'confirmer') {
                $paiement->update([
                    'statut'=> 'confirme',
                    'verifier_par'=>$conseiller->user_id,
                    'verifier_a'=>now(),
                ]);

                //$dossierController->changerStatut($dossier, 'paiement_accepte');

                $dossier->update(['statut'=>'paiement_accepte',]);

                return redirect()->route('conseiller.dossiers.show', $dossier->dossier_id)
                                ->with('success','Vous venez de confirmer que le paiement est bien correcte.');


            }else {
                $paiement->update([
                    'statut'=>'rejete',
                    'motif_rejet'=>$valideted_data['motif_rejet'],
                    'verifie_par'=>$conseiller->user_id,
                    'verifie_a'=>now(),
                ]);

                $dossierController->changerStatut($dossier, 'paiement_a_corriger', $request->motif_rejet);

                return redirect()->route('conseiller.dossiers.show', $dossier->dossier_id);
            }
        }

        return redirect()->back()->with('error','Aucun paiement trouver pour ce dossier');
    }



    public function formulaireTraitementDossier($dossierId)
    {

        if (Auth::user()->role !== 'conseiller') {
            abort(403, 'Accès non autorisé');
        }

        $conseiller = Auth::user();
        $dossier = $conseiller->dossiersAssignes()
            ->with(['etudiant.user', 'documents', 'paiements'])
            ->findOrFail($dossierId);

        // VÉRIFIER QUE LE DOSSIER EST AU BON STATUT
        $dossierController = new DossierController();
        if ($dossier->statut !== 'paiement_accepte' && $dossier->statut !== 'traitement_dossier' ) {
            //dd('ici bien');
            return redirect()->back()->with('error', 'Vous ne pouvez pas traiter ce dossier pour le moment.');
        }

        return view('conseiller.dossiers.traiter', compact('dossier'));
    }


    public function traiterDossier(Request $request, $dossierId)
    {
        if (Auth::user()->role !== 'conseiller') {
            abort(403, 'Accès non autorisé');
        }

        $validated_data=$request->validate([
            'documents_cabinet.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png',
            'type_documents.*' => 'required|string|in:cv,lettre_motivation,autres_documents',
            'commentaires' => 'nullable|string',
        ]);

        //dd($validated_data);

        $conseiller = Auth::user();
        $dossier = $conseiller->dossiersAssignes()->findOrFail($dossierId);

        $dossierController = new DossierController();

        if (!$dossierController->paiementAccepte($dossier) && !$dossierController->traitementDossier($dossier) ) {
            //dd('ici');
            return redirect()->back()->with('error', 'Vous ne pouvez pas traiter ce dossier pour le moment.');
        }


        if ($request->hasFile('documents_cabinet')) {
            $files = $validated_data['documents_cabinet'];
            $types = $validated_data['type_documents'];

            $count = min(count($files), count($types));

            for ($i = 0; $i < $count; $i++) {
                $file = $files[$i];
                $type = $types[$i];

                if ($file && $file->isValid()) {
                    $nomFichier = $file->getClientOriginalName();
                    $cheminFichier = $file->store('documents_cabinet', 'public');

                    Document::create([
                        'dossier_id' => $dossier->dossier_id,
                        'type_document' => $type,
                        'nom_original' => $nomFichier,
                        'chemin_fichier' => $cheminFichier,
                        'statut' => 'valide',
                        'verifie_par' => $conseiller->user_id,
                        'verifie_a' => now(),
                        'commentaires' => $validated_data['commentaires'] ?? 'Document ajouté par par le cabinet vous dévriez le verifier voir si c\'est correct',
                    ]);
                }
            }
        }


        //$dossierController->changerStatut($dossier, 'attente_validation_finale');

        $dossier->update(['statut'=>'attente_validation_finale',]);

        return redirect()->route('conseiller.dossiers.show', $dossier->dossier_id)
                    ->with('success', 'Vous venez d\'ajouter des documents pour le traitement');
    }


    public function soumettreCampusFrance(Request $request, $dossierId)
    {
        if (Auth::user()->role !== 'conseiller') {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'email_suivi' => 'required|email|unique:dossiers,email_suivi_campus_france',
        ]);

        $conseiller = Auth::user();
        $dossier = $conseiller->dossiersAssignes()->findOrFail($dossierId);

        $dossierController = new DossierController();

        if (!$dossierController->dossierComplet($dossier)) {
            return redirect()->back()->with('error', 'Le dossier doit être complet avant de le soumettre.');
        }

        $dossier->update([
            'statut' => 'soumis_campus_france',
            'email_suivi_campus_france' => $request->email_suivi,
            'soumis_a_campus_france_a' => now(),
        ]);

        return redirect()->back()->with('success', 'Dossier soumis à Campus France avec succès !');
    }


    /**finalisation avec le dossier email,password,site de suivi */

    public function envoyerEmailSuivi(Request $request, $dossierId)
    {
        if (Auth::user()->role !== 'conseiller') {
            abort(403, 'Accès non autorisé');
        }
        
        $request->validate([
            'email_suivi' => 'required|email|unique:dossiers,email_suivi_campus_france',
            'mot_de_passe' => 'required|string|min:6',
            'lien_site' => 'required|url',
        ]);

        $conseiller = Auth::user();
        $dossier = $conseiller->dossiersAssignes()->findOrFail($dossierId);

        if ($dossier->statut !== 'dossier_complet') {
            return redirect()->back()->with('error', 'Vous ne pouvez pas envoyer d\'email de suivi pour ce dossier.');
        }

        try {
            // Mail::to($dossier->etudiant->user->email)->send(new EmailSuiviCampusFrance($dossier, $request->all()));

            $dossier->update([
                'email_suivi_campus_france' => $request->email_suivi,
                'mot_de_passe_campus_france' => $request->mot_de_passe,
                'lien_site_campus_france' => $request->lien_site,
                'statut' => 'soumis_campus_france',
                'soumis_a_campus_france_a' => now(),
            ]);

            return redirect()->back()->with('success', 'Email de suivi envoyé avec succès ! Le dossier est maintenant soumis à Campus France.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }
    }



}
