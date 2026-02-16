<?php
// app/Models/ContratMaintenance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ContratMaintenance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contrats_maintenance';
    protected $primaryKey = 'ID_Contrat';

    protected $fillable = [
        'Numero_Contrat',
        'Libelle',
        'Type',
        'Date_Debut',
        'Date_Fin',
        'Montant',
        'Devise',
        'Periodicite_Interventions',
        'Delai_Intervention_Garanti',
        'ID_Fournisseur',
        'Couverture_Pieces',
        'Couverture_Main_Oeuvre',
        'Exclusions',
        'Statut',
        'Date_Alerte_Renouvellement',
        'Alerte_envoyee',
        'Date_derniere_alerte',
        'chemin_document',
        'fichier_original',
        'Conditions_Particulieres',
        'Notes_Internes',
        'cree_par',
        'modifie_par'
    ];

    protected $casts = [
        'Date_Debut' => 'date',
        'Date_Fin' => 'date',
        'Date_Alerte_Renouvellement' => 'date',
        'Date_derniere_alerte' => 'datetime',
        'Montant' => 'decimal:2',
        'Couverture_Pieces' => 'boolean',
        'Couverture_Main_Oeuvre' => 'boolean',
        'Alerte_envoyee' => 'boolean'
    ];

    /**
     * Relations
     */
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'ID_Fournisseur');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function moderateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    public function equipements()
    {
        return $this->belongsToMany(Equipement::class, 'contrat_equipements', 'contrat_id', 'equipement_id')
                    ->withTimestamps();
    }

    /**
     * Scopes
     */
    public function scopeActif($query)
    {
        return $query->where('Statut', 'actif');
    }

    public function scopeExpirant($query, $jours = 30)
    {
        return $query->where('Statut', 'actif')
                     ->whereDate('Date_Fin', '<=', now()->addDays($jours))
                     ->whereDate('Date_Fin', '>', now());
    }

    public function scopeExpires($query)
    {
        return $query->where('Statut', 'actif')
                     ->whereDate('Date_Fin', '<', now());
    }

    public function scopeAvecAlerte($query)
    {
        return $query->where('Statut', 'actif')
                     ->where('Alerte_envoyee', false)
                     ->whereDate('Date_Alerte_Renouvellement', '<=', now());
    }

    /**
     * Accesseurs
     */
    public function getJoursRestantsAttribute()
    {
        if ($this->Statut !== 'actif') {
            return null;
        }
        return Carbon::now()->diffInDays(Carbon::parse($this->Date_Fin), false);
    }

    public function getStatutAvecCouleurAttribute()
    {
        $statut = $this->Statut;
        $jours = $this->jours_restants;

        if ($statut === 'actif') {
            if ($jours < 0) {
                return ['text' => 'Expiré', 'class' => 'badge bg-danger'];
            } elseif ($jours <= 7) {
                return ['text' => 'Expire bientôt', 'class' => 'badge bg-warning text-dark'];
            } elseif ($jours <= 30) {
                return ['text' => 'Actif (fin proche)', 'class' => 'badge bg-info'];
            } else {
                return ['text' => 'Actif', 'class' => 'badge bg-success'];
            }
        }

        $labels = [
            'expire' => ['text' => 'Expiré', 'class' => 'badge bg-secondary'],
            'resilie' => ['text' => 'Résilié', 'class' => 'badge bg-dark'],
            'renouvellement_attente' => ['text' => 'Renouvellement', 'class' => 'badge bg-warning text-dark'],
            'brouillon' => ['text' => 'Brouillon', 'class' => 'badge bg-light text-dark']
        ];

        return $labels[$statut] ?? ['text' => ucfirst($statut), 'class' => 'badge bg-secondary'];
    }

    public function getMontantFormateAttribute()
    {
        return number_format($this->Montant, 0, ',', ' ') . ' ' . $this->Devise;
    }

    /**
     * Méthodes utilitaires
     */
    public function estActif()
    {
        return $this->Statut === 'actif';
    }

    public function estExpirant($jours = 7)
    {
        return $this->estActif() &&
               $this->jours_restants !== null &&
               $this->jours_restants <= $jours;
    }

    public function peutEtreRenouvele()
    {
        return in_array($this->Statut, ['actif', 'expire']) ||
               ($this->Statut === 'expire' && $this->jours_restants < 0);
    }

    public static function genererNumeroContrat()
    {
        $annee = date('Y');
        $mois = date('m');
        $dernier = self::whereYear('created_at', $annee)
                       ->whereMonth('created_at', $mois)
                       ->count();

        return 'CTR-' . $annee . $mois . '-' . str_pad($dernier + 1, 4, '0', STR_PAD_LEFT);
    }
}
