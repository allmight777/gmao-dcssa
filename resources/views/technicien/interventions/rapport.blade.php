@extends('layouts.admin')

@section('title', 'Saisie du rapport d\'intervention')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt mr-2"></i>Rapport d'intervention
        </h1>
        <a href="{{ route('technicien.interventions.show', $intervention->ID_Intervention) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i>Retour
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit mr-2"></i>Saisie du rapport
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('technicien.interventions.rapport.store', $intervention->ID_Intervention) }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Date_Fin">Date de fin <span class="text-danger">*</span></label>
                                    <input type="date"
                                           class="form-control @error('Date_Fin') is-invalid @enderror"
                                           id="Date_Fin"
                                           name="Date_Fin"
                                           value="{{ old('Date_Fin', now()->format('Y-m-d')) }}"
                                           min="{{ $intervention->Date_Debut }}"
                                           required>
                                    @error('Date_Fin')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Heure_Fin">Heure de fin <span class="text-danger">*</span></label>
                                    <input type="time"
                                           class="form-control @error('Heure_Fin') is-invalid @enderror"
                                           id="Heure_Fin"
                                           name="Heure_Fin"
                                           value="{{ old('Heure_Fin', now()->format('H:i')) }}"
                                           required>
                                    @error('Heure_Fin')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Resultat">Résultat de l'intervention <span class="text-danger">*</span></label>
                            <select class="form-control @error('Resultat') is-invalid @enderror"
                                    id="Resultat"
                                    name="Resultat"
                                    required>
                                <option value="">Sélectionnez...</option>
                                <option value="termine" {{ old('Resultat') == 'termine' ? 'selected' : '' }}>Terminé avec succès</option>
                                <option value="partiel" {{ old('Resultat') == 'partiel' ? 'selected' : '' }}>Partiellement terminé</option>
                                <option value="reporte" {{ old('Resultat') == 'reporte' ? 'selected' : '' }}>Reporté</option>
                                <option value="echec" {{ old('Resultat') == 'echec' ? 'selected' : '' }}>Échec</option>
                            </select>
                            @error('Resultat')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="Rapport_Technique">Rapport technique <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('Rapport_Technique') is-invalid @enderror"
                                      id="Rapport_Technique"
                                      name="Rapport_Technique"
                                      rows="6"
                                      required>{{ old('Rapport_Technique') }}</textarea>
                            @error('Rapport_Technique')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="Statut_Conformite">Statut de conformité</label>
                            <select class="form-control @error('Statut_Conformite') is-invalid @enderror"
                                    id="Statut_Conformite"
                                    name="Statut_Conformite">
                                <option value="">Non applicable</option>
                                <option value="conforme" {{ old('Statut_Conformite') == 'conforme' ? 'selected' : '' }}>Conforme</option>
                                <option value="non_conforme" {{ old('Statut_Conformite') == 'non_conforme' ? 'selected' : '' }}>Non conforme</option>
                            </select>
                            @error('Statut_Conformite')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Cout_Main_Oeuvre">Coût main d'œuvre (FCFA)</label>
                                    <input type="number"
                                           class="form-control @error('Cout_Main_Oeuvre') is-invalid @enderror"
                                           id="Cout_Main_Oeuvre"
                                           name="Cout_Main_Oeuvre"
                                           value="{{ old('Cout_Main_Oeuvre', 0) }}"
                                           min="0"
                                           step="0.01">
                                    @error('Cout_Main_Oeuvre')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Cout_Pieces">Coût pièces (FCFA)</label>
                                    <input type="number"
                                           class="form-control @error('Cout_Pieces') is-invalid @enderror"
                                           id="Cout_Pieces"
                                           name="Cout_Pieces"
                                           value="{{ old('Cout_Pieces', 0) }}"
                                           min="0"
                                           step="0.01">
                                    @error('Cout_Pieces')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Signature_Client">Signature du client</label>
                            <input type="text"
                                   class="form-control @error('Signature_Client') is-invalid @enderror"
                                   id="Signature_Client"
                                   name="Signature_Client"
                                   value="{{ old('Signature_Client') }}"
                                   placeholder="Nom du signataire">
                            @error('Signature_Client')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nouveau_statut_equipement">Nouveau statut de l'équipement</label>
                            <select class="form-control" id="nouveau_statut_equipement" name="nouveau_statut_equipement">
                                <option value="">Garder le statut actuel</option>
                                <option value="disponible">Disponible</option>
                                <option value="en_maintenance">En maintenance</option>
                                <option value="en_panne">En panne</option>
                                <option value="hors_service">Hors service</option>
                            </select>
                            <small class="form-text text-muted">
                                Statut actuel:
                                <span class="badge badge-info">{{ $intervention->equipement->statut ?? 'Non défini' }}</span>
                            </small>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>Enregistrer le rapport
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Informations de l'intervention -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>Intervention
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Début:</strong>
                        {{ \Carbon\Carbon::parse($intervention->Date_Debut)->format('d/m/Y') }} à {{ $intervention->Heure_Debut }}
                    </p>
                    <p class="mb-1"><strong>Demande:</strong> {{ $intervention->demande->Numero_Demande }}</p>
                    <p class="mb-1"><strong>Équipement:</strong> {{ $intervention->demande->equipement->nom }}</p>
                    <p class="mb-0"><strong>Type:</strong> {{ $intervention->demande->type_intervention_formate }}</p>
                </div>
            </div>

            <!-- Description de la panne -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Description initiale
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $intervention->demande->Description_Panne }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
