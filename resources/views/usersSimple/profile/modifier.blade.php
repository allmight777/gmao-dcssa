@extends('layouts.welcome')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-9 col-lg-10">

            <!-- En-tête -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="header-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1 fw-bold text-white">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Modifier Mon Profil
                                </h4>
                                <p class="mb-0 text-white-50">
                                    Mettez à jour vos informations personnelles
                                </p>
                            </div>
                            <a href="{{ route('user.profile.view') }}" class="btn btn-light-custom">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Formulaire de modification -->
                <div class="col-12">
                    <div class="card-modern">
                        <div class="card-header-modern">
                            <h6 class="mb-0">
                                <i class="fas fa-user-cog me-2 text-primary"></i>
                                Informations Personnelles
                            </h6>
                        </div>
                        <div class="card-body-modern">
                            <form method="POST" action="{{ route('user.profile.mettre-a-jour') }}" id="profileForm">
                                @csrf
                                @method('PUT')

                                <div class="row g-4">
                                    <!-- Matricule (lecture seule) -->
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Matricule</label>
                                        <div class="form-control-readonly">
                                            {{ $utilisateur->matricule }}
                                        </div>
                                    </div>

                                    <!-- Fonction (lecture seule) -->
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Fonction</label>
                                        <div class="form-control-readonly">
                                            {{ $utilisateur->fonction }}
                                        </div>
                                    </div>

                                    <!-- Nom -->
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Nom *</label>
                                        <input type="text"
                                               name="nom"
                                               class="form-control-custom @error('nom') is-invalid @enderror"
                                               value="{{ old('nom', $utilisateur->nom) }}"
                                               required>
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Prénom -->
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Prénom *</label>
                                        <input type="text"
                                               name="prenom"
                                               class="form-control-custom @error('prenom') is-invalid @enderror"
                                               value="{{ old('prenom', $utilisateur->prenom) }}"
                                               required>
                                        @error('prenom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Grade -->
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Grade</label>
                                        <input type="text"
                                               name="grade"
                                               class="form-control-custom @error('grade') is-invalid @enderror"
                                               value="{{ old('grade', $utilisateur->grade) }}">
                                        @error('grade')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Email *</label>
                                        <input type="email"
                                               name="email"
                                               class="form-control-custom @error('email') is-invalid @enderror"
                                               value="{{ old('email', $utilisateur->email) }}"
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Téléphone -->
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Téléphone</label>
                                        <input type="text"
                                               name="telephone"
                                               class="form-control-custom @error('telephone') is-invalid @enderror"
                                               value="{{ old('telephone', $utilisateur->telephone) }}"
                                               placeholder="Ex: 0612345678">
                                        @error('telephone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Service -->
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Service</label>
                                        <select name="service_id" class="form-control-custom @error('service_id') is-invalid @enderror">
                                            <option value="">Sélectionnez un service</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}"
                                                    {{ old('service_id', $utilisateur->service_id) == $service->id ? 'selected' : '' }}>
                                                    {{ $service->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('service_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Profil (lecture seule) -->
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Profil</label>
                                        <div class="form-control-readonly">
                                            {{ $utilisateur->profilFormate }}
                                        </div>
                                    </div>

                                    <!-- Statut (lecture seule) -->
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Statut</label>
                                        <div class="form-control-readonly">
                                            <span class="badge-custom bg-{{ $utilisateur->badgeStatut }}">
                                                {{ ucfirst($utilisateur->statut) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Boutons -->
                                    <div class="col-12 mt-4 pt-3">
                                        <div class="d-flex justify-content-between gap-3">
                                            <a href="{{ route('user.profile.view') }}" class="btn-cancel flex-grow-1 text-center">
                                                <i class="fas fa-times me-2"></i>Annuler
                                            </a>
                                            <button type="submit" class="btn-submit flex-grow-1">
                                                <i class="fas fa-save me-2"></i>Enregistrer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modification du mot de passe -->
                <div class="col-12">
                    <div class="card-modern" id="mot-de-passe">
                        <div class="card-header-modern">
                            <h6 class="mb-0">
                                <i class="fas fa-key me-2 text-warning"></i>
                                Modifier le Mot de Passe
                            </h6>
                        </div>
                        <div class="card-body-modern">
                            <form method="POST" action="{{ route('user.profile.modifier-mot-de-passe') }}" id="passwordForm">
                                @csrf
                                @method('PUT')

                                <div class="row g-4">
                                    <!-- Ancien mot de passe -->
                                    <div class="col-12">
                                        <label class="form-label-custom">Ancien mot de passe *</label>
                                        <div class="password-input">
                                            <input type="password"
                                                   name="ancien_mot_de_passe"
                                                   class="form-control-custom @error('ancien_mot_de_passe') is-invalid @enderror"
                                                   required>
                                            <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('ancien_mot_de_passe')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Nouveau mot de passe -->
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Nouveau mot de passe *</label>
                                        <div class="password-input">
                                            <input type="password"
                                                   name="nouveau_mot_de_passe"
                                                   id="nouveau_mot_de_passe"
                                                   class="form-control-custom @error('nouveau_mot_de_passe') is-invalid @enderror"
                                                   required>
                                            <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('nouveau_mot_de_passe')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Confirmation -->
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Confirmer le mot de passe *</label>
                                        <div class="password-input">
                                            <input type="password"
                                                   name="nouveau_mot_de_passe_confirmation"
                                                   id="nouveau_mot_de_passe_confirmation"
                                                   class="form-control-custom @error('nouveau_mot_de_passe') is-invalid @enderror"
                                                   required>
                                            <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Indications -->
                                    <div class="col-12">
                                        <div class="password-requirements">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Le mot de passe doit contenir au moins 8 caractères
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Bouton -->
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn-submit-warning w-100">
                                            <i class="fas fa-key me-2"></i>Changer le mot de passe
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #4e73df;
    --success: #1cc88a;
    --warning: #f6c23e;
    --danger: #e74a3b;
    --info: #36b9cc;
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Header Card */
.header-card {
    background: var(--gradient-primary);
    padding: 25px 30px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    animation: slideInDown 0.6s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.btn-light-custom {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-light-custom:hover {
    background: white;
    color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
}

/* Modern Cards */
.card-modern {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: none;
    overflow: hidden;
    transition: all 0.3s ease;
    animation: fadeIn 0.8s ease-out;
}

.card-modern:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.card-header-modern {
    background: linear-gradient(135deg, #f6f8fb 0%, #e9ecf5 100%);
    padding: 20px 25px;
    border-bottom: 2px solid #e2e8f0;
}

.card-header-modern h6 {
    font-weight: 700;
    color: #2d3748;
    font-size: 1rem;
}

.card-body-modern {
    padding: 30px;
}

/* Form Controls */
.form-label-custom {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
    margin-bottom: 10px;
    display: block;
}

.form-control-custom {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background-color: white;
}

.form-control-custom:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.15);
    background-color: #f8f9fc;
}

.form-control-custom.is-invalid {
    border-color: var(--danger);
    background-color: #fff5f5;
}

.form-control-custom.is-invalid:focus {
    box-shadow: 0 0 0 4px rgba(231, 74, 59, 0.15);
}

.invalid-feedback {
    color: var(--danger);
    font-size: 0.85rem;
    margin-top: 6px;
    padding-left: 5px;
}

.form-control-readonly {
    width: 100%;
    padding: 14px 16px;
    background: #f8f9fa;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    color: #6c757d;
    font-weight: 500;
}

/* Password Input */
.password-input {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 8px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.password-toggle:hover {
    color: var(--primary);
    background-color: #f8f9fa;
}

.password-requirements {
    padding: 12px 15px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid var(--info);
    margin-top: 5px;
}

.password-requirements small {
    font-size: 0.85rem;
}

/* Buttons */
.btn-submit {
    background: var(--primary);
    color: white;
    border: none;
    padding: 15px 25px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
}

.btn-submit:hover {
    background: #2e59d9;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(78, 115, 223, 0.4);
    color: white;
}

.btn-submit-warning {
    background: var(--warning);
    color: #2d3748;
    border: none;
    padding: 15px 25px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 10px rgba(246, 194, 62, 0.3);
}

.btn-submit-warning:hover {
    background: #e4b73a;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(246, 194, 62, 0.4);
    color: #2d3748;
}

.btn-cancel {
    background: #f8f9fa;
    color: #6c757d;
    border: 2px solid #dee2e6;
    padding: 15px 25px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-cancel:hover {
    background: #e2e6ea;
    color: #495057;
    text-decoration: none;
    border-color: #cbd3da;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Badge Custom */
.badge-custom {
    padding: 6px 12px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.75rem;
    display: inline-block;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.bg-success { background: var(--success) !important; }
.bg-danger { background: var(--danger) !important; }
.bg-warning { background: var(--warning) !important; }

/* Responsive */
@media (max-width: 1400px) {
    .col-xxl-8 {
        max-width: 90%;
    }
}

@media (max-width: 1200px) {
    .col-xl-9 {
        max-width: 95%;
    }
}

@media (max-width: 992px) {
    .col-lg-10 {
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .header-card {
        padding: 20px;
    }

    .card-body-modern {
        padding: 25px 20px;
    }

    .form-control-custom,
    .form-control-readonly {
        padding: 12px 14px;
        font-size: 0.9rem;
    }

    .btn-submit,
    .btn-submit-warning,
    .btn-cancel {
        padding: 12px 20px;
        font-size: 0.9rem;
    }

    .d-flex {
        flex-direction: column;
    }

    .btn-cancel,
    .btn-submit {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .header-card {
        padding: 18px;
    }

    .card-header-modern {
        padding: 18px 20px;
    }

    .card-body-modern {
        padding: 20px 18px;
    }

    .form-label-custom {
        font-size: 0.85rem;
    }
}

/* Animation pour la seconde carte */
.card-modern:nth-child(2) {
    animation-delay: 0.2s;
}

/* Transition pour les inputs */
.form-control-custom,
.form-control-readonly,
select.form-control-custom {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Focus state amélioré */
.form-control-custom:focus {
    transform: translateY(-1px);
}

/* Hover state pour les cartes */
.card-modern:hover {
    transform: translateY(-2px);
}
</style>

<script>
function togglePassword(button) {
    const input = button.previousElementSibling;
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
        button.setAttribute('title', 'Cacher le mot de passe');
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
        button.setAttribute('title', 'Afficher le mot de passe');
    }
}

// Validation des formulaires
document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.getElementById('profileForm');
    const passwordForm = document.getElementById('passwordForm');

    // Validation du formulaire de profil
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let valid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('is-invalid');

                    // Animation pour le champ invalide
                    field.style.animation = 'shake 0.5s ease-in-out';
                    setTimeout(() => {
                        field.style.animation = '';
                    }, 500);
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Champs obligatoires',
                    text: 'Veuillez remplir tous les champs obligatoires (*)',
                    confirmButtonColor: '#4e73df',
                    confirmButtonText: 'Compris'
                });
            }
        });
    }

    // Validation du formulaire de mot de passe
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('nouveau_mot_de_passe');
            const confirmPassword = document.getElementById('nouveau_mot_de_passe_confirmation');
            let hasError = false;

            // Vérification de la correspondance
            if (newPassword.value !== confirmPassword.value) {
                hasError = true;
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Les mots de passe ne correspondent pas',
                    confirmButtonColor: '#e74a3b'
                });
                confirmPassword.focus();
            }

            // Vérification de la longueur
            if (newPassword.value.length < 8) {
                hasError = true;
                Swal.fire({
                    icon: 'warning',
                    title: 'Mot de passe trop court',
                    text: 'Le mot de passe doit contenir au moins 8 caractères',
                    confirmButtonColor: '#f6c23e'
                });
                newPassword.focus();
            }

            if (hasError) {
                e.preventDefault();
            }
        });
    }

    // Messages de succès/erreur
    @if(session('success'))
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: 'success',
        title: '{{ session('success') }}',
        background: 'var(--success)',
        color: 'white'
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: '{{ session('error') }}',
        confirmButtonColor: '#e74a3b',
        confirmButtonText: 'Compris'
    });
    @endif

    // Animation de shake pour les erreurs
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);

    // Auto-focus sur le premier champ invalide
    @if($errors->any())
    setTimeout(() => {
        const firstError = document.querySelector('.is-invalid');
        if (firstError) {
            firstError.focus();
        }
    }, 300);
    @endif
});
</script>
@endsection
