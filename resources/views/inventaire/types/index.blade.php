@extends('layouts.admin')

@section('title', 'Types d\'équipement')

@section('page-title', 'Gestion des types d\'équipement')

@section('page-actions')
<style>
    .btn-return {
        display: inline-flex;
        align-items: center;
        padding: 8px 14px;
        background-color: #1d4ed8; /* bleu */
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.2s, transform 0.1s;
    }

    .btn-return i {
        margin-right: 6px;
    }

    .btn-return:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
    }

    /* Optionnel : ajouter un petit shadow pour faire flotter le bouton */
    .btn-return:active {
        transform: translateY(0);
    }
</style>

<a href="{{ route('inventaire.types.create') }}" class="btn-return">
    <i class="fas fa-plus"></i> Nouveau type
</a>
@endsection

@section('content')
<div class="inventory-container">
    <!-- Statistiques et graphiques -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="stat-content">
                <h3>Total Types</h3>
                <p class="stat-number">{{ $statistiques['total'] }}</p>
            </div>
        </div>

        <div class="stat-card low-risk">
            <div class="stat-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-content">
                <h3>Faible Risque</h3>
                <p class="stat-number">{{ $statistiques['faible_risque'] }}</p>
            </div>
        </div>

        <div class="stat-card medium-risk">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <h3>Risque Moyen</h3>
                <p class="stat-number">{{ $statistiques['moyen_risque'] }}</p>
            </div>
        </div>

        <div class="stat-card high-risk">
            <div class="stat-icon">
                <i class="fas fa-radiation"></i>
            </div>
            <div class="stat-content">
                <h3>Risque Élevé</h3>
                <p class="stat-number">{{ $statistiques['eleve_risque'] }}</p>
            </div>
        </div>

        <div class="stat-card critical-risk">
            <div class="stat-icon">
                <i class="fas fa-skull-crossbones"></i>
            </div>
            <div class="stat-content">
                <h3>Risque Critique</h3>
                <p class="stat-number">{{ $statistiques['critique_risque'] }}</p>
            </div>
        </div>
    </div>

    <!-- Graphique Chart.js -->
    <div class="chart-container">
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie"></i> Répartition par niveau de risque</h3>
            <canvas id="riskChart"></canvas>
        </div>
    </div>

    <!-- Tableau des types -->
    <div class="table-container">
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
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Rechercher un type...">
            </div>
            <div class="table-info">
                <span>{{ $types->total() }} type(s) trouvé(s)</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Libellé</th>
                        <th>Classe</th>
                        <th>Durée de vie</th>
                        <th>Maintenance</th>
                        <th>Niveau de risque</th>
                        <th>Créé le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($types as $type)
                        <tr>
                            <td>
                                <div class="type-code">
                                    <i class="fas fa-tag"></i>
                                    <strong>{{ $type->code_type }}</strong>
                                </div>
                            </td>
                            <td>
                                <div class="type-name">
                                    <strong>{{ $type->libelle }}</strong>
                                    @if($type->description)
                                        <small>{{ Str::limit($type->description, 50) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($type->classe)
                                    <span class="badge badge-class">{{ $type->classe }}</span>
                                @else
                                    <span class="text-muted">Non défini</span>
                                @endif
                            </td>
                            <td>
                                @if($type->duree_vie_standard)
                                    <span class="duration-badge">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $type->duree_vie_standard }} mois
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($type->periodicite_maintenance)
                                    <span class="maintenance-badge">
                                        <i class="fas fa-tools"></i>
                                        {{ $type->periodicite_maintenance }} mois
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $riskClasses = [
                                        'faible' => 'risk-low',
                                        'moyen' => 'risk-medium',
                                        'eleve' => 'risk-high',
                                        'critique' => 'risk-critical'
                                    ];
                                    $riskLabels = [
                                        'faible' => 'Faible',
                                        'moyen' => 'Moyen',
                                        'eleve' => 'Élevé',
                                        'critique' => 'Critique'
                                    ];
                                @endphp
                                <span class="risk-badge {{ $riskClasses[$type->risque] }}">
                                    <i class="fas fa-circle"></i>
                                    {{ $riskLabels[$type->risque] }}
                                </span>
                            </td>
                            <td>
                                {{ $type->created_at->format('d/m/Y') }}
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('inventaire.types.edit', $type->id) }}"
                                       class="btn-action btn-edit"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('inventaire.types.destroy', $type->id) }}"
                                          method="POST"
                                          class="delete-form"
                                          data-name="{{ $type->libelle }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action btn-delete" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-table">
                                <i class="fas fa-inbox"></i>
                                <p>Aucun type d'équipement trouvé</p>
                                <a href="{{ route('inventaire.types.create') }}" class="btn-create">
                                    <i class="fas fa-plus"></i> Créer le premier type
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($types->hasPages())
            <div class="pagination-container">
                {{ $types->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --primary-blue: #0351BC;
        --primary-light-blue: #4a7fd4;
        --primary-dark-blue: #023a8a;
        --success-green: #198754;
        --danger-red: #dc3545;
        --warning-orange: #ffc107;
        --warning-dark: #e0a800;
        --info-blue: #0dcaf0;
        --white: #ffffff;
        --light-gray: #f8f9fa;
        --medium-gray: #6c757d;
        --dark-gray: #212529;
    }

    .inventory-container {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    /* Statistiques */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: var(--white);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-left: 5px solid var(--primary-blue);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    .stat-card.low-risk {
        border-left-color: var(--success-green);
    }

    .stat-card.medium-risk {
        border-left-color: var(--warning-orange);
    }

    .stat-card.high-risk {
        border-left-color: #fd7e14;
    }

    .stat-card.critical-risk {
        border-left-color: var(--danger-red);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-light-blue));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 22px;
    }

    .low-risk .stat-icon {
        background: linear-gradient(135deg, var(--success-green), #2ecc71);
    }

    .medium-risk .stat-icon {
        background: linear-gradient(135deg, var(--warning-orange), var(--warning-dark));
    }

    .high-risk .stat-icon {
        background: linear-gradient(135deg, #fd7e14, #e74c3c);
    }

    .critical-risk .stat-icon {
        background: linear-gradient(135deg, var(--danger-red), #c0392b);
    }

    .stat-content h3 {
        margin: 0 0 5px 0;
        font-size: 14px;
        color: var(--medium-gray);
        font-weight: 600;
    }

    .stat-number {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: var(--dark-gray);
    }

    /* Graphique */
    .chart-container {
        background: var(--white);
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .chart-card h3 {
        margin: 0 0 20px 0;
        color: var(--dark-gray);
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-card h3 i {
        color: var(--primary-blue);
    }

    #riskChart {
        max-height: 300px;
        width: 100% !important;
    }

    /* Table */
    .table-container {
        background: var(--white);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .success-message {
        background: linear-gradient(135deg, var(--success-green) 0%, #157347 100%);
        color: var(--white);
        padding: 15px 25px;
        border-radius: 8px;
        margin: 20px;
        text-align: center;
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.2);
    }

    .error-message {
        background: linear-gradient(135deg, var(--danger-red) 0%, #bb2d3b 100%);
        color: var(--white);
        padding: 15px 25px;
        border-radius: 8px;
        margin: 20px;
        text-align: center;
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
    }

    .table-header {
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--light-gray);
    }

    .search-box {
        position: relative;
        width: 300px;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--medium-gray);
    }

    .search-box input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(3, 81, 188, 0.25);
    }

    .table-info {
        font-size: 14px;
        color: var(--medium-gray);
        font-weight: 500;
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
        border-bottom: 2px solid #dee2e6;
    }

    .data-table td {
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .data-table tbody tr {
        transition: background-color 0.3s ease;
    }

    .data-table tbody tr:hover {
        background-color: rgba(3, 81, 188, 0.05);
    }

    .type-code {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .type-code i {
        color: var(--primary-blue);
        font-size: 14px;
    }

    .type-name {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .type-name small {
        color: var(--medium-gray);
        font-size: 12px;
        line-height: 1.4;
    }

    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-class {
        background: rgba(13, 202, 240, 0.1);
        color: #0dcaf0;
        border: 1px solid rgba(13, 202, 240, 0.3);
    }

    .duration-badge,
    .maintenance-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        background: rgba(25, 135, 84, 0.1);
        color: var(--success-green);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid rgba(25, 135, 84, 0.2);
    }

    .maintenance-badge {
        background: rgba(255, 193, 7, 0.1);
        color: var(--warning-dark);
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .risk-badge {
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

    .risk-low {
        background: rgba(25, 135, 84, 0.1);
        color: var(--success-green);
        border: 1px solid rgba(25, 135, 84, 0.3);
    }

    .risk-medium {
        background: rgba(255, 193, 7, 0.1);
        color: var(--warning-dark);
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .risk-high {
        background: rgba(253, 126, 20, 0.1);
        color: #fd7e14;
        border: 1px solid rgba(253, 126, 20, 0.3);
    }

    .risk-critical {
        background: rgba(220, 53, 69, 0.1);
        color: var(--danger-red);
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    .risk-badge i {
        font-size: 8px;
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
        text-decoration: none;
        color: var(--white);
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-light-blue));
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, var(--primary-dark-blue), var(--primary-blue));
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(3, 81, 188, 0.3);
    }

    .btn-delete {
        background: linear-gradient(135deg, var(--danger-red), #e74c3c);
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #bb2d3b, var(--danger-red));
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    .empty-table {
        text-align: center;
        padding: 60px 20px !important;
    }

    .empty-table i {
        font-size: 48px;
        color: #dee2e6;
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
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-light-blue));
        color: var(--white);
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(3, 81, 188, 0.3);
    }

    .pagination-container {
        padding: 20px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 5px;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-light-blue));
        border-color: var(--primary-blue);
        color: var(--white);
    }

    .pagination .page-link {
        padding: 8px 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        color: var(--primary-blue);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background-color: rgba(3, 81, 188, 0.1);
        border-color: var(--primary-blue);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-container {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }

        .table-header {
            flex-direction: column;
            gap: 15px;
            align-items: stretch;
        }

        .search-box {
            width: 100%;
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
        .stat-card {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }

        .stat-number {
            font-size: 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser le graphique
        const ctx = document.getElementById('riskChart').getContext('2d');

        const riskChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Faible', 'Moyen', 'Élevé', 'Critique'],
                datasets: [{
                    data: [
                        {{ $statistiques['faible_risque'] }},
                        {{ $statistiques['moyen_risque'] }},
                        {{ $statistiques['eleve_risque'] }},
                        {{ $statistiques['critique_risque'] }}
                    ],
                    backgroundColor: [
                        '#198754',
                        '#ffc107',
                        '#fd7e14',
                        '#dc3545'
                    ],
                    borderColor: [
                        '#157347',
                        '#e0a800',
                        '#e8590c',
                        '#bb2d3b'
                    ],
                    borderWidth: 2,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const value = context.raw || 0;
                                const total = {{ $statistiques['total'] }};
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                label += value + ' (' + percentage + '%)';
                                return label;
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

        // Recherche en temps réel
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('.data-table tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }

        // Confirmation de suppression
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.delete-form');
                const typeName = form.dataset.name;

                Swal.fire({
                    title: 'Confirmer la suppression',
                    html: `Êtes-vous sûr de vouloir supprimer le type <strong>"${typeName}"</strong> ?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Animation pour les statistiques
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
