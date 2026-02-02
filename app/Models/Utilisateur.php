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
     * Accesseur pour le nom du profil
     */
    public function getNomProfilAttribute()
    {
        return $this->profil->nom_profil ?? 'Non défini';
    }

    /**
     * Accesseur pour l'affichage du profil formaté
     */
    public function getProfilFormateAttribute()
    {
        $nomProfil = $this->nom_profil;
        return ucfirst(str_replace('_', ' ', $nomProfil));
    }

    /**
     * Accesseur pour les classes CSS du badge de profil
     */
    public function getBadgeProfilAttribute()
    {
        $nomProfil = strtolower($this->nom_profil);

        $classes = [
            'admin' => 'badge-admin',
            'administrateur' => 'badge-admin',
            'gestionnaire_inventaire' => 'badge-gestionnaire',
            'gestionnaire' => 'badge-gestionnaire',
            'magasinier' => 'badge-technicien',
            'technicien' => 'badge-technicien',
            'utilisateur' => 'badge-secondary',
            'default' => 'badge-secondary'
        ];

        $key = $classes[$nomProfil] ?? $classes['default'];

        // Retourne les classes sans le préfixe 'badge-'
        return str_replace('badge-', '', $key);
    }

    /**
     * Accesseur pour les classes CSS du badge de statut
     */
    public function getBadgeStatutAttribute()
    {
        $classes = [
            'actif' => 'success',
            'inactif' => 'secondary',
            'suspendu' => 'danger',
            'bloque' => 'warning',
            'default' => 'secondary'
        ];

        return $classes[strtolower($this->statut)] ?? $classes['default'];
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
     */
    public function isAdmin(): bool
    {
        return optional($this->profil)->nom_profil && strtolower($this->profil->nom_profil) === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est gestionnaire d'inventaire
     */
    public function isGestionnaireInventaire(): bool
    {
        return optional($this->profil)->nom_profil && strtolower($this->profil->nom_profil) === 'gestionnaire_inventaire';
    }

    /**
     * Vérifie si l'utilisateur est magasinier
     */
    public function isMagasinier(): bool
    {
        return optional($this->profil)->nom_profil && strtolower($this->profil->nom_profil) === 'magasinier';
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

    // Dans App\Models\Utilisateur.php, ajoutez :

/**
 * Vérifie si l'utilisateur est chef de division
 */
public function isChefDivision(): bool
{
    // Vérifier si l'utilisateur est responsable d'une localisation
    return Localisation::where('responsable_id', $this->id)->exists();
}

/**
 * Récupère les localisations dont l'utilisateur est responsable
 */
public function localisationsResponsable()
{
    return $this->hasMany(Localisation::class, 'responsable_id');
}

/**
 * Récupère le service principal dont l'utilisateur est responsable
 */
public function serviceResponsable()
{
    return $this->hasOne(Localisation::class, 'responsable_id');
}

/**
 * Vérifie si l'utilisateur peut voir toutes les demandes du service
 */
public function canViewServiceDemandes(): bool
{
    return $this->isChefDivision() || $this->isAdmin() || $this->isGestionnaireInventaire();
}
}
