@extends('layouts.admin')

@section('title', 'Gestion des Contrats de Maintenance')

@push('styles')
<style>
    .stat-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .stat-icon {
        position: absolute;
        right: 15px;
        top: 15px;
        font-size: 3rem;
        opacity: 0.6;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    .stat-label {
        color: #ffffff;
        margin-bottom: 0;
    }
    .badge-expiration {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
    }
    .expiration-urgent {
        background: linear-gradient(45deg, #dc3545, #c82333);
        color: white;
        animation: pulse 2s infinite;
    }
    .expiration-warning {
        background: linear-gradient(45deg, #ffc107, #e0a800);
        color: #212529;
    }
    .expiration-info {
        background: linear-gradient(45deg, #17a2b8, #138496);
        color: white;
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.8; }
        100% { opacity: 1; }
    }
    .alert-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .filter-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="fas fa-file-contract me-2"></i>Gestion des Contrats de Maintenance
        </h1>
        <div>
            <a href="{{ route('admin.contrats.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus-circle me-2"></i>Nouveau Contrat
            </a>
            <a href="{{ route('admin.contrats.export.zip') }}" class="btn btn-success me-2">
                <i class="fas fa-download me-2"></i>Export ZIP
            </a>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#sendAlertsModal">
                <i class="fas fa-bell me-2"></i>Envoyer Alertes
            </button>
        </div>
    </div>

    <!-- Bannière d'alerte pour contrats expirants -->
    @if($contratsExpirants->count() > 0)
    <div class="alert-banner mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
            <div>
                <h5 class="mb-1">Attention : {{ $contratsExpirants->count() }} contrat(s) expire(nt) dans moins de 7 jours !</h5>
                <p class="mb-0">Pensez à renouveler ces contrats ou à contacter les fournisseurs.</p>
            </div>
            <button class="btn btn-light ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#expiringContracts">
                Voir les détails <i class="fas fa-chevron-down ms-2"></i>
            </button>
        </div>

        <div class="collapse mt-3" id="expiringContracts">
            <div class="row">
                @foreach($contratsExpirants as $contrat)
                <div class="col-md-4 mb-2">
                    <div class="bg-white text-dark p-3 rounded">
                        <strong>{{ $contrat->Numero_Contrat }}</strong> - {{ $contrat->Libelle }}<br>
                        <small>Fournisseur: {{ $contrat->fournisseur->raison_sociale }}</small><br>
                        <small class="text-danger">Expire le: {{ \Carbon\Carbon::parse($contrat->Date_Fin)->format('d/m/Y') }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Statistiques Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="stat-icon"><i class="fas fa-file-contract"></i></div>
                    <p class="stat-value">{{ $statistiques['totalContrats'] }}</p>
                    <p class="stat-label">Total Contrats</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <p class="stat-value">{{ $statistiques['contratsActifs'] }}</p>
                    <p class="stat-label">Contrats Actifs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <p class="stat-value">{{ $statistiques['expirantBientot'] }}</p>
                    <p class="stat-label">Expirent bientôt</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                    <p class="stat-value">{{ number_format($statistiques['montantTotalActifs'], 0, ',', ' ') }}</p>
                    <p class="stat-label">Montant Total (FCFA)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Répartition par Statut</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartStatut" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Top 5 Fournisseurs</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartFournisseurs" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Section des filtres -->
    <div class="filter-section">
        <form method="GET" action="{{ route('admin.contrats.index') }}" class="row g-3">
            <div class="col-md-2">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="expire" {{ request('statut') == 'expire' ? 'selected' : '' }}>Expiré</option>
                    <option value="resilie" {{ request('statut') == 'resilie' ? 'selected' : '' }}>Résilié</option>
                    <option value="renouvellement_attente" {{ request('statut') == 'renouvellement_attente' ? 'selected' : '' }}>Renouvellement</option>
                    <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Fournisseur</label>
                <select name="fournisseur_id" class="form-select">
                    <option value="">Tous</option>
                    @foreach($fournisseurs as $fournisseur)
                    <option value="{{ $fournisseur->id }}" {{ request('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                        {{ $fournisseur->raison_sociale }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">Tous</option>
                    <option value="preventive" {{ request('type') == 'preventive' ? 'selected' : '' }}>Préventive</option>
                    <option value="corrective" {{ request('type') == 'corrective' ? 'selected' : '' }}>Corrective</option>
                    <option value="globale" {{ request('type') == 'globale' ? 'selected' : '' }}>Globale</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Date début</label>
                <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date fin</label>
                <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Recherche</label>
                <input type="text" name="recherche" class="form-control" placeholder="N° contrat, libellé..." value="{{ request('recherche') }}">
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Filtrer
                </button>
                <a href="{{ route('admin.contrats.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo me-2"></i>Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau des contrats -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des Contrats</h5>
            <span class="badge bg-primary">{{ $contrats->total() }} contrat(s)</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Contrat</th>
                            <th>Libellé</th>
                            <th>Fournisseur</th>
                            <th>Période</th>
                            <th>Montant</th>
                            <th>Jours restants</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contrats as $contrat)
                        <tr>
                            <td>
                                <span class="fw-bold">{{ $contrat->Numero_Contrat }}</span>
                            </td>
                            <td>
                                <strong>{{ $contrat->Libelle }}</strong><br>
                                <small class="text-muted">{{ $contrat->Type }}</small>
                            </td>
                            <td>{{ $contrat->fournisseur->raison_sociale ?? 'N/A' }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($contrat->Date_Debut)->format('d/m/Y') }}<br>
                                <small class="text-muted">au {{ \Carbon\Carbon::parse($contrat->Date_Fin)->format('d/m/Y') }}</small>
                            </td>
                            <td>{{ $contrat->montant_formate }}</td>
                            <td>
                                @if($contrat->estActif() && $contrat->jours_restants)
                                    @if($contrat->jours_restants <= 0)
                                        <span class="badge-expiration expiration-urgent">Expiré</span>
                                    @elseif($contrat->jours_restants <= 7)
                                        <span class="badge-expiration expiration-urgent">{{ $contrat->jours_restants }} jour(s)</span>
                                    @elseif($contrat->jours_restants <= 30)
                                        <span class="badge-expiration expiration-warning">{{ $contrat->jours_restants }} jour(s)</span>
                                    @else
                                        <span class="badge-expiration expiration-info">{{ $contrat->jours_restants }} jour(s)</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $contrat->statut_avec_couleur['class'] }}">
                                    {{ $contrat->statut_avec_couleur['text'] }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.contrats.show', $contrat->ID_Contrat) }}"
                                       class="btn btn-sm btn-info" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.contrats.edit', $contrat->ID_Contrat) }}"
                                       class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.contrats.pdf', $contrat->ID_Contrat) }}"
                                       class="btn btn-sm btn-secondary" title="Télécharger PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete({{ $contrat->ID_Contrat }})" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun contrat trouvé</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $contrats->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmation Suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmation de suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce contrat ?</p>
                <p class="text-danger"><small>Cette action est irréversible.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Envoi Alertes -->
<div class="modal fade" id="sendAlertsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-bell me-2"></i>Envoyer les alertes d'expiration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.contrats.send-alerts') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Cette action enverra des emails aux fournisseurs dont les contrats expirent bientôt.</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Les alertes seront envoyées pour les contrats expirant dans les 7 prochains jours.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer les alertes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuration des charts
    document.addEventListener('DOMContentLoaded', function() {
        // Chart Statut
        const ctxStatut = document.getElementById('chartStatut').getContext('2d');
        new Chart(ctxStatut, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($statistiques['parStatut']->pluck('Statut')) !!},
                datasets: [{
                    data: {!! json_encode($statistiques['parStatut']->pluck('total')) !!},
                    backgroundColor: [
                        '#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6c757d'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Chart Fournisseurs
        const ctxFournisseurs = document.getElementById('chartFournisseurs').getContext('2d');
        new Chart(ctxFournisseurs, {
            type: 'bar',
            data: {
                labels: {!! json_encode($statistiques['parFournisseur']->pluck('fournisseur')) !!},
                datasets: [{
                    label: 'Nombre de contrats',
                    data: {!! json_encode($statistiques['parFournisseur']->pluck('total')) !!},
                    backgroundColor: '#667eea',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });

    // Fonction pour la confirmation de suppression
    function confirmDelete(id) {
        const form = document.getElementById('deleteForm');
        form.action = '/admin/contrats/' + id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endpush
