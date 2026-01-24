@extends('layouts.admin')

@section('title', 'Tableau de bord Inventaire')
@section('page-title', 'Tableau de bord - Gestion d\'Inventaire')

@section('content')
<div class="container-fluid">
    <!-- Alertes -->
    @if(count($alertes) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-triangle"></i> Alertes</h5>
                <ul class="mb-0">
                    @foreach($alertes as $alerte)
                    <li>
                        <a href="{{ $alerte['route'] }}" class="alert-link">
                            {{ $alerte['message'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Équipements totaux
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $statistiques['total_equipements'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Équipements actifs
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $statistiques['equipements_actifs'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Sous garantie
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $statistiques['equipements_sous_garantie'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Valeur totale
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($statistiques['valeur_totale'], 2, ',', ' ') }} €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt"></i> Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('inventaire.equipements.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus"></i> Nouvel équipement
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('inventaire.scanner.index') }}" class="btn btn-success w-100">
                                <i class="fas fa-barcode"></i> Scanner
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('inventaire.rapports.inventaire-physique') }}" class="btn btn-info w-100">
                                <i class="fas fa-clipboard-check"></i> Inventaire physique
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('inventaire.rapports.generer') }}" class="btn btn-warning w-100">
                                <i class="fas fa-file-pdf"></i> Générer rapport
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Équipements récents -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history"></i> Équipements récemment ajoutés
                    </h6>
                </div>
                <div class="card-body">
                    @if($equipements_recents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>N° Inventaire</th>
                                    <th>Marque/Modèle</th>
                                    <th>Type</th>
                                    <th>Localisation</th>
                                    <th>Date ajout</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipements_recents as $equipement)
                                <tr>
                                    <td>
                                        <strong>{{ $equipement->numero_inventaire }}</strong>
                                    </td>
                                    <td>{{ $equipement->marque }} {{ $equipement->modele }}</td>
                                    <td>{{ $equipement->type_equipement }}</td>
                                    <td>{{ $equipement->localisation->nom ?? 'Non affecté' }}</td>
                                    <td>{{ $equipement->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('inventaire.equipements.show', $equipement) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <p>Aucun équipement récent</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistiques par type -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-pie"></i> Répartition par type
                    </h6>
                </div>
                <div class="card-body">
                    @if($equipements_par_type->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($equipements_par_type as $type)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $type->type_equipement }}
                            <span class="badge bg-primary rounded-pill">{{ $type->total }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-chart-pie fa-3x mb-3"></i>
                        <p>Aucune donnée disponible</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto-refresh toutes les 30 secondes pour les alertes
        setInterval(function() {
            $.ajax({
                url: '{{ route("inventaire.dashboard") }}',
                success: function(data) {
                    // Vous pouvez implémenter une mise à jour partielle ici
                }
            });
        }, 30000);
    });
</script>
@endsection