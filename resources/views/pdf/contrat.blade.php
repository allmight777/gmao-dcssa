<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contrat {{ $contrat->Numero_Contrat }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #667eea;
        }
        .header h1 {
            color: #667eea;
            margin-bottom: 5px;
            font-size: 24px;
        }
        .header .numero {
            font-size: 18px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background: #667eea;
            color: white;
            padding: 8px 15px;
            margin-bottom: 15px;
            font-weight: bold;
            border-radius: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 15px;
        }
        .info-item {
            border-bottom: 1px solid #eee;
            padding: 8px 0;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            margin-bottom: 3px;
        }
        .info-value {
            font-size: 14px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .table th {
            background: #f8f9fa;
            padding: 10px;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        .table td {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-success { background: #28a745; color: white; }
        .badge-warning { background: #ffc107; color: #212529; }
        .badge-danger { background: #dc3545; color: white; }
        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CONTRAT DE MAINTENANCE</h1>
        <div class="numero">N° {{ $contrat->Numero_Contrat }}</div>
    </div>

    <!-- Informations générales -->
    <div class="section">
        <div class="section-title">INFORMATIONS GÉNÉRALES</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Libellé du contrat</div>
                <div class="info-value">{{ $contrat->Libelle }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Type de contrat</div>
                <div class="info-value">{{ ucfirst($contrat->Type) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Date de début</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($contrat->Date_Debut)->format('d/m/Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Date de fin</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($contrat->Date_Fin)->format('d/m/Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Statut</div>
                <div class="info-value">
                    <span class="badge badge-{{ $contrat->Statut === 'actif' ? 'success' : ($contrat->Statut === 'expire' ? 'danger' : 'warning') }}">
                        {{ ucfirst($contrat->Statut) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Fournisseur -->
    <div class="section">
        <div class="section-title">FOURNISSEUR</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Raison sociale</div>
                <div class="info-value">{{ $contrat->fournisseur->raison_sociale }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Code fournisseur</div>
                <div class="info-value">{{ $contrat->fournisseur->code_fournisseur }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Téléphone</div>
                <div class="info-value">{{ $contrat->fournisseur->telephone ?? 'Non renseigné' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $contrat->fournisseur->email ?? 'Non renseigné' }}</div>
            </div>
        </div>
    </div>

    <!-- Aspects financiers -->
    <div class="section">
        <div class="section-title">ASPECTS FINANCIERS</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Montant</div>
                <div class="info-value">{{ number_format($contrat->Montant, 0, ',', ' ') }} {{ $contrat->Devise }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Délai d'intervention garanti</div>
                <div class="info-value">{{ $contrat->Delai_Intervention_Garanti }} heures</div>
            </div>
            <div class="info-item">
                <div class="info-label">Couverture pièces</div>
                <div class="info-value">{{ $contrat->Couverture_Pieces ? 'Oui' : 'Non' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Couverture main d'œuvre</div>
                <div class="info-value">{{ $contrat->Couverture_Main_Oeuvre ? 'Oui' : 'Non' }}</div>
            </div>
        </div>
    </div>

    <!-- Équipements couverts -->
    <div class="section">
        <div class="section-title">ÉQUIPEMENTS COUVERTS</div>
        @if($contrat->equipements->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Localisation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contrat->equipements as $equipement)
                <tr>
                    <td>{{ $equipement->code }}</td>
                    <td>{{ $equipement->nom }}</td>
                    <td>{{ $equipement->type->nom ?? 'N/A' }}</td>
                    <td>{{ $equipement->localisation->nom ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Aucun équipement spécifié</p>
        @endif
    </div>

    <!-- Conditions particulières -->
    @if($contrat->Conditions_Particulieres)
    <div class="section">
        <div class="section-title">CONDITIONS PARTICULIÈRES</div>
        <p>{{ $contrat->Conditions_Particulieres }}</p>
    </div>
    @endif

    <!-- Exclusions -->
    @if($contrat->Exclusions)
    <div class="section">
        <div class="section-title">EXCLUSIONS</div>
        <p>{{ $contrat->Exclusions }}</p>
    </div>
    @endif

    <!-- Signatures -->
    <div class="signature">
        <div class="signature-box">
            <div class="signature-line">Pour le fournisseur</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Pour le client</div>
        </div>
    </div>

    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Ce document est la propriété de [Votre Société]</p>
    </div>
</body>
</html>
