<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContratMaintenance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contrats_maintenance';

    protected $fillable = [
        'numero_contrat',
        'libelle',
        'type',
        'date_debut',
        'date_fin',
        'montant',
        'periodicite_interventions',
        'delai_intervention_garanti',
        'fournisseur_id',
        'couverture_pieces',
        'couverture_main_oeuvre',
        'statut',
        'date_alerte_renouvellement',
        'conditions_particulieres',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_alerte_renouvellement' => 'date',
        'couverture_pieces' => 'boolean',
        'couverture_main_oeuvre' => 'boolean',
        'montant' => 'decimal:2'
    ];

    // Relations
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function equipements()
    {
        return $this->hasMany(Equipement::class, 'contrat_id');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editeur()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeExpire($query)
    {
        return $query->where('statut', 'expire');
    }

    // Accessors
    public function getEstActifAttribute()
    {
        return $this->statut === 'actif' && 
               $this->date_debut <= now() && 
               $this->date_fin >= now();
    }

    public function getJoursRestantsAttribute()
    {
        return $this->date_fin->diffInDays(now());
    }

    // Méthodes
    public function verifierRenouvellement()
    {
        if ($this->date_alerte_renouvellement && now()->greaterThanOrEqualTo($this->date_alerte_renouvellement)) {
            return true;
        }
        
        // Alerte 60 jours avant expiration
        if ($this->date_fin->diffInDays(now()) <= 60) {
            return true;
        }
        
        return false;
    }
}