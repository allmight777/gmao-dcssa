<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profil extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'profils';
    
    protected $fillable = [
        'nom_profil',
        'description',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Relation avec les utilisateurs
    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class, 'profil_id');
    }

    // Relation avec les permissions
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'profil_id');
    }

    // Vérifier si le profil a une permission spécifique
    public function hasPermission($module, $action)
    {
        return $this->permissions()
            ->where('module', $module)
            ->where('action', $action)
            ->exists();
    }

    // Ajouter une permission
    public function addPermission($module, $action)
    {
        return $this->permissions()->firstOrCreate([
            'module' => $module,
            'action' => $action,
        ]);
    }

    // Supprimer une permission
    public function removePermission($module, $action)
    {
        return $this->permissions()
            ->where('module', $module)
            ->where('action', $action)
            ->delete();
    }

    // Synchroniser les permissions
    public function syncPermissions(array $permissions)
    {
        $this->permissions()->delete();
        
        foreach ($permissions as $permission) {
            $this->permissions()->create([
                'module' => $permission['module'],
                'action' => $permission['action'],
            ]);
        }
    }

    // Liste des modules disponibles
    public static function getAvailableModules()
    {
        return [
            'administration' => 'Administration',
            'inventaire' => 'Gestion d\'inventaire',
            'stock' => 'Gestion des stocks',
            'maintenance' => 'Maintenance',
            'contrats' => 'Contrats et garanties',
            'rapports' => 'Rapports et statistiques',
            'configuration' => 'Configuration système',
            'formation' => 'Formations et compétences',
        ];
    }

    // Liste des actions disponibles par module
    public static function getAvailableActions($module)
    {
        $actions = [
            'administration' => ['view', 'create', 'edit', 'delete', 'manage'],
            'inventaire' => ['view', 'create', 'edit', 'delete', 'transfer', 'reform'],
            'stock' => ['view', 'create', 'edit', 'delete', 'order', 'receive'],
            'maintenance' => ['view', 'create', 'edit', 'delete', 'execute', 'validate'],
            'contrats' => ['view', 'create', 'edit', 'delete', 'renew', 'validate'],
            'rapports' => ['view', 'export', 'print'],
            'configuration' => ['view', 'edit'],
            'formation' => ['view', 'create', 'edit', 'delete', 'assign'],
        ];

        return $actions[$module] ?? [];
    }
}