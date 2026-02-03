<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoriqueMouvement extends Model
{
    use HasFactory;

    protected $table = 'historique_mouvements';

    protected $fillable = [
        'equipement_id',
        'date_mouvement',
        'ancienne_localisation_id',
        'nouvelle_localisation_id',
        'motif',
        'operateur_id',
        'commentaire'
    ];

    protected $casts = [
        'date_mouvement' => 'datetime'
    ];

    // Relations
    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }

    public function ancienneLocalisation()
    {
        return $this->belongsTo(Localisation::class, 'ancienne_localisation_id');
    }

    public function nouvelleLocalisation()
    {
        return $this->belongsTo(Localisation::class, 'nouvelle_localisation_id');
    }


    public function operateur()
{
    return $this->belongsTo(Utilisateur::class, 'operateur_id');
}
}
