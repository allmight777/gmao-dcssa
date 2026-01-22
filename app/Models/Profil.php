<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profil extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nom_profil',
        'description',
        'is_default',
    ];

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

    public function utilisateurs(): HasMany
    {
        return $this->hasMany(Utilisateur::class);
    }

    public static function getAvailableModules(): array
    {
        return [
            'administration' => 'Administration',
            'utilisateurs' => 'Gestion des utilisateurs',
            'profils' => 'Gestion des profils',
            'comptes' => 'Gestion des comptes',
            'inventaire' => 'Gestion d\'inventaire',
            'stock' => 'Gestion des stocks',
            'maintenance' => 'Maintenance',
            'contrats' => 'Contrats et garanties',
            'rapports' => 'Rapports et statistiques',
            'configuration' => 'Configuration système',
            'formations' => 'Formations et compétences',
        ];
    }

    public static function getAvailableActions(string $module): array
    {
        $actions = [
            'administration' => ['view', 'create', 'edit', 'delete', 'manage'],
            'utilisateurs' => ['view', 'create', 'edit', 'delete', 'export'],
            'profils' => ['view', 'create', 'edit', 'delete'],
            'comptes' => ['view', 'create', 'edit', 'delete', 'reset_password'],
            'inventaire' => ['view', 'create', 'edit', 'delete', 'transfer'],
            'stock' => ['view', 'create', 'edit', 'delete', 'order', 'receive'],
            'maintenance' => ['view', 'create', 'edit', 'delete', 'execute', 'validate'],
            'contrats' => ['view', 'create', 'edit', 'delete', 'renew', 'validate'],
            'rapports' => ['view', 'export', 'print'],
            'configuration' => ['view', 'edit'],
            'formations' => ['view', 'create', 'edit', 'delete', 'assign'],
        ];

        return $actions[$module] ?? [];
    }
}