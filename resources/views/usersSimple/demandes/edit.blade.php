@extends('layouts.welcome')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">
            <div class="card card-modern">
                <!-- En-tête du formulaire -->
                <div class="card-header-modern">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('user.demandes.show', $demande->ID_Demande) }}" class="btn-back me-3">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div>
                                <h4 class="mb-0 fw-bold text-white">
                                    <i class="fas fa-edit me-2"></i>
                                    Modifier la Demande #{{ $demande->Numero_Demande }}
                                </h4>
                                <p class="mb-0 text-white-50 small">Étape <span id="currentStep">1</span> sur 3</p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress bar -->
                    <div class="progress-bar-container mt-3">
                        <div class="progress-bar-step" id="progressBar"></div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('user.demandes.update', $demande->ID_Demande) }}" method="POST" id="demandeForm">
                        @csrf
                        @method('PUT')

                        @if($errors->any())
                        <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-exclamation-circle fa-2x me-3 mt-1"></i>
                                <div class="flex-grow-1">
                                    <strong class="d-block mb-2">Veuillez corriger les erreurs :</strong>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <!-- ÉTAPE 1: Sélection de l'équipement -->
                        <div class="form-step active" data-step="1">
                            <div class="step-header">
                                <div class="step-icon">
                                    <i class="fas fa-microscope"></i>
                                </div>
                                <h5 class="step-title">Modifier l'équipement</h5>
                                <p class="step-subtitle">Choisissez l'équipement concerné par l'intervention</p>
                            </div>

                            @if($equipements->count() > 0)
                            <div class="mb-4">
                                <label for="ID_Equipement" class="form-label-modern">
                                    Équipement concerné <span class="text-danger">*</span>
                                </label>
                                <div class="select-wrapper">
                                    <select class="form-control-modern @error('ID_Equipement') is-invalid @enderror"
                                            id="ID_Equipement"
                                            name="ID_Equipement"
                                            required>
                                        <option value="">Choisir un équipement...</option>
                                        @foreach($equipements as $equipement)
                                        <option value="{{ $equipement->id }}"
                                                {{ old('ID_Equipement', $demande->ID_Equipement) == $equipement->id ? 'selected' : '' }}
                                                data-localisation="{{ $equipement->localisation->nom ?? 'Non attribué' }}"
                                                data-etat="{{ $equipement->etat }}"
                                                data-marque="{{ $equipement->marque }}"
                                                data-modele="{{ $equipement->modele }}"
                                                data-type="{{ $equipement->type_equipement->libelle ?? 'Non spécifié' }}">
                                            📦 {{ $equipement->numero_inventaire }} - {{ $equipement->marque }} {{ $equipement->modele }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('ID_Equipement')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Carte d'informations -->
                            <div id="equipement-info" class="info-card d-none">
                                <div class="info-card-header">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informations de l'équipement
                                </div>
                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="fas fa-tag text-primary"></i>
                                            <div class="ms-3">
                                                <small class="text-muted">Marque & Modèle</small>
                                                <div id="info-marque-modele" class="fw-bold">-</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="fas fa-map-marker-alt text-success"></i>
                                            <div class="ms-3">
                                                <small class="text-muted">Localisation</small>
                                                <div id="info-localisation" class="fw-bold">-</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="fas fa-heartbeat text-danger"></i>
                                            <div class="ms-3">
                                                <small class="text-muted">État</small>
                                                <span id="info-etat" class="badge-etat">-</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="fas fa-layer-group text-info"></i>
                                            <div class="ms-3">
                                                <small class="text-muted">Type</small>
                                                <div id="info-type" class="fw-bold">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="alert-warning-custom">
                                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                <h5 class="mb-2">Aucun équipement disponible</h5>
                                <p class="mb-0">Contactez votre administrateur.</p>
                            </div>
                            @endif
                        </div>

                        <!-- ÉTAPE 2: Détails de l'intervention -->
                        <div class="form-step" data-step="2">
                            <div class="step-header">
                                <div class="step-icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <h5 class="step-title">Détails de l'intervention</h5>
                                <p class="step-subtitle">Modifiez le type et l'urgence de l'intervention</p>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="Type_Intervention" class="form-label-modern">
                                        Type d'intervention <span class="text-danger">*</span>
                                    </label>
                                    <div class="select-wrapper">
                                        <select class="form-control-modern @error('Type_Intervention') is-invalid @enderror"
                                                id="Type_Intervention"
                                                name="Type_Intervention"
                                                required>
                                            <option value="">Sélectionnez...</option>
                                            <option value="maintenance_preventive" {{ old('Type_Intervention', $demande->Type_Intervention) == 'maintenance_preventive' ? 'selected' : '' }}>
                                                🛡️ Maintenance préventive
                                            </option>
                                            <option value="maintenance_corrective" {{ old('Type_Intervention', $demande->Type_Intervention) == 'maintenance_corrective' ? 'selected' : '' }}>
                                                🔧 Maintenance corrective
                                            </option>
                                            <option value="reparation" {{ old('Type_Intervention', $demande->Type_Intervention) == 'reparation' ? 'selected' : '' }}>
                                                🔨 Réparation
                                            </option>
                                            <option value="calibration" {{ old('Type_Intervention', $demande->Type_Intervention) == 'calibration' ? 'selected' : '' }}>
                                                ⚖️ Calibration
                                            </option>
                                            <option value="verification" {{ old('Type_Intervention', $demande->Type_Intervention) == 'verification' ? 'selected' : '' }}>
                                                ✅ Vérification
                                            </option>
                                            <option value="controle" {{ old('Type_Intervention', $demande->Type_Intervention) == 'controle' ? 'selected' : '' }}>
                                                🔍 Contrôle
                                            </option>
                                            <option value="autre" {{ old('Type_Intervention', $demande->Type_Intervention) == 'autre' ? 'selected' : '' }}>
                                                📋 Autre
                                            </option>
                                        </select>
                                    </div>
                                    @error('Type_Intervention')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="Urgence" class="form-label-modern">
                                        Niveau d'urgence <span class="text-danger">*</span>
                                    </label>
                                    <div class="select-wrapper">
                                        <select class="form-control-modern @error('Urgence') is-invalid @enderror"
                                                id="Urgence"
                                                name="Urgence"
                                                required>
                                            <option value="">Sélectionnez...</option>
                                            <option value="normale" {{ old('Urgence', $demande->Urgence) == 'normale' ? 'selected' : '' }}>
                                                🟢 Normale (5 jours)
                                            </option>
                                            <option value="urgente" {{ old('Urgence', $demande->Urgence) == 'urgente' ? 'selected' : '' }}>
                                                🟡 Urgente (48h)
                                            </option>
                                            <option value="critique" {{ old('Urgence', $demande->Urgence) == 'critique' ? 'selected' : '' }}>
                                                🔴 Critique (immédiat)
                                            </option>
                                        </select>
                                    </div>
                                    @error('Urgence')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="Delai_Souhaite" class="form-label-modern">
                                        Délai souhaité (heures)
                                    </label>
                                    <div class="input-icon">
                                        <i class="fas fa-clock"></i>
                                        <input type="number"
                                               class="form-control-modern ps-5 @error('Delai_Souhaite') is-invalid @enderror"
                                               id="Delai_Souhaite"
                                               name="Delai_Souhaite"
                                               value="{{ old('Delai_Souhaite', $demande->Delai_Souhaite) }}"
                                               min="1"
                                               max="720"
                                               placeholder="Ex: 24 pour 24 heures">
                                    </div>
                                    <small class="text-muted">Optionnel</small>
                                    @error('Delai_Souhaite')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- ÉTAPE 3: Description -->
                        <div class="form-step" data-step="3">
                            <div class="step-header">
                                <div class="step-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <h5 class="step-title">Décrivez le problème</h5>
                                <p class="step-subtitle">Modifiez la description de la panne</p>
                            </div>

                            <div class="mb-4">
                                <label for="Description_Panne" class="form-label-modern">
                                    Description détaillée <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control-modern textarea-modern @error('Description_Panne') is-invalid @enderror"
                                          id="Description_Panne"
                                          name="Description_Panne"
                                          rows="6"
                                          required
                                          placeholder="Décrivez le problème en détail...">{{ old('Description_Panne', $demande->Description_Panne) }}</textarea>
                                <div class="char-counter">
                                    <small class="text-muted">
                                        <span id="charCount">0</span> / 2000 caractères (min. 10)
                                    </small>
                                </div>
                                @error('Description_Panne')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="Commentaires" class="form-label-modern">
                                    Commentaires supplémentaires
                                </label>
                                <textarea class="form-control-modern textarea-modern @error('Commentaires') is-invalid @enderror"
                                          id="Commentaires"
                                          name="Commentaires"
                                          rows="4"
                                          placeholder="Informations complémentaires...">{{ old('Commentaires', $demande->Commentaires) }}</textarea>
                                @error('Commentaires')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Boutons de navigation -->
                        <div class="form-navigation">
                            <button type="button" class="btn btn-prev" id="prevBtn" style="display: none;">
                                <i class="fas fa-arrow-left me-2"></i>
                                Précédent
                            </button>

                            <div class="ms-auto d-flex gap-3">
                                <a href="{{ route('user.demandes.show', $demande->ID_Demande) }}" class="btn btn-cancel">
                                    <i class="fas fa-times me-2"></i>
                                    Annuler
                                </a>

                                @if($equipements->count() > 0)
                                <button type="button" class="btn btn-next" id="nextBtn">
                                    Suivant
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </button>

                                <button type="submit" class="btn btn-submit" id="submitBtn" style="display: none;">
                                    <i class="fas fa-save me-2"></i>
                                    Enregistrer les modifications
                                </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #4e73df;
    --success: #1cc88a;
    --danger: #e74a3b;
    --warning: #f6c23e;
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-success: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.card-modern {
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
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

.card-header-modern {
    background: var(--gradient-primary);
    padding: 25px 30px;
    border: none;
}

.btn-back {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateX(-5px);
}

/* Progress Bar */
.progress-bar-container {
    width: 100%;
    height: 6px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar-step {
    height: 100%;
    background: white;
    border-radius: 10px;
    transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    width: 33.33%;
}

/* Form Steps */
.form-step {
    display: none;
    animation: fadeInRight 0.5s ease-out;
}

.form-step.active {
    display: block;
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeOutLeft {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(-50px);
    }
}

.form-step.fade-out {
    animation: fadeOutLeft 0.3s ease-out;
}

/* Step Header */
.step-header {
    text-align: center;
    margin-bottom: 35px;
}

.step-icon {
    width: 80px;
    height: 80px;
    background: var(--gradient-primary);
    color: white;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 20px;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    animation: bounce 1s ease-out;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.step-title {
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.step-subtitle {
    color: #718096;
    margin: 0;
}

/* Form Controls */
.form-label-modern {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 10px;
    font-size: 0.95rem;
}

.form-control-modern {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
}

.form-control-modern:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
    transform: translateY(-2px);
}

.select-wrapper {
    position: relative;
}

.select-wrapper::after {
    content: '\f078';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    pointer-events: none;
}

.select-wrapper select {
    appearance: none;
    cursor: pointer;
    padding-right: 45px;
}

.input-icon {
    position: relative;
}

.input-icon i {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
}

.textarea-modern {
    resize: vertical;
    min-height: 120px;
}

.char-counter {
    text-align: right;
    margin-top: 8px;
}

/* Info Card */
.info-card {
    background: linear-gradient(135deg, #f6f8fb 0%, #e9ecf5 100%);
    border-radius: 15px;
    padding: 20px;
    margin-top: 20px;
    border-left: 4px solid var(--primary);
    animation: slideDown 0.4s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
        padding: 0;
    }
    to {
        opacity: 1;
        max-height: 500px;
        padding: 20px;
    }
}

.info-card-header {
    font-weight: 700;
    color: var(--primary);
    font-size: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    padding: 12px;
    background: white;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.info-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.info-item i {
    font-size: 1.2rem;
}

.badge-etat {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
}

/* Alert */
.alert-modern {
    border-radius: 15px;
    border: none;
    padding: 20px;
    animation: shake 0.5s ease-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
}

.alert-warning-custom {
    background: linear-gradient(135deg, #fff5e6 0%, #ffe9cc 100%);
    border: 2px solid var(--warning);
    border-radius: 15px;
    padding: 30px;
    text-align: center;
}

.alert-warning-custom i {
    color: var(--warning);
}

/* Navigation Buttons */
.form-navigation {
    display: flex;
    gap: 15px;
    align-items: center;
    margin-top: 35px;
    padding-top: 25px;
    border-top: 2px solid #f0f0f0;
}

.btn {
    padding: 12px 28px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-next {
    background: var(--gradient-primary);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-next:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-prev {
    background: #f8f9fa;
    color: #6c757d;
    border: 2px solid #dee2e6;
}

.btn-prev:hover {
    background: #e2e6ea;
    transform: translateY(-2px);
}

.btn-submit {
    background: var(--gradient-success);
    color: white;
    box-shadow: 0 4px 15px rgba(28, 200, 138, 0.3);
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(28, 200, 138, 0.4);
}

.btn-cancel {
    background: white;
    color: #6c757d;
    border: 2px solid #dee2e6;
}

.btn-cancel:hover {
    background: #f8f9fa;
    border-color: #adb5bd;
}

/* Responsive */
@media (max-width: 768px) {
    .step-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .form-navigation {
        flex-direction: column;
    }

    .form-navigation .ms-auto {
        margin-left: 0 !important;
        width: 100%;
    }

    .form-navigation .d-flex {
        width: 100%;
    }

    .btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 3;

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.getElementById('progressBar');
    const currentStepSpan = document.getElementById('currentStep');
    const selectEquipement = document.getElementById('ID_Equipement');
    const infoDiv = document.getElementById('equipement-info');

    // Navigation entre les étapes
    function showStep(step) {
        const steps = document.querySelectorAll('.form-step');

        // Animation de sortie
        steps.forEach(s => {
            if (s.classList.contains('active')) {
                s.classList.add('fade-out');
            }
        });

        setTimeout(() => {
            steps.forEach((s, index) => {
                s.classList.remove('active', 'fade-out');
                if (index + 1 === step) {
                    s.classList.add('active');
                }
            });
        }, 300);

        // Mettre à jour les boutons
        prevBtn.style.display = step === 1 ? 'none' : 'block';
        nextBtn.style.display = step === totalSteps ? 'none' : 'block';
        submitBtn.style.display = step === totalSteps ? 'block' : 'none';

        // Mettre à jour la progress bar
        const progress = (step / totalSteps) * 100;
        progressBar.style.width = progress + '%';

        // Mettre à jour le numéro d'étape
        currentStepSpan.textContent = step;

        // Scroll vers le haut
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    nextBtn.addEventListener('click', function() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    });

    prevBtn.addEventListener('click', function() {
        currentStep--;
        showStep(currentStep);
    });

    // Validation simple par étape
    function validateStep(step) {
        if (step === 1) {
            const equipement = document.getElementById('ID_Equipement');
            if (!equipement.value) {
                alert('Veuillez sélectionner un équipement');
                equipement.focus();
                return false;
            }
        } else if (step === 2) {
            const type = document.getElementById('Type_Intervention');
            const urgence = document.getElementById('Urgence');

            if (!type.value) {
                alert('Veuillez sélectionner un type d\'intervention');
                type.focus();
                return false;
            }
            if (!urgence.value) {
                alert('Veuillez sélectionner un niveau d\'urgence');
                urgence.focus();
                return false;
            }
        }
        return true;
    }

    // Afficher les informations de l'équipement
    if (selectEquipement) {
        selectEquipement.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption.value) {
                const localisation = selectedOption.getAttribute('data-localisation');
                const etat = selectedOption.getAttribute('data-etat');
                const marque = selectedOption.getAttribute('data-marque');
                const modele = selectedOption.getAttribute('data-modele');
                const type = selectedOption.getAttribute('data-type');

                document.getElementById('info-marque-modele').textContent = `${marque} ${modele}`;
                document.getElementById('info-localisation').textContent = localisation || '-';
                document.getElementById('info-type').textContent = type || '-';

                const etatBadge = document.getElementById('info-etat');
                etatBadge.textContent = etat || '-';
                etatBadge.className = 'badge-etat';

                const etatColors = {
                    'neuf': 'background: #1cc88a; color: white;',
                    'bon': 'background: #1cc88a; color: white;',
                    'moyen': 'background: #f6c23e; color: white;',
                    'mauvais': 'background: #e74a3b; color: white;',
                    'hors_service': 'background: #858796; color: white;'
                };
                etatBadge.style.cssText = etatColors[etat] || 'background: #858796; color: white;';

                infoDiv.classList.remove('d-none');
            } else {
                infoDiv.classList.add('d-none');
            }
        });

        if (selectEquipement.value) {
            selectEquipement.dispatchEvent(new Event('change'));
        }
    }

    // Compteur de caractères
    const descriptionTextarea = document.getElementById('Description_Panne');
    const charCount = document.getElementById('charCount');

    if (descriptionTextarea && charCount) {
        descriptionTextarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;

            if (count < 10) {
                charCount.style.color = '#e74a3b';
            } else if (count > 1900) {
                charCount.style.color = '#f6c23e';
            } else {
                charCount.style.color = '#1cc88a';
            }
        });

        // Initialiser le compteur avec la valeur existante
        charCount.textContent = descriptionTextarea.value.length;
        if (descriptionTextarea.value.length < 10) {
            charCount.style.color = '#e74a3b';
        } else if (descriptionTextarea.value.length > 1900) {
            charCount.style.color = '#f6c23e';
        } else {
            charCount.style.color = '#1cc88a';
        }
    }

    // Animation à la soumission
    const form = document.getElementById('demandeForm');
    if (form) {
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Enregistrement...';
            submitBtn.disabled = true;
        });
    }

    // Vérifier les valeurs existantes pour les étapes 2 et 3
    function initializeForm() {
        const typeIntervention = document.getElementById('Type_Intervention');
        const urgence = document.getElementById('Urgence');
        const delaiSouhaite = document.getElementById('Delai_Souhaite');

        // Si toutes les informations des étapes 2 et 3 sont déjà remplies,
        // on peut suggérer à l'utilisateur de commencer à l'étape 1
        if (typeIntervention.value && urgence.value) {
            // L'utilisateur peut naviguer librement
        }
    }

    initializeForm();
});
</script>
@endsection
