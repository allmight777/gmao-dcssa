@extends('layouts.admin')

@section('title', 'Modifier l\'équipement')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', 'Modifier l\'équipement')

@section('page-actions')
<a href="{{ route('inventaire.equipements.show', $equipement) }}" class="btn-action">
    <i class="fas fa-eye"></i> Voir
</a> &nbsp &nbsp
<a href="{{ route('inventaire.equipements.index') }}" class="btn-return">
    <i class="fas fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')
<div class="edit-equipment-container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('inventaire.equipements.update', $equipement) }}" class="equipment-form" id="equipmentForm">
        @csrf
        @method('PUT')
        
        <!-- Section Identification -->
        <div class="form-section">
            <div class="section-header">
                <h2><i class="fas fa-fingerprint"></i> Identification</h2>
                <div class="section-border"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="numero_inventaire">Numéro d'inventaire *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-barcode"></i>
                        <input type="text" id="numero_inventaire" name="numero_inventaire" 
                               value="{{ old('numero_inventaire', $equipement->numero_inventaire) }}" 
                               placeholder="Ex: INV-2023-001" required
                               maxlength="50">
                    </div>
                    @error('numero_inventaire')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="numero_serie">Numéro de série</label>
                    <div class="input-with-icon">
                        <i class="fas fa-hashtag"></i>
                        <input type="text" id="numero_serie" name="numero_serie" 
                               value="{{ old('numero_serie', $equipement->numero_serie) }}" 
                               placeholder="Numéro de série fabricant"
                               maxlength="100">
                    </div>
                    @error('numero_serie')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="marque">Marque *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-industry"></i>
                        <input type="text" id="marque" name="marque" 
                               value="{{ old('marque', $equipement->marque) }}" 
                               placeholder="Ex: HP, Dell, Siemens" required
                               maxlength="100">
                    </div>
                    @error('marque')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="modele">Modèle *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-microchip"></i>
                        <input type="text" id="modele" name="modele" 
                               value="{{ old('modele', $equipement->modele) }}" 
                               placeholder="Ex: EliteBook 840 G9" required
                               maxlength="100">
                    </div>
                    @error('modele')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Classification -->
        <div class="form-section">
            <div class="section-header">
                <h2><i class="fas fa-tags"></i> Classification</h2>
                <div class="section-border"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="type_equipement_id">Type d'équipement *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-tag"></i>
                        <select id="type_equipement_id" name="type_equipement_id" class="form-select" required>
                            <option value="">Sélectionnez un type</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ old('type_equipement_id', $equipement->type_equipement_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->libelle }} ({{ $type->code_type }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('type_equipement_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="classe_equipement">Classe</label>
                    <div class="input-with-icon">
                        <i class="fas fa-layer-group"></i>
                        <input type="text" id="classe_equipement" name="classe_equipement" 
                               value="{{ old('classe_equipement', $equipement->classe_equipement) }}" 
                               placeholder="Ex: Classe I, Classe II"
                               maxlength="50">
                    </div>
                    @error('classe_equipement')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="etat">État *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-battery"></i>
                        <select id="etat" name="etat" class="form-select" required>
                            @foreach($etats as $key => $label)
                                <option value="{{ $key }}" {{ old('etat', $equipement->etat) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('etat')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type_maintenance">Type de maintenance *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-tools"></i>
                        <select id="type_maintenance" name="type_maintenance" class="form-select" required>
                            @foreach($typesMaintenance as $key => $label)
                                <option value="{{ $key }}" {{ old('type_maintenance', $equipement->type_maintenance) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('type_maintenance')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Acquisition -->
        <div class="form-section">
            <div class="section-header">
                <h2><i class="fas fa-shopping-cart"></i> Acquisition</h2>
                <div class="section-border"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date_achat">Date d'achat *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-plus"></i>
                        <input type="date" id="date_achat" name="date_achat" 
                               value="{{ old('date_achat', $equipement->date_achat->format('Y-m-d')) }}" 
                               required>
                    </div>
                    @error('date_achat')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date_mise_service">Date de mise en service</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-check"></i>
                        <input type="date" id="date_mise_service" name="date_mise_service" 
                               value="{{ old('date_mise_service', $equipement->date_mise_service?->format('Y-m-d')) }}">
                    </div>
                    @error('date_mise_service')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="prix_achat">Prix d'achat (FCFA)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-money-bill-wave"></i>
                        <input type="number" id="prix_achat" name="prix_achat" 
                               value="{{ old('prix_achat', $equipement->prix_achat) }}" 
                               placeholder="0.00"
                               step="0.01"
                               min="0">
                    </div>
                    @error('prix_achat')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="fournisseur_id">Fournisseur</label>
                    <div class="input-with-icon">
                        <i class="fas fa-truck"></i>
                        <select id="fournisseur_id" name="fournisseur_id" class="form-select">
                            <option value="">Sélectionnez un fournisseur</option>
                            @foreach($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id', $equipement->fournisseur_id) == $fournisseur->id ? 'selected' : '' }}>
                                    {{ $fournisseur->raison_sociale }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('fournisseur_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="duree_garantie">Durée de garantie (mois)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-shield-alt"></i>
                        <input type="number" id="duree_garantie" name="duree_garantie" 
                               value="{{ old('duree_garantie', $equipement->duree_garantie) }}" 
                               placeholder="Ex: 24"
                               min="0">
                    </div>
                    @error('duree_garantie')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="duree_vie_theorique">Durée de vie théorique (mois)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-hourglass-half"></i>
                        <input type="number" id="duree_vie_theorique" name="duree_vie_theorique" 
                               value="{{ old('duree_vie_theorique', $equipement->duree_vie_theorique) }}" 
                               placeholder="Ex: 60"
                               min="0">
                    </div>
                    @error('duree_vie_theorique')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Localisation -->
        <div class="form-section">
            <div class="section-header">
                <h2><i class="fas fa-map-marker-alt"></i> Localisation</h2>
                <div class="section-border"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="localisation_id">Localisation physique</label>
                    <div class="input-with-icon">
                        <i class="fas fa-building"></i>
                        <select id="localisation_id" name="localisation_id" class="form-select">
                            <option value="">Sélectionnez une localisation</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ old('localisation_id', $equipement->localisation_id) == $localisation->id ? 'selected' : '' }}>
                                    {{ $localisation->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('localisation_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="service_responsable_id">Service responsable</label>
                    <div class="input-with-icon">
                        <i class="fas fa-users"></i>
                        <select id="service_responsable_id" name="service_responsable_id" class="form-select">
                            <option value="">Sélectionnez un service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_responsable_id', $equipement->service_responsable_id) == $service->id ? 'selected' : '' }}>
                                    {{ $service->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('service_responsable_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Maintenance -->
        <div class="form-section">
            <div class="section-header">
                <h2><i class="fas fa-wrench"></i> Maintenance</h2>
                <div class="section-border"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="contrat_id">Contrat de maintenance</label>
                    <div class="input-with-icon">
                        <i class="fas fa-file-contract"></i>
                        <select id="contrat_id" name="contrat_id" class="form-select">
                            <option value="">Sans contrat</option>
                            @foreach($contrats as $contrat)
                                <option value="{{ $contrat->id }}" {{ old('contrat_id', $equipement->contrat_id) == $contrat->id ? 'selected' : '' }}>
                                    {{ $contrat->numero_contrat }} - {{ $contrat->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('contrat_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Informations complémentaires -->
        <div class="form-section">
            <div class="section-header">
                <h2><i class="fas fa-info-circle"></i> Informations complémentaires</h2>
                <div class="section-border"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="code_barres">Code-barres</label>
                    <div class="input-with-icon">
                        <i class="fas fa-qrcode"></i>
                        <input type="text" id="code_barres" name="code_barres" 
                               value="{{ old('code_barres', $equipement->code_barres) }}" 
                               placeholder="Code-barres unique"
                               maxlength="100">
                    </div>
                    @error('code_barres')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date_reforme">Date de réforme</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-times"></i>
                        <input type="date" id="date_reforme" name="date_reforme" 
                               value="{{ old('date_reforme', $equipement->date_reforme?->format('Y-m-d')) }}">
                    </div>
                    <small class="form-hint">Remplir uniquement si l'équipement est hors service</small>
                    @error('date_reforme')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="commentaires">Commentaires</label>
                    <div class="input-with-icon">
                        <i class="fas fa-comment-alt"></i>
                        <textarea id="commentaires" name="commentaires" rows="4" 
                                  placeholder="Informations complémentaires, observations..."
                                  class="form-control">{{ old('commentaires', $equipement->commentaires) }}</textarea>
                    </div>
                    @error('commentaires')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <div class="action-buttons">
                <a href="{{ route('inventaire.equipements.show', $equipement) }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    Annuler
                </a>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i>
                    Mettre à jour
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    :root {
        --primary-color: #0351BC;
        --primary-light: #4a7fd4;
        --primary-dark: #023a8a;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
        --black: #000000;
        --dark-gray: #1a1a1a;
        --medium-gray: #333333;
        --light-gray: #f5f5f5;
        --white: #ffffff;
        --card-bg: #ffffff;
    }

    .edit-equipment-container {
        background: var(--card-bg);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .alert-danger {
        background: linear-gradient(135deg, var(--danger-color), #dc2626);
        color: var(--white);
        border: none;
        border-radius: 12px;
        margin: 20px 30px;
        padding: 15px 25px;
        box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2);
    }

    .equipment-form {
        padding: 40px;
    }

    .form-section {
        margin-bottom: 30px;
        padding: 30px;
        border: 2px solid var(--light-gray);
        border-radius: 16px;
        background: var(--white);
        transition: all 0.3s ease;
        position: relative;
    }

    .form-section:hover {
        border-color: var(--primary-light);
        box-shadow: 0 15px 35px rgba(3, 81, 188, 0.1);
    }

    .section-header {
        margin-bottom: 25px;
        position: relative;
    }

    .section-border {
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 60px;
        height: 3px;
        border-radius: 2px;
        background: var(--primary-color);
    }

    .form-section h2 {
        color: var(--primary-color);
        margin-bottom: 15px;
        font-size: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        padding-bottom: 10px;
    }

    .form-section h2 i {
        font-size: 22px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--dark-gray);
        font-size: 14px;
    }

    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-with-icon i {
        position: absolute;
        left: 15px;
        color: var(--medium-gray);
        font-size: 16px;
        z-index: 1;
    }

    .input-with-icon input,
    .input-with-icon select,
    .input-with-icon textarea {
        width: 100%;
        padding: 14px 14px 14px 45px;
        border: 2px solid var(--light-gray);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: var(--white);
        color: var(--black);
        font-family: inherit;
        position: relative;
        z-index: 2;
    }

    .input-with-icon textarea {
        resize: vertical;
        min-height: 100px;
        line-height: 1.5;
    }

    .input-with-icon input:focus,
    .input-with-icon select:focus,
    .input-with-icon textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(3, 81, 188, 0.1);
    }

    .form-hint {
        display: block;
        margin-top: 8px;
        font-size: 12px;
        color: var(--medium-gray);
        line-height: 1.4;
    }

    .error {
        color: var(--danger-color);
        font-size: 12px;
        margin-top: 8px;
        display: block;
        font-weight: 500;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid var(--light-gray);
    }

    .action-buttons {
        display: flex;
        gap: 15px;
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: var(--white);
        padding: 14px 32px;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(3, 81, 188, 0.3);
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(3, 81, 188, 0.4);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-cancel {
        background: var(--white);
        color: var(--medium-gray);
        padding: 14px 32px;
        border: 2px solid var(--light-gray);
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        font-size: 15px;
    }

    .btn-cancel:hover {
        background: var(--light-gray);
        border-color: var(--medium-gray);
        transform: translateY(-2px);
        color: var(--dark-gray);
    }

    .btn-action {
        background: var(--primary-color);
        color: var(--white);
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(3, 81, 188, 0.3);
    }

    .btn-return {
        background: var(--white);
        color: var(--medium-gray);
        padding: 10px 20px;
        border: 2px solid var(--light-gray);
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-return:hover {
        background: var(--light-gray);
        border-color: var(--medium-gray);
        transform: translateY(-1px);
        color: var(--dark-gray);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .equipment-form {
            padding: 20px;
        }

        .alert-danger {
            margin: 15px;
            padding: 12px 20px;
        }

        .form-section {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .form-actions {
            flex-direction: column;
            gap: 20px;
            align-items: stretch;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-submit,
        .btn-cancel {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .edit-equipment-container {
            border-radius: 10px;
        }

        .form-section {
            padding: 15px;
        }

        .form-section h2 {
            font-size: 18px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('equipmentForm');
        const submitBtn = document.getElementById('submitBtn');
        
        // Validation avant soumission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validation
            const inventaire = document.getElementById('numero_inventaire').value.trim();
            const marque = document.getElementById('marque').value.trim();
            const modele = document.getElementById('modele').value.trim();
            const type = document.getElementById('type_equipement_id').value;
            const etat = document.getElementById('etat').value;
            const dateAchat = document.getElementById('date_achat').value;
            
            if (!inventaire || !marque || !modele || !type || !etat || !dateAchat) {
                Swal.fire({
                    title: 'Champs obligatoires',
                    text: 'Veuillez remplir tous les champs obligatoires (*).',
                    icon: 'error',
                    confirmButtonColor: '#0351BC'
                });
                return;
            }
            
            // Vérifier la date de mise en service
            const dateMiseService = document.getElementById('date_mise_service').value;
            if (dateMiseService && dateMiseService < dateAchat) {
                Swal.fire({
                    title: 'Date invalide',
                    text: 'La date de mise en service doit être postérieure ou égale à la date d\'achat.',
                    icon: 'error',
                    confirmButtonColor: '#0351BC'
                });
                return;
            }
            
            // Vérifier la date de réforme si hors service
            const etatSelect = document.getElementById('etat');
            const etatValue = etatSelect.value;
            const dateReforme = document.getElementById('date_reforme').value;
            
            if (etatValue === 'hors_service' && !dateReforme) {
                Swal.fire({
                    title: 'Date de réforme requise',
                    text: 'Pour un équipement hors service, veuillez saisir la date de réforme.',
                    icon: 'warning',
                    confirmButtonColor: '#0351BC'
                });
                return;
            }
            
            // Confirmation
            Swal.fire({
                title: 'Confirmer la modification',
                text: 'Êtes-vous sûr de vouloir modifier cet équipement ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0351BC',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, modifier',
                cancelButtonText: 'Annuler',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mise à jour...';
                        
                        setTimeout(() => {
                            resolve();
                        }, 500);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Mettre à jour';
                }
            });
        });
        
        // Afficher/masquer le champ date de réforme
        const etatSelect = document.getElementById('etat');
        const dateReformeGroup = document.querySelector('.form-group:has(#date_reforme)');
        
        function toggleDateReforme() {
            if (etatSelect.value === 'hors_service') {
                dateReformeGroup.style.display = 'block';
                document.getElementById('date_reforme').required = true;
            } else {
                dateReformeGroup.style.display = 'none';
                document.getElementById('date_reforme').required = false;
                document.getElementById('date_reforme').value = '';
            }
        }
        
        // Initialiser et écouter les changements
        toggleDateReforme();
        etatSelect.addEventListener('change', toggleDateReforme);
    });
</script>
@endpush