@extends('layouts.admin')

@section('title', 'Modifier le type d\'équipement')

@section('page-title', 'Modifier le type d\'équipement')

@section('page-actions')
<a href="{{ route('inventaire.types.index') }}" class="btn-return">
    <i class="fas fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')
<div class="edit-type-container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('inventaire.types.update', $type_equipement->id) }}" class="type-form" id="typeForm">
        @csrf
        @method('PUT')
        
        <!-- Informations Générales -->
        <div class="form-section info-general-section">
            <div class="section-header">
                <h2><i class="fas fa-id-card"></i> Informations Générales</h2>
                <div class="section-border"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="code_type">Code Type *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-barcode"></i>
                        <input type="text" id="code_type" name="code_type" 
                               value="{{ old('code_type', $type_equipement->code_type) }}" 
                               placeholder="Ex: TYP001" required
                               maxlength="20">
                    </div>
                    <small class="form-hint">Code unique identifiant le type d'équipement</small>
                    @error('code_type')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="libelle">Libellé *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-heading"></i>
                        <input type="text" id="libelle" name="libelle" 
                               value="{{ old('libelle', $type_equipement->libelle) }}" 
                               placeholder="Ex: Scanner médical" required
                               maxlength="100">
                    </div>
                    <small class="form-hint">Nom complet du type d'équipement</small>
                    @error('libelle')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Classification -->
        <div class="form-section classification-section">
            <div class="section-header">
                <h2><i class="fas fa-tags"></i> Classification</h2>
                <div class="section-border"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="classe">Classe d'équipement</label>
                    <div class="input-with-icon">
                        <i class="fas fa-layer-group"></i>
                        <select id="classe" name="classe" class="form-select">
                            <option value="">Sélectionnez une classe</option>
                            @foreach($classes as $key => $label)
                                <option value="{{ $key }}" {{ old('classe', $type_equipement->classe) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <small class="form-hint">Classification technique de l'équipement</small>
                    @error('classe')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="risque">Niveau de risque *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                        <select id="risque" name="risque" class="form-select" required>
                            @foreach($risques as $key => $label)
                                <option value="{{ $key }}" {{ old('risque', $type_equipement->risque) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <small class="form-hint">Détermine la criticité de l'équipement</small>
                    @error('risque')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Durées et Maintenance -->
        <div class="form-section maintenance-section">
            <div class="section-header">
                <h2><i class="fas fa-calendar-alt"></i> Durées & Maintenance</h2>
                <div class="section-border"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="duree_vie_standard">Durée de vie standard (mois)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-hourglass-half"></i>
                        <input type="number" id="duree_vie_standard" name="duree_vie_standard" 
                               value="{{ old('duree_vie_standard', $type_equipement->duree_vie_standard) }}" 
                               placeholder="Ex: 60"
                               min="0"
                               step="1">
                    </div>
                    <small class="form-hint">Durée de vie théorique en mois</small>
                    @error('duree_vie_standard')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="periodicite_maintenance">Périodicité maintenance (mois)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-tools"></i>
                        <input type="number" id="periodicite_maintenance" name="periodicite_maintenance" 
                               value="{{ old('periodicite_maintenance', $type_equipement->periodicite_maintenance) }}" 
                               placeholder="Ex: 12"
                               min="0"
                               step="1">
                    </div>
                    <small class="form-hint">Fréquence des maintenances préventives</small>
                    @error('periodicite_maintenance')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="form-section description-section">
            <div class="section-header">
                <h2><i class="fas fa-align-left"></i> Description</h2>
                <div class="section-border"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="description">Description détaillée</label>
                    <div class="input-with-icon">
                        <i class="fas fa-file-alt"></i>
                        <textarea id="description" name="description" rows="5" 
                                  placeholder="Description complète du type d'équipement..."
                                  class="form-control">{{ old('description', $type_equipement->description) }}</textarea>
                    </div>
                    <small class="form-hint">Informations complémentaires</small>
                    @error('description')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Indicateur de risque -->
        <div class="risk-indicator">
            <h3><i class="fas fa-chart-line"></i> Prévisualisation du risque</h3>
            <div class="risk-preview">
                <div class="risk-level" id="riskPreview">
                    <div class="risk-info">
                        <div class="risk-label" id="riskLabel">{{ $type_equipement->risque == 'faible' ? 'Faible' : ($type_equipement->risque == 'moyen' ? 'Moyen' : ($type_equipement->risque == 'eleve' ? 'Élevé' : 'Critique')) }}</div>
                        <div class="risk-icon" id="riskIcon">
                            @if($type_equipement->risque == 'faible')
                                <i class="fas fa-shield-alt"></i>
                            @elseif($type_equipement->risque == 'moyen')
                                <i class="fas fa-exclamation-triangle"></i>
                            @elseif($type_equipement->risque == 'eleve')
                                <i class="fas fa-radiation"></i>
                            @else
                                <i class="fas fa-skull-crossbones"></i>
                            @endif
                        </div>
                    </div>
                    <div class="risk-description" id="riskDescription">
                        @if($type_equipement->risque == 'faible')
                            Risque acceptable, maintenance standard recommandée
                        @elseif($type_equipement->risque == 'moyen')
                            Risque modéré, maintenance régulière nécessaire
                        @elseif($type_equipement->risque == 'eleve')
                            Risque important, maintenance intensive recommandée
                        @else
                            Risque critique, maintenance prioritaire obligatoire
                        @endif
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
                <a href="{{ route('inventaire.types.index') }}" class="btn-cancel">
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
        --primary-blue: #0351BC;
        --primary-light-blue: #4a7fd4;
        --primary-dark-blue: #023a8a;
        --success-green: #198754;
        --danger-red: #dc3545;
        --warning-orange: #ffc107;
        --warning-dark: #e0a800;
        --info-blue: #0dcaf0;
        --white: #ffffff;
        --light-gray: #f8f9fa;
        --medium-gray: #6c757d;
        --dark-gray: #212529;
    }

    .edit-type-container {
        background: var(--white);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e0e0e0;
    }

    .alert-danger {
        background: linear-gradient(135deg, var(--danger-red) 0%, #bb2d3b 100%);
        color: var(--white);
        border: none;
        border-radius: 8px;
        margin: 20px 30px;
        padding: 15px 25px;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
    }

    .type-form {
        padding: 30px;
    }

    .form-section {
        margin-bottom: 25px;
        padding: 25px;
        border: 2px solid;
        border-radius: 12px;
        background: var(--white);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .form-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-blue), var(--primary-light-blue));
    }

    .info-general-section {
        border-color: var(--primary-blue);
        box-shadow: 0 5px 20px rgba(3, 81, 188, 0.1);
    }

    .classification-section {
        border-color: var(--info-blue);
        box-shadow: 0 5px 20px rgba(13, 202, 240, 0.1);
    }

    .maintenance-section {
        border-color: var(--success-green);
        box-shadow: 0 5px 20px rgba(25, 135, 84, 0.1);
    }

    .description-section {
        border-color: var(--warning-orange);
        box-shadow: 0 5px 20px rgba(255, 193, 7, 0.1);
    }

    .form-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
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
    }

    .info-general-section .section-border {
        background: var(--primary-blue);
    }

    .classification-section .section-border {
        background: var(--info-blue);
    }

    .maintenance-section .section-border {
        background: var(--success-green);
    }

    .description-section .section-border {
        background: var(--warning-orange);
    }

    .form-section h2 {
        color: var(--dark-gray);
        margin-bottom: 15px;
        font-size: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        padding-bottom: 10px;
    }

    .form-section h2 i {
        color: var(--primary-blue);
        font-size: 22px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 0;
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
        color: var(--primary-blue);
        font-size: 16px;
        z-index: 1;
    }

    .input-with-icon input,
    .input-with-icon select,
    .input-with-icon textarea {
        width: 100%;
        padding: 14px 14px 14px 45px;
        border: 2px solid #ced4da;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: var(--white);
        color: var(--dark-gray);
        font-family: inherit;
        position: relative;
        z-index: 2;
    }

    .input-with-icon input:focus,
    .input-with-icon select:focus,
    .input-with-icon textarea:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(3, 81, 188, 0.25);
        background: rgba(3, 81, 188, 0.05);
    }

    .input-with-icon textarea {
        resize: vertical;
        min-height: 120px;
        line-height: 1.5;
    }

    .form-hint {
        display: block;
        margin-top: 8px;
        font-size: 12px;
        color: var(--medium-gray);
        line-height: 1.4;
    }

    .error {
        color: var(--danger-red);
        font-size: 12px;
        margin-top: 8px;
        display: block;
        font-weight: 500;
    }

    /* Indicateur de risque */
    .risk-indicator {
        background: var(--light-gray);
        border-radius: 12px;
        padding: 25px;
        margin: 25px 0;
        border-left: 5px solid var(--primary-blue);
    }

    .risk-indicator h3 {
        color: var(--dark-gray);
        margin-bottom: 20px;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .risk-indicator h3 i {
        color: var(--primary-blue);
    }

    .risk-preview {
        background: var(--white);
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 2px solid;
        transition: all 0.3s ease;
    }

    .risk-level {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .risk-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .risk-label {
        font-size: 24px;
        font-weight: 700;
        color: var(--success-green);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .risk-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--success-green), #2ecc71);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 24px;
        box-shadow: 0 6px 15px rgba(25, 135, 84, 0.3);
    }

    .risk-description {
        color: var(--medium-gray);
        font-size: 14px;
        line-height: 1.5;
        padding: 15px;
        background: rgba(0, 0, 0, 0.02);
        border-radius: 8px;
        border-left: 4px solid var(--success-green);
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 40px;
        padding-top: 25px;
        border-top: 2px solid #e9ecef;
    }

    .form-info {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--medium-gray);
        font-size: 14px;
    }

    .form-info i {
        color: var(--primary-blue);
    }

    .action-buttons {
        display: flex;
        gap: 15px;
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark-blue) 100%);
        color: var(--white);
        padding: 14px 32px;
        border: none;
        border-radius: 8px;
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
        background: linear-gradient(135deg, var(--primary-dark-blue) 0%, var(--primary-blue) 100%);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-cancel {
        background: var(--white);
        color: var(--medium-gray);
        padding: 14px 32px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        font-size: 15px;
    }

    .btn-cancel:hover {
        background: #f8f9fa;
        border-color: var(--medium-gray);
        transform: translateY(-2px);
        color: var(--dark-gray);
    }

    .btn-return {
        background: var(--white);
        color: var(--medium-gray);
        padding: 10px 20px;
        border: 2px solid #dee2e6;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-return:hover {
        background: #f8f9fa;
        border-color: var(--medium-gray);
        transform: translateY(-1px);
        color: var(--dark-gray);
    }

    /* Amélioration des selects */
    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%230351BC' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 16px;
        padding-right: 45px;
        cursor: pointer;
    }

    .form-select:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(3, 81, 188, 0.25);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .type-form {
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

        .risk-info {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .risk-label {
            font-size: 20px;
        }

        .risk-icon {
            width: 50px;
            height: 50px;
            font-size: 20px;
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
        .edit-type-container {
            border-radius: 10px;
        }

        .form-section {
            padding: 15px;
        }

        .form-section h2 {
            font-size: 18px;
        }

        .risk-indicator {
            padding: 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('typeForm');
        const submitBtn = document.getElementById('submitBtn');
        const risqueSelect = document.getElementById('risque');
        
        // Configuration des risques
        const riskConfig = {
            'faible': {
                label: 'Faible',
                color: '#198754',
                bgColor: 'linear-gradient(135deg, #198754, #2ecc71)',
                icon: 'fa-shield-alt',
                description: 'Risque acceptable, maintenance standard recommandée. Impact minimal en cas de défaillance.'
            },
            'moyen': {
                label: 'Moyen',
                color: '#ffc107',
                bgColor: 'linear-gradient(135deg, #ffc107, #e0a800)',
                icon: 'fa-exclamation-triangle',
                description: 'Risque modéré, maintenance régulière nécessaire. Impact limité en cas de défaillance.'
            },
            'eleve': {
                label: 'Élevé',
                color: '#fd7e14',
                bgColor: 'linear-gradient(135deg, #fd7e14, #e8590c)',
                icon: 'fa-radiation',
                description: 'Risque important, maintenance intensive recommandée. Impact significatif en cas de défaillance.'
            },
            'critique': {
                label: 'Critique',
                color: '#dc3545',
                bgColor: 'linear-gradient(135deg, #dc3545, #c0392b)',
                icon: 'fa-skull-crossbones',
                description: 'Risque critique, maintenance prioritaire obligatoire. Impact majeur en cas de défaillance.'
            }
        };
        
        // Mettre à jour la prévisualisation du risque
        function updateRiskPreview() {
            const riskValue = risqueSelect.value;
            const config = riskConfig[riskValue];
            
            if (!config) return;
            
            const riskPreview = document.getElementById('riskPreview');
            const riskLabel = document.getElementById('riskLabel');
            const riskIcon = document.getElementById('riskIcon');
            const riskDescription = document.getElementById('riskDescription');
            
            // Mettre à jour le label
            riskLabel.textContent = config.label;
            riskLabel.style.color = config.color;
            
            // Mettre à jour l'icône
            riskIcon.innerHTML = `<i class="fas ${config.icon}"></i>`;
            riskIcon.style.background = config.bgColor;
            
            // Mettre à jour la description
            riskDescription.textContent = config.description;
            riskDescription.style.borderLeftColor = config.color;
            
            // Mettre à jour la bordure de l'aperçu
            riskPreview.closest('.risk-preview').style.borderColor = config.color;
        }
        
        // Écouter les changements sur le select risque
        risqueSelect.addEventListener('change', updateRiskPreview);
        
        // Initialiser la prévisualisation
        updateRiskPreview();
        
        // Validation avant soumission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Confirmer la modification',
                text: 'Êtes-vous sûr de vouloir modifier ce type d\'équipement ?',
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
                }
            });
        });
    });
</script>
@endpush