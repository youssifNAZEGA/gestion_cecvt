<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $primaryKey = 'document_id';

    protected $fillable = [
        'dossier_id',
        'type_document',
        'nom_original',
        'chemin_fichier',
        'statut',
        'commentaires',
        'verifie_par',
        'verifie_a',
    ];

    protected $casts = [
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

}
