@extends('layouts.admin')

@section('title', 'Planifier maintenance préventive')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-plus mr-2"></i>Planifier maintenance préventive
        </h1>
        <a href="{{ route('technicien.preventive.equipements') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Détails de la planification</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('technicien.preventive.planifier.store', $equipement->id) }}">
                        @csrf

                        <div class="form-group">
                            <label>Date prévue <span class="text-danger">*</span></label>
                            <input type="date" name="Date_Prevue" class="form-control"
                                   value="{{ old('Date_Prevue', now()->format('Y-m-d')) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Heure prévue <span class="text-danger">*</span></label>
                            <input type="time" name="Heure_Prevue" class="form-control"
                                   value="{{ old('Heure_Prevue', now()->format('H:i')) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Type de maintenance <span class="text-danger">*</span></label>
                            <select name="Type_Maintenance" class="form-control" required>
                                <option value="preventive">Préventive</option>
                                <option value="mixte">Mixte</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Description des opérations <span class="text-danger">*</span></label>
                            <textarea name="Description" class="form-control" rows="4"
                                      placeholder="Décrivez les opérations de maintenance à effectuer..." required>{{ old('Description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Observations</label>
                            <textarea name="Observations" class="form-control" rows="2">{{ old('Observations') }}</textarea>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       id="mettre_en_maintenance" name="mettre_en_maintenance" value="1">
                                <label class="custom-control-label" for="mettre_en_maintenance">
                                    Mettre l'équipement en maintenance immédiatement
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Planifier la maintenance
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Équipement concerné</h6>
                </div>
                <div class="card-body">
                    <p><strong>Nom :</strong> {{ $equipement->nom }}</p>
                    <p><strong>Code barre :</strong> {{ $equipement->code_barres }}</p>
                    <p><strong>Type :</strong> {{ $equipement->type_equipement->nom ?? 'N/A' }}</p>
                    <p><strong>Maintenance :</strong> {{ $equipement->type_maintenance }}</p>
                    <p><strong>État actuel :</strong>
                        <span class="badge badge-{{ $equipement->etat == 'bon' ? 'success' : 'warning' }}">
                            {{ $equipement->etat }}
                        </span>
                    </p>
                    @if($equipement->localisation)
                    <p><strong>Localisation :</strong> {{ $equipement->localisation->nom }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
