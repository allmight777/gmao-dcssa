<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivite extends Model
{
    use HasFactory;

    protected $table = 'log_activite';
    
    protected $fillable = [
        'id_utilisateur',
        'date_heure',
        'action',
        'module',
        'id_element',
        'adresse_ip',
        'details',
        'user_agent',
    ];

    protected $casts = [
        'date_heure' => 'datetime',
    ];

    // Relation avec l'utilisateur
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    // Scope pour les filtres
    public function scopeModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('date_heure', [$start, $end]);
    }

    public function scopeRecherche($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('action', 'like', "%$term%")
              ->orWhere('module', 'like', "%$term%")
              ->orWhere('details', 'like', "%$term%")
              ->orWhereHas('utilisateur', function($query) use ($term) {
                  $query->where('nom', 'like', "%$term%")
                        ->orWhere('prenom', 'like', "%$term%");
              });
        });
    }
}