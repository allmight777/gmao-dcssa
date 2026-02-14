@extends('layouts.admin')

@section('title', 'Détails de l\'intervention #' . $intervention->ID_Intervention)

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-wrench mr-2"></i>Intervention #{{ $intervention->ID_Intervention }}
        </h1>
        <div>
            <a href="{{ route('technicien.interventions.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i>Retour à la liste
            </a>
            @if(!$intervention->Date_Fin)
            <a href="{{ route('technicien.interventions.rapport', $intervention->ID_Intervention) }}"
               class="btn btn-sm btn-success">
                <i class="fas fa-file-alt mr-1"></i>Saisir rapport
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
                        <i class="fas fa-info-circle mr-2"></i>Détails de l'intervention
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Demande associée</th>
                            <td>
                                <a href="{{ route('technicien.demandes.show', $intervention->demande->ID_Demande) }}">
                                    {{ $intervention->demande->Numero_Demande }}
                                </a>
                                <span class="badge badge-{{ $intervention->demande->badge_etat }} ml-2">
                                    {{ $intervention->demande->etat_formate }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Période d'intervention</th>
                            <td>
                                @if($intervention->Date_Debut)
                                    <strong>Début:</strong>
                                    {{ \Carbon\Carbon::parse($intervention->Date_Debut)->format('d/m/Y') }} à {{ $intervention->Heure_Debut }}<br>
                                @endif
                                @if($intervention->Date_Fin)
                                    <strong>Fin:</strong>
                                    {{ \Carbon\Carbon::parse($intervention->Date_Fin)->format('d/m/Y') }} à {{ $intervention->Heure_Fin }}<br>
                                    <strong>Durée totale:</strong> {{ $intervention->Duree_Reelle }} heures
                                @else
                                    <span class="text-warning">Intervention en cours</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Résultat</th>
                            <td>
                                @if($intervention->Resultat)
                                    <span class="badge badge-{{ $intervention->Resultat == 'termine' ? 'success' : 'warning' }}">
                                        {{ $intervention->Resultat }}
                                    </span>
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Statut de conformité</th>
                            <td>
                                @if($intervention->Statut_Conformite)
                                    <span class="badge badge-{{ $intervention->Statut_Conformite == 'conforme' ? 'success' : 'danger' }}">
                                        {{ $intervention->Statut_Conformite }}
                                    </span>
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Rapport technique</th>
                            <td>
                                @if($intervention->Rapport_Technique)
                                    {{ nl2br(e($intervention->Rapport_Technique)) }}
                                @else
                                    <span class="text-muted">Aucun rapport saisi</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Équipement -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tools mr-2"></i>Équipement
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $intervention->equipement->nom }}</strong></p>
                    <p class="mb-1"><i class="fas fa-barcode mr-1"></i> {{ $intervention->equipement->code_barre }}</p>
                    <p class="mb-0">
                        <span class="badge badge-{{ $intervention->equipement->statut == 'disponible' ? 'success' : 'warning' }}">
                            {{ $intervention->equipement->statut }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Coûts -->
            @if($intervention->Cout_Total > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                      Coûts
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td>Main d'œuvre:</td>
                            <td class="text-right">{{ number_format($intervention->Cout_Main_Oeuvre, 2) }} FCFA</td>
                        </tr>
                        <tr>
                            <td>Pièces:</td>
                            <td class="text-right">{{ number_format($intervention->Cout_Pieces, 2) }} FCFA</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td>TOTAL:</td>
                            <td class="text-right">{{ number_format($intervention->Cout_Total, 2) }} FCFA</td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif

            <!-- Signature -->
            @if($intervention->Signature_Client)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-signature mr-2"></i>Signature client
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $intervention->Signature_Client }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
