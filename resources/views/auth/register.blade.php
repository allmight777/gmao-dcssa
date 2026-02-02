@extends('layouts.welcome')

@section('content')
<div class="d-flex justify-content-center align-items-center login-bg" style="min-height: 70vh; padding: 20px 0;">

    <div class="login-card">

        <div class="login-header">
            <h3 class="text-light mb-1">Inscription</h3>
            <p class="mb-0 text-light opacity-75">Créer votre compte</p>
        </div>

        <div class="login-body p-4">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <div class="row g-3">
                    <!-- Nom et Prénom -->
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="nom" name="nom"
                                   value="{{ old('nom') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="prenom" name="prenom"
                                   value="{{ old('prenom') }}" required>
                        </div>
                    </div>

                    <!-- Matricule et Service -->
                    <div class="col-md-6">
                        <label for="matricule" class="form-label">Matricule <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="form-control" id="matricule" name="matricule"
                                   value="{{ old('matricule') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="service_id" class="form-label">Service <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                            <select class="form-control form-select-custom" id="service_id" name="service_id" required>
                                <option value="">Sélectionnez...</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->libelle ?? $service->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Email et Téléphone -->
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="tel" class="form-control" id="telephone" name="telephone"
                                   value="{{ old('telephone') }}" placeholder="+221 77 000 00 00">
                        </div>
                    </div>

                    <!-- Login -->
                    <div class="col-12">
                        <label for="login" class="form-label">Login <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                            <input type="text" class="form-control" id="login" name="login"
                                   value="{{ old('login') }}" required>
                        </div>
                    </div>

                    <!-- Mot de passe et Confirmation -->
                    <div class="col-md-6">
                        <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirmation <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password_confirmation"
                                   name="password_confirmation" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Champ caché pour le profil -->
                <input type="hidden" name="profil_id" value="5">

              <div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary btn-lg btn-animated">
        <i class="fas fa-user-plus me-2"></i> S'inscrire
    </button>

    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg btn-animated">
        <i class="fas fa-sign-in-alt me-2"></i> Déjà un compte ? Se connecter
    </a>
</div>

            </form>

        </div>
    </div>
</div>

<style>
    .login-bg {
        background: url('{{ asset("images/1.webp") }}') no-repeat center center;
        background-size: cover;
        position: relative;
          height: 100%;
    }

    .login-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
    }

    .login-card {
        width: 100%;
        max-width: 700px;
        background: rgba(255, 255, 255, 0.98);
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        overflow: hidden;
        margin: 20px;
        position: relative;
        animation: slideInUp 0.6s ease-out;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        color: white;
        padding: 20px 30px;
        text-align: center;
    }

    .login-header h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }

    .login-body {
        padding: 25px 30px !important;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 6px;
        color: #333333;
        font-size: 0.9rem;
    }

    .input-group-modern {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .input-group-modern:focus-within {
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        transform: translateY(-2px);
    }

    .input-group-text {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        color: #0d6efd;
        padding: 10px 12px;
        font-size: 1rem;
    }

    .form-control {
        background: #ffffff;
        border: none;
        color: #333333;
        padding: 10px 14px;
        height: auto;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background: #ffffff;
        box-shadow: none;
        color: #333333;
    }

    .form-select-custom {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%230d6efd' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 14px 10px;
        padding-right: 35px;
        cursor: pointer;
    }

    .form-select-custom:hover {
        background-color: #f8f9fa;
    }

    .form-select-custom option {
        padding: 10px;
    }

    .btn-animated {
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-animated::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-animated:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        color: white;
        padding: 12px 20px;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4);
    }

    .btn-outline-secondary {
        padding: 10px 20px;
        font-weight: 600;
        border-radius: 10px;
        border: 2px solid #6c757d;
        color: #6c757d;
        background: transparent;
    }

    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
    }

    .toggle-password {
        border: none;
        background: transparent;
        color: #6c757d;
        padding: 0 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .toggle-password:hover {
        color: #0d6efd;
        background: transparent;
    }

    .toggle-password i {
        font-size: 0.9rem;
    }

    .alert {
        border-radius: 10px;
        border: none;
        animation: slideInDown 0.4s ease-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .text-danger {
        color: #dc3545 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .login-card {
            max-width: 95%;
            margin: 10px;
        }

        .login-body {
            padding: 20px !important;
        }

        .login-header h3 {
            font-size: 1.3rem;
        }
    }

    /* Animation pour les inputs */
    .form-control {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Style pour les options du select */
    select.form-control option {
        padding: 10px;
        background: #fff;
        color: #333;
    }

    select.form-control option:hover {
        background: #f8f9fa;
    }
</style>

@section('scripts')
<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Format du téléphone
    document.getElementById('telephone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0) {
            if (value.startsWith('221')) {
                value = value.substring(3);
            }
            if (value.length <= 9) {
                e.target.value = '+221 ' + value;
            }
        }
    });

    // Générer un login automatiquement
    document.getElementById('nom').addEventListener('blur', generateLogin);
    document.getElementById('prenom').addEventListener('blur', generateLogin);

    function generateLogin() {
        const nom = document.getElementById('nom').value.toLowerCase().trim();
        const prenom = document.getElementById('prenom').value.toLowerCase().trim();

        if (nom && prenom && !document.getElementById('login').value) {
            let login = prenom.charAt(0) + nom;
            login = login.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            login = login.replace(/\s+/g, '');

            document.getElementById('login').value = login;

            if (!document.getElementById('email').value) {
                document.getElementById('email').value = login + '@dcssa.sn';
            }
        }
    }

    // Animation au focus
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('input-focused');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('input-focused');
        });
    });

    // Animation de soumission du formulaire
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Inscription en cours...';
        submitBtn.disabled = true;
    });

    // Animation stagger pour les inputs
    document.querySelectorAll('.input-group-modern').forEach((group, index) => {
        group.style.animation = `fadeIn 0.5s ease-out ${index * 0.1}s both`;
    });
</script>
@endsection
@endsection
