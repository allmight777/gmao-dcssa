<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Utilisateur;

class DemandeIntervention extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'demandes_intervention';

    protected $primaryKey = 'ID_Demande';

    protected $fillable = [
        'Numero_Demande',
        'Date_Demande',
        'Heure_Demande',
        'ID_Demandeur',
        'ID_Equipement',
        'Type_Intervention',
        'Urgence',
        'Description_Panne',
        'Statut',
        'Date_Validation',
        'ID_Validateur',
        'Priorite',
        'Delai_Souhaite',
        'Commentaires',
    ];

    protected $casts = [
        'Date_Demande' => 'date',
        'Date_Validation' => 'datetime',
        'Delai_Souhaite' => 'integer',
        'Priorite' => 'integer',
    ];

    /**
     * Générer un numéro de demande unique
     */
    public static function generateNumeroDemande()
    {
        $date = now()->format('Ymd');
        $last = self::where('Numero_Demande', 'like', "DEM-{$date}-%")
            ->orderBy('Numero_Demande', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->Numero_Demande, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return "DEM-{$date}-{$nextNumber}";
    }

    /**
     * Relation avec le demandeur
     */
public function demandeur()
{
    return $this->belongsTo(Utilisateur::class, 'ID_Demandeur');
}

    /**
     * Relation avec l'équipement
     */
    public function equipement()
    {
        return $this->belongsTo(Equipement::class, 'ID_Equipement');
    }

    /**
     * Relation avec le validateur
     */
    public function validateur()
    {
        return $this->belongsTo(User::class, 'ID_Validateur');
    }

    /**
     * Calculer la priorité automatiquement
     */
    public function calculatePriority()
    {
        $priority = 3; // Par défaut basse

        if ($this->Urgence === 'critique') {
            $priority = 1;
        } elseif ($this->Urgence === 'urgente') {
            $priority = 2;
        }

        return $priority;
    }

    /**
     * Vérifier si la demande est en attente
     */
    public function isEnAttente()
    {
        return $this->Statut === 'en_attente';
    }

    /**
     * Vérifier si la demande est validée
     */
    public function isValidee()
    {
        return $this->Statut === 'validee';
    }

    /**
     * Vérifier si la demande est rejetée
     */
    public function isRejetee()
    {
        return $this->Statut === 'rejetee';
    }

    /**
     * Vérifier si la demande est en cours
     */
    public function isEnCours()
    {
        return $this->Statut === 'en_cours';
    }

    /**
     * Vérifier si la demande est terminée
     */
    public function isTerminee()
    {
        return $this->Statut === 'terminee';
    }

    /**
     * Vérifier si la demande est annulée
     */
    public function isAnnulee()
    {
        return $this->Statut === 'annulee';
    }

    /**
     * Obtenir l'état formaté
     */
    public function getEtatFormateAttribute()
    {
        $etats = [
            'en_attente' => 'En attente',
            'en_cours' => 'En cours',
            'validee' => 'Validée',
            'rejetee' => 'Rejetée',
            'terminee' => 'Terminée',
            'annulee' => 'Annulée',
        ];

        return $etats[$this->Statut] ?? $this->Statut;
    }

    /**
     * Obtenir la couleur du badge pour l'état
     */
    public function getBadgeEtatAttribute()
    {
        $badges = [
            'en_attente' => 'warning',
            'en_cours' => 'info',
            'validee' => 'success',
            'rejetee' => 'danger',
            'terminee' => 'primary',
            'annulee' => 'secondary',
        ];

        return $badges[$this->Statut] ?? 'secondary';
    }

    /**
     * Obtenir l'urgence formatée
     */
    public function getUrgenceFormateAttribute()
    {
        $urgences = [
            'normale' => 'Normale',
            'urgente' => 'Urgente',
            'critique' => 'Critique',
        ];

        return $urgences[$this->Urgence] ?? $this->Urgence;
    }

    /**
     * Obtenir la couleur du badge pour l'urgence
     */
    public function getBadgeUrgenceAttribute()
    {
        $badges = [
            'normale' => 'success',
            'urgente' => 'warning',
            'critique' => 'danger',
        ];

        return $badges[$this->Urgence] ?? 'secondary';
    }

    /**
     * Obtenir le type d'intervention formaté
     */
    public function getTypeInterventionFormateAttribute()
    {
        $types = [
            'maintenance_preventive' => 'Maintenance préventive',
            'maintenance_corrective' => 'Maintenance corrective',
            'reparation' => 'Réparation',
            'calibration' => 'Calibration',
            'verification' => 'Vérification',
            'controle' => 'Contrôle',
            'autre' => 'Autre',
        ];

        return $types[$this->Type_Intervention] ?? $this->Type_Intervention;
    }

    /**
     * Scope pour les demandes de l'utilisateur
     */
    public function scopeMesDemandes($query, $userId)
    {
        return $query->where('ID_Demandeur', $userId);
    }

    /**
     * Scope pour les demandes en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('Statut', 'en_attente');
    }

    /**
     * Scope pour les demandes validées
     */
    public function scopeValidees($query)
    {
        return $query->where('Statut', 'validee');
    }

    /**
     * Scope pour les demandes rejetées
     */
    public function scopeRejetees($query)
    {
        return $query->where('Statut', 'rejetee');
    }

    /**
     * Scope pour les demandes en cours
     */
    public function scopeEnCours($query)
    {
        return $query->where('Statut', 'en_cours');
    }

    /**
     * Scope pour les demandes terminées
     */
    public function scopeTerminees($query)
    {
        return $query->where('Statut', 'terminee');
    }

    public function interventions()
    {
        return $this->hasMany(
            Intervention::class,
            'ID_Demande',      // clé étrangère dans intervention
            'ID_Demande'       // clé primaire locale
        );
    }
}
