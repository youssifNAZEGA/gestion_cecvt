<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DossierController extends Controller
{
    public const STATUTS_DOSSIER = [
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

    public function getStatutFr($statut)
    {
        return self::STATUTS_DOSSIER[$statut] ?? $statut;
    }

    public function estNouveau($dossier)
    {
        return $dossier->statut === 'nouveau';
    }

    public function enAttenteVerificationDocuments($dossier)
    {
        return $dossier->statut === 'attente_verification_documents';
    }

    public function documentsACorriger($dossier)
    {
        return $dossier->statut === 'documents_a_corriger';
    }

    public function documentsAcceptes($dossier)
    {
        return $dossier->statut === 'documents_acceptes';
    }

    public function attentePaiement($dossier)
    {
        return $dossier->statut === 'attente_paiement';
    }

    public function attenteVerificationPaiement($dossier)
    {
        return $dossier->statut === 'attente_verification_paiement';
    }

    public function paiementACorriger($dossier)
    {
        return $dossier->statut === 'paiement_a_corriger';
    }

    public function paiementAccepte($dossier)
    {
        return $dossier->statut === 'paiement_accepte';
    }

    public function traitementDossier($dossier)
    {
        return $dossier->statut === 'traitement_dossier';
    }

    public function attenteValidationFinale($dossier)
    {
        return $dossier->statut === 'attente_validation_finale';
    }

    public function dossierComplet($dossier)
    {
        return $dossier->statut === 'dossier_complet';
    }

    public function soumisCampusFrance($dossier)
    {
        return $dossier->statut === 'soumis_campus_france';
    }

    public function termine($dossier)
    {
        return $dossier->statut === 'termine';
    }


    public function peutPasserA($statutActuel, $nouveauStatut)
    {
        $transitionsAutorisees = [
            'nouveau' => ['attente_verification_documents'],
            'attente_verification_documents' => ['documents_a_corriger', 'documents_acceptes'],
            'documents_a_corriger' => ['attente_verification_documents','documents_a_corriger'],
            'documents_acceptes' => ['attente_paiement'],
            'attente_paiement' => ['attente_verification_paiement'],
            'attente_verification_paiement' => ['paiement_a_corriger', 'paiement_accepte'],
            'paiement_a_corriger' => ['attente_verification_paiement'],
            'paiement_accepte' => ['traitement_dossier'],
            'traitement_dossier' => ['attente_validation_finale'],
            'attente_validation_finale' => ['dossier_complet', 'traitement_dossier'],
            'dossier_complet' => ['soumis_campus_france'],
            'soumis_campus_france' => ['termine'],
            'termine' => [],
        ];

        return in_array($nouveauStatut, $transitionsAutorisees[$statutActuel] ?? []);
    }

    public function changerStatut($dossier, $nouveauStatut, $motifRejet = null)
    {
        if (!$this->peutPasserA($dossier->statut, $nouveauStatut)) {
            return false;
        }

        $dossier->update([
            'statut' => $nouveauStatut,
            'motif_rejet' => $motifRejet
        ]);

        return true;
    }
}

