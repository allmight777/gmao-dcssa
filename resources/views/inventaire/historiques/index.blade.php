@extends('layouts.admin')

@section('title', 'Historique des Mouvements')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', 'Historique des Mouvements')

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
<a href="{{ route('inventaire.historiques.export', request()->all()) }}"
   class="btn btn-primary">
    <i class="fas fa-file-excel"></i> Exporter CSV
</a>

</div>
@endsection

@section('content')
<div class="inventory-container">
    <!-- Filtres -->
    <div class="filters-card">
        <form method="GET" action="{{ route('inventaire.historiques.index') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <label for="date_debut"><i class="fas fa-calendar-alt"></i> Date début</label>
                    <input type="date" id="date_debut" name="date_debut"
                           value="{{ $dateDebut }}"
                           placeholder="Date début">
                </div>

                <div class="filter-group">
                    <label for="date_fin"><i class="fas fa-calendar-alt"></i> Date fin</label>
                    <input type="date" id="date_fin" name="date_fin"
                           value="{{ $dateFin }}"
                           placeholder="Date fin">
                </div>

                <div class="filter-group">
                    <label for="equipement_id"><i class="fas fa-laptop-medical"></i> Équipement</label>
                    <select id="equipement_id" name="equipement_id">
                        <option value="">Tous les équipements</option>
                        @foreach($equipements as $equip)
                            <option value="{{ $equip->id }}" {{ $equipementId == $equip->id ? 'selected' : '' }}>
                                {{ $equip->numero_inventaire }} - {{ $equip->marque }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="motif"><i class="fas fa-tag"></i> Motif</label>
                    <select id="motif" name="motif">
                        <option value="">Tous les motifs</option>
                        @foreach($motifs as $m)
                            <option value="{{ $m }}" {{ $motif == $m ? 'selected' : '' }}>
                                {{ $m }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="operateur_id"><i class="fas fa-user-tie"></i> Opérateur</label>
                    <select id="operateur_id" name="operateur_id">
                        <option value="">Tous les opérateurs</option>
                        @foreach($operateurs as $op)
                            <option value="{{ $op->id }}" {{ $operateurId == $op->id ? 'selected' : '' }}>
                                {{ $op->nom }} {{ $op->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="per_page"><i class="fas fa-list"></i> Par page</label>
                    <select id="per_page" name="per_page">
                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                        <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('inventaire.historiques.index') }}" class="btn-reset">
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
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="stat-content">
                <h3>Total Mouvements</h3>
                <p class="stat-number">{{ number_format($stats['total_mouvements']) }}</p>
                <small>{{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</small>
            </div>
        </div>

        <div class="stat-card active">
            <div class="stat-icon">
                <i class="fas fa-laptop-medical"></i>
            </div>
            <div class="stat-content">
                <h3>Équipements Déplacés</h3>
                <p class="stat-number">{{ number_format($stats['equipements_uniques']) }}</p>
                <small>Taux: {{ $stats['taux_changement'] }}%</small>
            </div>
        </div>

        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <h3>Moyenne par Jour</h3>
                <p class="stat-number">{{ $stats['moyenne_par_jour'] }}</p>
                <small>Mouvements/jour</small>
            </div>
        </div>

        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-content">
                <h3>Opérateurs Actifs</h3>
                <p class="stat-number">{{ number_format($stats['operateurs_uniques']) }}</p>
                <small>Utilisateurs</small>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="charts-grid">
        <!-- Graphique 1: Répartition par motif -->
        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-chart-pie"></i> Répartition par Motif</h3>
                <div class="chart-legend" id="motifLegend"></div>
            </div>
            <div class="chart-container">
                <canvas id="motifChart"></canvas>
            </div>
        </div>

        <!-- Graphique 2: Évolution mensuelle -->
        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-chart-line"></i> Évolution Mensuelle</h3>
            </div>
            <div class="chart-container">
                <canvas id="mouvementsParMoisChart"></canvas>
            </div>
        </div>

        <!-- Graphique 3: Top équipements -->
        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-medal"></i> Top Équipements</h3>
            </div>
            <div class="chart-container">
                <canvas id="topEquipementsChart"></canvas>
            </div>
        </div>

        <!-- Graphique 4: Top opérateurs -->
        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-user-check"></i> Top Opérateurs</h3>
            </div>
            <div class="chart-container">
                <canvas id="operateursChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Liste des mouvements -->
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
                <span>{{ $mouvements->total() }} mouvement(s) trouvé(s)</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="120">Date</th>
                        <th>Équipement</th>
                        <th>Ancienne Localisation</th>
                        <th><i class="fas fa-arrow-right text-primary"></i></th>
                        <th>Nouvelle Localisation</th>
                        <th>Motif</th>
                        <th>Opérateur</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mouvements as $mouvement)
                        <tr class="fade-in">
                            <td>
                                <div class="date-info">
                                    <strong>{{ $mouvement->date_mouvement->format('d/m/Y') }}</strong>
                                    <small>{{ $mouvement->date_mouvement->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('inventaire.equipements.show', $mouvement->equipement_id) }}"
                                   class="equipment-info text-decoration-none">
                                    <strong>{{ $mouvement->equipement->numero_inventaire }}</strong>
                                    <small>{{ $mouvement->equipement->marque }} {{ $mouvement->equipement->modele }}</small>
                                </a>
                                <div class="badge type-badge">
                                    {{ $mouvement->equipement->typeEquipement->nom ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                @if($mouvement->ancienneLocalisation)
                                    <div class="location-info">
                                        <i class="fas fa-map-marker-alt text-danger"></i>
                                        <div>
                                            <strong>{{ $mouvement->ancienneLocalisation->nom }}</strong>
                                            <small>
                                                {{ $mouvement->ancienneLocalisation->batiment ?? '' }}
                                                @if($mouvement->ancienneLocalisation->etage)
                                                    - Étage {{ $mouvement->ancienneLocalisation->etage }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Aucune</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <i class="fas fa-arrow-right fa-lg text-primary"></i>
                            </td>
                            <td>
                                @if($mouvement->nouvelleLocalisation)
                                    <div class="location-info">
                                        <i class="fas fa-map-marker-alt text-success"></i>
                                        <div>
                                            <strong>{{ $mouvement->nouvelleLocalisation->nom }}</strong>
                                            <small>
                                                {{ $mouvement->nouvelleLocalisation->batiment ?? '' }}
                                                @if($mouvement->nouvelleLocalisation->etage)
                                                    - Étage {{ $mouvement->nouvelleLocalisation->etage }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Aucune</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = 'state-badge state-new';
                                    if(str_contains(strtolower($mouvement->motif), 'retrait')) {
                                        $badgeClass = 'state-badge state-medium';
                                    } elseif(str_contains(strtolower($mouvement->motif), 'affectation')) {
                                        $badgeClass = 'state-badge state-good';
                                    } elseif(str_contains(strtolower($mouvement->motif), 'changement')) {
                                        $badgeClass = 'state-badge state-new';
                                    }
                                @endphp
                                <span class="{{ $badgeClass }}">
                                    {{ $mouvement->motif }}
                                </span>
                            </td>
                            <td>
                                <div class="user-info">
                                    <i class="fas fa-user text-muted"></i>
                                    <div>
                                        <strong>{{ $mouvement->operateur->nomComplet ?? ($mouvement->operateur->nom . ' ' . $mouvement->operateur->prenom) }}</strong>
                                        <small>{{ $mouvement->operateur->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('inventaire.historiques.show', $mouvement->id) }}"
                                       class="btn-action btn-view" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-table">
                                <i class="fas fa-inbox"></i>
                                <p>Aucun mouvement trouvé pour les critères sélectionnés</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($mouvements->hasPages())
            <div class="pagination-container">
                {{ $mouvements->appends(request()->all())->links() }}
            </div>
        @endif
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

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Styles spécifiques pour l'historique */
    .date-info {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .date-info small {
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

    .type-badge {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info-color);
        border: 1px solid rgba(59, 130, 246, 0.3);
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        margin-top: 5px;
        display: inline-block;
    }

    .location-info {
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .location-info i {
        font-size: 14px;
        margin-top: 3px;
    }

    .location-info div {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .location-info small {
        font-size: 11px;
        color: var(--medium-gray);
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

    .user-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .user-info i {
        color: var(--primary-color);
        font-size: 14px;
    }

    .user-info div {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .user-info strong {
        font-size: 13px;
    }

    .user-info small {
        font-size: 11px;
        color: var(--medium-gray);
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--white);
        text-decoration: none;
    }

    .btn-view {
        background: linear-gradient(135deg, var(--info-color), #60a5fa);
    }

    .btn-view:hover {
        background: linear-gradient(135deg, #2563eb, var(--info-color));
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

        .data-table th,
        .data-table td {
            padding: 10px 15px;
        }

        .action-buttons {
            flex-direction: column;
            align-items: center;
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
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique par motif
        const motifCtx = document.getElementById('motifChart');
        if (motifCtx) {
            const motifChart = new Chart(motifCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($statsByMotif->pluck('motif')) !!},
                    datasets: [{
                        data: {!! json_encode($statsByMotif->pluck('total')) !!},
                        backgroundColor: generateColors({!! $statsByMotif->count() !!}, 0.7),
                        borderColor: generateColors({!! $statsByMotif->count() !!}, 1),
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                },
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
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
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

            // Légende pour le graphique par motif
            const motifLegend = document.getElementById('motifLegend');
            {!! $statsByMotif->pluck('motif')->toJson() !!}.forEach((label, index) => {
                const span = document.createElement('span');
                span.innerHTML = `<span style="background-color: ${motifChart.data.datasets[0].backgroundColor[index]};"></span> ${label}`;
                motifLegend.appendChild(span);
            });
        }

        // Graphique mouvements par mois
        const moisCtx = document.getElementById('mouvementsParMoisChart');
        if (moisCtx) {
            new Chart(moisCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($mouvementsParMois->pluck('mois')) !!},
                    datasets: [{
                        label: 'Nombre de mouvements',
                        data: {!! json_encode($mouvementsParMois->pluck('total')) !!},
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nombre de mouvements'
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
        }

        // Graphique top équipements
        const equipCtx = document.getElementById('topEquipementsChart');
        if (equipCtx && {!! $topEquipements->count() !!} > 0) {
            new Chart(equipCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topEquipements->map(function($item) {
                        return $item->equipement ? $item->equipement->numero_inventaire : 'N/A';
                    })) !!},
                    datasets: [{
                        label: 'Nombre de mouvements',
                        data: {!! json_encode($topEquipements->pluck('nb_mouvements')) !!},
                        backgroundColor: '#f59e0b',
                        borderColor: '#f59e0b',
                        borderWidth: 1,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nombre de mouvements'
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
        }

        // Graphique opérateurs
        const operCtx = document.getElementById('operateursChart');
        if (operCtx && {!! $mouvementsParOperateur->count() !!} > 0) {
            new Chart(operCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($mouvementsParOperateur->map(function($item) {
                        return $item->operateur ? ($item->operateur->nomComplet || $item->operateur->nom + ' ' + $item->operateur->prenom) : 'N/A';
                    })) !!},
                    datasets: [{
                        label: 'Nombre de mouvements',
                        data: {!! json_encode($mouvementsParOperateur->pluck('nb_mouvements')) !!},
                        backgroundColor: '#3b82f6',
                        borderColor: '#3b82f6',
                        borderWidth: 1,
                        borderRadius: 8
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nombre de mouvements'
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
        }

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

        // Animation des lignes du tableau
        const tableRows = document.querySelectorAll('.data-table tbody tr');
        tableRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
        });
    });
</script>
@endpush
