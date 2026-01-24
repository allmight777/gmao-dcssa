@extends('layouts.admin')

@section('title', 'Détails du service')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', 'Détails du service')

@section('page-actions')
<a href="{{ route('admin.services.index') }}" class="btn-return">
    <i class="fas fa-arrow-left"></i> Retour à la liste
</a>
@endsection

@section('content')
<div class="service-details-container">
    <!-- En-tête du service -->
    <div class="service-header">
        <div class="service-badge">
            <i class="fas fa-building fa-2x"></i>
        </div>
        <div class="service-info">
            <h1>{{ $service->nom }}</h1>
            <div class="service-meta">
                <span class="service-type badge badge-type">{{ ucfirst($service->type) }}</span>
                @if($service->code_geographique)
                    <span class="service-code">
                        <i class="fas fa-hashtag"></i> {{ $service->code_geographique }}
                    </span>
                @endif
                @if($service->parent)
                    <span class="service-parent">
                        <i class="fas fa-level-up-alt"></i> {{ $service->parent->nom }}
                    </span>
                @endif
            </div>
        </div>
        <div class="service-actions">
            <a href="{{ route('admin.services.edit', $service) }}" class="btn-edit">
                <i class="fas fa-edit"></i> Modifier
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $statistiques['total_personnel'] }}</h3>
                <p>Personnel total</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-sitemap"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $statistiques['total_sous_services'] }}</h3>
                <p>Sous-services</p>
            </div>
        </div>
        
        @if($service->responsable)
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $service->responsable->nom_complet ?? $service->responsable->name }}</h3>
                <p>Responsable</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Onglets -->
    <div class="details-tabs">
        <ul class="tabs-nav">
            <li class="active" data-tab="info"><i class="fas fa-info-circle"></i> Informations</li>
            <li data-tab="personnel"><i class="fas fa-users"></i> Personnel</li>
            <li data-tab="sous-services"><i class="fas fa-sitemap"></i> Sous-services</li>
            <li data-tab="historique"><i class="fas fa-history"></i> Historique</li>
        </ul>

        <div class="tabs-content">
            <!-- Onglet Informations -->
            <div id="tab-info" class="tab-pane active">
                <div class="info-grid">
                    <div class="info-section">
                        <h3><i class="fas fa-info-circle"></i> Informations de base</h3>
                        <div class="info-item">
                            <label>Type :</label>
                            <span>{{ ucfirst($service->type) }}</span>
                        </div>
                        <div class="info-item">
                            <label>Code géographique :</label>
                            <span>{{ $service->code_geographique ?? 'Non défini' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Parent :</label>
                            <span>
                                @if($service->parent)
                                    <a href="{{ route('admin.services.show', $service->parent) }}" class="link-parent">
                                        {{ $service->parent->nom }}
                                    </a>
                                @else
                                    Aucun (Racine)
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <label>Date de création :</label>
                            <span>{{ $service->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="info-section">
                        <h3><i class="fas fa-user-tie"></i> Responsable</h3>
                        @if($service->responsable)
                            <div class="responsable-card">
                                <div class="responsable-avatar">
                                    <i class="fas fa-user-circle fa-3x"></i>
                                </div>
                                <div class="responsable-details">
                                    <h4>{{ $service->responsable->nom_complet ?? $service->responsable->name }}</h4>
                                    <p>{{ $service->responsable->email }}</p>
                                    @if($service->responsable->telephone)
                                        <p><i class="fas fa-phone"></i> {{ $service->responsable->telephone }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="no-responsable">
                                <i class="fas fa-user-slash fa-2x"></i>
                                <p>Aucun responsable assigné</p>
                            </div>
                        @endif
                    </div>

                    <div class="info-section">
                        <h3><i class="fas fa-address-card"></i> Coordonnées</h3>
                        @if($service->adresse)
                            <div class="info-item">
                                <label>Adresse :</label>
                                <span>{{ $service->adresse }}</span>
                            </div>
                        @endif
                        @if($service->telephone)
                            <div class="info-item">
                                <label>Téléphone :</label>
                                <span>{{ $service->telephone }}</span>
                            </div>
                        @endif
                        @if($service->description)
                            <div class="info-item full-width">
                                <label>Description :</label>
                                <div class="description-text">{{ $service->description }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Onglet Personnel -->
            <div id="tab-personnel" class="tab-pane">
                @if($service->personnel && $service->personnel->count() > 0)
                    <div class="personnel-table-container">
                        <table class="personnel-table">
                            <thead>
                                <tr>
                                    <th>Nom & Prénom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Fonction</th>
                                    <th>Date d'affectation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($service->personnel as $personne)
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <div class="user-avatar">
                                                <i class="fas fa-user-circle"></i>
                                            </div>
                                            <div class="user-details">
                                                <strong>{{ $personne->nom_complet ?? $personne->name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $personne->email }}</td>
                                    <td>{{ $personne->telephone ?? 'Non défini' }}</td>
                                    <td>
                                        <span class="badge badge-function">
                                            {{ $personne->pivot->fonction_service ?? 'Non défini' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $personne->pivot->date_affectation ? \Carbon\Carbon::parse($personne->pivot->date_affectation)->format('d/m/Y') : '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-users fa-3x"></i>
                        <h3>Aucun personnel assigné</h3>
                        <p>Ce service ne compte encore aucun membre de personnel.</p>
                    </div>
                @endif
            </div>

            <!-- Onglet Sous-services -->
            <div id="tab-sous-services" class="tab-pane">
                @if($service->sousServices && $service->sousServices->count() > 0)
                    <div class="subservices-grid">
                        @foreach($service->sousServices as $sousService)
                        <div class="subservice-card">
                            <div class="subservice-header">
                                <i class="fas fa-building"></i>
                                <h4>{{ $sousService->nom }}</h4>
                            </div>
                            <div class="subservice-body">
                                <div class="subservice-meta">
                                    <span class="badge badge-type">{{ ucfirst($sousService->type) }}</span>
                                    @if($sousService->code_geographique)
                                        <span class="subservice-code">{{ $sousService->code_geographique }}</span>
                                    @endif
                                </div>
                                <div class="subservice-stats">
                                    <span>
                                        <i class="fas fa-users"></i>
                                        {{ $sousService->utilisateurs->count() ?? 0 }} personnel
                                    </span>
                                    <span>
                                        <i class="fas fa-sitemap"></i>
                                        {{ $sousService->children->count() ?? 0 }} sous-services
                                    </span>
                                </div>
                            </div>
                            <div class="subservice-actions">
                                <a href="{{ route('admin.services.show', $sousService) }}" class="btn-view">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                <a href="{{ route('admin.services.edit', $sousService) }}" class="btn-edit-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-sitemap fa-3x"></i>
                        <h3>Aucun sous-service</h3>
                        <p>Ce service n'a pas encore de sous-services.</p>
                        <a href="{{ route('admin.services.create') }}?parent_id={{ $service->id }}" class="btn-submit">
                            <i class="fas fa-plus"></i> Créer un sous-service
                        </a>
                    </div>
                @endif
            </div>

            <!-- Onglet Historique -->
            <div id="tab-historique" class="tab-pane">
                @if($statistiques['dernieres_affectations']->count() > 0)
                    <div class="timeline">
                        @foreach($statistiques['dernieres_affectations'] as $affectation)
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <h4>Nouvelle affectation</h4>
                                    <span class="timeline-date">
                                        {{ \Carbon\Carbon::parse($affectation->pivot->date_affectation)->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <div class="timeline-body">
                                    <p>
                                        <strong>{{ $affectation->nom_complet ?? $affectation->name }}</strong>
                                        a été affecté(e) au service en tant que 
                                        <strong>{{ $affectation->pivot->fonction_service ?? 'Membre' }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-history fa-3x"></i>
                        <h3>Aucun historique disponible</h3>
                        <p>Les activités récentes apparaîtront ici.</p>
                    </div>
                @endif
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
        --black: #000000;
        --dark-gray: #1a1a1a;
        --medium-gray: #333333;
        --light-gray: #f5f5f5;
        --white: #ffffff;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --card-bg: #ffffff;
    }

    .service-details-container {
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .service-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: var(--white);
        padding: 40px;
        display: flex;
        align-items: center;
        gap: 30px;
    }

    .service-badge {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .service-info {
        flex: 1;
    }

    .service-info h1 {
        font-size: 32px;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .service-meta {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .badge-type {
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .service-code, .service-parent {
        background: rgba(255, 255, 255, 0.1);
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .service-actions {
        display: flex;
        gap: 10px;
    }

    .btn-edit {
        background: var(--white);
        color: var(--primary-color);
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-edit:hover {
        background: var(--light-gray);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Statistiques */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        padding: 30px;
        background: var(--light-gray);
    }

    .stat-card {
        background: var(--white);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        background: var(--primary-light);
        color: var(--white);
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-content h3 {
        font-size: 28px;
        margin: 0;
        color: var(--primary-color);
    }

    .stat-content p {
        margin: 5px 0 0;
        color: var(--medium-gray);
        font-size: 14px;
    }

    /* Onglets */
    .details-tabs {
        padding: 30px;
    }

    .tabs-nav {
        display: flex;
        gap: 1px;
        background: var(--light-gray);
        border-radius: 10px 10px 0 0;
        overflow: hidden;
    }

    .tabs-nav li {
        flex: 1;
        background: var(--white);
        padding: 20px;
        text-align: center;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-weight: 600;
        color: var(--medium-gray);
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
    }

    .tabs-nav li.active {
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
        background: #f8fafc;
    }

    .tabs-nav li:hover:not(.active) {
        background: #f8fafc;
        color: var(--primary-light);
    }

    .tabs-content {
        background: var(--white);
        border-radius: 0 0 10px 10px;
        overflow: hidden;
    }

    .tab-pane {
        display: none;
        padding: 30px;
    }

    .tab-pane.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Informations */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
    }

    .info-section {
        background: var(--light-gray);
        border-radius: 12px;
        padding: 25px;
    }

    .info-section h3 {
        color: var(--primary-color);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
    }

    .info-item {
        margin-bottom: 15px;
        display: grid;
        grid-template-columns: 150px 1fr;
        gap: 10px;
        align-items: start;
    }

    .info-item.full-width {
        grid-template-columns: 1fr;
    }

    .info-item label {
        font-weight: 600;
        color: var(--dark-gray);
    }

    .description-text {
        background: var(--white);
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        line-height: 1.6;
    }

    /* Responsable */
    .responsable-card {
        display: flex;
        align-items: center;
        gap: 20px;
        background: var(--white);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .responsable-avatar {
        color: var(--primary-color);
    }

    .responsable-details h4 {
        margin: 0 0 5px;
        color: var(--dark-gray);
    }

    .responsable-details p {
        margin: 5px 0;
        color: var(--medium-gray);
        font-size: 14px;
    }

    .no-responsable {
        text-align: center;
        padding: 30px;
        color: var(--medium-gray);
    }

    /* Personnel */
    .personnel-table-container {
        overflow-x: auto;
    }

    .personnel-table {
        width: 100%;
        border-collapse: collapse;
    }

    .personnel-table th {
        background: var(--light-gray);
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--dark-gray);
        border-bottom: 2px solid #e5e7eb;
    }

    .personnel-table td {
        padding: 15px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        color: var(--primary-color);
        font-size: 20px;
    }

    .badge-function {
        background: var(--primary-light);
        color: var(--white);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }

    /* Sous-services */
    .subservices-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .subservice-card {
        background: var(--white);
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .subservice-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .subservice-header {
        background: var(--light-gray);
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .subservice-header i {
        color: var(--primary-color);
        font-size: 20px;
    }

    .subservice-header h4 {
        margin: 0;
        font-size: 16px;
    }

    .subservice-body {
        padding: 20px;
    }

    .subservice-meta {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .subservice-stats {
        display: flex;
        gap: 20px;
        color: var(--medium-gray);
        font-size: 14px;
    }

    .subservice-stats i {
        margin-right: 5px;
    }

    .subservice-actions {
        padding: 15px 20px;
        background: var(--light-gray);
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        border-top: 1px solid #e5e7eb;
    }

    .btn-view, .btn-edit-sm {
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-view {
        background: var(--primary-color);
        color: var(--white);
    }

    .btn-edit-sm {
        background: var(--white);
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }

    /* Historique */
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--primary-light);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-icon {
        position: absolute;
        left: -40px;
        background: var(--white);
        border: 2px solid var(--primary-light);
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
    }

    .timeline-content {
        background: var(--light-gray);
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid var(--primary-color);
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .timeline-date {
        color: var(--medium-gray);
        font-size: 14px;
    }

    /* États vides */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--medium-gray);
    }

    .empty-state i {
        margin-bottom: 20px;
        color: var(--primary-light);
    }

    .empty-state h3 {
        margin-bottom: 10px;
        color: var(--dark-gray);
    }

    .btn-return {
        background: var(--white);
        color: var(--medium-gray);
        padding: 10px 20px;
        border: 2px solid var(--light-gray);
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-return:hover {
        background: var(--light-gray);
        border-color: var(--medium-gray);
        transform: translateY(-1px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .service-header {
            flex-direction: column;
            text-align: center;
            padding: 30px 20px;
        }

        .service-meta {
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            padding: 20px;
        }

        .tabs-nav {
            flex-direction: column;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-item {
            grid-template-columns: 1fr;
            gap: 5px;
        }

        .subservices-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion des onglets
        const tabs = document.querySelectorAll('.tabs-nav li');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Désactiver tous les onglets
                tabs.forEach(t => t.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));
                
                // Activer l'onglet courant
                this.classList.add('active');
                document.getElementById(`tab-${tabId}`).classList.add('active');
            });
        });

        // Afficher une alerte de confirmation avant la suppression
        const deleteBtns = document.querySelectorAll('.btn-delete');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                
                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "Cette action est irréversible !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0351BC',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, supprimer !',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        });
    });
</script>
@endpush