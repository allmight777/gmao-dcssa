<div class="modal fade" id="validerModal{{ $demande->ID_Demande }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-modern">
            <form action="{{ route('chef-division.demandes.valider', $demande->ID_Demande) }}" method="POST">
                @csrf

                <!-- Header avec icône -->
                <div class="modal-header-modern modal-header-success">
                    <div class="modal-icon-wrapper">
                        <div class="modal-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <button type="button" class="btn-close-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body-modern">
                    <h4 class="modal-title-modern text-center mb-3">
                        Valider la demande
                    </h4>

                    <div class="demande-info-box">
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
                        <div class="info-row">
                            <span class="info-label">Type</span>
                            <span class="info-value">{{ $demande->getTypeInterventionFormateAttribute() }}</span>
                        </div>
                    </div>

                    <div class="alert-info-modern">
                        <i class="fas fa-info-circle me-2"></i>
                        En validant cette demande, elle sera transmise au service de maintenance pour traitement.
                    </div>

                    <div class="mb-0">
                        <label for="commentaire{{ $demande->ID_Demande }}" class="form-label-modal">
                            <i class="fas fa-comment me-2"></i>Commentaire (optionnel)
                        </label>
                        <textarea class="form-control-modal"
                                  id="commentaire{{ $demande->ID_Demande }}"
                                  name="commentaire"
                                  rows="4"
                                  placeholder="Ajoutez un commentaire pour le service de maintenance..."></textarea>
                        <small class="text-muted">Maximum 500 caractères</small>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer-modern">
                    <button type="button" class="btn-modal btn-cancel" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn-modal btn-validate">
                        <i class="fas fa-check me-2"></i>Valider la demande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modal moderne */
.modal-modern {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

/* Header avec gradient */
.modal-header-modern {
    position: relative;
    padding: 40px 30px 30px;
    border: none;
}

.modal-header-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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
    box-shadow: 0 10px 30px rgba(56, 239, 125, 0.4);
    animation: scaleIn 0.5s ease-out;
}

@keyframes scaleIn {
    from {
        transform: scale(0);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
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

/* Info Box */
.demande-info-box {
    background: linear-gradient(135deg, #f6f8fb 0%, #e9ecf5 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border: 2px solid #e2e8f0;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid rgba(226, 232, 240, 0.5);
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
}

.form-control-modal {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    resize: vertical;
}

.form-control-modal:focus {
    outline: none;
    border-color: #1cc88a;
    box-shadow: 0 0 0 3px rgba(28, 200, 138, 0.1);
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

.btn-validate {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(56, 239, 125, 0.3);
}

.btn-validate:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(56, 239, 125, 0.4);
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
}
</style>
