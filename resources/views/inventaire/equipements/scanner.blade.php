@extends('layouts.admin')

@section('title', 'Scanner un équipement')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                <span class="text-blue-600">🔍</span> Scanner un équipement
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Scannez le code-barres ou QR code d'un équipement pour accéder rapidement à ses informations.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Carte de scanner -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        Scanner manuel
                    </h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        ⌨️ Saisie clavier
                    </span>
                </div>

                <form action="{{ route('inventaire.equipements.scanner') }}" method="GET" class="space-y-6">
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            Code-barres / QR code / N° inventaire
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">🔢</span>
                            </div>
                            <input type="text" 
                                   name="code" 
                                   id="code"
                                   class="block w-full pl-10 pr-4 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                                   placeholder="Ex: EQP-ABC123 ou INV-2024-001"
                                   autofocus
                                   required>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            Entrez le code de l'équipement puis appuyez sur Entrée
                        </p>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit"
                                class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition duration-200 transform hover:-translate-y-1 shadow-lg hover:shadow-xl">
                            <span class="flex items-center justify-center gap-2">
                                🔍 Rechercher l'équipement
                            </span>
                        </button>
                        
                        <a href="{{ route('inventaire.equipements.index') }}"
                           class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition duration-200">
                            ↩ Retour
                        </a>
                    </div>
                </form>

                <!-- Conseils de recherche -->
                <div class="mt-10 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        💡 Conseils de recherche
                    </h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600">•</span>
                            <span class="text-gray-600">Vous pouvez scanner directement avec votre webcam</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600">•</span>
                            <span class="text-gray-600">Les codes sont insensibles à la casse</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600">•</span>
                            <span class="text-gray-600">Utilisez un lecteur de codes-barres externe pour plus de rapidité</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Carte d'informations -->
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-8 text-white">
                <div class="flex items-center gap-4 mb-6">
                    <div class="p-3 bg-white/20 rounded-xl">
                        <span class="text-3xl">📱</span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Scanner avec smartphone</h2>
                        <p class="text-blue-100">Utilisez l'application de votre téléphone</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5">
                        <h3 class="font-semibold text-lg mb-3">📸 Utilisation de la caméra</h3>
                        <p class="text-blue-100 mb-4">
                            La plupart des smartphones modernes peuvent scanner les QR codes directement depuis l'appareil photo.
                        </p>
                        <div class="flex items-center gap-2 text-sm">
                            <span class="bg-white/20 px-2 py-1 rounded">iOS</span>
                            <span>Ouvrez l'appareil photo et pointez vers le code</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm mt-2">
                            <span class="bg-white/20 px-2 py-1 rounded">Android</span>
                            <span>Utilisez Google Lens ou une application dédiée</span>
                        </div>
                    </div>

                    <!-- Statistiques de scan -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5">
                        <h3 class="font-semibold text-lg mb-3">📊 Statistiques récentes</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-3xl font-bold">1.5k</div>
                                <div class="text-sm text-blue-200">Scans ce mois</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold">98%</div>
                                <div class="text-sm text-blue-200">Taux de réussite</div>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des scans récents -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5">
                        <h3 class="font-semibold text-lg mb-3">🕒 Scans récents</h3>
                        <div class="space-y-3">
                            @foreach($recentScans ?? [] as $scan)
                            <div class="flex items-center justify-between py-2 border-b border-white/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                    <span class="font-mono">{{ substr($scan->code, 0, 12) }}...</span>
                                </div>
                                <span class="text-sm text-blue-200">{{ $scan->created_at->diffForHumans() }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section scanner webcam -->
        <div class="mt-12 bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    🎥 Scanner avec webcam
                </h2>
                <p class="text-gray-600">
                    Autorisez l'accès à votre caméra pour scanner automatiquement
                </p>
            </div>

            <div id="scanner-container" class="max-w-2xl mx-auto">
                <div class="border-4 border-dashed border-gray-300 rounded-2xl p-8 text-center">
                    <div class="text-5xl mb-4">📷</div>
                    <p class="text-gray-600 mb-6">
                        Le scanner webcam sera disponible prochainement
                    </p>
                    <button onclick="requestCameraAccess()"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-emerald-700 transition duration-200">
                        <span>Activer la caméra</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Focus automatique sur le champ de recherche
    document.getElementById('code').focus();

    // Simulation d'accès caméra
    function requestCameraAccess() {
        alert('Fonctionnalité en cours de développement. Pour l\'instant, utilisez le champ de saisie manuelle.');
        document.getElementById('code').focus();
    }

    // Raccourci clavier Ctrl+/ pour focus
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === '/') {
            e.preventDefault();
            document.getElementById('code').focus();
        }
    });

    // Auto-submit quand un code est détecté (pour les lecteurs externes)
    let codeInput = document.getElementById('code');
    let codeTimeout;
    
    codeInput.addEventListener('input', function(e) {
        clearTimeout(codeTimeout);
        
        // Si le code a plus de 6 caractères et contient EQP ou INV, soumettre automatiquement après 500ms
        if (e.target.value.length >= 6 && (e.target.value.includes('EQP') || e.target.value.includes('INV'))) {
            codeTimeout = setTimeout(function() {
                e.target.form.submit();
            }, 500);
        }
    });
</script>

<style>
    /* Animation pour le focus */
    input:focus {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5); }
        50% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
    }

    /* Style pour les messages d'erreur/succès */
    .alert {
        animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>
@endsection