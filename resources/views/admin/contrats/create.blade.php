@extends('layouts.admin')

@section('title', 'Créer un contrat de maintenance')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', 'Nouveau contrat de maintenance')

@section('page-actions')
<a href="{{ route('admin.contrats.index') }}" class="btn-return">
    <i class="fas fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')
<div class="create-contrat-container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.contrats.store') }}" method="POST" enctype="multipart/form-data" class="contrat-form" id="contratForm">
        @csrf

        <!-- Section Informations générales -->
        <div class="form-section">
            <div class="section-header">
                <h2><i class="fas fa-info-circle"></i> Informations générales</h2>
                <div class="section-border"></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="Libelle">Libellé du contrat *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-tag"></i>
                        <input type="text" id="Libelle" name="Libelle"
                               value="{{ old('Libelle') }}"
                               placeholder="Ex: Contrat maintenance annuel" required
                               maxlength="255">
                    </div>
                    @error('Libelle')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="Type">Type de contrat *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-file-contract"></i>
                        <select id="Type" name="Type" class="form-select" required>
                            <option value="">Sélectionner un type</option>
                            <option value="preventive" {{ old('Type') == 'preventive' ? 'selected' : '' }}>Maintenance préventive</option>
                            <option value="corrective" {{ old('Type') == 'corrective' ? 'selected' : '' }}>Maintenance corrective</option>
                            <option value="globale" {{ old('Type') == 'globale' ? 'selected' : '' }}>Maintenance globale</option>
                            <option value="garantie" {{ old('Type') == 'garantie' ? 'selected' : '' }}>Garantie</option>
                            <option value="autre" {{ old('Type') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    @error('Type')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ID_Fournisseur">Fournisseur *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-building"></i>
                        <select id="ID_Fournisseur" name="ID_Fournisseur" class="form-select" required>
                            <option value="">Sélectionner un fournisseur</option>
                            @foreach($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id }}" {{ old('ID_Fournisseur') == $fournisseur->id ? 'selected' : '' }}>
                                    {{ $fournisseur->raison_sociale }} ({{ $fournisseur->code_fournisseur }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('ID_Fournisseur')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="Periodicite_Interventions">Périodicité *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-week"></i>
                        <select id="Periodicite_Interventions" name="Periodicite_Interventions" class="form-select" required>
                            <option value="">Sélectionner</option>
                            <option value="hebdomadaire" {{ old('Periodicite_Interventions') == 'hebdomadaire' ? 'selected' : '' }}>Hebdomadaire</option>
                            <option value="mensuelle" {{ old('Periodicite_Interventions') == 'mensuelle' ? 'selected' : '' }}>Mensuelle</option>
                            <option value="trimestrielle" {{ old('Periodicite_Interventions') == 'trimestrielle' ? 'selected' : '' }}>Trimestrielle</option>
                            <option value="semestrielle" {{ old('Periodicite_Interventions') == 'semestrielle' ? 'selected' : '' }}>Semestrielle</option>
                            <option value="annuelle" {{ old('Periodicite_Interventions') == 'annuelle' ? 'selected' : '' }}>Annuelle</option>
                            <option value="ponctuelle" {{ old('Periodicite_Interventions') == 'ponctuelle' ? 'selected' : '' }}>Ponctuelle</option>
                        </select>
                    </div>
                    @error('Periodicite_Interventions')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="Delai_Intervention_Garanti">Délai intervention (heures) *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-clock"></i>
                        <input type="number" id="Delai_Intervention_Garanti" name="Delai_Intervention_Garanti"
                               value="{{ old('Delai_Intervention_Garanti', 24) }}" required min="1">
                    </div>
                    @error('Delai_Intervention_Garanti')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Période et montant -->
        <div class="form-section">
            <div class="section-header">
                <h2><i class="fas fa-calendar-alt"></i> Période et montant</h2>
                <div class="section-border"></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="Date_Debut">Date de début *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-plus"></i>
                        <input type="date" id="Date_Debut" name="Date_Debut"
                               value="{{ old('Date_Debut') }}" required>
                    </div>
                    @error('Date_Debut')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="Date_Fin">Date de fin *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-times"></i>
                        <input type="date" id="Date_Fin" name="Date_Fin"
                               value="{{ old('Date_Fin') }}" required>
                    </div>
                    @error('Date_Fin')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="Montant">Montant *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-money-bill-wave"></i>
                        <input type="number" step="0.01" id="Montant" name="Montant"
                               value="{{ old('Montant') }}" required>
                    </div>
                    @error('Montant')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="Devise">Devise *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-currency-sign"></i>
                        <select id="Devise" name="Devise" class="form-select" required>
                            <option value="XOF" {{ old('Devise') == 'XOF' ? 'selected' : '' }}>FCFA (XOF)</option>
                            <option value="EUR" {{ old('Devise') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                            <option value="USD" {{ old('Devise') == 'USD' ? 'selected' : '' }}>Dollar (USD)</option>
                        </select>
                    </div>
                    @error('Devise')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Couverture -->
        <div class="form-section">
            <div class="section-header">
                <h2><i class="fas fa-shield-alt"></i> Couverture</h2>
                <div class="section-border"></div>
            </div>

            <div class="form-row">
                <div class="form-group checkbox-group">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="Couverture_Pieces" name="Couverture_Pieces" value="1"
                               {{ old('Couverture_Pieces') ? 'checked' : '' }}>
                        <label for="Couverture_Pieces">Couverture des pièces détachées</label>
                    </div>
                </div>

                <div class="form-group checkbox-group">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="Couverture_Main_Oeuvre" name="Couverture_Main_Oeuvre" value="1"
                               {{ old('Couverture_Main_Oeuvre') ? 'checked' : '' }}>
                        <label for="Couverture_Main_Oeuvre">Couverture de la main d'œuvre</label>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="Exclusions">Exclusions</label>
                    <div class="input-with-icon">
                        <i class="fas fa-ban"></i>
                        <textarea id="Exclusions" name="Exclusions" rows="3"
                                  placeholder="Éléments exclus de la couverture...">{{ old('Exclusions') }}</textarea>
                    </div>
                    @error('Exclusions')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Équipements couverts -->
        <div class="form-section">
            <div class="section-header">
                <h2><i class="fas fa-tools"></i> Équipements couverts</h2>
                <div class="section-border"></div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="equipements">Sélectionner les équipements</label>
                    <div class="input-with-icon">
                        <i class="fas fa-list"></i>
                        <select id="equipements" name="equipements[]" class="form-select" multiple size="5">
                            @foreach($equipements as $equipement)
                                <option value="{{ $equipement->id }}"
                                    {{ in_array($equipement->id, old('equipements', [])) ? 'selected' : '' }}>
                                    {{ $equipement->numero_inventaire }} - {{ $equipement->etat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <small class="form-hint">Maintenez Ctrl pour sélectionner plusieurs équipements</small>
                    @error('equipements')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Alertes et conditions -->
        <div class="form-section">
            <div class="section-header">
                <h2><i class="fas fa-bell"></i> Alertes et conditions</h2>
                <div class="section-border"></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="Date_Alerte_Renouvellement">Date d'alerte de renouvellement</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-exclamation"></i>
                        <input type="date" id="Date_Alerte_Renouvellement" name="Date_Alerte_Renouvellement"
                               value="{{ old('Date_Alerte_Renouvellement') }}">
                    </div>
                    @error('Date_Alerte_Renouvellement')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="Conditions_Particulieres">Conditions particulières</label>
                    <div class="input-with-icon">
                        <i class="fas fa-file-signature"></i>
                        <textarea id="Conditions_Particulieres" name="Conditions_Particulieres" rows="3"
                                  placeholder="Conditions spécifiques du contrat...">{{ old('Conditions_Particulieres') }}</textarea>
                    </div>
                    @error('Conditions_Particulieres')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="Notes_Internes">Notes internes</label>
                    <div class="input-with-icon">
                        <i class="fas fa-sticky-note"></i>
                        <textarea id="Notes_Internes" name="Notes_Internes" rows="3"
                                  placeholder="Notes pour usage interne...">{{ old('Notes_Internes') }}</textarea>
                    </div>
                    @error('Notes_Internes')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Document -->
        <div class="form-section">
            <div class="section-header">
                <h2><i class="fas fa-file-pdf"></i> Document du contrat</h2>
                <div class="section-border"></div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="document">Fichier du contrat (PDF, DOC, DOCX)</label>
                    <div class="file-input-wrapper">
                        <input type="file" id="document" name="document"
                               accept=".pdf,.doc,.docx" class="file-input">
                        <div class="file-input-preview">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span id="file-name">Aucun fichier sélectionné</span>
                        </div>
                    </div>
                    <small class="form-hint">Taille max: 10 Mo</small>
                    @error('document')
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
                    <div class="preview-title" id="previewTitle">Nouveau contrat</div>
                    <div class="preview-badge" id="previewType">Type</div>
                </div>
                <div class="preview-body">
                    <div class="preview-row">
                        <span class="preview-label">Fournisseur:</span>
                        <span class="preview-value" id="previewFournisseur">-</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Période:</span>
                        <span class="preview-value" id="previewPeriode">-</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Montant:</span>
                        <span class="preview-value" id="previewMontant">-</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Périodicité:</span>
                        <span class="preview-value" id="previewPeriodicite">-</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Couverture:</span>
                        <span class="preview-value" id="previewCouverture">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <div class="form-info">
                <i class="fas fa-info-circle"></i>
                <span>Tous les champs marqués d'un * sont obligatoires</span>
            </div>
            <div class="action-buttons">
                <a href="{{ route('admin.contrats.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    Annuler
                </a>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i>
                    Enregistrer le contrat
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

    .create-contrat-container {
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

    .alert-danger ul {
        list-style: none;
        padding-left: 0;
    }

    .contrat-form {
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

    /* Checkbox styling */
    .checkbox-group {
        display: flex;
        align-items: center;
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .checkbox-wrapper input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: var(--primary-color);
    }

    .checkbox-wrapper label {
        margin-bottom: 0;
        cursor: pointer;
    }

    /* File input styling */
    .file-input-wrapper {
        position: relative;
    }

    .file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 3;
    }

    .file-input-preview {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px;
        border: 2px dashed var(--light-gray);
        border-radius: 10px;
        background: var(--light-gray);
        color: var(--medium-gray);
        transition: all 0.3s ease;
    }

    .file-input-preview i {
        font-size: 20px;
    }

    .file-input-wrapper:hover .file-input-preview {
        border-color: var(--primary-color);
        background: #e8f0fe;
    }

    /* Select multiple styling */
    select[multiple] {
        height: auto;
        min-height: 120px;
        padding: 10px;
    }

    select[multiple] option {
        padding: 8px 12px;
        margin: 2px 0;
        border-radius: 4px;
    }

    select[multiple] option:checked {
        background: var(--primary-color) linear-gradient(0deg, var(--primary-color) 0%, var(--primary-color) 100%);
        color: var(--white);
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

    .preview-badge {
        background: var(--light-gray);
        color: var(--medium-gray);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
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
        width: 120px;
        font-weight: 600;
        color: var(--dark-gray);
        font-size: 14px;
    }

    .preview-value {
        flex: 1;
        color: var(--medium-gray);
        font-size: 14px;
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
    .form-section:nth-child(7) { animation-delay: 0.7s; }
    .preview-section { animation-delay: 0.8s; }

    /* Responsive */
    @media (max-width: 768px) {
        .contrat-form {
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
            align-items: flex-start;
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
        .create-contrat-container {
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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contratForm');
        const submitBtn = document.getElementById('submitBtn');

        // Éléments pour la prévisualisation
        const previewTitle = document.getElementById('previewTitle');
        const previewType = document.getElementById('previewType');
        const previewFournisseur = document.getElementById('previewFournisseur');
        const previewPeriode = document.getElementById('previewPeriode');
        const previewMontant = document.getElementById('previewMontant');
        const previewPeriodicite = document.getElementById('previewPeriodicite');
        const previewCouverture = document.getElementById('previewCouverture');

        // Élément pour l'affichage du nom du fichier
        const fileInput = document.getElementById('document');
        const fileName = document.getElementById('file-name');

        // Mettre à jour la prévisualisation
        function updatePreview() {
            // Titre (libellé)
            const libelle = document.getElementById('Libelle').value;
            previewTitle.textContent = libelle || 'Nouveau contrat';

            // Type
            const typeSelect = document.getElementById('Type');
            const typeText = typeSelect.options[typeSelect.selectedIndex].text;
            previewType.textContent = typeText !== 'Sélectionner un type' ? typeText : 'Type';

            // Fournisseur
            const fournisseurSelect = document.getElementById('ID_Fournisseur');
            const fournisseurText = fournisseurSelect.options[fournisseurSelect.selectedIndex].text;
            previewFournisseur.textContent = fournisseurText !== 'Sélectionner un fournisseur' ? fournisseurText : '-';

            // Période
            const dateDebut = document.getElementById('Date_Debut').value;
            const dateFin = document.getElementById('Date_Fin').value;
            if (dateDebut && dateFin) {
                previewPeriode.textContent = `${formatDate(dateDebut)} au ${formatDate(dateFin)}`;
            } else {
                previewPeriode.textContent = '-';
            }

            // Montant
            const montant = document.getElementById('Montant').value;
            const devise = document.getElementById('Devise').value;
            if (montant) {
                previewMontant.textContent = `${parseFloat(montant).toLocaleString()} ${devise}`;
            } else {
                previewMontant.textContent = '-';
            }

            // Périodicité
            const periodiciteSelect = document.getElementById('Periodicite_Interventions');
            const periodiciteText = periodiciteSelect.options[periodiciteSelect.selectedIndex].text;
            previewPeriodicite.textContent = periodiciteText !== 'Sélectionner' ? periodiciteText : '-';

            // Couverture
            const couverturePieces = document.getElementById('Couverture_Pieces').checked;
            const couvertureMO = document.getElementById('Couverture_Main_Oeuvre').checked;

            let couvertureText = [];
            if (couverturePieces) couvertureText.push('Pièces');
            if (couvertureMO) couvertureText.push('Main d\'œuvre');

            previewCouverture.textContent = couvertureText.length > 0 ? couvertureText.join(' + ') : 'Aucune';
        }

        // Formater la date
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        // Écouter les changements sur tous les champs
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', updatePreview);
            input.addEventListener('change', updatePreview);
        });

        // Gestionnaire pour l'upload de fichier
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                fileName.textContent = file.name;
            } else {
                fileName.textContent = 'Aucun fichier sélectionné';
            }
        });

        // Validation avant soumission
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validation des champs obligatoires
            const libelle = document.getElementById('Libelle').value.trim();
            const type = document.getElementById('Type').value;
            const fournisseur = document.getElementById('ID_Fournisseur').value;
            const periodicite = document.getElementById('Periodicite_Interventions').value;
            const delai = document.getElementById('Delai_Intervention_Garanti').value;
            const dateDebut = document.getElementById('Date_Debut').value;
            const dateFin = document.getElementById('Date_Fin').value;
            const montant = document.getElementById('Montant').value;

            if (!libelle || !type || !fournisseur || !periodicite || !delai || !dateDebut || !dateFin || !montant) {
                Swal.fire({
                    title: 'Champs obligatoires',
                    text: 'Veuillez remplir tous les champs obligatoires (*).',
                    icon: 'error',
                    confirmButtonColor: '#0351BC'
                });
                return;
            }

            // Vérifier que la date de fin est postérieure à la date de début
            if (new Date(dateFin) <= new Date(dateDebut)) {
                Swal.fire({
                    title: 'Dates invalides',
                    text: 'La date de fin doit être postérieure à la date de début.',
                    icon: 'error',
                    confirmButtonColor: '#0351BC'
                });
                return;
            }

            // Vérifier que le montant est positif
            if (parseFloat(montant) <= 0) {
                Swal.fire({
                    title: 'Montant invalide',
                    text: 'Le montant doit être supérieur à 0.',
                    icon: 'error',
                    confirmButtonColor: '#0351BC'
                });
                return;
            }

            // Confirmation
            Swal.fire({
                title: 'Confirmer la création',
                text: 'Êtes-vous sûr de vouloir créer ce contrat de maintenance ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0351BC',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, créer',
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
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Enregistrer le contrat';
                }
            });
        });

        // Validation de la date d'alerte
        document.getElementById('Date_Alerte_Renouvellement').addEventListener('change', function() {
            const alerteDate = this.value;
            const dateFin = document.getElementById('Date_Fin').value;

            if (alerteDate && dateFin && new Date(alerteDate) > new Date(dateFin)) {
                Swal.fire({
                    title: 'Attention',
                    text: 'La date d\'alerte devrait idéalement être antérieure à la date de fin du contrat.',
                    icon: 'warning',
                    confirmButtonColor: '#0351BC'
                });
            }
        });

        // Initialiser la prévisualisation
        updatePreview();
    });
</script>
@endpush
