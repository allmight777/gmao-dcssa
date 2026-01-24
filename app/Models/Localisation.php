<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Localisation extends Model
{
    use SoftDeletes;

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

    /**
     * Localisation parente (hiérarchie)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Localisation::class, 'parent_id');
    }

    /**
     * Localisations enfants
     */
    public function children(): HasMany
    {
        return $this->hasMany(Localisation::class, 'parent_id');
    }

    /**
     * Responsable du service / localisation
     * CORRECTION : Utilisateur pointe vers la table 'users'
     */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Utilisateur::class, 'responsable_id');
    }

    /**
     * Utilisateurs affectés à ce service
     */
    public function utilisateurs(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\Utilisateur::class,
            'utilisateur_service',
            'service_id',
            'utilisateur_id'
        )
        ->withPivot('date_affectation', 'fonction_service')
        ->withTimestamps();
    }

    /**
     * Scope : sites
     */
    public function scopeSite($query)
    {
        return $query->where('type', 'site');
    }

    /**
     * Scope : bâtiments
     */
    public function scopeBatiment($query)
    {
        return $query->where('type', 'batiment');
    }

    /**
     * Scope : services
     */
    public function scopeService($query)
    {
        return $query->where('type', 'service');
    }

    /**
     * Scope : directions
     */
    public function scopeDirection($query)
    {
        return $query->where('type', 'direction');
    }

    /**
     * Hiérarchie complète (parent → enfant)
     */
    public function getHierarchieAttribute(): array
    {
        $hierarchie = [];
        $current = $this;

        while ($current) {
            $hierarchie[] = $current->nom;
            $current = $current->parent;
        }

        return array_reverse($hierarchie);
    }

    /**
     * Nom complet hiérarchique
     */
    public function getNomCompletAttribute(): string
    {
        return implode(' > ', $this->hierarchie);
    }
}