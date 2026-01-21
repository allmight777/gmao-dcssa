<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';
    
    protected $fillable = [
        'module',
        'action',
        'profil_id',
    ];

    // Relation avec le profil
    public function profil()
    {
        return $this->belongsTo(Profil::class, 'profil_id');
    }

    // Accesseur pour le nom complet de la permission
    public function getNomCompletAttribute()
    {
        return ucfirst($this->module) . ' - ' . ucfirst($this->action);
    }

    // Scopes pour les filtres
    public function scopeModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }
}