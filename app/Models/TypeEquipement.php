<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeEquipement extends Model
{
    use HasFactory;

    protected $table = 'type_equipements';

    protected $fillable = [
        'code_type',
        'libelle',
        'classe',
        'duree_vie_standard',
        'periodicite_maintenance',
        'risque',
        'description'
    ];

    // Relations
    public function equipements()
    {
        return $this->hasMany(Equipement::class, 'type_equipement', 'code_type');
    }
}