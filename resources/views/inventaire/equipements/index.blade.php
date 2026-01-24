@extends('layouts.admin')

@section('title', 'Gestion des équipements')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', 'Inventaire des équipements')

@section('page-actions')
<div class="page-actions">
    <style>
        .page-actions {
    display: flex;          /* aligne les boutons sur une ligne */
    gap: 10px;              /* espace entre chaque bouton */
    flex-wrap: wrap;        /* permet de passer à la ligne si l’écran est petit */
}

.btn-action {
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    background-color: #1d4ed8; /* bleu bootstrap */
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: background-color 0.2s;
}

.btn-action i {
    margin-right: 6px; /* espace entre l’icône et le texte */
}

.btn-action:hover {
    background-color: #2563eb;
}

    </style>
    <a href="{{ route('inventaire.equipements.create') }}" class="btn-action">
        <i class="fas fa-plus"></i> Nouvel équipement
    </a>
    <a href="#" class="btn-action" onclick="event.preventDefault(); document.getElementById('rapportForm').submit();">
        <i class="fas fa-file-export"></i> Exporter
    </a>
    <form id="rapportForm" action="{{ route('inventaire.equipements.genererRapport') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="type_rapport" value="inventaire_complet">
        <input type="hidden" name="format" value="csv">
    </form>
</div>
@endsection


@section('content')
<div class="inventory-container">
    <!-- Filtres -->
    <div class="filters-card">
        <form method="GET" action="{{ route('inventaire.equipements.index') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <label for="search"><i class="fas fa-search"></i> Recherche</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="N° inv, série, marque, modèle...">
                </div>

                <div class="filter-group">
                    <label for="type_equipement_id"><i class="fas fa-tag"></i> Type</label>
                    <select id="type_equipement_id" name="type_equipement_id">
                        <option value="">Tous les types</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ request('type_equipement_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->libelle }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="etat"><i class="fas fa-battery"></i> État</label>
                    <select id="etat" name="etat">
                        <option value="">Tous les états</option>
                        <option value="neuf" {{ request('etat') == 'neuf' ? 'selected' : '' }}>Neuf</option>
                        <option value="bon" {{ request('etat') == 'bon' ? 'selected' : '' }}>Bon</option>
                        <option value="moyen" {{ request('etat') == 'moyen' ? 'selected' : '' }}>Moyen</option>
                        <option value="mauvais" {{ request('etat') == 'mauvais' ? 'selected' : '' }}>Mauvais</option>
                        <option value="hors_service" {{ request('etat') == 'hors_service' ? 'selected' : '' }}>Hors service</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="statut"><i class="fas fa-check-circle"></i> Statut</label>
                    <select id="statut" name="statut">
                        <option value="">Tous</option>
                        <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="hors_service" {{ request('statut') == 'hors_service' ? 'selected' : '' }}>Hors service</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="localisation_id"><i class="fas fa-map-marker-alt"></i> Localisation</label>
                    <select id="localisation_id" name="localisation_id">
                        <option value="">Toutes</option>
                        @foreach($localisations as $loc)
                            <option value="{{ $loc->id }}" {{ request('localisation_id') == $loc->id ? 'selected' : '' }}>
                                {{ $loc->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="fournisseur_id"><i class="fas fa-truck"></i> Fournisseur</label>
                    <select id="fournisseur_id" name="fournisseur_id">
                        <option value="">Tous</option>
                        @foreach($fournisseurs as $fournisseur)
                            <option value="{{ $fournisseur->id }}" {{ request('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                {{ $fournisseur->raison_sociale }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('inventaire.equipements.index') }}" class="btn-reset">
                        <i class="fas fa-redo"></i> Réinitialiser
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Statistiques -->
    <div class="stats-container">
        <div class="stat-card total">
            <div class="stat-icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="stat-content">
                <h3>Total</h3>
                <p class="stat-number">{{ $statistiques['total'] }}</p>
                <small>Équipements</small>
            </div>
        </div>

        <div class="stat-card active">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>Actifs</h3>
                <p class="stat-number">{{ $statistiques['actif'] }}</p>
                <small>En service</small>
            </div>
        </div>

        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <h3>Hors service</h3>
                <p class="stat-number">{{ $statistiques['hors_service'] }}</p>
                <small>À réparer/réformer</small>
            </div>
        </div>

        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-content">
                <h3>Sous garantie</h3>
                <p class="stat-number">{{ $statistiques['sous_garantie'] }}</p>
                <small>Garantie active</small>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="charts-grid">
        <!-- Graphique 1: Répartition par type -->
        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-chart-pie"></i> Par type d'équipement</h3>
                <div class="chart-legend" id="typeLegend"></div>
            </div>
            <div class="chart-container">
                <canvas id="typeChart"></canvas>
            </div>
        </div>

        <!-- Graphique 2: Répartition par état -->
        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-chart-bar"></i> Par état</h3>
                <div class="chart-legend" id="etatLegend"></div>
            </div>
            <div class="chart-container">
                <canvas id="etatChart"></canvas>
            </div>
        </div>

        <!-- Graphique 3: Âge des équipements -->
        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-chart-line"></i> Par âge</h3>
                <div class="chart-legend" id="ageLegend"></div>
            </div>
            <div class="chart-container">
                <canvas id="ageChart"></canvas>
            </div>
        </div>

        <!-- Graphique 4: Top localisations -->
        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-map-marked-alt"></i> Top localisations</h3>
            </div>
            <div class="chart-container">
                <canvas id="localisationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Liste des équipements -->
    <div class="table-card">
        @if(session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="table-header">
            <div class="table-info">
                <span>{{ $equipements->total() }} équipement(s) trouvé(s)</span>
            </div>
            <div class="table-actions">
                <button class="btn-export" onclick="exportToCSV()">
                    <i class="fas fa-download"></i> CSV
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>N° Inventaire</th>
                        <th>Équipement</th>
                        <th>Type</th>
                        <th>État</th>
                        <th>Localisation</th>
                        <th>Service</th>
                        <th>Date achat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipements as $equipement)
                        <tr class="{{ $equipement->etat == 'hors_service' ? 'disabled' : '' }}">
                            <td>
                                <div class="inventory-number">
                                    <i class="fas fa-barcode"></i>
                                    <strong>{{ $equipement->numero_inventaire }}</strong>
                                    @if($equipement->code_barres)
                                        <small>{{ $equipement->code_barres }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="equipment-info">
                                    <strong>{{ $equipement->marque }} {{ $equipement->modele }}</strong>
                                    <small>{{ $equipement->numero_serie }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge type-badge">
                                    {{ $equipement->typeEquipement->libelle ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $etatClasses = [
                                        'neuf' => 'state-new',
                                        'bon' => 'state-good',
                                        'moyen' => 'state-medium',
                                        'mauvais' => 'state-bad',
                                        'hors_service' => 'state-disabled'
                                    ];
                                    $etatLabels = [
                                        'neuf' => 'Neuf',
                                        'bon' => 'Bon',
                                        'moyen' => 'Moyen',
                                        'mauvais' => 'Mauvais',
                                        'hors_service' => 'Hors service'
                                    ];
                                @endphp
                                <span class="state-badge {{ $etatClasses[$equipement->etat] }}">
                                    <i class="fas fa-circle"></i>
                                    {{ $etatLabels[$equipement->etat] }}
                                </span>
                            </td>
                            <td>
                                @if($equipement->localisation)
                                    <div class="location-info">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $equipement->localisation->nom }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">Non localisé</span>
                                @endif
                            </td>
                            <td>
                                @if($equipement->serviceResponsable)
                                    <span class="service-badge">
                                        {{ $equipement->serviceResponsable->nom }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="date-info">
                                    {{ $equipement->date_achat->format('d/m/Y') }}
                                    @if($equipement->date_mise_service)
                                        <small>Mise service: {{ $equipement->date_mise_service->format('d/m/Y') }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('inventaire.equipements.show', $equipement) }}" 
                                       class="btn-action btn-view" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventaire.equipements.edit', $equipement) }}" 
                                       class="btn-action btn-edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($equipement->etat != 'hors_service')
                                        <button type="button" 
                                                class="btn-action btn-disable" 
                                                title="Marquer hors service"
                                                onclick="showReformeModal({{ $equipement->id }}, '{{ $equipement->numero_inventaire }}')">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-table">
                                <i class="fas fa-inbox"></i>
                                <p>Aucun équipement trouvé</p>
                                <a href="{{ route('inventaire.equipements.create') }}" class="btn-create">
                                    <i class="fas fa-plus"></i> Ajouter le premier équipement
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($equipements->hasPages())
            <div class="pagination-container">
                {{ $equipements->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal de réforme -->
<div id="reformeModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Marquer comme hors service</h3>
            <button type="button" class="modal-close" onclick="closeReformeModal()">&times;</button>
        </div>
        <form id="reformeForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <p>Vous allez marquer l'équipement <strong id="equipementName"></strong> comme hors service.</p>
                
                <div class="form-group">
                    <label for="motif_reforme">Motif de la réforme *</label>
                    <textarea id="motif_reforme" name="motif_reforme" 
                              rows="3" 
                              placeholder="Décrivez le motif de la réforme..."
                              required></textarea>
                </div>

                <div class="form-group">
                    <label for="date_reforme">Date de réforme *</label>
                    <input type="date" id="date_reforme" name="date_reforme" 
                           value="{{ date('Y-m-d') }}" 
                           required>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeReformeModal()">
                    Annuler
                </button>
                <button type="submit" class="btn-danger">
                    <i class="fas fa-ban"></i> Confirmer la réforme
                </button>
            </div>
        </form>
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
        --black: #000000;
        --dark-gray: #1a1a1a;
        --medium-gray: #333333;
        --light-gray: #f5f5f5;
        --white: #ffffff;
        --card-bg: #ffffff;
        --modal-overlay: rgba(0, 0, 0, 0.5);
    }

    .inventory-container {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    /* Filtres */
    .filters-card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-group label {
        font-weight: 600;
        color: var(--dark-gray);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-group input,
    .filter-group select {
        padding: 10px 15px;
        border: 2px solid var(--light-gray);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: var(--white);
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(3, 81, 188, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-filter {
        background: var(--primary-color);
        color: var(--white);
        padding: 10px 20px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    .btn-reset {
        background: var(--light-gray);
        color: var(--medium-gray);
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        background: var(--medium-gray);
        color: var(--white);
        transform: translateY(-2px);
    }

    /* Statistiques */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .stat-card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card.total {
        border-left: 5px solid var(--primary-color);
    }

    .stat-card.active {
        border-left: 5px solid var(--success-color);
    }

    .stat-card.warning {
        border-left: 5px solid var(--warning-color);
    }

    .stat-card.success {
        border-left: 5px solid var(--info-color);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: var(--white);
    }

    .total .stat-icon {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    }

    .active .stat-icon {
        background: linear-gradient(135deg, var(--success-color), #34d399);
    }

    .warning .stat-icon {
        background: linear-gradient(135deg, var(--warning-color), #fbbf24);
    }

    .success .stat-icon {
        background: linear-gradient(135deg, var(--info-color), #60a5fa);
    }

    .stat-content h3 {
        margin: 0;
        font-size: 14px;
        color: var(--medium-gray);
        font-weight: 600;
    }

    .stat-number {
        margin: 5px 0;
        font-size: 28px;
        font-weight: 700;
        color: var(--dark-gray);
    }

    .stat-content small {
        font-size: 12px;
        color: var(--medium-gray);
    }

    /* Graphiques */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 25px;
    }

    .chart-card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .chart-header {
        margin-bottom: 20px;
    }

    .chart-header h3 {
        color: var(--dark-gray);
        margin: 0 0 15px 0;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 12px;
    }

    .chart-legend span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .chart-legend span::before {
        content: '';
        width: 10px;
        height: 10px;
        border-radius: 2px;
        display: inline-block;
    }

    .chart-container {
        height: 250px;
        position: relative;
    }

    /* Table */
    .table-card {
        background: var(--card-bg);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .success-message {
        background: linear-gradient(135deg, var(--success-color), #059669);
        color: var(--white);
        padding: 15px 25px;
        border-radius: 8px;
        margin: 20px;
        text-align: center;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .error-message {
        background: linear-gradient(135deg, var(--danger-color), #dc2626);
        color: var(--white);
        padding: 15px 25px;
        border-radius: 8px;
        margin: 20px;
        text-align: center;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .table-header {
        padding: 20px;
        border-bottom: 1px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--light-gray);
    }

    .table-info {
        font-weight: 600;
        color: var(--dark-gray);
    }

    .table-actions {
        display: flex;
        gap: 10px;
    }

    .btn-export {
        background: var(--success-color);
        color: var(--white);
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-export:hover {
        background: #059669;
        transform: translateY(-2px);
    }

    .table-responsive {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead {
        background: var(--light-gray);
    }

    .data-table th {
        padding: 15px 20px;
        text-align: left;
        font-weight: 600;
        color: var(--dark-gray);
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(0, 0, 0, 0.1);
    }

    .data-table td {
        padding: 15px 20px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        vertical-align: middle;
    }

    .data-table tbody tr {
        transition: background-color 0.3s ease;
    }

    .data-table tbody tr:hover {
        background-color: rgba(3, 81, 188, 0.05);
    }

    .data-table tbody tr.disabled {
        opacity: 0.6;
        background-color: rgba(239, 68, 68, 0.05);
    }

    .inventory-number {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .inventory-number i {
        color: var(--primary-color);
        font-size: 14px;
    }

    .inventory-number small {
        font-size: 11px;
        color: var(--medium-gray);
    }

    .equipment-info {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .equipment-info small {
        font-size: 12px;
        color: var(--medium-gray);
    }

    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .type-badge {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info-color);
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .state-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .state-new {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .state-good {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info-color);
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .state-medium {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning-color);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .state-bad {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger-color);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .state-disabled {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
        border: 1px solid rgba(107, 114, 128, 0.3);
    }

    .state-badge i {
        font-size: 8px;
    }

    .location-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .location-info i {
        color: var(--primary-color);
        font-size: 14px;
    }

    .service-badge {
        padding: 4px 10px;
        background: rgba(3, 81, 188, 0.1);
        color: var(--primary-color);
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .date-info {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .date-info small {
        font-size: 11px;
        color: var(--medium-gray);
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

   
    .btn-view {
        background: linear-gradient(135deg, var(--info-color), #60a5fa);
    }

    .btn-view:hover {
        background: linear-gradient(135deg, #2563eb, var(--info-color));
        transform: scale(1.05);
    }

    .btn-edit {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        transform: scale(1.05);
    }

    .btn-disable {
        background: linear-gradient(135deg, var(--danger-color), #f87171);
    }

    .btn-disable:hover {
        background: linear-gradient(135deg, #dc2626, var(--danger-color));
        transform: scale(1.05);
    }

    .empty-table {
        text-align: center;
        padding: 60px 20px !important;
    }

    .empty-table i {
        font-size: 48px;
        color: var(--light-gray);
        margin-bottom: 15px;
    }

    .empty-table p {
        color: var(--medium-gray);
        margin-bottom: 20px;
        font-size: 16px;
    }

    .btn-create {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: var(--white);
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(3, 81, 188, 0.3);
    }

    .pagination-container {
        padding: 20px;
        border-top: 1px solid var(--light-gray);
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 5px;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        border-color: var(--primary-color);
        color: var(--white);
    }

    .pagination .page-link {
        padding: 8px 15px;
        border: 1px solid var(--light-gray);
        border-radius: 8px;
        color: var(--primary-color);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background-color: rgba(3, 81, 188, 0.1);
        border-color: var(--primary-color);
    }

    /* Page actions */
    .btn-action {
        background: var(--primary-color);
        color: var(--white);
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-action:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(3, 81, 188, 0.3);
    }

    /* Modal */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: var(--modal-overlay);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: var(--white);
        border-radius: 16px;
        width: 90%;
        max-width: 500px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        padding: 20px;
        border-bottom: 2px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        color: var(--primary-color);
        margin: 0;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: var(--medium-gray);
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        color: var(--danger-color);
    }

    .modal-body {
        padding: 20px;
    }

    .modal-body .form-group {
        margin-bottom: 20px;
    }

    .modal-body label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark-gray);
    }

    .modal-body textarea,
    .modal-body input[type="date"] {
        width: 100%;
        padding: 10px;
        border: 2px solid var(--light-gray);
        border-radius: 8px;
        font-size: 14px;
    }

    .modal-body textarea:focus,
    .modal-body input[type="date"]:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(3, 81, 188, 0.1);
    }

    .modal-actions {
        padding: 20px;
        border-top: 2px solid var(--light-gray);
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn-secondary {
        background: var(--light-gray);
        color: var(--medium-gray);
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: var(--medium-gray);
        color: var(--white);
    }

    .btn-danger {
        background: var(--danger-color);
        color: var(--white);
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }

        .stats-container {
            grid-template-columns: repeat(2, 1fr);
        }

        .table-header {
            flex-direction: column;
            gap: 15px;
            align-items: stretch;
        }

        .data-table th,
        .data-table td {
            padding: 10px 15px;
        }

        .action-buttons {
            flex-direction: column;
            align-items: center;
        }

        .btn-action {
            width: 30px;
            height: 30px;
        }
    }

    @media (max-width: 576px) {
        .stats-container {
            grid-template-columns: 1fr;
        }

        .stat-card {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }

        .stat-number {
            font-size: 24px;
        }

        .modal-content {
            width: 95%;
            margin: 10px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Données pour les graphiques
        const stats = @json($statistiques);

        // 1. Graphique par type (camembert)
        const typeData = {
            labels: Object.keys(stats.par_type),
            datasets: [{
                data: Object.values(stats.par_type),
                backgroundColor: generateColors(Object.keys(stats.par_type).length, 0.7),
                borderColor: generateColors(Object.keys(stats.par_type).length, 1),
                borderWidth: 2,
                hoverOffset: 15
            }]
        };

        const typeChart = new Chart(document.getElementById('typeChart'), {
            type: 'pie',
            data: typeData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = stats.total;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        // Légende pour le graphique par type
        const typeLegend = document.getElementById('typeLegend');
        Object.keys(stats.par_type).forEach((label, index) => {
            const span = document.createElement('span');
            span.innerHTML = `<span style="background-color: ${typeData.datasets[0].backgroundColor[index]};"></span> ${label}`;
            typeLegend.appendChild(span);
        });

        // 2. Graphique par état (barres)
        const etatData = {
            labels: Object.keys(stats.par_etat),
            datasets: [{
                label: 'Nombre d\'équipements',
                data: Object.values(stats.par_etat),
                backgroundColor: [
                    'rgba(16, 185, 129, 0.7)',  // Neuf
                    'rgba(59, 130, 246, 0.7)',   // Bon
                    'rgba(245, 158, 11, 0.7)',   // Moyen
                    'rgba(239, 68, 68, 0.7)',    // Mauvais
                    'rgba(107, 114, 128, 0.7)'   // Hors service
                ],
                borderColor: [
                    'rgb(16, 185, 129)',
                    'rgb(59, 130, 246)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)',
                    'rgb(107, 114, 128)'
                ],
                borderWidth: 2,
                borderRadius: 8
            }]
        };

        const etatChart = new Chart(document.getElementById('etatChart'), {
            type: 'bar',
            data: etatData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre d\'équipements'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // 3. Graphique par âge (ligne)
        const ageData = {
            labels: Object.keys(stats.par_age),
            datasets: [{
                label: 'Nombre d\'équipements',
                data: Object.values(stats.par_age),
                borderColor: 'rgb(3, 81, 188)',
                backgroundColor: 'rgba(3, 81, 188, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true
            }]
        };

        const ageChart = new Chart(document.getElementById('ageChart'), {
            type: 'line',
            data: ageData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre d\'équipements'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // 4. Graphique par localisation (doughnut)
        const localisationLabels = Object.keys(stats.par_localisation).slice(0, 5);
        const localisationData = Object.values(stats.par_localisation).slice(0, 5);

        const localisationChart = new Chart(document.getElementById('localisationChart'), {
            type: 'doughnut',
            data: {
                labels: localisationLabels,
                datasets: [{
                    data: localisationData,
                    backgroundColor: generateColors(localisationLabels.length, 0.7),
                    borderColor: generateColors(localisationLabels.length, 1),
                    borderWidth: 2,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // Fonction pour générer des couleurs
        function generateColors(count, alpha = 1) {
            const colors = [];
            const hueStep = 360 / count;
            
            for (let i = 0; i < count; i++) {
                const hue = i * hueStep;
                colors.push(`hsla(${hue}, 70%, 50%, ${alpha})`);
            }
            
            return colors;
        }

        // Export CSV
        window.exportToCSV = function() {
            const form = document.getElementById('filterForm');
            const originalAction = form.action;
            const originalMethod = form.method;
            
            form.action = '{{ route("inventaire.equipements.genererRapport") }}';
            form.method = 'POST';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const rapportType = document.createElement('input');
            rapportType.type = 'hidden';
            rapportType.name = 'type_rapport';
            rapportType.value = 'inventaire_complet';
            form.appendChild(rapportType);
            
            const format = document.createElement('input');
            format.type = 'hidden';
            format.name = 'format';
            format.value = 'csv';
            form.appendChild(format);
            
            form.submit();
            
            // Restaurer le formulaire original
            setTimeout(() => {
                form.removeChild(csrfToken);
                form.removeChild(rapportType);
                form.removeChild(format);
                form.action = originalAction;
                form.method = originalMethod;
            }, 100);
        };

        // Modal de réforme
        window.showReformeModal = function(id, name) {
            document.getElementById('equipementName').textContent = name;
            document.getElementById('reformeForm').action = `/inventaire/equipements/${id}`;
            document.getElementById('reformeModal').style.display = 'flex';
        };

        window.closeReformeModal = function() {
            document.getElementById('reformeModal').style.display = 'none';
            document.getElementById('motif_reforme').value = '';
            document.getElementById('date_reforme').value = new Date().toISOString().split('T')[0];
        };

        // Fermer la modal en cliquant en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('reformeModal');
            if (event.target === modal) {
                closeReformeModal();
            }
        };

        // Validation du formulaire de réforme
        document.getElementById('reformeForm').addEventListener('submit', function(e) {
            const motif = document.getElementById('motif_reforme').value.trim();
            const date = document.getElementById('date_reforme').value;
            
            if (!motif) {
                e.preventDefault();
                Swal.fire({
                    title: 'Erreur',
                    text: 'Veuillez saisir le motif de la réforme.',
                    icon: 'error',
                    confirmButtonColor: '#0351BC'
                });
                return;
            }
            
            if (!date) {
                e.preventDefault();
                Swal.fire({
                    title: 'Erreur',
                    text: 'Veuillez sélectionner une date de réforme.',
                    icon: 'error',
                    confirmButtonColor: '#0351BC'
                });
                return;
            }
            
            // Confirmation
            e.preventDefault();
            Swal.fire({
                title: 'Confirmer la réforme',
                text: 'Êtes-vous sûr de vouloir marquer cet équipement comme hors service ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, réformer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        // Animation des statistiques
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endpush