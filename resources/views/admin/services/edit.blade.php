@extends('layouts.admin')

@section('title', 'Modifier un service/localisation')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', 'Modifier un service/localisation')
<br><br>
@section('page-actions')
<a href="{{ route('admin.services.index') }}" class="btn-return">
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

    <form method="POST" action="{{ route('admin.services.update', $service) }}" class="service-form" id="serviceForm">
        @csrf
        @method('PUT')
        
        <!-- Section Type et Nom -->
        <div class="form-section">
            <h2><i class="fas fa-info-circle"></i> Informations de base</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="type">Type *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-tag"></i>
                        <select id="type" name="type" class="form-select" required>
                            <option value="">Sélectionnez un type</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type', $service->type) == $key ? 'selected' : '' }}>
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
                    <label for="nom">Nom *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-building"></i>
                        <input type="text" id="nom" name="nom" 
                               value="{{ old('nom', $service->nom) }}" 
                               placeholder="Ex: Direction Informatique" required>
                    </div>
                    @error('nom')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="parent_id">Parent</label>
                    <div class="input-with-icon">
                        <i class="fas fa-level-up-alt"></i>
                        <select id="parent_id" name="parent_id" class="form-select">
                            <option value="">Aucun parent (racine)</option>
                            @foreach($parents as $id => $label)
                                <option value="{{ $id }}" {{ old('parent_id', $service->parent_id) == $id ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <small class="form-hint">Sélectionnez l'élément parent dans la hiérarchie</small>
                    @error('parent_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="code_geographique">Code géographique</label>
                    <div class="input-with-icon">
                        <i class="fas fa-qrcode"></i>
                        <input type="text" id="code_geographique" name="code_geographique" 
                               value="{{ old('code_geographique', $service->code_geographique) }}" 
                               placeholder="Ex: DCSSA-001">
                    </div>
                    <small class="form-hint">Laissé vide pour génération automatique</small>
                    @error('code_geographique')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Responsable -->
        <div class="form-section">
            <h2><i class="fas fa-user-tie"></i> Responsable</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="responsable_id">Responsable</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <select id="responsable_id" name="responsable_id" class="form-select">
                            <option value="">Aucun responsable</option>
                            @foreach($responsables as $id => $label)
                                <option value="{{ $id }}" {{ old('responsable_id', $service->responsable_id) == $id ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('responsable_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section Contact -->
        <div class="form-section">
            <h2><i class="fas fa-address-card"></i> Coordonnées</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <div class="input-with-icon">
                        <i class="fas fa-map-marker-alt"></i>
                        <textarea id="adresse" name="adresse" 
                                  placeholder="Adresse complète"
                                  rows="2">{{ old('adresse', $service->adresse) }}</textarea>
                    </div>
                    @error('adresse')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <div class="input-with-icon">
                        <i class="fas fa-phone"></i>
                        <input type="text" id="telephone" name="telephone" 
                               value="{{ old('telephone', $service->telephone) }}" 
                               placeholder="+221 XX XXX XX XX">
                    </div>
                    @error('telephone')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="description">Description</label>
                    <div class="input-with-icon">
                        <i class="fas fa-align-left"></i>
                        <textarea id="description" name="description" 
                                  placeholder="Description détaillée"
                                  rows="3">{{ old('description', $service->description) }}</textarea>
                    </div>
                    @error('description')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <div class="action-buttons">
                <a href="{{ route('admin.services.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    Annuler
                </a>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i>
                    Mettre à jour
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<!-- Même CSS que create.blade.php -->
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-génération du code géographique
        const typeSelect = document.getElementById('type');
        const nomInput = document.getElementById('nom');
        const codeInput = document.getElementById('code_geographique');
        
        function generateCode() {
            if (typeSelect.value && nomInput.value && !codeInput.value) {
                const type = typeSelect.value.toUpperCase().substring(0, 3);
                const nom = nomInput.value.toUpperCase().replace(/[^A-Z]/g, '').substring(0, 3);
                const random = Math.random().toString(36).substring(2, 6).toUpperCase();
                
                codeInput.value = `${type}-${nom}-${random}`;
            }
        }
        
        typeSelect.addEventListener('change', generateCode);
        nomInput.addEventListener('blur', generateCode);
        
        // Validation du formulaire
        const form = document.getElementById('serviceForm');
        const submitBtn = document.getElementById('submitBtn');
        
        form.addEventListener('submit', function(e) {
            // Validation simple
            const type = typeSelect.value.trim();
            const nom = nomInput.value.trim();
            
            if (!type) {
                e.preventDefault();
                showAlert('Erreur', 'Veuillez sélectionner un type.', 'error');
                typeSelect.focus();
                return;
            }
            
            if (!nom) {
                e.preventDefault();
                showAlert('Erreur', 'Veuillez saisir un nom.', 'error');
                nomInput.focus();
                return;
            }
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

        // Initialiser Select2 pour les select
        $('#parent_id, #responsable_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Sélectionnez une option',
            allowClear: true
        });

        // Changer la classe du select pour s'adapter à Select2
        $('#type').select2({
            theme: 'bootstrap-5',
            placeholder: 'Sélectionnez un type'
        });
    });
</script>
@endpush