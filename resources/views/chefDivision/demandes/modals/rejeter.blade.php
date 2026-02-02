<div class="modal fade" id="rejeterModal{{ $demande->ID_Demande }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-modern-reject">
            <form action="{{ route('chef-division.demandes.rejeter', $demande->ID_Demande) }}" method="POST">
                @csrf

                <!-- Header avec icône -->
                <div class="modal-header-modern modal-header-danger">
                    <div class="modal-icon-wrapper">
                        <div class="modal-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    <button type="button" class="btn-close-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body-modern">
                    <h4 class="modal-title-modern text-center mb-3">
                        Rejeter la demande
                    </h4>

                    <div class="demande-info-box-reject">
                        <div class="info-row">
                            <span class="info-label">N° Demande</span>
                            <span class="info-value">{{ $demande->Numero_Demande }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Demandeur</span>
                            <span class="info-value">{{ $demande->demandeur->nom_complet ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Équipement</span>
                            <span class="info-value">{{ $demande->equipement->numero_inventaire ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="alert-warning-modern">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Le demandeur sera notifié par email du rejet de sa demande.
                    </div>

                    <!-- Raison du rejet -->
                    <div class="mb-3">
                        <label for="raison{{ $demande->ID_Demande }}" class="form-label-modal">
                            <i class="fas fa-list-ul me-2"></i>Raison du rejet <span class="text-danger">*</span>
                        </label>
                        <div class="select-wrapper-modern">
                            <select class="form-control-modal"
                                    id="raison{{ $demande->ID_Demande }}"
                                    name="raison"
                                    required>
                                <option value="">Sélectionner une raison...</option>
                                <option value="inappropriée">❌ Demande inappropriée</option>
                                <option value="équipement_non_disponible">🔧 Équipement non disponible</option>
                                <option value="manque_informations">📋 Manque d'informations</option>
                                <option value="autre">💬 Autre raison</option>
                            </select>
                            <i class="fas fa-chevron-down select-icon"></i>
                        </div>
                    </div>

                    <!-- Commentaire -->
                    <div class="mb-0">
                        <label for="commentaire_rejet{{ $demande->ID_Demande }}" class="form-label-modal">
                            <i class="fas fa-comment-dots me-2"></i>Commentaire détaillé <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control-modal"
                                  id="commentaire_rejet{{ $demande->ID_Demande }}"
                                  name="commentaire"
                                  rows="4"
                                  placeholder="Expliquez en détail la raison du rejet..."
                                  required></textarea>
                        <small class="text-muted">Ce commentaire sera visible par le demandeur</small>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer-modern">
                    <button type="button" class="btn-modal btn-cancel" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn-modal btn-reject">
                        <i class="fas fa-ban me-2"></i>Confirmer le rejet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modal rejet */
.modal-modern-reject {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

/* Header danger */
.modal-header-danger {
    background: linear-gradient(135deg, #e74a3b 0%, #d32f2f 100%);
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
    box-shadow: 0 10px 30px rgba(231, 74, 59, 0.4);
    animation: shake 0.6s ease-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0) scale(1); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px) scale(1.05); }
    20%, 40%, 60%, 80% { transform: translateX(5px) scale(1.05); }
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

/* Info Box Reject */
.demande-info-box-reject {
    background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border: 2px solid #ffd5d5;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255, 213, 213, 0.5);
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

/* Alert warning */
.alert-warning-modern {
    background: linear-gradient(135deg, #fff5e6 0%, #ffe9cc 100%);
    border-left: 4px solid #f6c23e;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    color: #7c5d0c;
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
    border-color: #e74a3b;
    box-shadow: 0 0 0 3px rgba(231, 74, 59, 0.1);
}

/* Select wrapper */
.select-wrapper-modern {
    position: relative;
}

.select-wrapper-modern select {
    appearance: none;
    cursor: pointer;
    padding-right: 45px;
}

.select-icon {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #e74a3b;
    pointer-events: none;
    transition: all 0.3s ease;
}

.select-wrapper-modern:hover .select-icon {
    transform: translateY(-50%) translateY(2px);
}

textarea.form-control-modal {
    resize: vertical;
    min-height: 100px;
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

.btn-reject {
    background: linear-gradient(135deg, #e74a3b 0%, #d32f2f 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(231, 74, 59, 0.3);
}

.btn-reject:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(231, 74, 59, 0.4);
}

/* Animation du modal */
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
    transform: translateY(-50px);
}

.modal.show .modal-dialog {
    transform: translateY(0);
}

/* Validation visuelle */
.form-control-modal:invalid {
    border-color: #e2e8f0;
}

.form-control-modal:valid {
    border-color: #1cc88a;
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

/* Animation au focus */
.form-control-modal:focus {
    animation: focusPulse 0.3s ease-out;
}

@keyframes focusPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}
</style>
