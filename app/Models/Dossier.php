<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dossier extends Model
{
    use HasFactory;

    protected $primaryKey = 'dossier_id';

    protected $fillable = [
        'etudiant_id',
        'assigne_a',
        'statut',
        'montant',
        'reference_paiement',
        'motif_rejet',
        'email_suivi_campus_france',
        'soumis_a_campus_france_a',
        'termine_a',
    ];


    protected $casts=[
        'statut'=>'string',
        'montant'=>'decimal:2',
        'soumis_a_campus_france_a'=>'datetime',
        'termine_a'=>'datetime',
    ];
    

    public function etudiant():BelongsTo
    {
        return $this->belongsTo(Etudiant::class,'etudiant_id','user_id');
    }

    public function assigne():BelongsTo
    {
        return $this->belongsTo(User::class, 'assigne_a','user_id');
    }

    public function documents():HasMany
    {
        return $this->hasMany(Document::class,'dossier_id','dossier_id');
    }

    public function paiements():HasMany
    {
        return $this->hasMany(Paiement::class,'dossier_id','dossier_id');
    }


}
