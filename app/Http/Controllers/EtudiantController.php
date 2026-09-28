<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Dossier;
use App\Models\Document;
use App\Models\Paiement;
use Illuminate\Support\Facades\Auth;

class EtudiantController extends Controller
{
    /**
     * Afficher le dashboard de l'étudiant
     */
    public function dashboard()
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès non autorisé');
        }
        
        $etudiant = Auth::user()->etudiant;
        $dossiers = $etudiant->dossiers()
            ->with(['assigne'])
            ->latest()
            ->take(5)
            ->get();

        return view('etudiant.dashboard', compact('etudiant', 'dossiers'));
    }

    /**
     * Afficher la liste des dossiers de l'étudiant
     */
    public function mesDossiers()
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès non autorisé');
        }

        $etudiant = Auth::user()->etudiant;
        $dossiers = $etudiant->dossiers()
            ->with(['assigne'])
            ->latest()
            ->paginate();

        return view('etudiant.dossiers.index', compact('dossiers'));
    }

    /**
     * Afficher le formulaire de création d'un nouveau dossier
     */
    public function creerDossier()
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès non autorisé');
        }

        return view('etudiant.dossiers.create');
    }

    /**
     * Enregistrer un nouveau dossier
     */
    public function enregistrerDossier(Request $request)
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès non autorisé');
        }

        $valideted_data=$request->validate([
            'domaine_etude' => 'required|string',
            'niveau_etude' => 'required|string',
            'universite_actuelle' => 'required|string',
        ]);

        $etudiant = Auth::user()->etudiant;

        $dossier = Dossier::create([
            'etudiant_id' => $etudiant->user_id,
            'statut' => 'nouveau',
            'montant' => 200000,
            'reference_paiement' => 'REF' . time() . rand(1000, 9999),
        ]);

        $etudiant->update([
            'domaine_etude' => $valideted_data['domaine_etude'],
            'niveau_etude' => $valideted_data['niveau_etude'],
        ]);

        //$dossierController = new DossierController();
        //$dossierController->changerStatut($dossier, 'attente_verification_documents');

        return redirect()->route('etudiant.dossiers.show', $dossier->dossier_id)
                        ->with('success', 'Dossier créé avec succès ! En attente de verification des documents');
    }

    /**
     * Afficher un dossier
     */
    public function voirDossier($dossierId)
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès non autorisé');
        }

        $etudiant = Auth::user()->etudiant;
        $dossier = $etudiant->dossiers()
            ->with(['documents', 'paiements', 'assigne'])
            ->findOrFail($dossierId);

        return view('etudiant.dossiers.show', compact('dossier'));
    }

    /**
     * Preuve de paiement
     */

    public function uploadRecu($dossierId)
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès non autorisé');
        }

        $etudiant = Auth::user()->etudiant;
        $dossier = $etudiant->dossiers()->findOrFail($dossierId);

        $dossierController=new DossierController();
        if ($dossierController->attentePaiement($dossier)) {
            return view('etudiant.dossiers.upload-recu', compact('dossier'));

        }elseif($dossierController->paiementACorriger($dossier)){
            return view('etudiant.dossiers.upload-recu', compact('dossier'));

        }
        return redirect()->back()->with('error', 'Vous ne pouvez pas charger de reçu pour ce dossier.');


    }

    public function enregistrerRecu(Request $request, $dossierId)
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès non autorisé');
        }

        $validated_data=$request->validate([
            'recu' => 'required|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $etudiant = Auth::user()->etudiant;
        $dossier = $etudiant->dossiers()->findOrFail($dossierId);

        if ($request->hasFile('recu')) {
            $cheminRecu = $request->file('recu')->store('recus', 'public');

            $paiement = Paiement::updateOrCreate(
                ['dossier_id' => $dossier->dossier_id],
                [
                    'montant' => $dossier->montant,
                    'methode_paiement' => 'virement_bancaire',
                    'chemin_recu' => $cheminRecu,
                    'statut' => 'a_verifier',
                ]
            );

            $dossierController=new DossierController();
            $dossierController->changerStatut($dossier,'attente_verification_paiement');
            //$dossier->update(['statut' => 'preuve_paiement_envoyee']);

            return redirect()->route('etudiant.dossiers.show', $dossier->dossier_id)
                            ->with('success', 'Reçu chargeé avec succès ! En attente de vérification.');
        }

        return redirect()->back()->with('error', 'Erreur lors du chargement du reçu.');
    }


    /**
     * Ajouter documents
     */

    public function formulaireUploadDocument($dossierId)
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès non autorisé');
        }

        $etudiant = Auth::user()->etudiant;
        $dossier = $etudiant->dossiers()->findOrFail($dossierId);

        // Vérifier que le dossier est au bon statut
        if (!in_array($dossier->statut, ['nouveau', 'attente_verification_documents', 'documents_a_corriger', 'documents_acceptes','attente_paiement'])) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas chargé de documents pour ce dossier.');
        }

        return view('etudiant.dossiers.upload-document', compact('dossier'));
    }

    public function uploadDocuments(Request $request, $dossierId)
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès non autorisé');
        }

        $validated_data = $request->validate([
            'documents.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'type_documents.*' => 'required|string',
        ]);

        //dd($validated_data);

        $etudiant = Auth::user()->etudiant;
        $dossier = $etudiant->dossiers()->findOrFail($dossierId);

        //dd($dossier);attentePaiement

        $dossierController = new DossierController();
        if (!$dossierController->enAttenteVerificationDocuments($dossier) &&
            !$dossierController->documentsACorriger($dossier) && !$dossierController->estNouveau($dossier) &&
            !$dossierController->attentePaiement($dossier)) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas chargé de documents pour ce dossier.');
        }

        $documentsUploades = 0;

        //dd($documentsUploades);

        if ($request->hasFile('documents')) {
            $files = $request->file('documents');
            $types = $request->type_documents;

            //dd($types);

            if (count($files) === count($types)) {
                for ($i = 0; $i < count($files); $i++) {
                    $file = $files[$i];
                    $typeDocument = $types[$i];

                    //dd($typeDocument);

                    if ($file && $file->isValid()) {
                        $nomFichier = $file->getClientOriginalName();
                        $cheminFichier = $file->store('documents', 'public');

                        //dd($nomFichier);

                        $document = Document::where('dossier_id', $dossier->dossier_id)
                            ->where('type_document', $typeDocument)
                            ->where('statut', '!=', 'valide')
                            ->first();

                        //dd($document);

                        if ($document) {
                            $document->update([
                                'nom_original' => $nomFichier,
                                'chemin_fichier' => $cheminFichier,
                                'statut' => 'en_attente',
                                'verifie_par' => null,
                                'verifie_a' => null,
                                'commentaires' => null,
                            ]);
                        } else {
                            Document::create([
                                'dossier_id' => $dossier->dossier_id,
                                'type_document' => $typeDocument,
                                'nom_original' => $nomFichier,
                                'chemin_fichier' => $cheminFichier,
                                'statut' => 'en_attente',
                            ]);

                            //dd('document creer');

                        }

                        $documentsUploades++;
                    }
                }
            } else {
                return redirect()->back()->with('error', 'Erreur : nombre de fichiers et de types différents.');
            }
        }

        if ($dossier->statut === 'nouveau' || $dossier->statut === 'documents_a_corriger') {
            $dossier->update(['statut' => 'attente_verification_documents']);
        }

        if ($documentsUploades > 0) {
            return redirect()->route('etudiant.dossiers.show', $dossier->dossier_id)
                            ->with('success', $documentsUploades . ' document(s) chargé(s) avec succès ! En attente de vérification.');
        } else {
            return redirect()->back()->with('error', 'Aucun document valide n\'a été chargé.');
        }
    }


    public function validerDossierFinal(Request $request, $dossierId)
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès non autorisé');
        }

        $etudiant=Auth::user()->etudiant;
        $dossier=$etudiant->dossiers()->findOrFail($dossierId);


        $dossierController=new DossierController();
        if (!$dossierController->attenteVerificationFinale($dossier)) {
            return redirect()->back()->with('erreur', 'Vous ne pouvez pas valider ce dossier pour le moment car elle est en cours de traitement.');
        }

        $action=$request->input('action');

        if ($action === 'valider') {
            $dossierController->changerStatut($dossier,'dossier_complet');

            return redirect()->route('etudiant.dossiers.show', $dossier->dossier_id)
                            ->with('success', 'Dossier final validé avec succès !');
        } elseif ($action === 'corriger') {
                $motif = $request->input('motif_rejet');
            $dossierController->changerStatut($dossier, 'traitement_dossier', $motif);
            return redirect()->route('etudiant.dossiers.show', $dossier->dossier_id)
                            ->with('success', 'Demande de correction envoyée au conseiller.');
        }

        return redirect()->back()->with('error', 'Action non reconnue.');
    }


    public function formulaireVerificationDocumentsConseiller($dossierId)
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès non autorisé');
        }

        $etudiant = Auth::user()->etudiant;
        $dossier = $etudiant->dossiers()->with(['documents.verifiePar', 'assigne'])->findOrFail($dossierId);

        $dossierController = new DossierController();
        if (!$dossierController->attenteValidationFinale($dossier) && !$dossierController->paiementAccepte($dossier)) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vérifier les documents pour ce dossier.');
        }

        $documentsConseiller = $dossier->documents()
            ->where('verifie_par', '!=', $etudiant->user_id)
            ->whereNotNull('verifie_par')
            ->get();

        if ($documentsConseiller->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun document du conseiller à vérifier.');
        }

        return view('etudiant.dossiers.verifier-documents-conseiller',
                    compact('dossier', 'documentsConseiller'));
    }

    public function verifierDocumentsConseiller(Request $request, $dossierId)
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'action' => 'required|in:valider,corriger',
            'motif_rejet' => 'nullable|string|max:500',
        ]);

        $etudiant = Auth::user()->etudiant;
        $dossier = $etudiant->dossiers()->findOrFail($dossierId);

        $dossierController = new DossierController();
        if (!$dossierController->attenteValidationFinale($dossier) && !$dossierController->paiementAccepte($dossier)) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vérifier les documents pour ce dossier.');
        }

        if ($request->action === 'valider') {
            $dossierController->changerStatut($dossier, 'dossier_complet');

            return redirect()->route('etudiant.dossiers.show', $dossier->dossier_id)
                            ->with('success', 'Documents du conseiller validés avec succès ! Votre dossier est maintenant complet.Il reste a soumettre a campus France.');

        } else {
            $motifRejet = $request->motif_rejet ?? 'Documents à corriger selon l\'étudiant';

            $dossierController->changerStatut($dossier, 'traitement_dossier', $motifRejet);

            return redirect()->route('etudiant.dossiers.show', $dossier->dossier_id)
                            ->with('success', 'Demande de correction envoyée au conseiller. Il va revoir vos documents.');
        }
    }


    /**Email suivi (simulation messagerie) */

    public function confirmerReceptionEmail(Request $request, $dossierId)
    {
        if (Auth::user()->role !== 'etudiant') {
            abort(403, 'Accès non autorisé');
        }

        $etudiant = Auth::user()->etudiant;
        $dossier = $etudiant->dossiers()->findOrFail($dossierId);

        if ($dossier->statut !== 'soumis_campus_france') {
            return redirect()->back()->with('error', 'Vous ne pouvez pas confirmer la réception pour ce dossier.');
        }

        $dossier->update([
            'statut' => 'termine',
            'termine_a' => now(),
        ]);

        return redirect()->back()->with('success', 'Réception confirmée ! Votre dossier est maintenant terminé.Nous vous souhaitons une bonne chance et n\'ésitez pas à continuez avec nous après votre admission merci beaucoup pour la confiance.');
    }



}
