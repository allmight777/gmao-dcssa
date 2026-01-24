@extends('layouts.admin')

@section('title', 'Créer un service/localisation')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', 'Créer un service/localisation')

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
                    <small class="form-hint">
                        <a href="javascript:void(0)" onclick="showTypeHelp()" class="link-help">
                            <i class="fas fa-question-circle"></i> Besoin d'un type non listé ?
                        </a>
                    </small>
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
                    <div class="search-container">
                        <div class="input-with-icon">
                            <i class="fas fa-search"></i>
                            <input type="text" id="parent_search" class="search-input" 
                                   placeholder="Rechercher un parent..." 
                                   onkeyup="filterSelect('parent_id', this.value)">
                        </div>
                        <div class="input-with-icon">
                            <i class="fas fa-level-up-alt"></i>
                            <select id="parent_id" name="parent_id" class="form-select" size="5">
                                <option value="">Aucun parent (racine)</option>
                                @foreach($parents as $id => $label)
                                    <option value="{{ $id }}" {{ old('parent_id') == $id ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="select-actions">
                            <a href="{{ route('admin.services.index') }}" target="_blank" class="btn-action">
                                <i class="fas fa-external-link-alt"></i> Voir tous les services
                            </a>
                            <a href="{{ route('admin.services.create') }}" target="_blank" class="btn-action">
                                <i class="fas fa-plus"></i> Créer un nouveau service
                            </a>
                        </div>
                    </div>
                    <small class="form-hint">Sélectionnez l'élément parent dans la hiérarchie</small>
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
                    <div class="search-container">
                        <div class="input-with-icon">
                            <i class="fas fa-search"></i>
                            <input type="text" id="responsable_search" class="search-input" 
                                   placeholder="Rechercher un responsable..." 
                                   onkeyup="filterSelect('responsable_id', this.value)">
                        </div>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <select id="responsable_id" name="responsable_id" class="form-select" size="5">
                                <option value="">Aucun responsable</option>
                                @foreach($responsables as $id => $label)
                                    <option value="{{ $id }}" {{ old('responsable_id') == $id ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="select-actions">
                            <a href="{{ route('admin.comptes.index') }}" target="_blank" class="btn-action">
                                <i class="fas fa-external-link-alt"></i> Voir tous les utilisateurs
                            </a>
                            <a href="{{ route('admin.comptes.create') }}" target="_blank" class="btn-action">
                                <i class="fas fa-plus"></i> Créer un nouvel utilisateur
                            </a>
                        </div>
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

<!-- Modal d'aide pour le type -->
<div id="typeHelpModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-question-circle"></i> Aide - Types de localisation</h3>
            <button type="button" class="modal-close" onclick="closeTypeHelp()">&times;</button>
        </div>
        <div class="modal-body">
            <p><strong>Si votre type n'est pas dans la liste :</strong></p>
            <ol>
                <li>Sélectionnez "Autre (saisie libre)" dans la liste</li>
                <li>Un champ supplémentaire apparaîtra</li>
                <li>Saisissez votre type spécifique (ex: Commission, Cellule, Unité, etc.)</li>
            </ol>
            <p><strong>Types recommandés :</strong></p>
            <ul>
                <li><strong>Service</strong> : Unité administrative (Direction, Département)</li>
                <li><strong>Site</strong> : Localisation géographique (Hôpital, Camp)</li>
                <li><strong>Bâtiment</strong> : Édifice physique</li>
                <li><strong>Salle/Bureau</strong> : Pièce spécifique</li>
                <li><strong>Laboratoire/Atelier</strong> : Espace technique</li>
            </ul>
            <div class="modal-actions">
                <button type="button" class="btn-primary" onclick="closeTypeHelp()">
                    <i class="fas fa-check"></i> Compris
                </button>
            </div>
        </div>
    </div>
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
        --modal-overlay: rgba(0, 0, 0, 0.5);
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
        line-height: 1.4;
    }

    .form-hint .link-help {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
    }

    .form-hint .link-help:hover {
        text-decoration: underline;
    }

    .error {
        color: var(--danger);
        font-size: 12px;
        margin-top: 8px;
        display: block;
        font-weight: 500;
    }

    /* Search Container */
    .search-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .search-input {
        width: 100%;
        padding: 10px 10px 10px 40px;
        border: 2px solid var(--light-gray);
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(3, 81, 188, 0.1);
        outline: none;
    }

    .select-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 5px;
    }

    .btn-action {
        background: var(--light-gray);
        color: var(--medium-gray);
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        background: var(--primary-light);
        color: var(--white);
        transform: translateY(-1px);
    }

    /* Style amélioré pour les selects avec scroll */
    .form-select[multiple],
    .form-select[size] {
        height: auto;
        min-height: 120px;
        max-height: 200px;
        overflow-y: auto;
    }

    .form-select option {
        padding: 8px 12px;
        border-bottom: 1px solid var(--light-gray);
        cursor: pointer;
    }

    .form-select option:hover {
        background-color: var(--light-gray);
    }

    .form-select option:checked {
        background-color: var(--primary-light);
        color: var(--white);
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

    /* Modal */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: var(--modal-overlay);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: var(--white);
        border-radius: 16px;
        width: 90%;
        max-width: 500px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        padding: 20px;
        border-bottom: 2px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        color: var(--primary-color);
        margin: 0;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: var(--medium-gray);
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        color: var(--danger);
    }

    .modal-body {
        padding: 20px;
    }

    .modal-body p {
        margin-bottom: 15px;
        line-height: 1.6;
    }

    .modal-body ol, .modal-body ul {
        margin-bottom: 20px;
        padding-left: 20px;
    }

    .modal-body li {
        margin-bottom: 8px;
        line-height: 1.5;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .btn-primary {
        background: var(--primary-color);
        color: var(--white);
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
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

        .select-actions {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }

        .modal-content {
            width: 95%;
            margin: 10px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fonction pour afficher/masquer le champ type personnalisé
        function toggleCustomType() {
            const typeSelect = document.getElementById('type');
            const customTypeGroup = document.getElementById('typeCustomGroup');
            
            if (typeSelect.value === 'autre') {
                customTypeGroup.style.display = 'block';
                document.getElementById('type_custom').required = true;
                
                // Afficher une alerte d'information
                Swal.fire({
                    title: 'Type personnalisé',
                    html: `
                        <p>Vous avez sélectionné "Autre".</p>
                        <p>Veuillez saisir le type spécifique dans le champ qui apparaît.</p>
                        <p><strong>Exemples :</strong> Commission, Cellule, Unité, Division, etc.</p>
                    `,
                    icon: 'info',
                    confirmButtonColor: '#0351BC',
                    confirmButtonText: 'Compris'
                });
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
            
            // Confirmation avant soumission
            e.preventDefault();
            const parentSelect = document.getElementById('parent_id');
            const responsableSelect = document.getElementById('responsable_id');
            
            let message = 'Êtes-vous sûr de vouloir créer ce service/localisation ?';
            
            if (parentSelect.value || responsableSelect.value) {
                message += '\n\n';
                if (parentSelect.value) {
                    const parentText = parentSelect.options[parentSelect.selectedIndex].text;
                    message += `• Parent : ${parentText}\n`;
                }
                if (responsableSelect.value) {
                    const responsableText = responsableSelect.options[responsableSelect.selectedIndex].text;
                    message += `• Responsable : ${responsableText}\n`;
                }
            }
            
            Swal.fire({
                title: 'Confirmer la création',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0351BC',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, créer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Désactiver le bouton pour éviter les doubles soumissions
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création en cours...';
                    
                    // Soumettre le formulaire
                    form.submit();
                }
            });
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
    });

    // Fonction pour filtrer les options des selects
    function filterSelect(selectId, searchText) {
        const select = document.getElementById(selectId);
        const options = select.options;
        searchText = searchText.toLowerCase();
        
        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            const text = option.text.toLowerCase();
            
            if (text.includes(searchText) || searchText === '') {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        }
    }

    // Fonctions pour la modal d'aide
    function showTypeHelp() {
        document.getElementById('typeHelpModal').style.display = 'flex';
    }

    function closeTypeHelp() {
        document.getElementById('typeHelpModal').style.display = 'none';
    }

    // Fermer la modal en cliquant en dehors
    window.onclick = function(event) {
        const modal = document.getElementById('typeHelpModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endpush