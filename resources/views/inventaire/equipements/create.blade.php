@extends('layouts.admin')

@section('title', 'Ajouter un équipement')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', 'Ajouter un nouvel équipement')

@section('page-actions')
<a href="{{ route('inventaire.equipements.index') }}" class="btn-return">
    <i class="fas fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')
<div class="create-equipment-container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('inventaire.equipements.store') }}" class="equipment-form" id="equipmentForm">
        @csrf
        
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
                               value="{{ old('numero_inventaire') }}" 
                               placeholder="Ex: INV-2023-001" required
                               maxlength="50">
                    </div>
                    <small class="form-hint">Numéro unique d'identification</small>
                    @error('numero_inventaire')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="numero_serie">Numéro de série</label>
                    <div class="input-with-icon">
                        <i class="fas fa-hashtag"></i>
                        <input type="text" id="numero_serie" name="numero_serie" 
                               value="{{ old('numero_serie') }}" 
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
                               value="{{ old('marque') }}" 
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
                               value="{{ old('modele') }}" 
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
                                <option value="{{ $type->id }}" {{ old('type_equipement_id') == $type->id ? 'selected' : '' }}>
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
                               value="{{ old('classe_equipement') }}" 
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
                                <option value="{{ $key }}" {{ old('etat') == $key ? 'selected' : '' }}>
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
                                <option value="{{ $key }}" {{ old('type_maintenance') == $key ? 'selected' : '' }}>
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
                               value="{{ old('date_achat', date('Y-m-d')) }}" 
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
                               value="{{ old('date_mise_service') }}">
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
                               value="{{ old('prix_achat') }}" 
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
                                <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
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
                               value="{{ old('duree_garantie') }}" 
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
                               value="{{ old('duree_vie_theorique') }}" 
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
                                <option value="{{ $localisation->id }}" {{ old('localisation_id') == $localisation->id ? 'selected' : '' }}>
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
                                <option value="{{ $service->id }}" {{ old('service_responsable_id') == $service->id ? 'selected' : '' }}>
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
                                <option value="{{ $contrat->id }}" {{ old('contrat_id') == $contrat->id ? 'selected' : '' }}>
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
                               value="{{ old('code_barres') }}" 
                               placeholder="Laissé vide pour génération automatique"
                               maxlength="100">
                    </div>
                    <button type="button" class="btn-generate" onclick="generateBarcode()">
                        <i class="fas fa-sync-alt"></i> Générer
                    </button>
                    @error('code_barres')
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
                                  class="form-control">{{ old('commentaires') }}</textarea>
                    </div>
                    @error('commentaires')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Prévisualisation -->
        <div class="preview-section">
            <h3><i class="fas fa-eye"></i> Prévisualisation</h3>
            <div class="preview-card">
                <div class="preview-header">
                    <div class="preview-title" id="previewTitle">Nouvel équipement</div>
                    <div class="preview-barcode" id="previewBarcode">Code-barres</div>
                </div>
                <div class="preview-body">
                    <div class="preview-row">
                        <span class="preview-label">Marque/Modèle:</span>
                        <span class="preview-value" id="previewModel">-</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Type:</span>
                        <span class="preview-value" id="previewType">-</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">État:</span>
                        <span class="preview-value" id="previewState">-</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Localisation:</span>
                        <span class="preview-value" id="previewLocation">-</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <div class="form-info">
                <i class="fas fa-info-circle"></i>
                <span>Tous les champs marqués d'un * sont obligatoires</span>
            </div>
            <div class="action-buttons">
                <a href="{{ route('inventaire.equipements.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    Annuler
                </a>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i>
                    Enregistrer l'équipement
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

    .create-equipment-container {
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

    .btn-generate {
        margin-left: 10px;
        background: var(--light-gray);
        color: var(--medium-gray);
        padding: 10px 15px;
        border: 2px solid var(--light-gray);
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
        font-size: 13px;
    }

    .btn-generate:hover {
        background: var(--primary-color);
        color: var(--white);
        border-color: var(--primary-color);
    }

    /* Prévisualisation */
    .preview-section {
        background: var(--light-gray);
        border-radius: 16px;
        padding: 30px;
        margin: 30px 0;
    }

    .preview-section h3 {
        color: var(--dark-gray);
        margin-bottom: 20px;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .preview-card {
        background: var(--white);
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: 2px solid var(--primary-light);
    }

    .preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--light-gray);
    }

    .preview-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary-color);
    }

    .preview-barcode {
        background: var(--light-gray);
        color: var(--medium-gray);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .preview-body {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .preview-row {
        display: flex;
        align-items: center;
    }

    .preview-label {
        width: 150px;
        font-weight: 600;
        color: var(--dark-gray);
        font-size: 14px;
    }

    .preview-value {
        flex: 1;
        color: var(--medium-gray);
        font-size: 14px;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid var(--light-gray);
    }

    .form-info {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--medium-gray);
        font-size: 14px;
    }

    .form-info i {
        color: var(--primary-color);
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

    /* Amélioration des selects */
    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 16px;
        padding-right: 45px;
        cursor: pointer;
    }

    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(3, 81, 188, 0.25);
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

        .preview-section {
            padding: 20px;
        }

        .preview-header {
            flex-direction: column;
            gap: 10px;
            align-items: stretch;
        }

        .preview-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }

        .preview-label {
            width: 100%;
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
        .create-equipment-container {
            border-radius: 10px;
        }

        .form-section {
            padding: 15px;
        }

        .form-section h2 {
            font-size: 18px;
        }

        .preview-section {
            padding: 15px;
        }
    }

    /* Animation pour les sections */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-section {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    .form-section:nth-child(1) { animation-delay: 0.1s; }
    .form-section:nth-child(2) { animation-delay: 0.2s; }
    .form-section:nth-child(3) { animation-delay: 0.3s; }
    .form-section:nth-child(4) { animation-delay: 0.4s; }
    .form-section:nth-child(5) { animation-delay: 0.5s; }
    .form-section:nth-child(6) { animation-delay: 0.6s; }
    .preview-section { animation-delay: 0.7s; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('equipmentForm');
        const submitBtn = document.getElementById('submitBtn');
        
        // Éléments pour la prévisualisation
        const previewTitle = document.getElementById('previewTitle');
        const previewBarcode = document.getElementById('previewBarcode');
        const previewModel = document.getElementById('previewModel');
        const previewType = document.getElementById('previewType');
        const previewState = document.getElementById('previewState');
        const previewLocation = document.getElementById('previewLocation');
        
        // Mettre à jour la prévisualisation
        function updatePreview() {
            // Titre (numéro d'inventaire)
            const inventaire = document.getElementById('numero_inventaire').value;
            previewTitle.textContent = inventaire || 'Nouvel équipement';
            
            // Code-barres
            const barcode = document.getElementById('code_barres').value;
            previewBarcode.textContent = barcode || 'Code-barres';
            
            // Marque/Modèle
            const marque = document.getElementById('marque').value;
            const modele = document.getElementById('modele').value;
            previewModel.textContent = (marque && modele) ? `${marque} ${modele}` : '-';
            
            // Type
            const typeSelect = document.getElementById('type_equipement_id');
            const typeText = typeSelect.options[typeSelect.selectedIndex].text;
            previewType.textContent = typeText !== 'Sélectionnez un type' ? typeText.split(' (')[0] : '-';
            
            // État
            const etatSelect = document.getElementById('etat');
            const etatText = etatSelect.options[etatSelect.selectedIndex].text;
            previewState.textContent = etatText;
            
            // Localisation
            const locSelect = document.getElementById('localisation_id');
            const locText = locSelect.options[locSelect.selectedIndex].text;
            previewLocation.textContent = locText !== 'Sélectionnez une localisation' ? locText : '-';
        }
        
        // Écouter les changements sur tous les champs
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', updatePreview);
            input.addEventListener('change', updatePreview);
        });
        
        // Générer un code-barres
        window.generateBarcode = function() {
            const inventaire = document.getElementById('numero_inventaire').value;
            const marque = document.getElementById('marque').value.substring(0, 3).toUpperCase();
            const modele = document.getElementById('modele').value.substring(0, 3).toUpperCase();
            const timestamp = new Date().getTime().toString().slice(-4);
            
            if (inventaire) {
                const barcode = `${marque || 'EQP'}-${modele || '000'}-${inventaire.substring(0, 8)}-${timestamp}`;
                document.getElementById('code_barres').value = barcode;
                updatePreview();
            } else {
                Swal.fire({
                    title: 'Information',
                    text: 'Veuillez d\'abord saisir le numéro d\'inventaire.',
                    icon: 'info',
                    confirmButtonColor: '#0351BC'
                });
            }
        };
        
        // Générer un numéro d'inventaire automatique
        function generateInventoryNumber() {
            const inventaireInput = document.getElementById('numero_inventaire');
            if (!inventaireInput.value.trim()) {
                const year = new Date().getFullYear();
                const month = (new Date().getMonth() + 1).toString().padStart(2, '0');
                const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                inventaireInput.value = `INV-${year}${month}-${random}`;
                updatePreview();
            }
        }
        
        document.getElementById('numero_inventaire').addEventListener('focus', generateInventoryNumber);
        
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
            
            // Confirmation
            Swal.fire({
                title: 'Confirmer l\'ajout',
                text: 'Êtes-vous sûr de vouloir ajouter cet équipement à l\'inventaire ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0351BC',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, ajouter',
                cancelButtonText: 'Annuler',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
                        
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
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Enregistrer l\'équipement';
                }
            });
        });
        
        // Initialiser la prévisualisation
        updatePreview();
        
        // Calcul automatique de la durée de vie théorique basée sur le type
        document.getElementById('type_equipement_id').addEventListener('change', function() {
            const dureeVieInput = document.getElementById('duree_vie_theorique');
            if (!dureeVieInput.value) {
                // Si un type est sélectionné, suggérer une durée de vie
                if (this.value) {
                    dureeVieInput.value = 60; // 5 ans par défaut
                    updatePreview();
                }
            }
        });
    });
</script>
@endpush