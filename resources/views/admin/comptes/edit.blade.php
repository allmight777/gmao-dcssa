@extends('layouts.admin')

@section('title', 'Modifier le compte')

@section('page-title', 'Modifier le compte')

@section('page-actions')
<a href="{{ route('admin.comptes.index') }}" class="btn-return">
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

    @if(session('success'))
        <div class="success-message">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.comptes.update', $compte) }}" class="service-form" id="compteForm">
        @csrf
        @method('PUT')

        <!-- Section Informations Personnelles -->
        <div class="form-section">
            <h2><i class="fas fa-id-card"></i> Informations Personnelles</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="matricule">Matricule *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-id-badge"></i>
                        <input type="text" id="matricule" name="matricule" 
                               value="{{ old('matricule', $compte->matricule) }}" 
                               placeholder="Ex: MAT001" required>
                    </div>
                    <small class="form-hint">Identifiant unique de l'agent</small>
                    @error('matricule')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="profil_id">Profil *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user-tag"></i>
                        <select id="profil_id" name="profil_id" class="form-select" required>
                            <option value="">Sélectionnez un profil</option>
                            @foreach($profils as $profil)
                                <option value="{{ $profil->id }}" {{ old('profil_id', $compte->profil_id) == $profil->id ? 'selected' : '' }}>
                                    {{ $profil->nom_profil }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                     <br>
                      <a href="{{ route('admin.profils.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Nouveau profil
    </a>
                    <small class="form-hint">Détermine les permissions de l'utilisateur</small>
                    @error('profil_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="nom" name="nom" 
                               value="{{ old('nom', $compte->nom) }}" 
                               placeholder="Votre nom" required>
                    </div>
                    @error('nom')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="prenom">Prénom *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="prenom" name="prenom" 
                               value="{{ old('prenom', $compte->prenom) }}" 
                               placeholder="Votre prénom" required>
                    </div>
                    @error('prenom')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Informations Professionnelles -->
        <div class="form-section">
            <h2><i class="fas fa-briefcase"></i> Informations Professionnelles</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="grade">Grade</label>
                    <div class="input-with-icon">
                        <i class="fas fa-graduation-cap"></i>
                        <input type="text" id="grade" name="grade" 
                               value="{{ old('grade', $compte->grade) }}" 
                               placeholder="Ex: Ingénieur">
                    </div>
                    @error('grade')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="fonction">Fonction *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-tasks"></i>
                        <input type="text" id="fonction" name="fonction" 
                               value="{{ old('fonction', $compte->fonction) }}" 
                               placeholder="Ex: Responsable IT" required>
                    </div>
                    @error('fonction')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="service_id">Service</label>
                    <div class="search-container">
                        <div class="input-with-icon">
                            <i class="fas fa-search"></i>
                            <input type="text" id="service_search" class="search-input" 
                                   placeholder="Rechercher un service..." 
                                   onkeyup="filterSelect('service_id', this.value)">
                        </div>
                        <div class="input-with-icon">
                            <i class="fas fa-building"></i>
                            <select id="service_id" name="service_id" class="form-select" size="5">
                                <option value="">Sélectionnez un service</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id', $compte->service_id) == $service->id ? 'selected' : '' }}>
                                        {{ $service->nom }}
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
                    @error('service_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="statut">Statut *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-circle"></i>
                        <select id="statut" name="statut" class="form-select" required>
                            <option value="actif" {{ old('statut', $compte->statut) == 'actif' ? 'selected' : '' }}>
                                Actif
                            </option>
                            <option value="inactif" {{ old('statut', $compte->statut) == 'inactif' ? 'selected' : '' }}>
                                Inactif
                            </option>
                            <option value="suspendu" {{ old('statut', $compte->statut) == 'suspendu' ? 'selected' : '' }}>
                                Suspendu
                            </option>
                        </select>
                    </div>
                    @error('statut')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Coordonnées -->
        <div class="form-section">
            <h2><i class="fas fa-address-card"></i> Coordonnées</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" 
                               value="{{ old('email', $compte->email) }}" 
                               placeholder="exemple@dcssa.sn" required>
                    </div>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <div class="input-with-icon">
                        <i class="fas fa-phone"></i>
                        <input type="text" id="telephone" name="telephone" 
                               value="{{ old('telephone', $compte->telephone) }}" 
                               placeholder="+221 XX XXX XX XX">
                    </div>
                    @error('telephone')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Authentification -->
        <div class="form-section">
            <h2><i class="fas fa-key"></i> Authentification</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="login">Login *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-sign-in-alt"></i>
                        <input type="text" id="login" name="login" 
                               value="{{ old('login', $compte->login) }}" 
                               placeholder="Nom d'utilisateur" required>
                    </div>
                    @error('login')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="password-reset-section">
                <div class="password-reset-header">
                    <h3><i class="fas fa-lock"></i> Réinitialisation du mot de passe</h3>
                    <button type="button" class="btn-toggle-password-reset" id="togglePasswordReset">
                        <i class="fas fa-chevron-down"></i> Modifier le mot de passe
                    </button>
                </div>
                
                <div class="password-reset-fields" id="passwordResetFields" style="display: none;">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="new_password">Nouveau mot de passe</label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="new_password" name="new_password" 
                                       placeholder="Minimum 12 caractères (laissez vide pour conserver l'actuel)">
                                <button type="button" class="password-toggle" id="toggleNewPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-hint">Si rempli, le mot de passe sera mis à jour</small>
                        </div>

                        <div class="form-group">
                            <label for="new_password_confirmation">Confirmer le mot de passe</label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" 
                                       placeholder="Confirmez le nouveau mot de passe">
                            </div>
                        </div>
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
                <a href="{{ route('admin.comptes.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    Annuler
                </a>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i>
                    Enregistrer les modifications
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
        --modal-overlay: rgba(0, 0, 0, 0.5);
    }

    .create-service-container {
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .success-message {
        background: var(--success);
        color: var(--white);
        padding: 15px 20px;
        border-radius: 10px;
        margin: 20px 40px;
        text-align: center;
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
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

    /* Password Toggle */
    .password-toggle {
        position: absolute;
        right: 15px;
        background: none;
        border: none;
        color: var(--medium-gray);
        cursor: pointer;
        font-size: 16px;
        z-index: 2;
    }

    .password-toggle:hover {
        color: var(--primary-color);
    }

    /* Password Reset Section */
    .password-reset-section {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--light-gray);
    }

    .password-reset-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .password-reset-header h3 {
        color: var(--primary-color);
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }

    .btn-toggle-password-reset {
        background: var(--light-gray);
        color: var(--dark-gray);
        padding: 8px 16px;
        border: 2px solid var(--medium-gray);
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-toggle-password-reset:hover {
        background: var(--medium-gray);
        color: var(--white);
        border-color: var(--dark-gray);
    }

    .password-reset-fields {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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

    /* Responsive */
    @media (max-width: 768px) {
        .service-form {
            padding: 20px;
        }

        .success-message {
            margin: 20px;
        }

        .form-section {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .password-reset-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .form-actions {
            flex-direction: column;
            gap: 20px;
            align-items: stretch;
        }

        .action-buttons {
            flex-direction: column;
        }

        .requirements-grid {
            grid-template-columns: 1fr;
        }

        .select-actions {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('compteForm');
        const submitBtn = document.getElementById('submitBtn');
        
        // Toggle password reset fields
        const toggleBtn = document.getElementById('togglePasswordReset');
        const resetFields = document.getElementById('passwordResetFields');
        
        toggleBtn.addEventListener('click', function() {
            if (resetFields.style.display === 'none') {
                resetFields.style.display = 'block';
                toggleBtn.innerHTML = '<i class="fas fa-chevron-up"></i> Masquer';
            } else {
                resetFields.style.display = 'none';
                toggleBtn.innerHTML = '<i class="fas fa-chevron-down"></i> Modifier le mot de passe';
            }
        });

        // Toggle new password visibility
        const toggleNewPasswordBtn = document.getElementById('toggleNewPassword');
        const newPasswordInput = document.getElementById('new_password');
        
        if (toggleNewPasswordBtn && newPasswordInput) {
            toggleNewPasswordBtn.addEventListener('click', function() {
                const type = newPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                newPasswordInput.setAttribute('type', type);
                this.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            });
        }

        // Vérification du mot de passe
        function checkPassword(password) {
            const checks = {
                length: password.length >= 12,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
            };
            
            return checks;
        }

        // Valider le formulaire
        function validateForm() {
            const newPassword = document.getElementById('new_password')?.value || '';
            const confirmPassword = document.getElementById('new_password_confirmation')?.value || '';
            
            // Si un nouveau mot de passe est fourni
            if (newPassword) {
                // Vérifier la longueur
                if (newPassword.length < 12) {
                    return {
                        valid: false,
                        message: 'Le nouveau mot de passe doit contenir au moins 12 caractères.'
                    };
                }
                
                // Vérifier la correspondance
                if (newPassword !== confirmPassword) {
                    return {
                        valid: false,
                        message: 'Les nouveaux mots de passe ne correspondent pas.'
                    };
                }
                
                // Vérifier la force du mot de passe
                const checks = checkPassword(newPassword);
                const allValid = Object.values(checks).every(Boolean);
                
                if (!allValid) {
                    return {
                        valid: false,
                        message: 'Le nouveau mot de passe ne respecte pas toutes les exigences de sécurité.'
                    };
                }
            }
            
            return { valid: true };
        }

        // Validation avant soumission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const validation = validateForm();
            
            if (!validation.valid) {
                showAlert('Erreur de validation', validation.message, 'error');
                return;
            }
            
            // Vérifier si des modifications ont été apportées
            const formData = new FormData(form);
            const originalData = {
                matricule: '{{ $compte->matricule }}',
                nom: '{{ $compte->nom }}',
                prenom: '{{ $compte->prenom }}',
                grade: '{{ $compte->grade }}',
                fonction: '{{ $compte->fonction }}',
                service_id: '{{ $compte->service_id }}',
                email: '{{ $compte->email }}',
                telephone: '{{ $compte->telephone }}',
                login: '{{ $compte->login }}',
                profil_id: '{{ $compte->profil_id }}',
                statut: '{{ $compte->statut }}'
            };
            
            let hasChanges = false;
            let changes = [];
            
            for (let [key, value] of formData.entries()) {
                value = value.toString().trim();
                if (key === 'new_password' && value === '') continue;
                if (key === 'new_password_confirmation' && value === '') continue;
                if (key === '_method' || key === '_token') continue;
                
                if (value !== originalData[key]) {
                    hasChanges = true;
                    
                    // Récupérer les noms des champs pour l'affichage
                    const fieldNames = {
                        matricule: 'Matricule',
                        nom: 'Nom',
                        prenom: 'Prénom',
                        grade: 'Grade',
                        fonction: 'Fonction',
                        service_id: 'Service',
                        email: 'Email',
                        telephone: 'Téléphone',
                        login: 'Login',
                        profil_id: 'Profil',
                        statut: 'Statut',
                        new_password: 'Mot de passe'
                    };
                    
                    const fieldName = fieldNames[key] || key;
                    changes.push(fieldName);
                }
            }
            
            if (!hasChanges) {
                Swal.fire({
                    title: 'Aucune modification',
                    text: 'Vous n\'avez effectué aucune modification.',
                    icon: 'info',
                    confirmButtonColor: '#0351BC',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = "{{ route('admin.comptes.index') }}";
                });
                return;
            }
            
            // Confirmation avant soumission
            let message = 'Êtes-vous sûr de vouloir mettre à jour ce compte ?';
            
            if (changes.length > 0) {
                message += '\n\nChangements détectés :';
                changes.forEach(change => {
                    message += `\n• ${change}`;
                });
            }
            
            if (document.getElementById('new_password')?.value) {
                message += '\n\n⚠️ Le mot de passe sera modifié.';
            }
            
            Swal.fire({
                title: 'Confirmer la modification',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0351BC',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, enregistrer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Désactiver le bouton pour éviter les doubles soumissions
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement en cours...';
                    
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
</script>
@endpush