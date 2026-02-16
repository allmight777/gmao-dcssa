<?php
// app/Console/Commands/SendContratAlerts.php

namespace App\Console\Commands;

use App\Models\ContratMaintenance;
use App\Mail\ContratExpirationAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendContratAlerts extends Command
{
    protected $signature = 'contrats:send-alerts';
    protected $description = 'Envoie les alertes pour les contrats proches de l\'expiration';

    public function handle()
    {
        $this->info('Début de l\'envoi des alertes...');

        $contrats = ContratMaintenance::with('fournisseur')
            ->where('Statut', 'actif')
            ->whereDate('Date_Fin', '<=', Carbon::now()->addDays(7))
            ->whereDate('Date_Fin', '>', Carbon::now())
            ->where(function ($q) {
                $q->where('Alerte_envoyee', false)
                  ->orWhere('Date_derniere_alerte', '<=', Carbon::now()->subDays(1));
            })
            ->get();

        $count = 0;
        foreach ($contrats as $contrat) {
            if ($contrat->fournisseur && $contrat->fournisseur->email) {
                Mail::to($contrat->fournisseur->email)->send(
                    new ContratExpirationAlert($contrat)
                );

                $contrat->update([
                    'Alerte_envoyee' => true,
                    'Date_derniere_alerte' => Carbon::now()
                ]);

                $count++;
                $this->info("Alerte envoyée pour le contrat: {$contrat->Numero_Contrat}");
            }
        }

        $this->info("{$count} alerte(s) envoyée(s) avec succès.");
    }
}
