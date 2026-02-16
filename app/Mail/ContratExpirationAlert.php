<?php
// app/Mail/ContratExpirationAlert.php

namespace App\Mail;

use App\Models\ContratMaintenance;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class ContratExpirationAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $contrat;
    public $destinataire;
    public $joursRestants;

    /**
     * Create a new message instance.
     */
    public function __construct(ContratMaintenance $contrat, ?User $destinataire = null)
    {
        $this->contrat = $contrat;
        $this->destinataire = $destinataire;
        $this->joursRestants = Carbon::now()->diffInDays(Carbon::parse($contrat->Date_Fin), false);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $sujet = "Alerte: Contrat de maintenance {$this->contrat->Numero_Contrat} expire dans {$this->joursRestants} jours";

        return new Envelope(
            subject: $sujet,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contrat-expiration',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
