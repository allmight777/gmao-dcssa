<?php
// app/Models/Intervention.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    protected $table = 'intervention';
    protected $primaryKey = 'ID_Intervention';

    public $timestamps = false;

    protected $fillable = [
        'ID_Demande',
        'Date_Debut',
        'Heure_Debut',
        'Date_Fin',
        'Heure_Fin',
        'Duree_Reelle',
        'Type_Intervenant',
        'ID_Intervenant',
        'Cout_Main_Oeuvre',
        'Cout_Pieces',
        'Cout_Total',
        'Resultat',
        'Rapport_Technique',
        'ID_Equipement_Controle',
        'Statut_Conformite',
        'Signature_Client'
    ];

    /**
     * Relation avec la demande d'intervention
     */
    public function demande()
    {
        return $this->belongsTo(
            DemandeIntervention::class,
            'ID_Demande',      // clé étrangère dans intervention
            'ID_Demande'       // clé primaire dans demandes_intervention
        );
    }

    /**
     * Relation avec l'équipement contrôlé
     */
    public function equipement()
    {
        return $this->belongsTo(
            Equipement::class,
            'ID_Equipement_Controle',
            'id'
        );
    }

    /**
     * Relation avec l'intervenant (technicien)
     */
    public function intervenant()
    {
        return $this->belongsTo(
            User::class,
            'ID_Intervenant',
            'id'
        );
    }

    /**
     * Scope pour les interventions en cours
     */
    public function scopeEnCours($query)
    {
        return $query->whereNull('Date_Fin');
    }

    /**
     * Scope pour les interventions terminées
     */
    public function scopeTerminees($query)
    {
        return $query->whereNotNull('Date_Fin');
    }

    /**
     * Calculer la durée automatiquement
     */
    public function calculerDuree()
    {
        if ($this->Date_Debut && $this->Heure_Debut && $this->Date_Fin && $this->Heure_Fin) {
            $debut = \Carbon\Carbon::parse($this->Date_Debut . ' ' . $this->Heure_Debut);
            $fin = \Carbon\Carbon::parse($this->Date_Fin . ' ' . $this->Heure_Fin);
            return $debut->diffInHours($fin) + ($debut->diffInMinutes($fin) % 60) / 60;
        }
        return null;
    }

    /**
     * Obtenir le statut formaté
     */
    public function getStatutAttribute()
    {
        if ($this->Date_Fin) {
            return 'terminee';
        } elseif ($this->Date_Debut) {
            return 'en_cours';
        } else {
            return 'planifiee';
        }
    }

    /**
     * Obtenir le badge de statut
     */
    public function getBadgeStatutAttribute()
    {
        $badges = [
            'planifiee' => 'info',
            'en_cours' => 'warning',
            'terminee' => 'success'
        ];

        return $badges[$this->statut] ?? 'secondary';
    }
}
