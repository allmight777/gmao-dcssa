@extends('layouts.admin')

@section('title', 'Détails du Mouvement')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', 'Détails du Mouvement #' . $mouvement->id)

@section('page-actions')
<div class="page-actions">
    <style>
        .page-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            background-color: #1d4ed8;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-action i {
            margin-right: 6px;
        }

        .btn-action:hover {
            background-color: #2563eb;
        }
    </style>
    <a href="{{ route('inventaire.historiques.index') }}" class="btn-action">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>
</div>
@endsection

@section('content')
<div class="inventory-container">
    <!-- Navigation entre mouvements -->
    @if($mouvementPrecedent || $mouvementSuivant)
    <div class="navigation-card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                @if($mouvementPrecedent)
                    <a href="{{ route('inventaire.historiques.show', $mouvementPrecedent->id) }}"
                       class="btn-navigation">
                        <i class="fas fa-chevron-left"></i> Mouvement Précédent
                    </a>
                @endif
            </div>
            <div class="text-center">
                <small class="text-muted">
                    Navigation dans l'historique de
                    <strong>{{ $mouvement->equipement->numero_inventaire }}</strong>
                </small>
            </div>
            <div>
                @if($mouvementSuivant)
                    <a href="{{ route('inventaire.historiques.show', $mouvementSuivant->id) }}"
                       class="btn-navigation">
                        Mouvement Suivant <i class="fas fa-chevron-right"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-8 mb-4">
            <!-- Carte informations du mouvement -->
            <div class="detail-card">
                <div class="card-header bg-primary">
                    <h3><i class="fas fa-file-alt"></i> Informations du Mouvement</h3>
                </div>
                <div class="card-body">
                    <!-- Date et opérateur -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-icon bg-info">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Date et Heure</h4>
                                    <p class="info-value">{{ $mouvement->date_mouvement->format('d/m/Y à H:i:s') }}</p>
                                    <small class="text-muted">
                                        Il y a {{ $mouvement->date_mouvement->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-icon bg-success">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Opérateur</h4>
                                    <p class="info-value">{{ $mouvement->operateur->nom }} {{ $mouvement->operateur->prenom }}</p>
                                    <small class="text-muted">
                                        {{ $mouvement->operateur->email }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Motif -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="info-box">
                                <div class="info-icon bg-warning">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Motif du Mouvement</h4>
                                    @php
                                        $badgeClass = 'badge-primary';
                                        if(str_contains(strtolower($mouvement->motif), 'retrait')) {
                                            $badgeClass = 'badge-warning';
                                        } elseif(str_contains(strtolower($mouvement->motif), 'affectation')) {
                                            $badgeClass = 'badge-success';
                                        } elseif(str_contains(strtolower($mouvement->motif), 'changement')) {
                                            $badgeClass = 'badge-info';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $mouvement->motif }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Commentaire -->
                    @if($mouvement->commentaire)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="info-box">
                                <div class="info-icon bg-secondary">
                                    <i class="fas fa-comment-alt"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Commentaire</h4>
                                    <p class="info-value">{{ $mouvement->commentaire }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Visualisation du mouvement -->
                    <div class="row">
                        <div class="col-md-5">
                            <div class="location-card departure">
                                <div class="location-header">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <h4>Ancienne Localisation</h4>
                                </div>
                                <div class="location-body">
                                    @if($mouvement->ancienneLocalisation)
                                        <p class="location-name">{{ $mouvement->ancienneLocalisation->nom }}</p>
                                        <div class="location-details">
                                            <p><i class="fas fa-building"></i> {{ $mouvement->ancienneLocalisation->batiment }}</p>
                                            <p><i class="fas fa-layer-group"></i> Étage {{ $mouvement->ancienneLocalisation->etage }}</p>
                                            @if($mouvement->ancienneLocalisation->service)
                                                <p><i class="fas fa-briefcase"></i> {{ $mouvement->ancienneLocalisation->service->nom }}</p>
                                            @endif
                                        </div>
                                    @else
                                        <div class="no-location">
                                            <i class="fas fa-times-circle"></i>
                                            <p>Aucune localisation</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-center d-flex align-items-center justify-content-center">
                            <div class="arrow-container">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="location-card arrival">
                                <div class="location-header">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <h4>Nouvelle Localisation</h4>
                                </div>
                                <div class="location-body">
                                    @if($mouvement->nouvelleLocalisation)
                                        <p class="location-name">{{ $mouvement->nouvelleLocalisation->nom }}</p>
                                        <div class="location-details">
                                            <p><i class="fas fa-building"></i> {{ $mouvement->nouvelleLocalisation->batiment }}</p>
                                            <p><i class="fas fa-layer-group"></i> Étage {{ $mouvement->nouvelleLocalisation->etage }}</p>
                                            @if($mouvement->nouvelleLocalisation->service)
                                                <p><i class="fas fa-briefcase"></i> {{ $mouvement->nouvelleLocalisation->service->nom }}</p>
                                            @endif
                                        </div>
                                    @else
                                        <div class="no-location">
                                            <i class="fas fa-times-circle"></i>
                                            <p>Aucune localisation</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique complet -->
            <div class="detail-card mt-4">
                <div class="card-header bg-info">
                    <h3><i class="fas fa-history"></i> Historique de l'Équipement (10 derniers mouvements)</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($historique as $index => $hist)
                        <div class="timeline-item {{ $hist->id === $mouvement->id ? 'active' : '' }}">
                            <div class="timeline-marker {{ $hist->id === $mouvement->id ? 'bg-primary' : 'bg-secondary' }}">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <div>
                                        @if($hist->id === $mouvement->id)
                                            <span class="badge badge-primary">Mouvement actuel</span>
                                        @endif
                                        <span class="badge badge-info">{{ $hist->motif }}</span>
                                    </div>
                                    <div class="timeline-date">
                                        {{ $hist->date_mouvement->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="timeline-body">
                                    <p class="mb-2">
                                        <i class="fas fa-arrow-right text-muted"></i>
                                        De: <strong>{{ $hist->ancienneLocalisation->nom ?? 'N/A' }}</strong>
                                        vers <strong>{{ $hist->nouvelleLocalisation->nom ?? 'N/A' }}</strong>
                                    </p>
                                    <small class="text-muted">
                                        Par {{ $hist->operateur->nom }} {{ $hist->operateur->prenom }}
                                    </small>
                                </div>
                                @if($hist->id !== $mouvement->id)
                                <div class="timeline-actions">
                                    <a href="{{ route('inventaire.historiques.show', $hist->id) }}"
                                       class="btn-action btn-view">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne de droite -->
        <div class="col-lg-4">
            <!-- Informations équipement -->
            <div class="detail-card">
                <div class="card-header bg-success">
                    <h3><i class="fas fa-laptop-medical"></i> Équipement Concerné</h3>
                </div>
                <div class="card-body">
                    <div class="equipment-icon">
                        <i class="fas fa-laptop-medical"></i>
                    </div>
                    <div class="equipment-info">
                        <table class="info-table">
                            <tr>
                                <td>N° Inventaire:</td>
                                <td><strong>{{ $mouvement->equipement->numero_inventaire }}</strong></td>
                            </tr>
                            <tr>
                                <td>Marque:</td>
                                <td>{{ $mouvement->equipement->marque }}</td>
                            </tr>
                            <tr>
                                <td>Modèle:</td>
                                <td>{{ $mouvement->equipement->modele }}</td>
                            </tr>
                            <tr>
                                <td>Type:</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $mouvement->equipement->typeEquipement->nom ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>État:</td>
                                <td>
                                    @php
                                        $etatClass = [
                                            'neuf' => 'badge-success',
                                            'bon' => 'badge-primary',
                                            'moyen' => 'badge-warning',
                                            'mauvais' => 'badge-danger',
                                            'hors_service' => 'badge-dark'
                                        ][$mouvement->equipement->etat] ?? 'badge-secondary';
                                    @endphp
                                    <span class="badge {{ $etatClass }}">
                                        {{ ucfirst($mouvement->equipement->etat) }}
                                    </span>
                                </td>
                            </tr>
                            @if($mouvement->equipement->fournisseur)
                            <tr>
                                <td>Fournisseur:</td>
                                <td>{{ $mouvement->equipement->fournisseur->raison_sociale }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('inventaire.equipements.show', $mouvement->equipement_id) }}"
                           class="btn btn-primary btn-block">
                            <i class="fas fa-eye"></i> Voir la Fiche Complète
                        </a>
                    </div>
                </div>
            </div>

            <!-- Logs d'activité -->
            @if($logsActivite->count() > 0)
            <div class="detail-card mt-4">
                <div class="card-header bg-warning">
                    <h3><i class="fas fa-file-alt"></i> Logs d'Activité Associés</h3>
                </div>
                <div class="card-body">
                    <div class="logs-container">
                        @foreach($logsActivite as $log)
                        <div class="log-item">
                            <div class="log-header">
                                <span class="log-action">{{ $log->action }}</span>
                                <span class="log-time">{{ $log->date_heure->format('d/m/Y H:i:s') }}</span>
                            </div>
                            <div class="log-body">
                                <p>{{ $log->details }}</p>
                            </div>
                            <div class="log-footer">
                                <i class="fas fa-user"></i>
                                {{ $log->utilisateur->name ?? 'N/A' }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions rapides -->
            <div class="detail-card mt-4">
                <div class="card-header bg-secondary">
                    <h3><i class="fas fa-cog"></i> Actions Rapides</h3>
                </div>
                <div class="card-body">
                    <div class="action-links">
                        <a href="{{ route('inventaire.equipements.show', $mouvement->equipement_id) }}"
                           class="action-link">
                            <i class="fas fa-laptop-medical"></i>
                            <span>Voir l'Équipement</span>
                        </a>
                        <a href="{{ route('inventaire.historiques.index', ['equipement_id' => $mouvement->equipement_id]) }}"
                           class="action-link">
                            <i class="fas fa-history"></i>
                            <span>Historique Complet</span>
                        </a>
                        @if($mouvement->ancienneLocalisation)
                        <a href="{{ route('inventaire.historiques.index', ['localisation_id' => $mouvement->ancienneLocalisation->id]) }}"
                           class="action-link">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Mouvements depuis cette localisation</span>
                        </a>
                        @endif
                        <a href="{{ route('inventaire.historiques.index') }}"
                           class="action-link">
                            <i class="fas fa-arrow-left"></i>
                            <span>Retour à la liste</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
:root {
    --primary-color: #0351BC;
    --primary-light: #4a7fd4;
    --primary-dark: #023a8a;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #3b82f6;
    --secondary-color: #6b7280;
    --light-color: #f8fafc;
    --dark-color: #1e293b;
    --white: #ffffff;
    --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

.inventory-container {
    padding: 20px;
}

/* Navigation */
.navigation-card {
    background: var(--white);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: var(--card-shadow);
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.btn-navigation {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    border: none;
    gap: 8px;
}

.btn-navigation:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(3, 81, 188, 0.3);
    color: var(--white);
}

/* Cartes de détail */
.detail-card {
    background: var(--white);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--card-shadow);
    border: 1px solid rgba(0, 0, 0, 0.1);
    margin-bottom: 25px;
}

.detail-card .card-header {
    padding: 20px 25px;
    color: var(--white);
}

.detail-card .card-header h3 {
    margin: 0;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.detail-card .card-body {
    padding: 25px;
}

/* Info boxes */
.info-box {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 20px;
    background: var(--light-color);
    border-radius: 12px;
    transition: var(--transition);
}

.info-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.info-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: var(--white);
    flex-shrink: 0;
}

.info-content h4 {
    margin: 0 0 8px 0;
    font-size: 14px;
    color: var(--dark-color);
    font-weight: 600;
}

.info-value {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: var(--primary-color);
}

/* Cartes de localisation */
.location-card {
    height: 100%;
    border-radius: 12px;
    padding: 20px;
    transition: var(--transition);
}

.location-card.departure {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border: 2px solid var(--danger-color);
}

.location-card.arrival {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border: 2px solid var(--success-color);
}

.location-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.location-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.location-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
}

.departure .location-header {
    color: var(--danger-color);
}

.arrival .location-header {
    color: var(--success-color);
}

.location-name {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--dark-color);
}

.location-details p {
    margin: 5px 0;
    color: var(--dark-color);
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.no-location {
    text-align: center;
    padding: 20px 0;
    color: var(--secondary-color);
}

.no-location i {
    font-size: 48px;
    margin-bottom: 10px;
}

.arrow-container {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 24px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e3e6f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-left: 20px;
}

.timeline-item.active .timeline-content {
    background: rgba(3, 81, 188, 0.05);
    border-left: 3px solid var(--primary-color);
}

.timeline-marker {
    position: absolute;
    left: -20px;
    top: 10px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 10px;
    z-index: 1;
}

.timeline-content {
    background: var(--white);
    padding: 15px;
    border-radius: 10px;
    border: 1px solid rgba(0, 0, 0, 0.1);
    transition: var(--transition);
}

.timeline-content:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}

.timeline-date {
    font-size: 12px;
    color: var(--secondary-color);
}

.timeline-body p {
    margin: 0;
    font-size: 14px;
}

.timeline-actions {
    margin-top: 10px;
    text-align: right;
}

/* Équipement */
.equipment-icon {
    text-align: center;
    margin-bottom: 20px;
}

.equipment-icon i {
    font-size: 60px;
    color: var(--success-color);
}

.info-table {
    width: 100%;
}

.info-table tr td {
    padding: 8px 0;
    vertical-align: top;
}

.info-table tr td:first-child {
    color: var(--secondary-color);
    font-weight: 500;
    width: 40%;
}

/* Logs */
.logs-container {
    max-height: 300px;
    overflow-y: auto;
}

.log-item {
    padding: 15px;
    margin-bottom: 10px;
    background: var(--light-color);
    border-radius: 10px;
    border-left: 4px solid var(--info-color);
}

.log-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.log-action {
    background: var(--info-color);
    color: var(--white);
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.log-time {
    font-size: 12px;
    color: var(--secondary-color);
}

.log-body p {
    margin: 0;
    font-size: 14px;
    color: var(--dark-color);
}

.log-footer {
    margin-top: 10px;
    font-size: 12px;
    color: var(--secondary-color);
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Actions rapides */
.action-links {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.action-link {
    display: flex;
    align-items: center;
    padding: 15px;
    background: var(--light-color);
    border-radius: 10px;
    text-decoration: none;
    color: var(--dark-color);
    transition: var(--transition);
    gap: 15px;
}

.action-link:hover {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    transform: translateX(5px);
}

.action-link i {
    font-size: 18px;
    width: 24px;
    text-align: center;
}

.action-link span {
    flex: 1;
    font-weight: 500;
}

/* Badges */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
    transition: var(--transition);
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    color: var(--white);
}

/* Responsive */
@media (max-width: 992px) {
    .arrow-container {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
}

@media (max-width: 768px) {
    .inventory-container {
        padding: 15px;
    }

    .detail-card .card-body {
        padding: 20px;
    }

    .info-box {
        flex-direction: column;
        text-align: center;
    }

    .location-header {
        flex-direction: column;
        text-align: center;
        gap: 5px;
    }

    .timeline-header {
        flex-direction: column;
        gap: 5px;
    }
}
</style>
@endpush
