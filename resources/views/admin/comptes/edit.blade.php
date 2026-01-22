@extends('layouts.admin')

@section('title', 'Modifier le compte')

@section('page-title', 'Modifier le compte')

<br><br>

@section('page-actions')
<a href="{{ route('admin.comptes.index') }}" class="btn-return">
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

    <form method="POST" action="{{ route('admin.comptes.update', $compte) }}" class="compte-form" id="compteForm">
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
                                <option value="{{ $profil->id }}" {{ old('profil_id', $compte->profil_id) == $profil->id ? 'selected' : '' }}>
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
                    <div class="input-with-icon">
                        <i class="fas fa-building"></i>
                        <select id="service_id" name="service_id">
                            <option value="">Sélectionnez un service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id', $compte->service_id) == $service->id ? 'selected' : '' }}>
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
                                       placeholder="Laissez vide pour conserver l'actuel">
                                <button type="button" class="password-toggle" id="toggleNewPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
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
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>

    <!-- Modal de confirmation de réinitialisation -->
    <div class="modal" id="resetPasswordModal" style="display: none;">
        <div class="modal-content">
            <h3><i class="fas fa-key"></i> Réinitialiser le mot de passe</h3>
            <form id="resetPasswordForm" action="{{ route('admin.comptes.reset-password', $compte) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="reset_password">Nouveau mot de passe *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="reset_password" name="password" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="reset_password_confirmation">Confirmer le mot de passe *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="reset_password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeResetModal()">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check"></i> Réinitialiser
                    </button>
                </div>
            </form>
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

    .error {
        color: var(--danger);
        font-size: 12px;
        margin-top: 8px;
        display: block;
        font-weight: 500;
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

    /* Modal Styles */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background: var(--white);
        padding: 30px;
        border-radius: 15px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-content h3 {
        color: var(--primary-color);
        margin-bottom: 20px;
        font-size: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 20px;
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

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(3, 81, 188, 0.4);
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
        
        toggleNewPasswordBtn.addEventListener('click', function() {
            const type = newPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            newPasswordInput.setAttribute('type', type);
            this.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        });

        // Validation du formulaire
        const form = document.getElementById('compteForm');
        
        form.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;
            
            // Si un nouveau mot de passe est fourni, vérifier la confirmation
            if (newPassword && newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Les nouveaux mots de passe ne correspondent pas.');
                document.getElementById('new_password_confirmation').focus();
            }
            
            // Validation de la force du mot de passe si fourni
            if (newPassword && newPassword.length < 12) {
                e.preventDefault();
                alert('Le nouveau mot de passe doit contenir au moins 12 caractères.');
                document.getElementById('new_password').focus();
            }
        });

        // Modal functions
        window.openResetModal = function() {
            document.getElementById('resetPasswordModal').style.display = 'flex';
        };

        window.closeResetModal = function() {
            document.getElementById('resetPasswordModal').style.display = 'none';
        };

        // Fermer la modal en cliquant à l'extérieur
        document.getElementById('resetPasswordModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeResetModal();
            }
        });

        // Validation de la modal de réinitialisation
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            const password = document.getElementById('reset_password').value;
            const confirmPassword = document.getElementById('reset_password_confirmation').value;
            
            if (password.length < 12) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 12 caractères.');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas.');
                return;
            }
        });

        // Initialiser Select2
        $('#profil_id, #service_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Sélectionnez une option'
        });
    });
</script>
@endpush