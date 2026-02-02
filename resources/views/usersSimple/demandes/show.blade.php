@extends('layouts.welcome')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <!-- En-tête de la demande -->
            <div class="card card-modern mb-4">
                <div class="card-header-modern">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('user.demandes.index') }}" class="btn-back me-3">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div>
                                <h4 class="mb-0 fw-bold text-white">
                                    <i class="fas fa-file-alt me-2"></i>
                                    Demande #{{ $demande->Numero_Demande }}
                                </h4>
                                <p class="mb-0 text-white-50 small">
                                    Créée le {{ $demande->Date_Demande->format('d/m/Y') }} à {{ $demande->Heure_Demande }}
                                </p>
                            </div>
                        </div>
                        <div class="status-display">
                            <span class="status-badge {{ $demande->Statut }}">
                                {{ $demande->etat_formate }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Onglets de navigation -->
                    <div class="tabs-container">
                        <ul class="nav nav-tabs-modern" id="demandeTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab"
                                        data-bs-target="#overview" type="button" role="tab">
                                    <i class="fas fa-eye me-2"></i>Vue d'ensemble
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="equipment-tab" data-bs-toggle="tab"
                                        data-bs-target="#equipment" type="button" role="tab">
                                    <i class="fas fa-microscope me-2"></i>Équipement
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="problem-tab" data-bs-toggle="tab"
                                        data-bs-target="#problem" type="button" role="tab">
                                    <i class="fas fa-tools me-2"></i>Problème
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="validation-tab" data-bs-toggle="tab"
                                        data-bs-target="#validation" type="button" role="tab">
                                    <i class="fas fa-check-circle me-2"></i>Validation
                                </button>
                            </li>
                        </ul>

                        <!-- Contenu des onglets -->
                        <div class="tab-content" id="demandeTabsContent">
                            <!-- Onglet 1: Vue d'ensemble -->
                            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                <div class="p-4">
                                    <!-- Statistiques rapides -->
                                    <div class="row mb-4">
                                        <div class="col-md-3 col-6">
                                            <div class="stat-card">
                                                <div class="stat-icon bg-primary">
                                                    <i class="fas fa-fire"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <span class="stat-label">Urgence</span>
                                                    <span class="stat-value {{ $demande->Urgence }}">
                                                        @switch($demande->Urgence)
                                                            @case('critique')
                                                                Critique
                                                                @break
                                                            @case('urgente')
                                                                Urgente
                                                                @break
                                                            @default
                                                                Normale
                                                        @endswitch
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="stat-card">
                                                <div class="stat-icon bg-warning">
                                                    <i class="fas fa-flag"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <span class="stat-label">Priorité</span>
                                                    <span class="stat-value">
                                                        @if($demande->Priorite == 1)
                                                            Haute
                                                        @elseif($demande->Priorite == 2)
                                                            Moyenne
                                                        @else
                                                            Basse
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="stat-card">
                                                <div class="stat-icon bg-info">
                                                    <i class="fas fa-tools"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <span class="stat-label">Type</span>
                                                    <span class="stat-value">
                                                        @php
                                                            $types = [
                                                                'maintenance_preventive' => 'Préventive',
                                                                'maintenance_corrective' => 'Corrective',
                                                                'reparation' => 'Réparation',
                                                                'calibration' => 'Calibration',
                                                                'verification' => 'Vérification',
                                                                'controle' => 'Contrôle',
                                                                'autre' => 'Autre'
                                                            ];
                                                        @endphp
                                                        {{ $types[$demande->Type_Intervention] ?? $demande->Type_Intervention }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="stat-card">
                                                <div class="stat-icon bg-success">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <span class="stat-label">Délai souhaité</span>
                                                    <span class="stat-value">
                                                        {{ $demande->Delai_Souhaite ? $demande->Delai_Souhaite . 'h' : 'Non spécifié' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Timeline de la demande -->
                                    <div class="timeline-vertical mb-4">
                                        <h5 class="mb-3"><i class="fas fa-history me-2"></i>Historique de la demande</h5>
                                        <div class="timeline-item completed">
                                            <div class="timeline-marker">
                                                <i class="fas fa-plus-circle"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6>Demande créée</h6>
                                                <p class="text-muted mb-0">
                                                    {{ $demande->Date_Demande->format('d/m/Y à H:i') }}
                                                </p>
                                            </div>
                                        </div>

                                        @if($demande->isValidee() && $demande->Date_Validation)
                                        <div class="timeline-item completed">
                                            <div class="timeline-marker">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6>Demande validée</h6>
                                                <p class="text-muted mb-0">
                                                    {{ $demande->Date_Validation->format('d/m/Y à H:i') }}
                                                    @if($demande->validateur)
                                                        <br>Par {{ $demande->validateur->nom }} {{ $demande->validateur->prenom }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        @elseif($demande->isRejetee())
                                        <div class="timeline-item canceled">
                                            <div class="timeline-marker">
                                                <i class="fas fa-times-circle"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6>Demande rejetée</h6>
                                                <p class="text-muted mb-0">
                                                    {{ $demande->Date_Validation->format('d/m/Y à H:i') }}
                                                    @if($demande->validateur)
                                                        <br>Par {{ $demande->validateur->nom }} {{ $demande->validateur->prenom }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        @else
                                        <div class="timeline-item current">
                                            <div class="timeline-marker">
                                                <i class="fas fa-hourglass-half"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6>En attente de validation</h6>
                                                <p class="text-muted mb-0">
                                                    Votre demande est en cours d'examen par le service technique
                                                </p>
                                            </div>
                                        </div>
                                        @endif

                                        @if($demande->isTerminee())
                                        <div class="timeline-item completed">
                                            <div class="timeline-marker">
                                                <i class="fas fa-check-double"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6>Intervention terminée</h6>
                                                <p class="text-muted mb-0">
                                                    L'intervention a été réalisée avec succès
                                                </p>
                                            </div>
                                        </div>
                                        @elseif($demande->isEnCours())
                                        <div class="timeline-item current">
                                            <div class="timeline-marker">
                                                <i class="fas fa-hammer"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6>Intervention en cours</h6>
                                                <p class="text-muted mb-0">
                                                    Un technicien travaille sur votre équipement
                                                </p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Boutons d'action -->
                                    <div class="actions-container mt-4">
                                        <div class="d-flex flex-wrap gap-3">
                                            @if($demande->isEnAttente())
                                            <a href="{{ route('user.demandes.edit', $demande->ID_Demande) }}"
                                               class="btn-action btn-action-primary">
                                                <i class="fas fa-edit me-2"></i>
                                                Modifier la demande
                                            </a>
                                            <form action="{{ route('user.demandes.destroy', $demande->ID_Demande) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn-action btn-action-danger"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')">
                                                    <i class="fas fa-trash me-2"></i>
                                                    Supprimer
                                                </button>
                                            </form>
                                            @endif

                                            <a href="{{ route('user.demandes.index') }}"
                                               class="btn-action btn-action-secondary">
                                                <i class="fas fa-list me-2"></i>
                                                Retour à la liste
                                            </a>

                                            <button class="btn-action btn-action-success" onclick="window.print()">
                                                <i class="fas fa-print me-2"></i>
                                                Imprimer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Onglet 2: Équipement -->
                            <div class="tab-pane fade" id="equipment" role="tabpanel">
                                <div class="p-4">
                                    @if($demande->equipement)
                                    <div class="equipment-detail-card">
                                        <div class="equipment-header">
                                            <div class="equipment-title">
                                                <h5 class="mb-1">
                                                    <i class="fas fa-microscope me-2"></i>
                                                    Fiche technique de l'équipement
                                                </h5>
                                                <p class="text-muted mb-0">Numéro d'inventaire : {{ $demande->equipement->numero_inventaire }}</p>
                                            </div>
                                            <span class="equipment-status {{ $demande->equipement->etat }}">
                                                {{ ucfirst($demande->equipement->etat) }}
                                            </span>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <i class="fas fa-tag text-primary"></i>
                                                    <div>
                                                        <span class="label">Marque</span>
                                                        <span class="value">{{ $demande->equipement->marque }}</span>
                                                    </div>
                                                </div>
                                                <div class="info-row">
                                                    <i class="fas fa-cogs text-primary"></i>
                                                    <div>
                                                        <span class="label">Modèle</span>
                                                        <span class="value">{{ $demande->equipement->modele }}</span>
                                                    </div>
                                                </div>
                                                <div class="info-row">
                                                    <i class="fas fa-barcode text-primary"></i>
                                                    <div>
                                                        <span class="label">Numéro de série</span>
                                                        <span class="value">{{ $demande->equipement->numero_serie ?? 'Non renseigné' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <i class="fas fa-map-marker-alt text-success"></i>
                                                    <div>
                                                        <span class="label">Localisation</span>
                                                        <span class="value">{{ $demande->equipement->localisation->nom ?? 'Non spécifié' }}</span>
                                                    </div>
                                                </div>
                                                <div class="info-row">
                                                    <i class="fas fa-layer-group text-info"></i>
                                                    <div>
                                                        <span class="label">Type d'équipement</span>
                                                        <span class="value">{{ $demande->equipement->type_equipement->libelle ?? 'Non spécifié' }}</span>
                                                    </div>
                                                </div>
                                                <div class="info-row">
                                                    <i class="fas fa-calendar-alt text-warning"></i>
                                                    <div>
                                                        <span class="label">Date d'acquisition</span>
                                                        <span class="value">{{ $demande->equipement->date_acquisition ? $demande->equipement->date_acquisition->format('d/m/Y') : 'Non renseignée' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($demande->equipement->description)
                                        <div class="mt-4">
                                            <h6 class="mb-2"><i class="fas fa-align-left me-2"></i>Description</h6>
                                            <div class="description-box">
                                                {{ $demande->equipement->description }}
                                            </div>
                                        </div>
                                        @endif

                                        @if($demande->equipement->caracteristiques)
                                        <div class="mt-4">
                                            <h6 class="mb-2"><i class="fas fa-list-alt me-2"></i>Caractéristiques techniques</h6>
                                            <div class="characteristics-grid">
                                                @php
                                                    $caracteristiques = json_decode($demande->equipement->caracteristiques, true) ?? [];
                                                @endphp
                                                @foreach($caracteristiques as $key => $value)
                                                <div class="characteristic-item">
                                                    <span class="char-label">{{ $key }} :</span>
                                                    <span class="char-value">{{ $value }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @else
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <h5 class="mb-2">Équipement non trouvé</h5>
                                        <p class="text-muted">Les informations de l'équipement ne sont pas disponibles.</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Onglet 3: Problème -->
                            <div class="tab-pane fade" id="problem" role="tabpanel">
                                <div class="p-4">
                                    <div class="problem-detail-card">
                                        <div class="problem-header">
                                            <h5 class="mb-1">
                                                <i class="fas fa-tools me-2"></i>
                                                Description détaillée du problème
                                            </h5>
                                            <p class="text-muted mb-0">Symptômes et observations</p>
                                        </div>

                                        <div class="mt-4">
                                            <div class="problem-content">
                                                <div class="content-section">
                                                    <h6 class="section-title">
                                                        <i class="fas fa-stethoscope me-2"></i>
                                                        Symptômes observés
                                                    </h6>
                                                    <div class="content-box rich-text">
                                                        {!! nl2br(e($demande->Description_Panne)) !!}
                                                    </div>
                                                </div>

                                                @if($demande->Commentaires)
                                                <div class="content-section mt-4">
                                                    <h6 class="section-title">
                                                        <i class="fas fa-comment-dots me-2"></i>
                                                        Observations supplémentaires
                                                    </h6>
                                                    <div class="content-box">
                                                        {!! nl2br(e($demande->Commentaires)) !!}
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="content-section mt-4">
                                                    <h6 class="section-title">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        Analyse de la situation
                                                    </h6>
                                                    <div class="analysis-grid">
                                                        <div class="analysis-item">
                                                            <i class="fas fa-clock text-primary"></i>
                                                            <div>
                                                                <span class="label">Durée estimée</span>
                                                                <span class="value">
                                                                    @php
                                                                        $delais = [
                                                                            'maintenance_preventive' => '2-4 heures',
                                                                            'maintenance_corrective' => '4-8 heures',
                                                                            'reparation' => '1-3 jours',
                                                                            'calibration' => '2-3 heures',
                                                                            'verification' => '1-2 heures',
                                                                            'controle' => '1-2 heures',
                                                                            'autre' => 'À déterminer'
                                                                        ];
                                                                    @endphp
                                                                    {{ $delais[$demande->Type_Intervention] ?? 'À déterminer' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="analysis-item">
                                                            <i class="fas fa-user-cog text-success"></i>
                                                            <div>
                                                                <span class="label">Niveau de compétence</span>
                                                                <span class="value">
                                                                    @switch($demande->Type_Intervention)
                                                                        @case('reparation')
                                                                            Technicien spécialisé
                                                                            @break
                                                                        @case('calibration')
                                                                            Technicien certifié
                                                                            @break
                                                                        @default
                                                                            Technicien standard
                                                                    @endswitch
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="analysis-item">
                                                            <i class="fas fa-exclamation-triangle text-danger"></i>
                                                            <div>
                                                                <span class="label">Impact sur le service</span>
                                                                <span class="value">
                                                                    @if($demande->Urgence == 'critique')
                                                                        Élevé
                                                                    @elseif($demande->Urgence == 'urgente')
                                                                        Moyen
                                                                    @else
                                                                        Faible
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Onglet 4: Validation -->
                            <div class="tab-pane fade" id="validation" role="tabpanel">
                                <div class="p-4">
                                    @if($demande->isValidee() || $demande->isRejetee())
                                    <div class="validation-card">
                                        <div class="validation-header {{ $demande->isValidee() ? 'validated' : 'rejected' }}">
                                            <i class="fas fa-{{ $demande->isValidee() ? 'check-circle' : 'times-circle' }} fa-3x"></i>
                                            <h4 class="mt-3">
                                                {{ $demande->isValidee() ? 'Demande Validée' : 'Demande Rejetée' }}
                                            </h4>
                                            <p class="mb-0">
                                                {{ $demande->Date_Validation->format('d/m/Y à H:i') }}
                                            </p>
                                        </div>

                                        <div class="validation-body">
                                            @if($demande->validateur)
                                            <div class="validator-info">
                                                <div class="validator-avatar">
                                                    {{ substr($demande->validateur->prenom, 0, 1) }}{{ substr($demande->validateur->nom, 0, 1) }}
                                                </div>
                                                <div class="validator-details">
                                                    <h6 class="mb-1">{{ $demande->validateur->nom }} {{ $demande->validateur->prenom }}</h6>
                                                    <p class="text-muted mb-0">
                                                        {{ $demande->validateur->fonction }}
                                                        @if($demande->validateur->service)
                                                            <br>{{ $demande->validateur->service->nom }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            @endif

                                            @if($demande->Commentaire_Validation)
                                            <div class="validation-comment mt-4">
                                                <h6><i class="fas fa-comment me-2"></i>Commentaire du validateur</h6>
                                                <div class="comment-box">
                                                    {{ $demande->Commentaire_Validation }}
                                                </div>
                                            </div>
                                            @endif

                                            @if($demande->isValidee() && $demande->Date_Intervention_Prevue)
                                            <div class="intervention-plan mt-4">
                                                <h6><i class="fas fa-calendar-check me-2"></i>Planning d'intervention</h6>
                                                <div class="planning-grid">
                                                    <div class="plan-item">
                                                        <i class="fas fa-play-circle text-primary"></i>
                                                        <div>
                                                            <span class="label">Début prévu</span>
                                                            <span class="value">
                                                                {{ $demande->Date_Intervention_Prevue->format('d/m/Y') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @if($demande->Date_Intervention_Terminee)
                                                    <div class="plan-item">
                                                        <i class="fas fa-check-circle text-success"></i>
                                                        <div>
                                                            <span class="label">Intervention terminée</span>
                                                            <span class="value">
                                                                {{ $demande->Date_Intervention_Terminee->format('d/m/Y') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @else
                                    <div class="pending-validation">
                                        <div class="pending-icon">
                                            <i class="fas fa-hourglass-half fa-3x"></i>
                                        </div>
                                        <h4 class="mt-3">En attente de validation</h4>
                                        <p class="text-muted">
                                            Votre demande est actuellement en cours d'examen par le service technique.
                                            Vous serez notifié dès qu'une décision sera prise.
                                        </p>

                                        <div class="estimated-time mt-4">
                                            <div class="time-indicator">
                                                <div class="time-progress">
                                                    <div class="progress-bar" style="width: 60%"></div>
                                                </div>
                                                <div class="time-labels">
                                                    <span>Soumission</span>
                                                    <span>Examen</span>
                                                    <span>Validation</span>
                                                </div>
                                            </div>
                                            <p class="text-center mt-2 small text-muted">
                                                Temps moyen de traitement : 24-48 heures
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Demandeur Info -->
            <div class="card card-modern">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-user me-2"></i>Informations du demandeur</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-card">
                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                                <div>
                                    <h6 class="mb-1">{{ $demande->demandeur->nom }} {{ $demande->demandeur->prenom }}</h6>
                                    <p class="text-muted mb-0">Demandeur</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-card">
                                <i class="fas fa-id-card fa-2x text-success"></i>
                                <div>
                                    <h6 class="mb-1">{{ $demande->demandeur->matricule }}</h6>
                                    <p class="text-muted mb-0">Matricule</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-card">
                                <i class="fas fa-building fa-2x text-info"></i>
                                <div>
                                    <h6 class="mb-1">{{ $demande->demandeur->service->nom ?? 'Non spécifié' }}</h6>
                                    <p class="text-muted mb-0">Service</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #4e73df;
    --primary-light: #e8eefc;
    --success: #1cc88a;
    --success-light: #e2f7ef;
    --danger: #e74a3b;
    --danger-light: #fce8e6;
    --warning: #f6c23e;
    --warning-light: #fef5e6;
    --info: #36b9cc;
    --info-light: #e8f6f8;
    --gray: #858796;
    --gray-light: #f8f9fc;
    --border: #e3e6f0;
    --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-modern {
    border: none;
    border-radius: 20px;
    box-shadow: var(--shadow);
    overflow: hidden;
    background: white;
    margin-bottom: 25px;
}

.card-header-modern {
    background: var(--gradient-primary);
    padding: 25px 30px;
    border: none;
}

.btn-back {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateX(-3px);
}

.status-badge {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

/* Tabs */
.tabs-container {
    background: white;
}

.nav-tabs-modern {
    border-bottom: 2px solid var(--border);
    padding: 0 30px;
    margin: 0;
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    scrollbar-width: none;
}

.nav-tabs-modern::-webkit-scrollbar {
    display: none;
}

.nav-tabs-modern .nav-link {
    border: none;
    border-radius: 0;
    padding: 20px 25px;
    color: var(--gray);
    font-weight: 600;
    font-size: 0.95rem;
    white-space: nowrap;
    position: relative;
    background: none;
    transition: all 0.3s ease;
}

.nav-tabs-modern .nav-link:hover {
    color: var(--primary);
}

.nav-tabs-modern .nav-link.active {
    color: var(--primary);
    background: none;
}

.nav-tabs-modern .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--primary);
    border-radius: 3px 3px 0 0;
}

.tab-content {
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Stat Cards */
.stat-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    display: flex;
    align-items: center;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
}

.stat-icon i {
    color: white;
    font-size: 1.5rem;
}

.stat-content {
    flex-grow: 1;
}

.stat-label {
    display: block;
    font-size: 0.85rem;
    color: var(--gray);
    margin-bottom: 5px;
}

.stat-value {
    display: block;
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
}

.stat-value.critique { color: var(--danger); }
.stat-value.urgente { color: var(--warning); }
.stat-value.normale { color: var(--success); }

.bg-primary { background: var(--primary); }
.bg-success { background: var(--success); }
.bg-warning { background: var(--warning); }
.bg-danger { background: var(--danger); }
.bg-info { background: var(--info); }

/* Timeline */
.timeline-vertical {
    position: relative;
    padding-left: 30px;
}

.timeline-vertical::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--border);
}

.timeline-item {
    position: relative;
    padding-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.timeline-item.completed .timeline-marker {
    background: var(--success);
    color: white;
}

.timeline-item.current .timeline-marker {
    background: var(--primary);
    color: white;
    animation: pulse 2s infinite;
}

.timeline-item.canceled .timeline-marker {
    background: var(--danger);
    color: white;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(78, 115, 223, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(78, 115, 223, 0); }
    100% { box-shadow: 0 0 0 0 rgba(78, 115, 223, 0); }
}

.timeline-content {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--border);
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.timeline-content h6 {
    color: #2d3748;
    margin-bottom: 5px;
}

/* Actions */
.actions-container {
    padding: 20px;
    background: var(--gray-light);
    border-radius: 15px;
    border: 1px solid var(--border);
}

.btn-action {
    padding: 12px 25px;
    border-radius: 12px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    cursor: pointer;
}

.btn-action-primary {
    background: var(--primary);
    color: white;
}

.btn-action-primary:hover {
    background: #3a56c9;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
}

.btn-action-secondary {
    background: white;
    color: var(--gray);
    border: 2px solid var(--border);
}

.btn-action-secondary:hover {
    background: var(--gray-light);
    border-color: var(--gray);
    transform: translateY(-2px);
}

.btn-action-danger {
    background: var(--danger);
    color: white;
}

.btn-action-danger:hover {
    background: #d62c1a;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(231, 74, 59, 0.3);
}

.btn-action-success {
    background: var(--success);
    color: white;
}

.btn-action-success:hover {
    background: #17a673;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(28, 200, 138, 0.3);
}

/* Equipment Detail */
.equipment-detail-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    border: 1px solid var(--border);
}

.equipment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--border);
    margin-bottom: 20px;
}

.equipment-status {
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

.equipment-status.neuf,
.equipment-status.bon {
    background: var(--success-light);
    color: var(--success);
    border: 1px solid var(--success);
}

.equipment-status.moyen {
    background: var(--warning-light);
    color: var(--warning);
    border: 1px solid var(--warning);
}

.equipment-status.mauvais,
.equipment-status.hors_service {
    background: var(--danger-light);
    color: var(--danger);
    border: 1px solid var(--danger);
}

.info-row {
    display: flex;
    align-items: center;
    padding: 15px;
    background: var(--gray-light);
    border-radius: 10px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.info-row:hover {
    transform: translateX(5px);
    background: white;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.info-row i {
    font-size: 1.2rem;
    margin-right: 15px;
    width: 24px;
    text-align: center;
}

.info-row .label {
    display: block;
    font-size: 0.85rem;
    color: var(--gray);
    margin-bottom: 3px;
}

.info-row .value {
    display: block;
    font-weight: 600;
    color: #2d3748;
}

.description-box {
    background: var(--gray-light);
    padding: 20px;
    border-radius: 10px;
    border-left: 4px solid var(--primary);
    font-size: 0.95rem;
    line-height: 1.6;
}

.characteristics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.characteristic-item {
    background: white;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 12px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.characteristic-item:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.char-label {
    font-weight: 600;
    color: #2d3748;
}

.char-value {
    color: var(--gray);
    font-weight: 500;
}

/* Problem Detail */
.problem-detail-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    border: 1px solid var(--border);
}

.problem-header {
    padding-bottom: 20px;
    border-bottom: 2px solid var(--border);
    margin-bottom: 20px;
}

.content-section {
    margin-bottom: 25px;
}

.section-title {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    color: #2d3748;
    font-weight: 600;
}

.section-title i {
    margin-right: 10px;
}

.content-box {
    background: var(--gray-light);
    padding: 20px;
    border-radius: 10px;
    line-height: 1.6;
}

.rich-text {
    border-left: 4px solid var(--primary);
}

.analysis-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.analysis-item {
    background: white;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 20px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.analysis-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.analysis-item i {
    font-size: 1.5rem;
    margin-right: 15px;
}

.analysis-item .label {
    display: block;
    font-size: 0.85rem;
    color: var(--gray);
    margin-bottom: 5px;
}

.analysis-item .value {
    display: block;
    font-weight: 700;
    color: #2d3748;
    font-size: 1.1rem;
}

/* Validation Card */
.validation-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    border: 1px solid var(--border);
}

.validation-header {
    padding: 40px 20px;
    text-align: center;
    color: white;
}

.validation-header.validated {
    background: linear-gradient(135deg, var(--success) 0%, #17a673 100%);
}

.validation-header.rejected {
    background: linear-gradient(135deg, var(--danger) 0%, #c42e1e 100%);
}

.validator-info {
    display: flex;
    align-items: center;
    padding: 25px;
    background: var(--gray-light);
    border-radius: 10px;
    margin: 20px;
}

.validator-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
    margin-right: 20px;
}

.validation-comment {
    padding: 0 20px 20px;
}

.comment-box {
    background: var(--gray-light);
    padding: 20px;
    border-radius: 10px;
    border-left: 4px solid var(--primary);
    margin-top: 10px;
    line-height: 1.6;
}

.intervention-plan {
    padding: 0 20px 20px;
}

.planning-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.plan-item {
    display: flex;
    align-items: center;
    padding: 15px;
    background: var(--gray-light);
    border-radius: 10px;
}

.plan-item i {
    font-size: 1.2rem;
    margin-right: 15px;
}

/* Pending Validation */
.pending-validation {
    text-align: center;
    padding: 40px 20px;
}

.pending-icon {
    color: var(--warning);
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.time-indicator {
    max-width: 400px;
    margin: 0 auto;
}

.time-progress {
    height: 6px;
    background: var(--border);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 10px;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(to right, var(--success), var(--warning));
    border-radius: 3px;
    transition: width 1s ease;
}

.time-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    color: var(--gray);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px 20px;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    background: var(--warning-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.empty-state-icon i {
    font-size: 2rem;
    color: var(--warning);
}

/* Demandeur Info */
.info-card {
    display: flex;
    align-items: center;
    background: var(--gray-light);
    padding: 20px;
    border-radius: 12px;
    height: 100%;
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.info-card i {
    margin-right: 15px;
}

/* Responsive */
@media (max-width: 768px) {
    .nav-tabs-modern {
        padding: 0 15px;
    }

    .nav-tabs-modern .nav-link {
        padding: 15px;
        font-size: 0.9rem;
    }

    .stat-card {
        margin-bottom: 15px;
    }

    .actions-container .d-flex {
        flex-direction: column;
        gap: 10px;
    }

    .btn-action {
        width: 100%;
        justify-content: center;
    }

    .characteristics-grid {
        grid-template-columns: 1fr;
    }

    .analysis-grid {
        grid-template-columns: 1fr;
    }

    .planning-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips
    var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(function (tooltip) {
        return new bootstrap.Tooltip(tooltip);
    });

    // Effet d'apparition pour les cartes
    const cards = document.querySelectorAll('.card-modern, .timeline-content, .stat-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Animation pour les onglets
    const tabLinks = document.querySelectorAll('.nav-link');
    tabLinks.forEach(link => {
        link.addEventListener('click', function() {
            tabLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Affichage dynamique de la progression
    function updateProgressBar() {
        const progressBar = document.querySelector('.progress-bar');
        if (progressBar) {
            let width = 30; // Démarre à 30%
            const interval = setInterval(() => {
                if (width < 90) {
                    width += Math.random() * 5;
                    progressBar.style.width = width + '%';
                } else {
                    clearInterval(interval);
                }
            }, 1000);
        }
    }

    // Lancer l'animation si la demande est en attente
    if (document.querySelector('.pending-validation')) {
        updateProgressBar();
    }

    // Copier le numéro de demande
    const copyBtn = document.querySelector('.btn-copy');
    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            const num = '{{ $demande->Numero_Demande }}';
            navigator.clipboard.writeText(num);

            // Animation de confirmation
            const originalHTML = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check me-2"></i>Copié !';
            this.classList.add('btn-action-success');

            setTimeout(() => {
                this.innerHTML = originalHTML;
                this.classList.remove('btn-action-success');
            }, 2000);
        });
    }
});
</script>
@endsection
