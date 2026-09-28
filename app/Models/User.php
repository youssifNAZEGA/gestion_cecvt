<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
//use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'prenom',
        'sexe',
        'contact',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function etudiant()
    {
        return $this->hasOne(Etudiant::class,'user_id');
    }

    public function dossiersAssignes():HasMany
    {
        return $this->hasMany(Dossier::class, 'assigne_a');
    }

    public function documentsVerifies():HasMany
    {
        return $this->hasMany(Document::class, 'verifie_par');
    }

    public function paiementsVerifies():HasMany
    {
        return $this->hasMany(Paiement::class, 'verifie_par');
    }

}
