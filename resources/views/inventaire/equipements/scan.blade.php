<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan - {{ $equipement->numero_inventaire }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br  min-h-screen p-4">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-t-2xl shadow-2xl p-6 text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $equipement->numero_inventaire }}</h1>
            <p class="text-sm text-gray-500 mt-1">Code: {{ $equipement->code_barres }}</p>
        </div>

        <!-- Content -->
        <div class="bg-white shadow-2xl p-6 space-y-4">
            <!-- Identification -->
            <div class="border-l-4 border-blue-500 pl-4 py-2 bg-blue-50 rounded">
                <h2 class="text-lg font-bold text-blue-900 mb-3">🔍 Identification</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-semibold">Marque:</span>
                        <span class="text-gray-900 font-bold">{{ $equipement->marque }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-semibold">Modèle:</span>
                        <span class="text-gray-900 font-bold">{{ $equipement->modele }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-semibold">N° Série:</span>
                        <span class="text-gray-900 font-bold">{{ $equipement->numero_serie ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-semibold">Type:</span>
                        <span class="text-gray-900 font-bold">{{ $equipement->typeEquipement->libelle ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- État & Localisation -->
            <div class="border-l-4 border-green-500 pl-4 py-2 bg-green-50 rounded">
                <h2 class="text-lg font-bold text-green-900 mb-3">📍 Localisation</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-semibold">État:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white">
                            {{ strtoupper($equipement->etat) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-semibold">Lieu:</span>
                        <span class="text-gray-900 font-bold">{{ $equipement->localisation->nom ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-semibold">Service:</span>
                        <span class="text-gray-900 font-bold">{{ $equipement->serviceResponsable->nom ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Infos Financières -->
            <div class="border-l-4 border-yellow-500 pl-4 py-2 bg-yellow-50 rounded">
                <h2 class="text-lg font-bold text-yellow-900 mb-3">💰 Informations Financières</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-semibold">Prix d'achat:</span>
                        <span class="text-gray-900 font-bold">{{ number_format($equipement->prix_achat, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-semibold">Date d'achat:</span>
                        <span class="text-gray-900 font-bold">{{ $equipement->date_achat->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-semibold">Fournisseur:</span>
                        <span class="text-gray-900 font-bold">{{ $equipement->fournisseur->raison_sociale ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Maintenance -->
            <div class="border-l-4 border-purple-500 pl-4 py-2 bg-purple-50 rounded">
                <h2 class="text-lg font-bold text-purple-900 mb-3">⚙️ Maintenance</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-semibold">Type:</span>
                        <span class="text-gray-900 font-bold">{{ ucfirst($equipement->type_maintenance) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-semibold">Garantie:</span>
                        <span class="text-gray-900 font-bold">{{ $equipement->duree_garantie }} mois</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer avec bouton -->
        <div class="bg-white rounded-b-2xl shadow-2xl p-6">
            <a href="{{ route('inventaire.equipements.show', $equipement->id) }}" 
               class="block w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white text-center py-3 rounded-lg font-bold hover:shadow-lg transition">
                 Voir la fiche complète
            </a>
            <p class="text-center text-xs text-gray-400 mt-4">
                Scanné le {{ now()->format('d/m/Y à H:i') }}
            </p>
        </div>
    </div>
</body>
</html>