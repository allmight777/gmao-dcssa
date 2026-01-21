<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Localisation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'localisations';
    
    protected $fillable = [
        'type',
        'nom',
        'parent_id',
        'code_geographique',
        'responsable_id',
        'adresse',
        'telephone',
        'description',
    ];

    // Relation avec le parent (hiérarchie)
    public function parent()
    {
        return $this->belongsTo(Localisation::class, 'parent_id');
    }

    // Relation avec les enfants
    public function enfants()
    {
        return $this->hasMany(Localisation::class, 'parent_id');
    }

    // Relation avec le responsable
    public function responsable()
    {
        return $this->belongsTo(Utilisateur::class, 'responsable_id');
    }

    // Relation avec les utilisateurs du service
    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class, 'service_id');
    }

    // Scope pour les services
    public function scopeService($query)
    {
        return $query->where('type', 'service');
    }

    // Scope pour les sites
    public function scopeSite($query)
    {
        return $query->where('type', 'site');
    }

    // Scope pour les bâtiments
    public function scopeBatiment($query)
    {
        return $query->where('type', 'batiment');
    }

    // Scope pour les salles
    public function scopeSalle($query)
    {
        return $query->where('type', 'salle');
    }
}