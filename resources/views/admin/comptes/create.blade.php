@extends('layouts.admin')

@section('title', 'Créer un nouveau compte')

@section('page-title', 'Créer un nouveau compte')
<br><br>
@section('page-actions')
<a href="{{ route('admin.comptes.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')
<div class="create-compte-container">


    @if(session('success'))
        <div class="success-message">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.comptes.store') }}" class="compte-form" id="compteForm">
        @csrf

        <!-- Section Informations Personnelles -->
        <div class="form-section">
            <h2><i class="fas fa-id-card"></i> Informations Personnelles</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="matricule">Matricule *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-id-badge"></i>
                        <input type="text" id="matricule" name="matricule" 
                               value="{{ old('matricule') }}" 
                               placeholder="Ex: MAT001" required>
                    </div>
                    @error('matricule')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="profil_id">Profil *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user-tag"></i>
                        <select id="profil_id" name="profil_id" required>
                            <option value="">Sélectionnez un profil</option>
                            @foreach($profils as $profil)
                                <option value="{{ $profil->id }}" {{ old('profil_id') == $profil->id ? 'selected' : '' }}>
                                    {{ $profil->nom_profil }}
                                </option>
                            @endforeach
                        </select>
                    </div>
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
                               value="{{ old('nom') }}" 
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
                               value="{{ old('prenom') }}" 
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
                               value="{{ old('grade') }}" 
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
                               value="{{ old('fonction') }}" 
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
                    <div class="input-with-icon">
                        <i class="fas fa-building"></i>
                        <select id="service_id" name="service_id">
                            <option value="">Sélectionnez un service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('service_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="statut">Statut *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-circle"></i>
                        <select id="statut" name="statut" required>
                            <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>
                                Actif
                            </option>
                            <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>
                                Inactif
                            </option>
                            <option value="suspendu" {{ old('statut') == 'suspendu' ? 'selected' : '' }}>
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
                               value="{{ old('email') }}" 
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
                               value="{{ old('telephone') }}" 
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
                               value="{{ old('login') }}" 
                               placeholder="Nom d'utilisateur" required>
                    </div>
                    <small class="form-hint">Sera généré automatiquement si vide</small>
                    @error('login')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Mot de passe *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" 
                               placeholder="Minimum 12 caractères" required>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="passwordStrength"></div>
                        </div>
                        <div class="strength-label" id="strengthText">Faible</div>
                    </div>
                    
                    <div class="password-requirements">
                        <small>Le mot de passe doit contenir :</small>
                        <div class="requirements-grid">
                            <span class="requirement" id="lengthReq">
                                <i class="fas fa-times"></i> 12 caractères minimum
                            </span>
                            <span class="requirement" id="upperReq">
                                <i class="fas fa-times"></i> Une majuscule
                            </span>
                            <span class="requirement" id="lowerReq">
                                <i class="fas fa-times"></i> Une minuscule
                            </span>
                            <span class="requirement" id="numberReq">
                                <i class="fas fa-times"></i> Un chiffre
                            </span>
                            <span class="requirement" id="specialReq">
                                <i class="fas fa-times"></i> Un caractère spécial
                            </span>
                        </div>
                    </div>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmer le mot de passe *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               placeholder="Retapez le mot de passe" required>
                    </div>
                    <div class="password-match" id="passwordMatch">
                        <i class="fas fa-times"></i> Les mots de passe ne correspondent pas
                    </div>
                    <div class="password-match success" id="passwordMatchSuccess">
                        <i class="fas fa-check"></i> Les mots de passe correspondent
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
                    Créer le compte
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
    }

    .create-compte-container {
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .compte-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: var(--white);
        padding: 40px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .compte-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    }

    .compte-header h1 {
        font-size: 32px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        position: relative;
        z-index: 1;
    }

    .compte-header p {
        font-size: 16px;
        opacity: 0.9;
        position: relative;
        z-index: 1;
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

    .compte-form {
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
    .input-with-icon select {
        width: 100%;
        padding: 14px 14px 14px 45px;
        border: 2px solid var(--light-gray);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: var(--white);
        color: var(--black);
    }

    .input-with-icon input:focus,
    .input-with-icon select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(3, 81, 188, 0.1);
    }

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

    /* Password Strength Indicator */
    .password-strength {
        margin-top: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .strength-bar {
        flex: 1;
        height: 6px;
        background: var(--light-gray);
        border-radius: 3px;
        overflow: hidden;
    }

    .strength-fill {
        height: 100%;
        width: 0%;
        background: var(--danger);
        border-radius: 3px;
        transition: all 0.3s ease;
    }

    .strength-label {
        font-size: 12px;
        font-weight: 600;
        min-width: 60px;
    }

    /* Password Requirements */
    .password-requirements {
        margin-top: 15px;
        padding: 15px;
        background: var(--light-gray);
        border-radius: 8px;
    }

    .password-requirements small {
        display: block;
        margin-bottom: 10px;
        color: var(--medium-gray);
        font-weight: 500;
    }

    .requirements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 8px;
    }

    .requirement {
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--danger);
    }

    .requirement.valid {
        color: var(--success);
    }

    .requirement i {
        font-size: 10px;
    }

    /* Password Match Indicator */
    .password-match {
        margin-top: 8px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--danger);
        display: none;
    }

    .password-match.success {
        color: var(--success);
    }

    .password-match.show,
    .password-match.success.show {
        display: flex;
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

    /* Responsive */
    @media (max-width: 768px) {
        .compte-header,
        .compte-form {
            padding: 20px;
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

        .requirements-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('submitBtn');
        const togglePasswordBtn = document.getElementById('togglePassword');
        const passwordStrengthBar = document.getElementById('passwordStrength');
        const strengthText = document.getElementById('strengthText');
        const form = document.getElementById('compteForm');
        
        // Éléments de vérification
        const requirements = {
            length: document.getElementById('lengthReq'),
            upper: document.getElementById('upperReq'),
            lower: document.getElementById('lowerReq'),
            number: document.getElementById('numberReq'),
            special: document.getElementById('specialReq')
        };
        
        const matchWarning = document.getElementById('passwordMatch');
        const matchSuccess = document.getElementById('passwordMatchSuccess');
        
        // Générer login automatique
        function generateLogin() {
            const nom = document.getElementById('nom').value.trim().toLowerCase();
            const prenom = document.getElementById('prenom').value.trim().toLowerCase();
            
            if (nom && prenom) {
                const login = nom.substring(0, 3) + prenom.substring(0, 3);
                const loginInput = document.getElementById('login');
                if (loginInput.value === '') {
                    loginInput.value = login;
                }
            }
        }
        
        // Générer email automatique
        function generateEmail() {
            const nom = document.getElementById('nom').value.trim().toLowerCase();
            const prenom = document.getElementById('prenom').value.trim().toLowerCase();
            
            if (nom && prenom) {
                const email = prenom + '.' + nom + '@dcssa.sn';
                const emailInput = document.getElementById('email');
                if (emailInput.value === '') {
                    emailInput.value = email;
                }
            }
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
            
            // Mettre à jour les icônes
            Object.keys(checks).forEach(key => {
                const req = requirements[key];
                if (checks[key]) {
                    req.classList.add('valid');
                    req.querySelector('i').className = 'fas fa-check';
                } else {
                    req.classList.remove('valid');
                    req.querySelector('i').className = 'fas fa-times';
                }
            });
            
            // Calculer la force
            let score = Object.values(checks).filter(Boolean).length;
            let percentage = (score / 5) * 100;
            
            // Mettre à jour la barre de progression
            passwordStrengthBar.style.width = percentage + '%';
            
            // Changer la couleur et le texte
            let color, text;
            if (percentage <= 40) {
                color = '#ef4444'; // red
                text = 'Faible';
            } else if (percentage <= 70) {
                color = '#f59e0b'; // orange
                text = 'Moyen';
            } else {
                color = '#10b981'; // green
                text = 'Fort';
            }
            
            passwordStrengthBar.style.backgroundColor = color;
            strengthText.textContent = text;
            strengthText.style.color = color;
            
            return checks;
        }
        
        // Vérifier la correspondance des mots de passe
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirm = passwordConfirm.value;
            
            if (!confirm) {
                matchWarning.classList.remove('show');
                matchSuccess.classList.remove('show');
                return false;
            }
            
            if (password === confirm && password.length > 0) {
                matchWarning.classList.remove('show');
                matchSuccess.classList.add('show');
                return true;
            } else {
                matchWarning.classList.add('show');
                matchSuccess.classList.remove('show');
                return false;
            }
        }
        
        // Valider le formulaire
        function validateForm() {
            const password = passwordInput.value;
            const confirm = passwordConfirm.value;
            
            // Vérifier le mot de passe
            const passwordReq = checkPassword(password);
            const passwordValid = Object.values(passwordReq).every(Boolean);
            
            // Vérifier la correspondance
            const matchValid = checkPasswordMatch();
            
            // Activer/désactiver le bouton
            const isValid = passwordValid && matchValid;
            submitBtn.disabled = !isValid;
            
            return isValid;
        }
        
        // Basculer la visibilité du mot de passe
        togglePasswordBtn.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        });
        
        // Événements
        document.getElementById('nom').addEventListener('blur', function() {
            generateLogin();
            generateEmail();
        });
        
        document.getElementById('prenom').addEventListener('blur', function() {
            generateLogin();
            generateEmail();
        });
        
        passwordInput.addEventListener('input', validateForm);
        passwordConfirm.addEventListener('input', validateForm);
        
        // Validation initiale
        validateForm();
        
        // Validation avant soumission
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                alert('Veuillez corriger les erreurs dans le formulaire avant de soumettre.');
            }
        });
        
        // Initialiser Select2
        $('#profil_id, #service_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Sélectionnez une option',
            dropdownParent: $('.create-compte-container')
        });
    });
</script>
@endpush