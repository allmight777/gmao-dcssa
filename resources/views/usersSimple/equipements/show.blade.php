@extends('layouts.welcome')

@section('content')
<div class="container-fluid py-4">

    <!-- En-tête avec fond gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="header-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <a href="{{ route('user.equipements.index') }}" class="btn-back me-3">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <h4 class="mb-0 fw-bold text-white">{{ $equipement->marque }} {{ $equipement->modele }}</h4>
                        </div>
                        <p class="mb-0 text-white-50">
                            <i class="fas fa-hashtag me-2"></i>N° inventaire: {{ $equipement->numero_inventaire }}
                            @if($equipement->numero_serie)
                            <span class="ms-3"><i class="fas fa-barcode me-2"></i>S/N: {{ $equipement->numero_serie }}</span>
                            @endif
                        </p>
                    </div>
                   <span class="badge-custom bg-{{ $isAvailable['color'] }} text-white px-4 py-2 fs-6">
    <i class="fas fa-{{
        $isAvailable['color'] == 'success' ? 'check-circle' :
        ($isAvailable['color'] == 'warning' ? 'exclamation-triangle' : 'times-circle')
    }} me-2"></i>
    {{ $isAvailable['message'] }}
</span>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne gauche - Informations détaillées -->
        <div class="col-xl-8">

            <!-- Fiche technique -->
            <div class="card-modern mb-4">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-clipboard-list me-2 text-primary"></i>
                        Fiche Technique
                    </h6>
                </div>
                <div class="card-body-modern">
                    <div class="row g-4">
                        <!-- Première ligne -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-icon bg-primary">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div class="info-content">
                                    <p class="info-label">Type d'équipement</p>
                                    <h6 class="info-value">{{ $equipement->typeEquipement->libelle ?? 'Non spécifié' }}</h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-icon bg-info">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div class="info-content">
                                    <p class="info-label">Classe</p>
                                    <h6 class="info-value">{{ $equipement->classe_equipement ?? 'Non spécifié' }}</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Deuxième ligne -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-icon bg-{{ $equipement->etat == 'neuf' || $equipement->etat == 'bon' ? 'success' : ($equipement->etat == 'moyen' ? 'warning' : 'danger') }}">
                                    <i class="fas fa-thermometer-half"></i>
                                </div>
                                <div class="info-content">
                                    <p class="info-label">État</p>
                                    <h6 class="info-value">
                                        <span class="badge-custom bg-{{ $equipement->etat == 'neuf' || $equipement->etat == 'bon' ? 'success' : ($equipement->etat == 'moyen' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($equipement->etat) }}
                                        </span>
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-icon bg-warning">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="info-content">
                                    <p class="info-label">Type de maintenance</p>
                                    <h6 class="info-value">{{ ucfirst($equipement->type_maintenance) }}</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Troisième ligne -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-icon bg-success">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="info-content">
                                    <p class="info-label">Date d'achat</p>
                                    <h6 class="info-value">{{ $equipement->date_achat->format('d/m/Y') }}</h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-icon bg-purple">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <div class="info-content">
                                    <p class="info-label">Mise en service</p>
                                    <h6 class="info-value">{{ $equipement->date_mise_service ? $equipement->date_mise_service->format('d/m/Y') : 'Non spécifié' }}</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Informations financières -->
                        @if($equipement->prix_achat || $equipement->duree_vie_theorique)
                        <div class="col-12 mt-3">
                            <hr>
                            <div class="row">
                                @if($equipement->prix_achat)
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between py-2">
                                        <span class="text-muted">Prix d'achat</span>
                                        <span class="fw-bold">{{ number_format($equipement->prix_achat, 2, ',', ' ') }} €</span>
                                    </div>
                                </div>
                                @endif

                                @if($equipement->duree_vie_theorique)
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between py-2">
                                        <span class="text-muted">Durée de vie théorique</span>
                                        <span class="fw-bold">{{ $equipement->duree_vie_theorique }} ans</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Localisation -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card-modern h-100">
                        <div class="card-header-modern">
                            <h6 class="mb-0">
                                <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                Localisation Physique
                            </h6>
                        </div>
                        <div class="card-body-modern">
                            <div class="text-center mb-3">
                                <i class="fas fa-location-dot fa-3x text-danger"></i>
                            </div>
                            <h4 class="text-center fw-bold mb-2">{{ $equipement->localisation->nom ?? 'Non spécifié' }}</h4>
                            @if($equipement->localisation)
                            <div class="text-center text-muted">
                                @if($equipement->localisation->adresse)
                                <p class="mb-1">{{ $equipement->localisation->adresse }}</p>
                                @endif
                                @if($equipement->localisation->telephone)
                                <p class="mb-0">
                                    <i class="fas fa-phone me-2"></i>{{ $equipement->localisation->telephone }}
                                </p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card-modern h-100">
                        <div class="card-header-modern">
                            <h6 class="mb-0">
                                <i class="fas fa-building me-2 text-info"></i>
                                Service Responsable
                            </h6>
                        </div>
                        <div class="card-body-modern">
                            <div class="text-center mb-3">
                                <i class="fas fa-users fa-3x text-info"></i>
                            </div>
                            <h4 class="text-center fw-bold">{{ $equipement->serviceResponsable->nom ?? 'Non spécifié' }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des interventions -->
            @if($equipement->interventions && $equipement->interventions->count() > 0)
            <div class="card-modern mb-4">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2 text-warning"></i>
                        Historique des Interventions
                    </h6>
                </div>
                <div class="card-body-modern p-0">
                    <div class="timeline">
                        @foreach($equipement->interventions as $intervention)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $intervention->statut == 'terminee' ? 'success' : ($intervention->statut == 'en_cours' ? 'warning' : 'secondary') }}"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="fw-bold mb-0">{{ $intervention->type_intervention }}</h6>
                                    <span class="badge-custom bg-{{ $intervention->statut == 'terminee' ? 'success' : ($intervention->statut == 'en_cours' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($intervention->statut) }}
                                    </span>
                                </div>
                                <p class="text-muted mb-2">{{ $intervention->description }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $intervention->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne droite - Actions et informations complémentaires -->
        <div class="col-xl-4">

            <!-- Carte de disponibilité -->
            <div class="card-modern mb-4">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line me-2 text-{{ $isAvailable['color'] }}"></i>
                        État de Disponibilité
                    </h6>
                </div>
                <div class="card-body-modern text-center">
                    <div class="availability-icon mb-3">
                        <i class="fas fa-{{ $isAvailable['color'] == 'success' ? 'check-circle' : ($isAvailable['color'] == 'warning' ? 'exclamation-triangle' : 'times-circle') }} fa-4x text-{{ $isAvailable['color'] }}"></i>
                    </div>
                    <h3 class="mb-3 text-{{ $isAvailable['color'] }}">{{ $isAvailable['message'] }}</h3>


                    <!-- Messages d'alerte -->
                    @if($isAvailable['status'] === 'non_disponible')
                    <div class="alert-card bg-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Équipement hors service</strong>
                            <p class="mb-0">Cet équipement n'est pas utilisable dans son état actuel.</p>
                        </div>
                    </div>
                    @endif

                    @if($isAvailable['status'] === 'en_intervention')
                    <div class="alert-card bg-warning">
                        <i class="fas fa-hammer"></i>
                        <div>
                            <strong>Intervention en cours</strong>
                            <p class="mb-0">Une intervention est en cours sur cet équipement.</p>
                        </div>
                    </div>
                    @endif

                    @if($isAvailable['status'] === 'usage_limite')
                    <div class="alert-card bg-warning">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <strong>Usage limité</strong>
                            <p class="mb-0">État moyen - Utilisation avec précaution recommandée.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Fournisseur et contrat -->
            <div class="card-modern mb-4">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-handshake me-2 text-purple"></i>
                        Fournisseur & Contrat
                    </h6>
                </div>
                <div class="card-body-modern">
                    @if($equipement->fournisseur)
                    <div class="supplier-card">
                        <div class="supplier-icon bg-purple">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="supplier-info">
                            <h6 class="fw-bold mb-1">{{ $equipement->fournisseur->nom }}</h6>
                            @if($equipement->fournisseur->telephone)
                            <p class="text-muted mb-0">
                                <i class="fas fa-phone me-1"></i>{{ $equipement->fournisseur->telephone }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($equipement->contrat)
                    <div class="contract-card mt-4">
                        <div class="contract-icon bg-success">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div class="contract-info">
                            <h6 class="fw-bold mb-1">{{ $equipement->contrat->numero_contrat }}</h6>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar me-1"></i>
                                Du {{ $equipement->contrat->date_debut->format('d/m/Y') }} au {{ $equipement->contrat->date_fin->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                    @endif

                    @if($equipement->duree_garantie)
                    <div class="warranty-card mt-4">
                        <div class="warranty-icon bg-info">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="warranty-info">
                            <h6 class="fw-bold mb-1">Garantie</h6>
                            <p class="text-muted mb-0">{{ $equipement->duree_garantie }} mois</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Commentaires -->
            @if($equipement->commentaires)
            <div class="card-modern">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-comment-dots me-2 text-secondary"></i>
                        Commentaires
                    </h6>
                </div>
                <div class="card-body-modern">
                    <div class="comment-box">
                        <i class="fas fa-quote-left fa-2x text-muted mb-3"></i>
                        <p class="comment-text">{!! nl2br(e($equipement->commentaires)) !!}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

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
.header-card {
    background: var(--gradient-primary);
    padding: 30px 35px;
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

.btn-back {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: white;
    color: var(--primary);
    transform: translateX(-3px);
}

/* Badge Custom */
.badge-custom {
    padding: 8px 16px;
    border-radius: 10px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

/* Modern Cards */
.card-modern {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    border: none;
    overflow: hidden;
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.card-header-modern {
    background: linear-gradient(135deg, #f6f8fb 0%, #e9ecf5 100%);
    padding: 20px 25px;
    border-bottom: 2px solid #e2e8f0;
}

.card-header-modern h6 {
    font-weight: 700;
    color: #2d3748;
}

.card-body-modern {
    padding: 25px;
}

/* Info Cards */
.info-card {
    display: flex;
    align-items: center;
    padding: 15px;
    background: #f8f9fc;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.info-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-right: 15px;
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

.info-label {
    color: #6c757d;
    font-size: 0.85rem;
    margin-bottom: 5px;
}

.info-value {
    margin: 0;
    color: #2d3748;
    font-size: 1.1rem;
}

/* Timeline */
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    display: flex;
    padding: 20px 25px;
    position: relative;
}

.timeline-item:hover {
    background: #f8f9fc;
}

.timeline-marker {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    position: absolute;
    left: 25px;
    top: 25px;
}

.timeline-content {
    margin-left: 30px;
    flex: 1;
}

/* Action Button */
.btn-action-primary {
    background: var(--primary);
    color: white;
    border: none;
    padding: 15px 20px;
    border-radius: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-action-primary:hover {
    background: #2e59d9;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(78, 115, 223, 0.3);
    color: white;
}

/* Alert Cards */
.alert-card {
    background: linear-gradient(135deg, currentColor 0%, rgba(255,255,255,0.1) 100%);
    color: white;
    padding: 15px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.alert-card i {
    font-size: 1.5rem;
    margin-right: 15px;
}

.alert-card strong {
    display: block;
    margin-bottom: 5px;
}

.alert-card p {
    margin: 0;
    opacity: 0.9;
}

/* Supplier/Contract/Warranty Cards */
.supplier-card,
.contract-card,
.warranty-card {
    display: flex;
    align-items: center;
    padding: 15px;
    background: #f8f9fc;
    border-radius: 12px;
    margin-bottom: 15px;
}

.supplier-icon,
.contract-icon,
.warranty-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
    margin-right: 15px;
    flex-shrink: 0;
}

.supplier-info,
.contract-info,
.warranty-info {
    flex: 1;
}

/* Comment Box */
.comment-box {
    background: linear-gradient(135deg, #f6f8fb 0%, #e9ecf5 100%);
    border-radius: 12px;
    padding: 25px;
    position: relative;
}

.comment-text {
    line-height: 1.6;
    color: #2d3748;
    font-style: italic;
}

/* Availability Icon */
.availability-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Colors */
.bg-primary { background: var(--primary) !important; }
.bg-success { background: var(--success) !important; }
.bg-danger { background: var(--danger) !important; }
.bg-warning { background: var(--warning) !important; }
.bg-info { background: var(--info) !important; }
.bg-purple { background: var(--purple) !important; }
.bg-secondary { background: #858796 !important; }

.text-primary { color: var(--primary) !important; }
.text-success { color: var(--success) !important; }
.text-danger { color: var(--danger) !important; }
.text-warning { color: var(--warning) !important; }
.text-info { color: var(--info) !important; }

/* Responsive */
@media (max-width: 768px) {
    .header-card {
        padding: 20px;
    }

    .info-card {
        flex-direction: column;
        text-align: center;
    }

    .info-icon {
        margin-right: 0;
        margin-bottom: 10px;
    }

    .timeline-item {
        padding: 15px;
    }

    .timeline-marker {
        left: 15px;
        top: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes au chargement
    const cards = document.querySelectorAll('.card-modern');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Effet de survol sur les cartes d'info
    const infoCards = document.querySelectorAll('.info-card');
    infoCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 10px 25px rgba(0,0,0,0.15)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.08)';
        });
    });
});
</script>
@endsection
