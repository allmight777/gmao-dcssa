<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fournisseur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fournisseurs';

    protected $fillable = [
        'code_fournisseur',
        'raison_sociale',
        'adresse',
        'telephone',
        'email',
        'contact_principal',
        'type',
        'statut',
        'date_premiere_commande',
        'date_derniere_commande',
        'evaluation',
        'notes'
    ];

    protected $casts = [
        'date_premiere_commande' => 'date',
        'date_derniere_commande' => 'date'
    ];

    // Relations
    public function equipements()
    {
        return $this->hasMany(Equipement::class);
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }
}