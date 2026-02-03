<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Intervention;

class Equipement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipements';

    protected $fillable = [
        'numero_inventaire',
        'numero_serie',
        'marque',
        'modele',
        'type_equipement_id',
        'classe_equipement',
        'date_achat',
        'date_mise_service',
        'prix_achat',
        'duree_vie_theorique',
        'duree_garantie',
        'etat',
        'type_maintenance',
        'localisation_id',
        'service_responsable_id',
        'fournisseur_id',
        'contrat_id',
        'commentaires',
        'date_reforme',
        'code_barres',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'date_achat' => 'date',
        'date_mise_service' => 'date',
        'date_reforme' => 'date',
        'prix_achat' => 'decimal:2'
    ];

    // Relations
    public function localisation()
    {
        return $this->belongsTo(Localisation::class);
    }

    public function serviceResponsable()
    {
        return $this->belongsTo(Localisation::class, 'service_responsable_id');
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function contrat()
    {
        return $this->belongsTo(ContratMaintenance::class, 'contrat_id');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editeur()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function mouvements()
    {
        return $this->hasMany(HistoriqueMouvement::class);
    }

    public function typeEquipement()
    {
        return $this->belongsTo(TypeEquipement::class, 'type_equipement_id');
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('etat', '!=', 'hors_service')->whereNull('date_reforme');
    }

    public function scopeHorsService($query)
    {
        return $query->where('etat', 'hors_service')->orWhereNotNull('date_reforme');
    }

    public function scopeByLocalisation($query, $localisationId)
    {
        return $query->where('localisation_id', $localisationId);
    }

    // Accessors
    public function getAgeAttribute()
    {
        return $this->date_achat ? $this->date_achat->diffInYears(now()) : null;
    }

    public function getEstSousGarantieAttribute()
    {
        if (!$this->duree_garantie || !$this->date_achat) {
            return false;
        }

        $dateFinGarantie = $this->date_achat->copy()->addMonths($this->duree_garantie);
        return now()->lessThanOrEqualTo($dateFinGarantie);
    }

    // Générer un code-barres automatique
    public static function generateBarcode()
    {
        $prefix = 'EQP-';
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        return $prefix . $timestamp . $random;
    }


public function interventions()
{
    return $this->hasMany(
        Intervention::class,
        'ID_Equipement_Controle',
        'id'
    );
}

public function operateur()
{
    return $this->belongsTo(Utilisateur::class, 'operateur_id');
}

}
