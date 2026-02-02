<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    protected $table = 'intervention'; 
    protected $primaryKey = 'ID_Intervention';

    public $timestamps = false; // pas de created_at / updated_at

    protected $fillable = [
        'ID_Demande',
        'Date_Debut',
        'Heure_Debut',
        'Date_Fin',
        'Heure_Fin',
        'Duree_Reelle',
        'Type_Intervenant',
        'ID_Intervenant',
        'Cout_Main_Oeuvre',
        'Cout_Pieces',
        'Cout_Total',
        'Resultat',
        'Rapport_Technique',
        'ID_Equipement_Controle',
        'Statut_Conformite',
        'Signature_Client'
    ];

    /**
     * Relation avec l’équipement
     */
    public function equipement()
    {
        return $this->belongsTo(
            Equipement::class,
            'ID_Equipement_Controle', // clé étrangère
            'id' // clé primaire equipements
        );
    }
}
