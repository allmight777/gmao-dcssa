@extends('layouts.admin')

@section('title', 'Gestion des interventions')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-wrench mr-2"></i>Interventions techniques
        </h1>
    </div>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter mr-2"></i>Filtres
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('technicien.interventions.index') }}" class="form-inline">
                <div class="row w-100">
                    <div class="col-md-3 mb-2">
                        <select name="statut" class="form-control w-100">
                            <option value="">Tous les statuts</option>
                            <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="terminees" {{ request('statut') == 'terminees' ? 'selected' : '' }}>Terminées</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="date" name="date_debut" class="form-control w-100" value="{{ request('date_debut') }}" placeholder="Date début">
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="date" name="date_fin" class="form-control w-100" value="{{ request('date_fin') }}" placeholder="Date fin">
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search mr-1"></i>Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des interventions -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>Liste des interventions
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Demande</th>
                            <th>Équipement</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Durée</th>
                            <th>Résultat</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interventions as $intervention)
                        <tr>
                            <td>{{ $intervention->ID_Intervention }}</td>
                            <td>
                                <span class="font-weight-bold">{{ $intervention->demande->Numero_Demande }}</span><br>
                                <small class="text-muted">{{ $intervention->demande->type_intervention_formate }}</small>
                            </td>
                            <td>
                                {{ $intervention->demande->equipement->nom }}<br>
                                <small class="text-muted">{{ $intervention->demande->equipement->code_barre }}</small>
                            </td>
                            <td>
                                @if($intervention->Date_Debut)
                                    {{ \Carbon\Carbon::parse($intervention->Date_Debut)->format('d/m/Y') }}<br>
                                    <small>{{ $intervention->Heure_Debut }}</small>
                                @else
                                    <span class="text-muted">Non commencée</span>
                                @endif
                            </td>
                            <td>
                                @if($intervention->Date_Fin)
                                    {{ \Carbon\Carbon::parse($intervention->Date_Fin)->format('d/m/Y') }}<br>
                                    <small>{{ $intervention->Heure_Fin }}</small>
                                @else
                                    <span class="text-warning">En cours</span>
                                @endif
                            </td>
                            <td>{{ $intervention->Duree_Reelle ? $intervention->Duree_Reelle . ' h' : '-' }}</td>
                            <td>
                                @if($intervention->Resultat)
                                    <span class="badge badge-{{ $intervention->Resultat == 'termine' ? 'success' : 'warning' }}">
                                        {{ $intervention->Resultat }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if(!$intervention->Date_Fin)
                                    <span class="badge badge-warning">En cours</span>
                                @else
                                    <span class="badge badge-success">Terminée</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('technicien.interventions.show', $intervention->ID_Intervention) }}"
                                   class="btn btn-sm btn-info" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!$intervention->Date_Fin)
                                <a href="{{ route('technicien.interventions.rapport', $intervention->ID_Intervention) }}"
                                   class="btn btn-sm btn-success" title="Saisir rapport">
                                    <i class="fas fa-file-alt"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Aucune intervention trouvée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $interventions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
