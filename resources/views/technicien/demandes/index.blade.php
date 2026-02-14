@extends('layouts.admin')

@section('title', 'Gestion des demandes d\'intervention')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-list mr-2"></i>Demandes d'intervention
        </h1>
        <div>
            <a href="{{ route('technicien.demandes.export') }}" class="btn btn-sm btn-success">
                <i class="fas fa-file-csv mr-1"></i>Exporter CSV
            </a>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-weight-bold mb-0">{{ $stats['en_attente'] }}</h5>
                            <small>En attente</small>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-weight-bold mb-0">{{ $stats['validees'] }}</h5>
                            <small>Validées</small>
                        </div>
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-weight-bold mb-0">{{ $stats['total'] }}</h5>
                            <small>Total</small>
                        </div>
                        <i class="fas fa-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter mr-2"></i>Filtres
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('technicien.demandes.index') }}" class="form-inline">
                <div class="row w-100">
                    <div class="col-md-3 mb-2">
                        <select name="statut" class="form-control w-100">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="validee" {{ request('statut') == 'validee' ? 'selected' : '' }}>Validée</option>
                            <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="terminee" {{ request('statut') == 'terminee' ? 'selected' : '' }}>Terminée</option>
                            <option value="rejetee" {{ request('statut') == 'rejetee' ? 'selected' : '' }}>Rejetée</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="urgence" class="form-control w-100">
                            <option value="">Toutes urgences</option>
                            <option value="normale" {{ request('urgence') == 'normale' ? 'selected' : '' }}>Normale</option>
                            <option value="urgente" {{ request('urgence') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                            <option value="critique" {{ request('urgence') == 'critique' ? 'selected' : '' }}>Critique</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="date" name="date_debut" class="form-control w-100" value="{{ request('date_debut') }}" placeholder="Date début">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="date" name="date_fin" class="form-control w-100" value="{{ request('date_fin') }}" placeholder="Date fin">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search mr-1"></i>Filtrer
                        </button>
                    </div>
                </div>
                <div class="row w-100 mt-2">
                    <div class="col-md-12">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                               placeholder="Rechercher par n° demande, équipement, demandeur...">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des demandes -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>Liste des demandes
            </h6>
            <span class="badge badge-primary p-2">Total: {{ $demandes->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>N° Demande</th>
                            <th>Date</th>
                            <th>Demandeur</th>
                            <th>Équipement</th>
                            <th>Type</th>
                            <th>Urgence</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demandes as $demande)
                        <tr>
                            <td>
                                <span class="font-weight-bold">{{ $demande->Numero_Demande }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($demande->Date_Demande)->format('d/m/Y') }}<br>
                                <small>{{ $demande->Heure_Demande }}</small>
                            </td>
                            <td>
                                {{ $demande->demandeur->nom }} {{ $demande->demandeur->prenom }}<br>
                                <small class="text-muted">{{ $demande->demandeur->fonction }}</small>
                            </td>
                            <td>
                                <a href="{{ route('inventaire.equipements.show', $demande->equipement->id) }}"
                                   class="text-primary">
                                    {{ $demande->equipement->nom }}
                                </a><br>
                                <small class="text-muted">{{ $demande->equipement->code_barre }}</small>
                            </td>
                            <td>
                                <span class="badge badge-secondary">
                                    {{ $demande->type_intervention_formate }}
                                </span>
                            </td>
                            <td>
                                @if($demande->Urgence == 'critique')
                                    <span class="badge badge-danger">Critique</span>
                                @elseif($demande->Urgence == 'urgente')
                                    <span class="badge badge-warning">Urgente</span>
                                @else
                                    <span class="badge badge-info">Normale</span>
                                @endif
                                @if($demande->Priorite == 1)
                                    <span class="badge badge-danger">Haute priorité</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $demande->badge_etat }}">
                                    {{ $demande->etat_formate }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('technicien.demandes.show', $demande->ID_Demande) }}"
                                   class="btn btn-sm btn-info" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($demande->Statut == 'validee')
                                <a href="{{ route('technicien.interventions.planifier', $demande->ID_Demande) }}"
                                   class="btn btn-sm btn-success" title="Planifier">
                                    <i class="fas fa-calendar-plus"></i>
                                </a>
                                @endif

                                @if($demande->Statut == 'en_cours')
                                    @php
                                        $intervention = \App\Models\Intervention::where('ID_Demande', $demande->ID_Demande)->first();
                                    @endphp
                                    @if($intervention)
                                    <a href="{{ route('technicien.interventions.rapport', $intervention->ID_Intervention) }}"
                                       class="btn btn-sm btn-warning" title="Saisir rapport">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Aucune demande trouvée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $demandes->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
