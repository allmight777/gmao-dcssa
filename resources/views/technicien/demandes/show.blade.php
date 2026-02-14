@extends('layouts.admin')

@section('title', 'Détails de la demande ' . $demande->Numero_Demande)

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-list mr-2"></i>Demande {{ $demande->Numero_Demande }}
        </h1>
        <div>
            <a href="{{ route('technicien.demandes.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i>Retour à la liste
            </a>
            @if($demande->Statut == 'validee')
            <a href="{{ route('technicien.interventions.planifier', $demande->ID_Demande) }}"
               class="btn btn-sm btn-success">
                <i class="fas fa-calendar-plus mr-1"></i>Planifier intervention
            </a>
            @endif
        </div>
    </div>

    <!-- Informations principales -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>Informations de la demande
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Numéro de demande</th>
                            <td>
                                <span class="font-weight-bold">{{ $demande->Numero_Demande }}</span>
                                <span class="badge badge-{{ $demande->badge_etat }} ml-2">
                                    {{ $demande->etat_formate }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Date de demande</th>
                            <td>{{ \Carbon\Carbon::parse($demande->Date_Demande)->format('d/m/Y') }} à {{ $demande->Heure_Demande }}</td>
                        </tr>
                        <tr>
                            <th>Type d'intervention</th>
                            <td>{{ $demande->type_intervention_formate }}</td>
                        </tr>
                        <tr>
                            <th>Urgence</th>
                            <td>
                                <span class="badge badge-{{ $demande->badge_urgence }}">
                                    {{ $demande->urgence_formate }}
                                </span>
                                @if($demande->Priorite == 1)
                                    <span class="badge badge-danger ml-2">Haute priorité</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Description de la panne</th>
                            <td>{{ nl2br(e($demande->Description_Panne)) }}</td>
                        </tr>
                        @if($demande->Commentaires)
                        <tr>
                            <th>Commentaires</th>
                            <td>{{ nl2br(e($demande->Commentaires)) }}</td>
                        </tr>
                        @endif
                        @if($demande->Delai_Souhaite)
                        <tr>
                            <th>Délai souhaité</th>
                            <td>{{ $demande->Delai_Souhaite }} heures</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Demandeur -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user mr-2"></i>Demandeur
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $demande->demandeur->nom }} {{ $demande->demandeur->prenom }}</strong></p>
                    <p class="mb-1"><i class="fas fa-briefcase mr-1"></i> {{ $demande->demandeur->fonction }}</p>
                    <p class="mb-1"><i class="fas fa-envelope mr-1"></i> {{ $demande->demandeur->email }}</p>
                    <p class="mb-0"><i class="fas fa-phone mr-1"></i> {{ $demande->demandeur->telephone ?? 'Non renseigné' }}</p>
                </div>
            </div>

            <!-- Équipement -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tools mr-2"></i>Équipement concerné
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $demande->equipement->nom }}</strong></p>
                    <p class="mb-1"><i class="fas fa-barcode mr-1"></i> {{ $demande->equipement->code_barre }}</p>
                    <p class="mb-1"><i class="fas fa-tag mr-1"></i> {{ $demande->equipement->type_equipement->nom ?? 'N/A' }}</p>
                    <p class="mb-0">
                        <span class="badge badge-{{ $demande->equipement->statut == 'disponible' ? 'success' : 'warning' }}">
                            {{ $demande->equipement->statut }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Validation -->
            @if($demande->Date_Validation)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-check-circle mr-2"></i>Validation
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $demande->validateur->nom }} {{ $demande->validateur->prenom }}</strong></p>
                    <p class="mb-0">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ \Carbon\Carbon::parse($demande->Date_Validation)->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Intervention associée -->
    @if(isset($intervention) && $intervention)
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-wrench mr-2"></i>Intervention associée
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID Intervention</th>
                                    <th>Début</th>
                                    <th>Fin</th>
                                    <th>Durée</th>
                                    <th>Résultat</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $intervention->ID_Intervention }}</td>
                                    <td>
                                        @if($intervention->Date_Debut)
                                            {{ \Carbon\Carbon::parse($intervention->Date_Debut)->format('d/m/Y') }}<br>
                                            <small>{{ $intervention->Heure_Debut }}</small>
                                        @else
                                            Non commencée
                                        @endif
                                    </td>
                                    <td>
                                        @if($intervention->Date_Fin)
                                            {{ \Carbon\Carbon::parse($intervention->Date_Fin)->format('d/m/Y') }}<br>
                                            <small>{{ $intervention->Heure_Fin }}</small>
                                        @else
                                            En cours
                                        @endif
                                    </td>
                                    <td>{{ $intervention->Duree_Reelle ?? 'N/A' }} h</td>
                                    <td>
                                        <span class="badge badge-{{ $intervention->Resultat == 'termine' ? 'success' : 'warning' }}">
                                            {{ $intervention->Resultat ?? 'En cours' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('technicien.interventions.show', $intervention->ID_Intervention) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!$intervention->Date_Fin)
                                        <a href="{{ route('technicien.interventions.rapport', $intervention->ID_Intervention) }}"
                                           class="btn btn-sm btn-success">
                                            <i class="fas fa-file-alt"></i> Rapport
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
