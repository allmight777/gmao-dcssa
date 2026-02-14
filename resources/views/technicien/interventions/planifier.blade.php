@extends('layouts.admin')

@section('title', 'Planifier une intervention')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-plus mr-2"></i>Planifier une intervention
        </h1>
        <a href="{{ route('technicien.demandes.show', $demande->ID_Demande) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i>Retour à la demande
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock mr-2"></i>Planification
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('technicien.interventions.planifier.store', $demande->ID_Demande) }}">
                        @csrf

                        <div class="form-group">
                            <label for="Date_Debut">Date de début <span class="text-danger">*</span></label>
                            <input type="date"
                                   class="form-control @error('Date_Debut') is-invalid @enderror"
                                   id="Date_Debut"
                                   name="Date_Debut"
                                   value="{{ old('Date_Debut', now()->format('Y-m-d')) }}"
                                   min="{{ now()->format('Y-m-d') }}"
                                   required>
                            @error('Date_Debut')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="Heure_Debut">Heure de début <span class="text-danger">*</span></label>
                            <input type="time"
                                   class="form-control @error('Heure_Debut') is-invalid @enderror"
                                   id="Heure_Debut"
                                   name="Heure_Debut"
                                   value="{{ old('Heure_Debut', now()->format('H:i')) }}"
                                   required>
                            @error('Heure_Debut')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="Date_Fin_Prevue">Date de fin prévue</label>
                            <input type="date"
                                   class="form-control @error('Date_Fin_Prevue') is-invalid @enderror"
                                   id="Date_Fin_Prevue"
                                   name="Date_Fin_Prevue"
                                   value="{{ old('Date_Fin_Prevue') }}"
                                   min="{{ now()->format('Y-m-d') }}">
                            <small class="form-text text-muted">Laissez vide si non déterminée</small>
                            @error('Date_Fin_Prevue')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="mettre_hors_service"
                                       name="mettre_hors_service"
                                       value="1"
                                       {{ old('mettre_hors_service') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="mettre_hors_service">
                                    Mettre l'équipement hors service pendant l'intervention
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Observations">Observations / Instructions</label>
                            <textarea class="form-control @error('Observations') is-invalid @enderror"
                                      id="Observations"
                                      name="Observations"
                                      rows="4">{{ old('Observations') }}</textarea>
                            @error('Observations')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>Enregistrer la planification
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Informations sur la demande -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>Demande
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>N°:</strong> {{ $demande->Numero_Demande }}</p>
                    <p class="mb-1"><strong>Type:</strong> {{ $demande->type_intervention_formate }}</p>
                    <p class="mb-1"><strong>Urgence:</strong>
                        <span class="badge badge-{{ $demande->badge_urgence }}">
                            {{ $demande->urgence_formate }}
                        </span>
                    </p>
                    <p class="mb-0"><strong>Équipement:</strong> {{ $demande->equipement->nom }}</p>
                </div>
            </div>

            <!-- Description de la panne -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Description
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $demande->Description_Panne }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
