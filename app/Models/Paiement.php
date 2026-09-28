<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    use HasFactory;

    protected $primaryKey = 'paiement_id';

    protected $fillable = [
        'dossier_id',
        'montant',
        'methode_paiement',
        'chemin_recu',
        'statut',
        'motif_rejet',
        'verifie_par',
        'verifie_a',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'verifie_a' => 'datetime',
    ];


    public function dossier():BelongsTo
    {
        return $this->belongsTo(Dossier::class,'dossier_id');
    }

    public function verifiePar():BelongsTo
    {
        return $this->belongsTo(User::class, 'verifie_par');
    }




/*
    // Scopes
    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    public function scopeConfirmes($query)
    {
        return $query->where('statut', 'confirme');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAVerifier($query)
    {
        return $query->where('statut', 'a_verifier');
    }

    // Accesseurs
    public function getStatutFrAttribute()
    {
        $statuts = [
            'en_attente' => 'En attente',
            'a_verifier' => 'À vérifier',
            'confirme' => 'Confirmé',
            'rejete' => 'Rejeté'
        ];

        return $statuts[$this->statut] ?? $this->statut;
    }

    public function getMethodePaiementFrAttribute()
    {
        $methodes = [
            'virement_bancaire' => 'Virement bancaire',
            'especes' => 'Espèces',
            'carte_bancaire' => 'Carte bancaire'
        ];

        return $methodes[$this->methode_paiement] ?? $this->methode_paiement;
    }
*/
}
