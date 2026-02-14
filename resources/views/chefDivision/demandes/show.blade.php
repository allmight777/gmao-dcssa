@extends('layouts.welcome')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">

            <!-- En-tête moderne -->
            <div class="header-card-show mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('chef-division.demandes.index') }}" class="btn-back-show me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h3 class="mb-1 fw-bold text-white">
                                <i class="fas fa-file-medical me-2"></i>
                                Demande #{{ $demande->Numero_Demande }}
                            </h3>
                            <p class="mb-0 text-white-50">
                                <i class="fas fa-building me-2"></i>{{ $service->nom }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <span class="badge-show badge-{{ $demande->badge_etat }}">
                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                            {{ $demande->etat_formate }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            @if($demande->isEnAttente())
            <div class="card-modern mb-4">
                <div class="card-body-show">
                    <h6 class="section-title-small mb-3">
                        <i class="fas fa-cogs me-2 text-primary"></i>
                        Actions disponibles
                    </h6>
                    <div class="action-buttons">
                        <button type="button" class="btn-action-show btn-success-show"
                                data-bs-toggle="modal"
                                data-bs-target="#validerModal">
                            <i class="fas fa-check-circle me-2"></i>
                            <span>Valider</span>
                        </button>
                        <button type="button" class="btn-action-show btn-danger-show"
                                data-bs-toggle="modal"
                                data-bs-target="#rejeterModal">
                            <i class="fas fa-times-circle me-2"></i>
                            <span>Rejeter</span>
                        </button>
                        <button type="button" class="btn-action-show btn-warning-show"
                                data-bs-toggle="modal"
                                data-bs-target="#attenteModal">
                            <i class="fas fa-clock me-2"></i>
                            <span>Mettre en attente</span>
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Timeline info -->
            <div class="card-modern mb-4">
                <div class="card-header-show">
                    <i class="fas fa-info-circle me-2"></i>
                    Informations générales
                </div>
                <div class="card-body-show">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="info-box-show">
                                <div class="info-icon bg-primary">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <label class="info-label">Date de création</label>
                                    <p class="info-value">{{ $demande->Date_Demande->format('d/m/Y') }}</p>
                                    <small class="text-muted">{{ $demande->Heure_Demande }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box-show">
                                <div class="info-icon bg-{{ $demande->badge_urgence }}">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <label class="info-label">Urgence</label>
                                    <p class="info-value">
                                        <span class="badge-pill bg-{{ $demande->badge_urgence }}">
                                            {{ $demande->urgence_formate }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box-show">
                                <div class="info-icon bg-info">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div>
                                    <label class="info-label">Type d'intervention</label>
                                    <p class="info-value">{{ $demande->getTypeInterventionFormateAttribute() }}</p>
                                </div>
                            </div>
                        </div>

                        @if($demande->Delai_Souhaite)
                        <div class="col-md-6">
                            <div class="info-box-show">
                                <div class="info-icon bg-warning">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                                <div>
                                    <label class="info-label">Délai souhaité</label>
                                    <p class="info-value">{{ $demande->Delai_Souhaite }} heures</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-6">
                            <div class="info-box-show">
                                <div class="info-icon bg-purple">
                                    <i class="fas fa-flag"></i>
                                </div>
                                <div>
                                    <label class="info-label">Priorité</label>
                                    <p class="info-value">
                                        @if($demande->Priorite == 1)
                                            <span class="badge-pill bg-danger">Haute</span>
                                        @elseif($demande->Priorite == 2)
                                            <span class="badge-pill bg-warning">Moyenne</span>
                                        @else
                                            <span class="badge-pill bg-success">Basse</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations du demandeur -->
            <div class="card-modern mb-4">
                <div class="card-header-show">
                    <i class="fas fa-user me-2"></i>
                    Demandeur
                </div>
                <div class="card-body-show">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="info-box-show">
                                <div class="info-icon bg-success">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div>
                                    <label class="info-label">Nom complet</label>
                                   <p class="info-value">
    {{ $demande->demandeur->nom }} {{ $demande->demandeur->prenom }}
</p>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box-show">
                                <div class="info-icon bg-info">
                                    <i class="fas fa-id-badge"></i>
                                </div>
                                <div>
                                    <label class="info-label">Matricule</label>
                                    <p class="info-value">{{ $demande->demandeur->matricule }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box-show">
                                <div class="info-icon bg-primary">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div>
                                    <label class="info-label">Service</label>
                                   <p class="info-value">
    {{ optional($demande->demandeur->service)->nom ?? 'Non spécifié' }}
</p>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Équipement concerné -->
            <div class="card-modern mb-4">
                <div class="card-header-show">
                    <i class="fas fa-microscope me-2"></i>
                    Équipement concerné
                </div>
                <div class="card-body-show">
                    @if($demande->equipement)
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="info-box-show">
                                <div class="info-icon bg-primary">
                                    <i class="fas fa-barcode"></i>
                                </div>
                                <div>
                                    <label class="info-label">N° Inventaire</label>
                                    <p class="info-value">{{ $demande->equipement->numero_inventaire }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box-show">
                                <div class="info-icon bg-info">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div>
                                    <label class="info-label">Marque/Modèle</label>
                                    <p class="info-value">{{ $demande->equipement->marque }} {{ $demande->equipement->modele }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box-show">
                                <div class="info-icon bg-success">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <label class="info-label">Localisation</label>
                                    <p class="info-value">{{ $demande->equipement->localisation->nom ?? 'Non spécifié' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box-show">
                                <div class="info-icon bg-{{ $demande->equipement->etat == 'bon' ? 'success' : ($demande->equipement->etat == 'moyen' ? 'warning' : 'danger') }}">
                                    <i class="fas fa-heartbeat"></i>
                                </div>
                                <div>
                                    <label class="info-label">État</label>
                                    <p class="info-value">
                                        <span class="badge-pill bg-{{ $demande->equipement->etat == 'bon' ? 'success' : ($demande->equipement->etat == 'moyen' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($demande->equipement->etat) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert-custom alert-warning-custom">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <p class="mb-0">Information de l'équipement non disponible</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Description du problème -->
            <div class="card-modern mb-4">
                <div class="card-header-show">
                    <i class="fas fa-file-alt me-2"></i>
                    Description du problème
                </div>
                <div class="card-body-show">
                    <div class="content-box-show">
                        <div class="content-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <div class="content-text">
                            {!! nl2br(e($demande->Description_Panne)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commentaires supplémentaires -->
            @if($demande->Commentaires)
            <div class="card-modern mb-4">
                <div class="card-header-show">
                    <i class="fas fa-comment me-2"></i>
                    Commentaires supplémentaires
                </div>
                <div class="card-body-show">
                    <div class="content-box-show">
                        <div class="content-icon">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                        <div class="content-text">
                            {!! nl2br(e($demande->Commentaires)) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Historique de validation -->
            @if($demande->Date_Validation)
            <div class="card-modern">
                <div class="card-header-show">
                    <i class="fas fa-history me-2"></i>
                    Historique de validation
                </div>
                <div class="card-body-show">
                    <div class="timeline-validation">
                        <div class="timeline-item">
                            <div class="timeline-icon bg-primary">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="timeline-content">
                                <label class="info-label">Date de validation</label>
                                <p class="info-value">{{ $demande->Date_Validation->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-icon bg-success">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="timeline-content">
                                <label class="info-label">Validateur</label>
                                <p class="info-value">{{ $demande->validateur->nom_complet ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($demande->Commentaire_Validation)
                    <div class="mt-4">
                        <label class="info-label mb-2">Commentaire de validation</label>
                        <div class="content-box-show">
                            <div class="content-icon">
                                <i class="fas fa-pen"></i>
                            </div>
                            <div class="content-text">
                                {!! nl2br(e($demande->Commentaire_Validation)) !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<!-- Modales -->
@if($demande->isEnAttente())
    @include('chefDivision.demandes.modals.valider', ['demande' => $demande])
    @include('chefDivision.demandes.modals.rejeter', ['demande' => $demande])
    @include('chefDivision.demandes.modals.attente', ['demande' => $demande])
@endif

<style>
:root {
    --primary: #4e73df;
    --success: #1cc88a;
    --danger: #e74a3b;
    --warning: #f6c23e;
    --info: #36b9cc;
    --purple: #6f42c1;
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Header Card */
.header-card-show {
    background: var(--gradient-primary);
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    animation: slideInDown 0.6s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.btn-back-show {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 1.1rem;
}

.btn-back-show:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateX(-5px);
    color: white;
}

.badge-show {
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.95rem;
    background: rgba(255, 255, 255, 0.3);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.5);
}

/* Cards modernes */
.card-modern {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    border: none;
    overflow: hidden;
    animation: fadeInUp 0.6s ease-out backwards;
}

.card-modern:nth-child(2) { animation-delay: 0.1s; }
.card-modern:nth-child(3) { animation-delay: 0.2s; }
.card-modern:nth-child(4) { animation-delay: 0.3s; }
.card-modern:nth-child(5) { animation-delay: 0.4s; }
.card-modern:nth-child(6) { animation-delay: 0.5s; }

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-header-show {
    background: linear-gradient(135deg, #f6f8fb 0%, #e9ecf5 100%);
    padding: 20px 25px;
    border-bottom: 2px solid #e2e8f0;
    font-weight: 700;
    color: #2d3748;
    font-size: 1.1rem;
}

.card-body-show {
    padding: 30px;
}

/* Section title */
.section-title-small {
    font-weight: 700;
    color: #2d3748;
    font-size: 1rem;
}

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn-action-show {
    padding: 14px 28px;
    border-radius: 12px;
    border: none;
    font-weight: 600;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.btn-action-show:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.2);
}

.btn-success-show {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
}

.btn-danger-show {
    background: linear-gradient(135deg, #e74a3b 0%, #d32f2f 100%);
    color: white;
}

.btn-warning-show {
    background: linear-gradient(135deg, #f6c23e 0%, #f0b429 100%);
    color: white;
}

/* Info boxes */
.info-box-show {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fc 0%, #f0f2f7 100%);
    border-radius: 12px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.info-box-show:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    border-color: #e2e8f0;
}

.info-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.info-label {
    font-size: 0.85rem;
    color: #718096;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.info-value {
    font-size: 1.05rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

/* Badge pill */
.badge-pill {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

/* Content box */
.content-box-show {
    background: linear-gradient(135deg, #f8f9fc 0%, #f0f2f7 100%);
    border-left: 4px solid var(--primary);
    padding: 25px;
    border-radius: 12px;
    display: flex;
    gap: 20px;
    line-height: 1.8;
}

.content-icon {
    font-size: 2rem;
    color: var(--primary);
    opacity: 0.3;
}

.content-text {
    flex: 1;
    color: #2d3748;
    font-size: 1rem;
}

/* Alert custom */
.alert-custom {
    padding: 25px;
    border-radius: 12px;
    text-align: center;
}

.alert-warning-custom {
    background: linear-gradient(135deg, #fff5e6 0%, #ffe9cc 100%);
    border: 2px solid var(--warning);
    color: #7c5d0c;
}

/* Timeline validation */
.timeline-validation {
    display: flex;
    gap: 30px;
    margin-bottom: 20px;
}

.timeline-item {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fc 0%, #f0f2f7 100%);
    border-radius: 12px;
}

.timeline-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.timeline-content {
    flex: 1;
}

/* Background colors */
.bg-success { background: #1cc88a !important; }
.bg-warning { background: #f6c23e !important; }
.bg-danger { background: #e74a3b !important; }
.bg-info { background: #36b9cc !important; }
.bg-primary { background: #4e73df !important; }
.bg-purple { background: #6f42c1 !important; }

/* Responsive */
@media (max-width: 768px) {
    .header-card-show {
        padding: 20px;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-action-show {
        width: 100%;
        justify-content: center;
    }

    .timeline-validation {
        flex-direction: column;
    }

    .card-body-show {
        padding: 20px;
    }

    .content-box-show {
        flex-direction: column;
        text-align: center;
    }
}
</style>
@endsection
