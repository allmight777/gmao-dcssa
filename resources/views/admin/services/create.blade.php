@extends('layouts.admin')

@section('title', 'Créer un service/localisation')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', 'Créer un service/localisation')
<br><br>
@section('page-actions')
<a href="{{ route('admin.services.index') }}" class="btn-return">
    <i class="fas fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')
<div class="create-service-container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.services.store') }}" class="service-form" id="serviceForm">
        @csrf
        
        <!-- Section Type et Nom -->
        <div class="form-section">
            <h2><i class="fas fa-info-circle"></i> Informations de base</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="type">Type *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-tag"></i>
                        <select id="type" name="type" class="form-select" required onchange="toggleCustomType()">
                            <option value="">Sélectionnez un type</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('type')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-building"></i>
                        <input type="text" id="nom" name="nom" 
                               value="{{ old('nom') }}" 
                               placeholder="Ex: Direction Informatique" required>
                    </div>
                    @error('nom')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" id="typeCustomGroup" style="display: none;">
                    <label for="type_custom">Spécifiez le type *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-edit"></i>
                        <input type="text" id="type_custom" name="type_custom" 
                               value="{{ old('type_custom') }}" 
                               placeholder="Ex: Division, Unité, Cellule...">
                    </div>
                    @error('type_custom')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="parent_id">Parent</label>
                    <div class="input-with-icon">
                        <i class="fas fa-level-up-alt"></i>
                        <select id="parent_id" name="parent_id" class="form-select">
                            <option value="">Aucun parent (racine)</option>
                            @foreach($parents as $id => $label)
                                <option value="{{ $id }}" {{ old('parent_id') == $id ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <small class="form-hint">Sélectionnez l'élément parent dans la hiérarchie parmi les services déjà enregistrés</small>
                    @error('parent_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="code_geographique">Code géographique</label>
                    <div class="input-with-icon">
                        <i class="fas fa-qrcode"></i>
                        <input type="text" id="code_geographique" name="code_geographique" 
                               value="{{ old('code_geographique') }}" 
                               placeholder="Ex: DCSSA-001">
                    </div>
                    <small class="form-hint">Laissé vide pour génération automatique</small>
                    @error('code_geographique')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Responsable -->
        <div class="form-section">
            <h2><i class="fas fa-user-tie"></i> Responsable</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="responsable_id">Responsable</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <select id="responsable_id" name="responsable_id" class="form-select">
                            <option value="">Aucun responsable</option>
                            @foreach($responsables as $id => $label)
                                <option value="{{ $id }}" {{ old('responsable_id') == $id ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <small class="form-hint">Le responsable doit être cherché dans la table users</small>
                    @error('responsable_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Contact -->
        <div class="form-section">
            <h2><i class="fas fa-address-card"></i> Coordonnées</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <div class="input-with-icon">
                        <i class="fas fa-map-marker-alt"></i>
                        <textarea id="adresse" name="adresse" 
                                  placeholder="Adresse complète"
                                  rows="2">{{ old('adresse') }}</textarea>
                    </div>
                    @error('adresse')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <div class="input-with-icon">
                        <i class="fas fa-phone"></i>
                        <input type="text" id="telephone" name="telephone" 
                               value="{{ old('telephone') }}" 
                               placeholder="+221 XX XXX XX XX">
                    </div>
                    @error('telephone')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="description">Description</label>
                    <div class="input-with-icon">
                        <i class="fas fa-align-left"></i>
                        <textarea id="description" name="description" 
                                  placeholder="Description détaillée"
                                  rows="3">{{ old('description') }}</textarea>
                    </div>
                    @error('description')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <div class="action-buttons">
                <a href="{{ route('admin.services.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    Annuler
                </a>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i>
                    Créer le service
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
        --black: #000000;
        --dark-gray: #1a1a1a;
        --medium-gray: #333333;
        --light-gray: #f5f5f5;
        --white: #ffffff;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --card-bg: #ffffff;
    }

    .create-service-container {
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .service-form {
        padding: 40px;
    }

    .form-section {
        margin-bottom: 30px;
        padding: 30px;
        border: 2px solid var(--light-gray);
        border-radius: 16px;
        background: var(--white);
        transition: all 0.3s ease;
    }

    .form-section:hover {
        border-color: var(--primary-light);
        box-shadow: 0 10px 30px rgba(3, 81, 188, 0.1);
    }

    .form-section h2 {
        color: var(--primary-color);
        margin-bottom: 25px;
        font-size: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--light-gray);
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
        z-index: 2;
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
    }

    .input-with-icon textarea {
        resize: vertical;
        min-height: 60px;
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
    }

    .error {
        color: var(--danger);
        font-size: 12px;
        margin-top: 8px;
        display: block;
        font-weight: 500;
    }

    /* Form Actions */
    .form-actions {
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid var(--light-gray);
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
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
    }

    /* Responsive */
    @media (max-width: 768px) {
        .service-form {
            padding: 20px;
        }

        .form-section {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
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
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fonction pour afficher/masquer le champ type personnalisé
        function toggleCustomType() {
            const typeSelect = document.getElementById('type');
            const customTypeGroup = document.getElementById('typeCustomGroup');
            
            if (typeSelect.value === 'autre') {
                customTypeGroup.style.display = 'block';
                document.getElementById('type_custom').required = true;
            } else {
                customTypeGroup.style.display = 'none';
                document.getElementById('type_custom').required = false;
            }
        }
        
        // Initialiser l'état du champ type personnalisé
        toggleCustomType();
        
        // Écouter les changements sur le select type
        document.getElementById('type').addEventListener('change', toggleCustomType);
        
        // Auto-génération du code géographique
        const typeSelect = document.getElementById('type');
        const nomInput = document.getElementById('nom');
        const codeInput = document.getElementById('code_geographique');
        
        function generateCode() {
            if (typeSelect.value && nomInput.value && !codeInput.value) {
                let typeValue = typeSelect.value;
                
                // Si c'est "autre", utiliser la valeur personnalisée si disponible
                if (typeValue === 'autre') {
                    const typeCustom = document.getElementById('type_custom').value;
                    if (typeCustom) {
                        typeValue = typeCustom;
                    }
                }
                
                const type = typeValue.toUpperCase().substring(0, 3);
                const nom = nomInput.value.toUpperCase().replace(/[^A-Z]/g, '').substring(0, 3);
                const timestamp = new Date().toISOString().slice(2, 10).replace(/-/g, '');
                const random = Math.random().toString(36).substring(2, 5).toUpperCase();
                
                codeInput.value = `${type}-${nom}-${timestamp}${random}`;
            }
        }
        
        typeSelect.addEventListener('change', generateCode);
        nomInput.addEventListener('blur', generateCode);
        
        // Également générer le code si on saisit un type personnalisé
        document.getElementById('type_custom')?.addEventListener('blur', generateCode);
        
        // Validation du formulaire
        const form = document.getElementById('serviceForm');
        const submitBtn = document.getElementById('submitBtn');
        
        form.addEventListener('submit', function(e) {
            // Validation
            const type = typeSelect.value.trim();
            const nom = nomInput.value.trim();
            
            if (!type) {
                e.preventDefault();
                showAlert('Erreur', 'Veuillez sélectionner un type.', 'error');
                typeSelect.focus();
                return;
            }
            
            if (type === 'autre') {
                const typeCustom = document.getElementById('type_custom').value.trim();
                if (!typeCustom) {
                    e.preventDefault();
                    showAlert('Erreur', 'Veuillez saisir le type personnalisé.', 'error');
                    document.getElementById('type_custom').focus();
                    return;
                }
            }
            
            if (!nom) {
                e.preventDefault();
                showAlert('Erreur', 'Veuillez saisir un nom.', 'error');
                nomInput.focus();
                return;
            }
        });

        function showAlert(title, text, icon) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                confirmButtonColor: '#0351BC',
                confirmButtonText: 'OK'
            });
        }

        // Initialiser Select2 pour les select
        $('#parent_id, #responsable_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Sélectionnez une option',
            allowClear: true,
            width: '100%'
        });

        $('#type').select2({
            theme: 'bootstrap-5',
            placeholder: 'Sélectionnez un type',
            width: '100%'
        });
    });
</script>
@endpush