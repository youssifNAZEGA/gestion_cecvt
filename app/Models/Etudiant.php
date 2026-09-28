<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Etudiant extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id',
        'date_naissance',
        'lieu_naissance',
        'numero_passeport',
        'universite_actuelle',
        'niveau_etude',
        'domaine_etude',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function dossiers():HasMany
    {
        return $this->hasMany(Dossier::class,'etudiant_id','user_id');
    }

}
