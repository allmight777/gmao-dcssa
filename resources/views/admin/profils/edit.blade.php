@extends('layouts.admin')

@section('title', 'Modifier le profil')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', 'Modifier le profil')

@section('page-actions')
<a href="{{ route('admin.profils.index') }}" class="btn-return">
    <i class="fas fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')
<div class="create-profil-container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.profils.update', $profil) }}" class="profil-form" id="profilForm">
        @csrf
        @method('PUT')
        
        <!-- Section Informations du profil -->
        <div class="form-section">
            <h2><i class="fas fa-id-card"></i> Informations du profil</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="nom_profil">Nom du profil *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-tag"></i>
                        <input type="text" id="nom_profil" name="nom_profil" 
                               value="{{ old('nom_profil', $profil->nom_profil) }}" 
                               placeholder="Ex: administrateur_systeme" required>
                    </div>
                    <small class="form-hint">Utilisez des underscores (_) pour les espaces</small>
                    @error('nom_profil')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <div class="input-with-icon">
                        <i class="fas fa-align-left"></i>
                        <textarea id="description" name="description" 
                                  placeholder="Description du rôle et des responsabilités"
                                  rows="2">{{ old('description', $profil->description) }}</textarea>
                    </div>
                    @error('description')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Permissions -->
        <div class="form-section">
            <div class="permissions-header">
                <h2><i class="fas fa-key"></i> Permissions</h2>
                <div class="global-select">
                    <input type="checkbox" id="selectAllModules">
                    <label for="selectAllModules">
                        <span class="checkmark"></span>
                        Tout sélectionner
                    </label>
                </div>
            </div>
            
            <p class="permissions-intro">Sélectionnez les modules et actions autorisés pour ce profil</p>
            
            <div class="permissions-grid">
                @foreach($modules as $moduleKey => $moduleName)
                @php
                    $actions = \App\Models\Profil::getAvailableActions($moduleKey);
                    $oldPermissions = old("permissions.{$moduleKey}.actions", 
                        $permissionsParModule->has($moduleKey) 
                            ? $permissionsParModule[$moduleKey]->pluck('action')->toArray() 
                            : []
                    );
                @endphp
                
                <div class="module-card">
                    <div class="module-card-header">
                        <div class="module-checkbox">
                            <input type="checkbox" 
                                   class="module-checkbox-input" 
                                   id="module_{{ $moduleKey }}"
                                   data-module="{{ $moduleKey }}"
                                   @if(count($oldPermissions) === count($actions)) checked @endif
                                   @if(count($oldPermissions) > 0 && count($oldPermissions) < count($actions)) 
                                        data-indeterminate="true"
                                   @endif>
                            <label for="module_{{ $moduleKey }}" class="module-title">
                                <span class="checkmark"></span>
                                <span class="module-name">{{ $moduleName }}</span>
                                <span class="module-icon">
                                    @switch($moduleKey)
                                        @case('utilisateurs')
                                            <i class="fas fa-users"></i>
                                            @break
                                        @case('profils')
                                            <i class="fas fa-user-tag"></i>
                                            @break
                                        @case('comptes')
                                            <i class="fas fa-user-cog"></i>
                                            @break
                                        @case('inventaire')
                                            <i class="fas fa-boxes"></i>
                                            @break
                                        @case('rapports')
                                            <i class="fas fa-chart-bar"></i>
                                            @break
                                        @case('administration')
                                            <i class="fas fa-cogs"></i>
                                            @break
                                        @case('maintenance')
                                            <i class="fas fa-tools"></i>
                                            @break
                                        @case('stock')
                                            <i class="fas fa-warehouse"></i>
                                            @break
                                        @default
                                            <i class="fas fa-cog"></i>
                                    @endswitch
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="module-card-body">
                        @if(!empty($actions))
                        <div class="actions-grid">
                            @foreach($actions as $action)
                            <div class="action-item">
                                <input type="checkbox" 
                                       class="action-checkbox action-checkbox-{{ $moduleKey }}" 
                                       name="permissions[{{ $moduleKey }}][actions][]"
                                       value="{{ $action }}"
                                       id="{{ $moduleKey }}_{{ $action }}"
                                       {{ in_array($action, $oldPermissions) ? 'checked' : '' }}>
                                <label for="{{ $moduleKey }}_{{ $action }}">
                                    <span class="checkmark"></span>
                                    <span class="action-name">
                                        @switch($action)
                                            @case('view')
                                                <i class="fas fa-eye"></i> Voir
                                                @break
                                            @case('create')
                                                <i class="fas fa-plus"></i> Créer
                                                @break
                                            @case('edit')
                                                <i class="fas fa-edit"></i> Modifier
                                                @break
                                            @case('delete')
                                                <i class="fas fa-trash"></i> Supprimer
                                                @break
                                            @case('export')
                                                <i class="fas fa-download"></i> Exporter
                                                @break
                                            @case('import')
                                                <i class="fas fa-upload"></i> Importer
                                                @break
                                            @case('print')
                                                <i class="fas fa-print"></i> Imprimer
                                                @break
                                            @case('manage')
                                                <i class="fas fa-cog"></i> Gérer
                                                @break
                                            @case('validate')
                                                <i class="fas fa-check-circle"></i> Valider
                                                @break
                                            @case('transfer')
                                                <i class="fas fa-exchange-alt"></i> Transférer
                                                @break
                                            @case('order')
                                                <i class="fas fa-shopping-cart"></i> Commander
                                                @break
                                            @case('receive')
                                                <i class="fas fa-box-open"></i> Réceptionner
                                                @break
                                            @default
                                                <i class="fas fa-check"></i> {{ ucfirst($action) }}
                                        @endswitch
                                    </span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="no-actions">
                            <i class="fas fa-info-circle"></i>
                            <span>Aucune action disponible</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="form-actions">
            <div class="action-buttons">
                <a href="{{ route('admin.profils.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    Annuler
                </a>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i>
                    Mettre à jour le profil
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

    .alert-danger {
        background: var(--danger);
        color: var(--white);
        padding: 15px 20px;
        border-radius: 10px;
        margin: 20px 40px;
        font-weight: 500;
        font-size: 14px;
    }

    .alert-danger ul {
        padding-left: 20px;
    }

    .create-profil-container {
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .profil-form {
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
        margin-bottom: 10px;
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

    /* Permissions Header */
    .permissions-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .global-select {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 16px;
        background: var(--light-gray);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .global-select:hover {
        background: #e8e8e8;
    }

    .global-select input[type="checkbox"] {
        display: none;
    }

    .global-select label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-weight: 600;
        color: var(--dark-gray);
        margin: 0;
    }

    .permissions-intro {
        color: var(--medium-gray);
        margin-bottom: 25px;
        font-size: 14px;
    }

    /* Permissions Grid */
    .permissions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
    }

    .module-card {
        background: var(--card-bg);
        border: 2px solid var(--light-gray);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .module-card:hover {
        border-color: var(--primary-light);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(3, 81, 188, 0.1);
    }

    .module-card-header {
        padding: 20px;
        background: linear-gradient(to right, var(--light-gray), #f0f5ff);
        border-bottom: 2px solid var(--light-gray);
    }

    .module-checkbox {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .module-checkbox-input {
        display: none;
    }

    .module-title {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        font-weight: 600;
        color: var(--dark-gray);
        font-size: 15px;
    }

    .checkmark {
        width: 20px;
        height: 20px;
        border: 2px solid var(--medium-gray);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        position: relative;
    }

    .module-checkbox-input:checked + .module-title .checkmark,
    .action-checkbox:checked + label .checkmark {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    .module-checkbox-input:checked + .module-title .checkmark::after,
    .action-checkbox:checked + label .checkmark::after {
        content: '✓';
        color: var(--white);
        font-size: 12px;
        font-weight: bold;
    }

    .module-checkbox-input:indeterminate + .module-title .checkmark {
        background: var(--primary-light);
        border-color: var(--primary-light);
    }

    .module-checkbox-input:indeterminate + .module-title .checkmark::after {
        content: '–';
        color: var(--white);
        font-size: 14px;
        font-weight: bold;
    }

    .module-name {
        flex: 1;
    }

    .module-icon {
        color: var(--primary-color);
        font-size: 18px;
    }

    .module-card-body {
        padding: 20px;
        max-height: 300px;
        overflow-y: auto;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
    }

    .action-item {
        display: flex;
        align-items: center;
    }

    .action-checkbox {
        display: none;
    }

    .action-item label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.3s ease;
        width: 100%;
        font-size: 13px;
        color: var(--medium-gray);
    }

    .action-item label:hover {
        background: var(--light-gray);
    }

    .action-checkbox:checked + label {
        color: var(--primary-color);
        background: rgba(3, 81, 188, 0.05);
        font-weight: 500;
    }

    .action-checkbox:checked + label .action-name i {
        color: var(--primary-color);
    }

    .action-name {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .action-name i {
        font-size: 12px;
        width: 16px;
        text-align: center;
        color: var(--medium-gray);
    }

    .no-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 30px;
        color: var(--medium-gray);
        font-style: italic;
    }

    .no-actions i {
        font-size: 16px;
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

    /* Scrollbar */
    .module-card-body::-webkit-scrollbar {
        width: 6px;
    }

    .module-card-body::-webkit-scrollbar-track {
        background: var(--light-gray);
        border-radius: 3px;
    }

    .module-card-body::-webkit-scrollbar-thumb {
        background: var(--primary-light);
        border-radius: 3px;
    }

    .module-card-body::-webkit-scrollbar-thumb:hover {
        background: var(--primary-color);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .permissions-grid {
            grid-template-columns: 1fr;
        }
        
        .actions-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .profil-form {
            padding: 20px;
        }

        .form-section {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .permissions-header {
            flex-direction: column;
            align-items: stretch;
        }

        .actions-grid {
            grid-template-columns: 1fr;
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
        // Auto-format du nom du profil
        const nomProfilInput = document.getElementById('nom_profil');
        nomProfilInput.addEventListener('blur', function() {
            let value = this.value;
            value = value.toLowerCase()
                        .replace(/[^a-z0-9_]/g, '_')
                        .replace(/_+/g, '_')
                        .replace(/^_|_$/g, '');
            this.value = value;
        });

        // Sélectionner/désélectionner toutes les actions d'un module
        const moduleCheckboxes = document.querySelectorAll('.module-checkbox-input');
        moduleCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const module = this.dataset.module;
                const isChecked = this.checked;
                const actionCheckboxes = document.querySelectorAll(`.action-checkbox-${module}`);
                
                actionCheckboxes.forEach(action => {
                    action.checked = isChecked;
                });
                
                // Supprimer l'état indéterminé si coché
                this.indeterminate = false;
            });
        });

        // Sélectionner/désélectionner toutes les permissions
        const selectAllCheckbox = document.getElementById('selectAllModules');
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            
            // Mettre à jour tous les modules
            moduleCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                checkbox.indeterminate = false;
            });
            
            // Mettre à jour toutes les actions
            const allActions = document.querySelectorAll('.action-checkbox');
            allActions.forEach(action => {
                action.checked = isChecked;
            });
        });

        // Gérer l'état du module checkbox basé sur les actions
        const actionCheckboxes = document.querySelectorAll('.action-checkbox');
        actionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const moduleClass = this.classList[1]; // "action-checkbox-module"
                const module = moduleClass.split('-')[2];
                const moduleCheckbox = document.getElementById(`module_${module}`);
                const totalActions = document.querySelectorAll(`.action-checkbox-${module}`).length;
                const checkedActions = document.querySelectorAll(`.action-checkbox-${module}:checked`).length;
                
                if (checkedActions === 0) {
                    moduleCheckbox.checked = false;
                    moduleCheckbox.indeterminate = false;
                } else if (checkedActions === totalActions) {
                    moduleCheckbox.checked = true;
                    moduleCheckbox.indeterminate = false;
                } else {
                    moduleCheckbox.checked = false;
                    moduleCheckbox.indeterminate = true;
                }
                
                // Mettre à jour l'état de "Sélectionner tout"
                updateSelectAllState();
            });
        });

        // Mettre à jour l'état du checkbox "Sélectionner tout"
        function updateSelectAllState() {
            const totalActions = document.querySelectorAll('.action-checkbox').length;
            const checkedActions = document.querySelectorAll('.action-checkbox:checked').length;
            
            if (checkedActions === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (checkedActions === totalActions) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            }
        }

        // Validation du formulaire
        const form = document.getElementById('profilForm');
        const submitBtn = document.getElementById('submitBtn');
        
        form.addEventListener('submit', function(e) {
            let hasPermissions = false;
            
            // Vérifier si au moins une permission est sélectionnée
            actionCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    hasPermissions = true;
                }
            });
            
            // Valider le nom du profil
            const nomProfil = nomProfilInput.value.trim();
            if (!nomProfil) {
                e.preventDefault();
                showAlert('Erreur', 'Le nom du profil est requis.', 'error');
                return;
            }
            
            if (!hasPermissions) {
                e.preventDefault();
                showAlert('Aucune permission sélectionnée', 
                          'Veuillez sélectionner au moins une permission pour ce profil.', 
                          'warning');
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

        // Initialiser les états indéterminés au chargement
        moduleCheckboxes.forEach(checkbox => {
            if (checkbox.hasAttribute('data-indeterminate') && checkbox.dataset.indeterminate === 'true') {
                checkbox.indeterminate = true;
            }
        });

        // Initialiser l'état de "Sélectionner tout"
        updateSelectAllState();
    });
</script>
@endpush