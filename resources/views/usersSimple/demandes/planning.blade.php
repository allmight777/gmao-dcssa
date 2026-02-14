@extends('layouts.welcome')

@section('title', 'Planning de l\'intervention')

@section('content')
<style>
.timeline {
    position: relative;
    padding: 30px 0 20px 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 17px;
    top: 40px;
    bottom: 20px;
    width: 3px;
    background: linear-gradient(to bottom, #4e73df 0%, #1cc88a 100%);
    border-radius: 2px;
    opacity: 0.3;
}

.timeline-item {
    position: relative;
    padding-left: 65px;
    margin-bottom: 35px;
    animation: slideInLeft 0.5s ease-out;
    opacity: 0;
    animation-fill-mode: forwards;
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.timeline-item:nth-child(1) { animation-delay: 0.1s; }
.timeline-item:nth-child(2) { animation-delay: 0.2s; }
.timeline-item:nth-child(3) { animation-delay: 0.3s; }
.timeline-item:nth-child(4) { animation-delay: 0.4s; }

.timeline-icon {
    position: absolute;
    left: 0;
    top: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 2;
    transition: all 0.3s ease;
    border: 3px solid #fff;
}

.timeline-icon:hover {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}

.timeline-icon i {
    font-size: 16px;
}

.timeline-content {
    background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
    padding: 18px 20px;
    border-radius: 12px;
    border-left: 4px solid #e3e6f0;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.timeline-content:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-left-color: #4e73df;
}

.timeline-item:nth-child(1) .timeline-content { border-left-color: #36b9cc; }
.timeline-item:nth-child(2) .timeline-content { border-left-color: #1cc88a; }
.timeline-item:nth-child(3) .timeline-content { border-left-color: #4e73df; }
.timeline-item:nth-child(4) .timeline-content { border-left-color: #1cc88a; }

.timeline-date {
    font-size: 0.75rem;
    color: #858796;
    font-weight: 600;
    display: inline-block;
    background: #e7f3ff;
    padding: 4px 12px;
    border-radius: 20px;
    margin-bottom: 10px;
    letter-spacing: 0.3px;
}

.timeline-content h6 {
    font-weight: 700;
    color: #2e3338;
    margin: 8px 0 6px 0;
    font-size: 1rem;
}

.timeline-content p {
    margin: 0;
    font-size: 0.85rem;
    line-height: 1.5;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

/* Variantes de couleur pour les dates */
.timeline-item:nth-child(1) .timeline-date { background: #d1ecf1; color: #0c5460; }
.timeline-item:nth-child(2) .timeline-date { background: #d4edda; color: #155724; }
.timeline-item:nth-child(3) .timeline-date { background: #e7f3ff; color: #004085; }
.timeline-item:nth-child(4) .timeline-date { background: #d4edda; color: #155724; }

/* Responsive */
@media (max-width: 768px) {
    .timeline-item {
        padding-left: 55px;
    }

    .timeline-icon {
        width: 35px;
        height: 35px;
    }

    .timeline-icon i {
        font-size: 14px;
    }

    .timeline::before {
        left: 16px;
    }
}
</style>

<br><br>
<div class="container-fluid" style="width: 80%;">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-alt mr-2"></i>Planning de l'intervention
        </h1>
        <div>
            <a href="{{ route('user.demandes.show', $demande->ID_Demande) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la demande
            </a>
            <a href="{{ route('user.demandes.calendrier') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-calendar-week"></i> Voir le calendrier
            </a>
        </div>
    </div>

    <!-- En-tête de la demande -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Demande #{{ $demande->Numero_Demande }}
            </h6>
            <span class="badge badge-{{ $demande->badge_etat }} p-2">
                {{ $demande->etat_formate }}
            </span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Équipement:</strong> {{ $demande->equipement->nom }}</p>
                    <p><strong>Code barre:</strong> {{ $demande->equipement->code_barres }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Type d'intervention:</strong> {{ $demande->type_intervention_formate }}</p>
                    <p><strong>Urgence:</strong>
                        <span class="badge badge-{{ $demande->badge_urgence }}">
                            {{ $demande->urgence_formate }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if($intervention)
        <!-- Planning de l'intervention -->
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-clock mr-2"></i>Détails de la planification
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">Statut de l'intervention</th>
                                    <td>
                                        @if($intervention->Date_Fin)
                                            <span class="badge badge-success p-2">Terminée</span>
                                        @elseif($intervention->Date_Debut)
                                            <span class="badge badge-warning p-2">En cours</span>
                                        @else
                                            <span class="badge badge-info p-2">Planifiée</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date de début prévue</th>
                                    <td>
                                        @if($intervention->Date_Debut)
                                            {{ \Carbon\Carbon::parse($intervention->Date_Debut)->format('d/m/Y') }}
                                            à {{ $intervention->Heure_Debut }}
                                        @else
                                            <span class="text-muted">Non planifiée</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($intervention->Date_Fin)
                                <tr>
                                    <th>Date de fin</th>
                                    <td>
                                        {{ \Carbon\Carbon::parse($intervention->Date_Fin)->format('d/m/Y') }}
                                        à {{ $intervention->Heure_Fin }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Durée totale</th>
                                    <td>{{ $intervention->Duree_Reelle ?? 'N/A' }} heures</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Technicien assigné</th>
                                    <td>
                                        @if($intervention->intervenant)
                                            {{ $intervention->intervenant->nom }} {{ $intervention->intervenant->prenom }}
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($intervention->Rapport_Technique)
                                <tr>
                                    <th>Rapport technique</th>
                                    <td>{{ nl2br(e($intervention->Rapport_Technique)) }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        @if(!$intervention->Date_Fin && $intervention->Date_Debut)
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            L'intervention est actuellement en cours. Le technicien saisira le rapport à la fin.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Timeline -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-stream mr-2"></i>Timeline
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <!-- Création -->
                            <div class="timeline-item">
                                <div class="timeline-icon bg-info">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <div class="timeline-content">
                                    <span class="timeline-date">{{ $demande->created_at->format('d/m/Y H:i') }}</span>
                                    <h6>Demande créée</h6>
                                    <p class="text-muted small">Votre demande a été soumise</p>
                                </div>
                            </div>

                            <!-- Validation -->
                            @if($demande->Date_Validation)
                            <div class="timeline-item">
                                <div class="timeline-icon bg-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="timeline-content">
                                    <span class="timeline-date">{{ \Carbon\Carbon::parse($demande->Date_Validation)->format('d/m/Y H:i') }}</span>
                                    <h6>Demande validée</h6>
                                    <p class="text-muted small">Validée par {{ $demande->validateur->nom ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Planification -->
                            @if($intervention->Date_Debut)
                            <div class="timeline-item">
                                <div class="timeline-icon bg-primary">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <span class="timeline-date">{{ \Carbon\Carbon::parse($intervention->Date_Debut)->format('d/m/Y') }}</span>
                                    <h6>Intervention planifiée</h6>
                                    <p class="text-muted small">Début prévu à {{ $intervention->Heure_Debut }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Fin -->
                            @if($intervention->Date_Fin)
                            <div class="timeline-item">
                                <div class="timeline-icon bg-success">
                                    <i class="fas fa-flag-checkered"></i>
                                </div>
                                <div class="timeline-content">
                                    <span class="timeline-date">{{ \Carbon\Carbon::parse($intervention->Date_Fin)->format('d/m/Y H:i') }}</span>
                                    <h6>Intervention terminée</h6>
                                    <p class="text-muted small">Rapport disponible</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            Aucune intervention n'a encore été planifiée pour cette demande.
        </div>
    @endif
</div>
@endsection
