<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Utilisateur extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    // Table associée
    protected $table = 'users';
    
    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'grade',
        'fonction',
        'service_id',
        'email',
        'telephone',
        'login',
        'password',
        'profil_id',
        'statut',
        'date_derniere_connexion',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_derniere_connexion' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Mutateur pour le mot de passe : hash automatique
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Accesseur pour le nom complet
     */
    public function getNomCompletAttribute()
    {
        return trim($this->nom . ' ' . $this->prenom);
    }

    /**
     * Relation avec le profil
     */
    public function profil()
    {
        return $this->belongsTo(Profil::class, 'profil_id');
    }

    /**
     * Relation avec le service/localisation
     */
    public function service()
    {
        return $this->belongsTo(Localisation::class, 'service_id');
    }

    /**
     * Relation avec les logs d'activité
     */
    public function logs()
    {
        return $this->hasMany(LogActivite::class, 'id_utilisateur');
    }

    /**
     * Vérifie si l'utilisateur est actif
     */
    public function isActif(): bool
    {
        return $this->statut === 'actif';
    }

    /**
     * Vérifie si l'utilisateur est administrateur
     * (ajuster 'nom' selon le nom réel de la colonne dans profils)
     */
 public function isAdmin(): bool
{
    return optional($this->profil)->nom_profil && strtolower($this->profil->nom_profil) === 'admin';
}


    /**
     * Scope pour utilisateurs actifs
     */
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour recherche par nom, prénom, matricule ou email
     */
    public function scopeRecherche($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('nom', 'like', "%$term%")
              ->orWhere('prenom', 'like', "%$term%")
              ->orWhere('matricule', 'like', "%$term%")
              ->orWhere('email', 'like', "%$term%");
        });
    }
}
