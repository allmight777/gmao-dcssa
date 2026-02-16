<?php
// app/Console/Commands/CheckContratsExpiration.php

namespace App\Console\Commands;

use App\Models\ContratMaintenance;
use App\Mail\ContratExpirationAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CheckContratsExpiration extends Command
{
    protected $signature = 'contrats:check-expiration';
    protected $description = 'Vérifie les contrats proches de l\'expiration et envoie des alertes';

    public function handle()
    {
        $this->info('Vérification des contrats en cours...');

        // Contrats expirant dans les 7 jours
        $contratsAlerte = ContratMaintenance::with('fournisseur')
            ->where('Statut', 'actif')
            ->whereDate('Date_Fin', '<=', Carbon::now()->addDays(7))
            ->whereDate('Date_Fin', '>', Carbon::now())
            ->where(function ($q) {
                $q->where('Alerte_envoyee', false)
                  ->orWhereNull('Date_derniere_alerte')
                  ->orWhere('Date_derniere_alerte', '<=', Carbon::now()->subDays(1));
            })
            ->get();

        $count = 0;
        foreach ($contratsAlerte as $contrat) {
            try {
                if ($contrat->fournisseur && $contrat->fournisseur->email) {
                    Mail::to($contrat->fournisseur->email)->send(
                        new ContratExpirationAlert($contrat)
                    );
                }

                $contrat->update([
                    'Alerte_envoyee' => true,
                    'Date_derniere_alerte' => Carbon::now()
                ]);

                $count++;
                $this->info("Alerte envoyée pour le contrat: {$contrat->Numero_Contrat}");
            } catch (\Exception $e) {
                $this->error("Erreur pour {$contrat->Numero_Contrat}: " . $e->getMessage());
            }
        }

        // Contrats expirés aujourd'hui
        $contratsExpires = ContratMaintenance::where('Statut', 'actif')
            ->whereDate('Date_Fin', '<', Carbon::now())
            ->update(['Statut' => 'expire']);

        $this->info("Traitement terminé. {$count} alerte(s) envoyée(s), {$contratsExpires} contrat(s) marqué(s) comme expiré(s).");
    }
}
