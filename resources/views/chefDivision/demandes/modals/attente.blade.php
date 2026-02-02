<div class="modal fade" id="attenteModal{{ $demande->ID_Demande }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-modern-attente">
            <form action="{{ route('chef-division.demandes.mettre-en-attente', $demande->ID_Demande) }}" method="POST">
                @csrf

                <!-- Header avec icône -->
                <div class="modal-header-modern modal-header-warning">
                    <div class="modal-icon-wrapper">
                        <div class="modal-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <button type="button" class="btn-close-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body-modern">
                    <h4 class="modal-title-modern text-center mb-3">
                        Mettre en attente
                    </h4>

                    <div class="demande-info-box-attente">
                        <div class="info-row">
                            <span class="info-label">N° Demande</span>
                            <span class="info-value">{{ $demande->Numero_Demande }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Demandeur</span>
                            <span class="info-value">{{ $demande->demandeur->nom_complet ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date demande</span>
                            <span class="info-value">{{ $demande->Date_Demande->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="alert-info-modern">
                        <i class="fas fa-info-circle me-2"></i>
                        Ajoutez un commentaire pour informer le demandeur de la raison de l'attente.
                    </div>

                    <!-- Commentaire -->
                    <div class="mb-3">
                        <label for="commentaire_attente{{ $demande->ID_Demande }}" class="form-label-modal">
                            <i class="fas fa-comment me-2"></i>Raison de la mise en attente <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control-modal"
                                  id="commentaire_attente{{ $demande->ID_Demande }}"
                                  name="commentaire"
                                  rows="4"
                                  placeholder="Ex: En attente de pièces de rechange, vérifications complémentaires nécessaires..."
                                  required></textarea>
                    </div>

                    <!-- Délai supplémentaire -->
                    <div class="mb-0">
                        <label for="delai{{ $demande->ID_Demande }}" class="form-label-modal">
                            <i class="fas fa-hourglass-half me-2"></i>Délai supplémentaire estimé (optionnel)
                        </label>
                        <div class="input-group-modern">
                            <input type="number"
                                   class="form-control-modal"
                                   id="delai{{ $demande->ID_Demande }}"
                                   name="delai_supplementaire"
                                   min="1"
                                   max="168"
                                   placeholder="24">
                            <span class="input-group-text-modern">heures</span>
                        </div>
                        <small class="text-muted">Indiquez le délai estimé avant traitement (max 168h = 7 jours)</small>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer-modern">
                    <button type="button" class="btn-modal btn-cancel" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn-modal btn-attente">
                        <i class="fas fa-pause-circle me-2"></i>Mettre en attente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modal attente */
.modal-modern-attente {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

/* Header warning */
.modal-header-warning {
    background: linear-gradient(135deg, #f6c23e 0%, #f0b429 100%);
}

.modal-header-modern {
    position: relative;
    padding: 40px 30px 30px;
    border: none;
}

.modal-icon-wrapper {
    text-align: center;
    margin-bottom: 0;
}

.modal-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    box-shadow: 0 10px 30px rgba(246, 194, 62, 0.4);
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.btn-close-modern {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-close-modern:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

/* Body */
.modal-body-modern {
    padding: 30px;
    animation: fadeInUp 0.5s ease-out;
}

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

.modal-title-modern {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
}

/* Info Box Attente */
.demande-info-box-attente {
    background: linear-gradient(135deg, #fffbf0 0%, #fff5d9 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border: 2px solid #ffe9b3;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255, 233, 179, 0.5);
}

.info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-label {
    font-size: 0.9rem;
    color: #718096;
    font-weight: 600;
}

.info-value {
    font-size: 0.95rem;
    color: #2d3748;
    font-weight: 700;
}

/* Alert info */
.alert-info-modern {
    background: linear-gradient(135deg, #e6f7ff 0%, #cceeff 100%);
    border-left: 4px solid #36b9cc;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    color: #0c5460;
    font-size: 0.9rem;
    line-height: 1.6;
}

/* Form controls */
.form-label-modal {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 10px;
    font-size: 0.95rem;
    display: block;
}

.form-control-modal {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
}

.form-control-modal:focus {
    outline: none;
    border-color: #f6c23e;
    box-shadow: 0 0 0 3px rgba(246, 194, 62, 0.1);
}

textarea.form-control-modal {
    resize: vertical;
    min-height: 100px;
}

/* Input group */
.input-group-modern {
    display: flex;
    gap: 0;
}

.input-group-modern .form-control-modal {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-right: none;
}

.input-group-text-modern {
    padding: 12px 16px;
    background: linear-gradient(135deg, #f6f8fb 0%, #e9ecf5 100%);
    border: 2px solid #e2e8f0;
    border-left: none;
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
}

/* Footer */
.modal-footer-modern {
    padding: 20px 30px;
    border-top: 2px solid #f0f0f0;
    background: #f8f9fc;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn-modal {
    padding: 12px 25px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-cancel {
    background: white;
    color: #6c757d;
    border: 2px solid #dee2e6;
}

.btn-cancel:hover {
    background: #f8f9fa;
    color: #495057;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.btn-attente {
    background: linear-gradient(135deg, #f6c23e 0%, #f0b429 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(246, 194, 62, 0.3);
}

.btn-attente:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(246, 194, 62, 0.4);
}

/* Animation du modal */
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
    transform: translateY(-50px);
}

.modal.show .modal-dialog {
    transform: translateY(0);
}

/* Responsive */
@media (max-width: 576px) {
    .modal-body-modern {
        padding: 20px;
    }

    .modal-footer-modern {
        flex-direction: column;
    }

    .btn-modal {
        width: 100%;
    }

    .info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }

    .modal-icon {
        width: 70px;
        height: 70px;
        font-size: 2rem;
    }
}
</style>
