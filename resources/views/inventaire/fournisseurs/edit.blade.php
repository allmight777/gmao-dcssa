@extends('layouts.admin')

@section('title', 'Modifier le fournisseur')

@section('page-title', 'Modifier le fournisseur')

@section('page-actions')
<a href="{{ route('inventaire.fournisseurs.index') }}" class="btn-return">
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

    <form method="POST" action="{{ route('inventaire.fournisseurs.update', $fournisseur->id) }}" class="service-form" id="fournisseurForm">
        @csrf
        @method('PUT')
        
        <!-- Section Informations Générales -->
        <div class="form-section info-general-section">
            <div class="section-header">
                <h2><i class="fas fa-id-card"></i> Informations Générales</h2>
                <div class="section-border"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="code_fournisseur">Code Fournisseur *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-barcode"></i>
                        <input type="text" id="code_fournisseur" name="code_fournisseur" 
                               value="{{ old('code_fournisseur', $fournisseur->code_fournisseur) }}" 
                               placeholder="Ex: FRN001" required>
                    </div>
                    <small class="form-hint">Code unique identifiant le fournisseur</small>
                    @error('code_fournisseur')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="raison_sociale">Raison Sociale *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-building"></i>
                        <input type="text" id="raison_sociale" name="raison_sociale" 
                               value="{{ old('raison_sociale', $fournisseur->raison_sociale) }}" 
                               placeholder="Nom de l'entreprise" required>
                    </div>
                    @error('raison_sociale')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="type">Type de Fournisseur *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-tags"></i>
                        <select id="type" name="type" class="form-select" required>
                            <option value="">Sélectionnez un type</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type', $fournisseur->type) == $key ? 'selected' : '' }}>
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
                    <label for="statut">Statut *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-circle"></i>
                        <select id="statut" name="statut" class="form-select" required>
                            @foreach($statuts as $key => $label)
                                <option value="{{ $key }}" {{ old('statut', $fournisseur->statut) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('statut')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Coordonnées -->
        <div class="form-section contact-section">
            <div class="section-header">
                <h2><i class="fas fa-address-book"></i> Coordonnées</h2>
                <div class="section-border"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <div class="input-with-icon">
                        <i class="fas fa-map-marker-alt"></i>
                        <textarea id="adresse" name="adresse" rows="2" 
                                  placeholder="Adresse complète"
                                  class="form-control">{{ old('adresse', $fournisseur->adresse) }}</textarea>
                    </div>
                    @error('adresse')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <div class="input-with-icon">
                        <i class="fas fa-phone"></i>
                        <input type="text" id="telephone" name="telephone" 
                               value="{{ old('telephone', $fournisseur->telephone) }}" 
                               placeholder="Ex: +221 77 123 45 67">
                    </div>
                    @error('telephone')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" 
                               value="{{ old('email', $fournisseur->email) }}" 
                               placeholder="contact@entreprise.com">
                    </div>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="contact_principal">Contact Principal</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user-tie"></i>
                        <input type="text" id="contact_principal" name="contact_principal" 
                               value="{{ old('contact_principal', $fournisseur->contact_principal) }}" 
                               placeholder="Nom du contact principal">
                    </div>
                    @error('contact_principal')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Commandes & Évaluation -->
        <div class="form-section evaluation-section">
            <div class="section-header">
                <h2><i class="fas fa-chart-line"></i> Historique & Évaluation</h2>
                <div class="section-border"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date_premiere_commande">Date Première Commande</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-plus"></i>
                        <input type="date" id="date_premiere_commande" name="date_premiere_commande" 
                               value="{{ old('date_premiere_commande', $fournisseur->date_premiere_commande ? $fournisseur->date_premiere_commande->format('Y-m-d') : '') }}">
                    </div>
                    @error('date_premiere_commande')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date_derniere_commande">Date Dernière Commande</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-check"></i>
                        <input type="date" id="date_derniere_commande" name="date_derniere_commande" 
                               value="{{ old('date_derniere_commande', $fournisseur->date_derniere_commande ? $fournisseur->date_derniere_commande->format('Y-m-d') : '') }}">
                    </div>
                    @error('date_derniere_commande')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="evaluation">Évaluation</label>
                    <div class="input-with-icon">
                        <i class="fas fa-star"></i>
                        <select id="evaluation" name="evaluation" class="form-select">
                            <option value="">Non évalué</option>
                            @foreach($evaluations as $key => $label)
                                <option value="{{ $key }}" {{ old('evaluation', $fournisseur->evaluation) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('evaluation')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Notes -->
        <div class="form-section notes-section">
            <div class="section-header">
                <h2><i class="fas fa-sticky-note"></i> Notes & Informations Complémentaires</h2>
                <div class="section-border"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <div class="input-with-icon">
                        <i class="fas fa-edit"></i>
                        <textarea id="notes" name="notes" rows="4" 
                                  placeholder="Notes internes, observations, conditions particulières..."
                                  class="form-control">{{ old('notes', $fournisseur->notes) }}</textarea>
                    </div>
                    <small class="form-hint">Ces notes ne sont visibles qu'en interne</small>
                    @error('notes')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <div class="form-info">
                <i class="fas fa-info-circle"></i>
                <span>Tous les champs marqués d'un * sont obligatoires</span>
            </div>
            <div class="action-buttons">
                <a href="{{ route('inventaire.fournisseurs.index') }}" class="btn-cancel">
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
        --primary-blue: #0351BC;
        --primary-light-blue: #4a7fd4;
        --primary-dark-blue: #023a8a;
        --bright-blue: #0d6efd;
        --bright-blue-light: rgba(13, 110, 253, 0.1);
        --success-green: #198754;
        --danger-red: #dc3545;
        --warning-orange: #ffc107;
        --white: #ffffff;
        --light-gray: #f8f9fa;
        --medium-gray: #6c757d;
        --dark-gray: #212529;
        --section-bg: #ffffff;
    }

    .create-service-container {
        background: var(--white);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e0e0e0;
    }

    .success-message {
        background: linear-gradient(135deg, var(--success-green) 0%, #157347 100%);
        color: var(--white);
        padding: 15px 25px;
        border-radius: 8px;
        margin: 20px 30px;
        text-align: center;
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.2);
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

    .service-form {
        padding: 30px;
    }

    .form-section {
        margin-bottom: 25px;
        padding: 25px;
        border: 2px solid;
        border-radius: 12px;
        background: var(--section-bg);
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

    .contact-section {
        border-color: var(--bright-blue);
        box-shadow: 0 5px 20px rgba(13, 110, 253, 0.1);
    }

    .evaluation-section {
        border-color: var(--warning-orange);
        box-shadow: 0 5px 20px rgba(255, 193, 7, 0.1);
    }

    .notes-section {
        border-color: var(--success-green);
        box-shadow: 0 5px 20px rgba(25, 135, 84, 0.1);
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

    .contact-section .section-border {
        background: var(--bright-blue);
    }

    .evaluation-section .section-border {
        background: var(--warning-orange);
    }

    .notes-section .section-border {
        background: var(--success-green);
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
        border-color: var(--bright-blue);
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
        background: var(--bright-blue-light);
    }

    .input-with-icon textarea {
        resize: vertical;
        min-height: 100px;
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
        border-color: var(--bright-blue);
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
    }

    /* Date picker */
    input[type="date"] {
        cursor: pointer;
        background-color: var(--white);
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: 0.6;
        transition: opacity 0.3s;
        filter: invert(26%) sepia(89%) saturate(1833%) hue-rotate(207deg) brightness(96%) contrast(93%);
    }

    input[type="date"]::-webkit-calendar-picker-indicator:hover {
        opacity: 1;
    }

    input[type="date"]:focus {
        background-color: var(--bright-blue-light);
    }

    /* Style pour les options des selects */
    .form-select option {
        padding: 10px;
        background-color: var(--white);
        color: var(--dark-gray);
    }

    .form-select option:hover {
        background-color: var(--primary-blue) !important;
        color: var(--white) !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .service-form {
            padding: 20px;
        }

        .success-message {
            margin: 15px;
            padding: 12px 20px;
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
        .create-service-container {
            border-radius: 10px;
        }

        .form-section {
            padding: 15px;
        }

        .form-section h2 {
            font-size: 18px;
        }
    }

    /* Animation pour les sections */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-section {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    .info-general-section { animation-delay: 0.1s; }
    .contact-section { animation-delay: 0.2s; }
    .evaluation-section { animation-delay: 0.3s; }
    .notes-section { animation-delay: 0.4s; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('fournisseurForm');
        const submitBtn = document.getElementById('submitBtn');
        
        // Validation avant soumission
        form.addEventListener('submit', function(e) {
            // Confirmation avant soumission
            e.preventDefault();
            
            Swal.fire({
                title: 'Confirmer la modification',
                text: 'Êtes-vous sûr de vouloir modifier ce fournisseur ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0351BC',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, modifier',
                cancelButtonText: 'Annuler',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        // Désactiver le bouton pour éviter les doubles soumissions
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Modification en cours...';
                        
                        setTimeout(() => {
                            resolve();
                        }, 500);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Soumettre le formulaire
                    form.submit();
                }
            });
        });
        
        // Animation pour les champs focus
        const inputs = document.querySelectorAll('.input-with-icon input, .input-with-icon select, .input-with-icon textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.3s ease';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
        
        // Mettre à jour les couleurs des icônes en fonction du focus
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                const icon = this.parentElement.querySelector('i');
                if (icon) {
                    icon.style.color = 'var(--bright-blue)';
                    icon.style.transform = 'scale(1.1)';
                    icon.style.transition = 'all 0.3s ease';
                }
            });
            
            input.addEventListener('blur', function() {
                const icon = this.parentElement.querySelector('i');
                if (icon) {
                    icon.style.color = 'var(--primary-blue)';
                    icon.style.transform = 'scale(1)';
                }
            });
        });
    });
</script>
@endpush