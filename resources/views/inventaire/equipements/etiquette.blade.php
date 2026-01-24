<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A5 portrait;
            margin: 5mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 7pt;
        }
        
        .container {
            width: 138mm;
            height: 200mm;
            margin: 0 auto;
            border: 3px solid #667eea;
            padding: 0;
            overflow: hidden;
        }
        
        .header {
            background-color: #667eea;
            color: white;
            padding: 3mm;
            text-align: center;
            border-bottom: 2px solid #ffd700;
        }
        
        .header h1 {
            margin: 0 0 1mm 0;
            font-size: 10pt;
            font-weight: bold;
        }
        
        .header .subtitle {
            margin: 0;
            font-size: 6pt;
            font-weight: normal;
        }
        
        .content {
            padding: 3mm;
        }
        
        .numero-box {
            background-color: #667eea;
            color: white;
            padding: 3mm;
            text-align: center;
            margin-bottom: 2mm;
        }
        
        .numero-box .label {
            font-size: 5pt;
            margin-bottom: 1mm;
        }
        
        .numero-box .value {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        .section {
            background-color: #f5f7fa;
            border-left: 2px solid #667eea;
            padding: 2mm;
            margin-bottom: 2mm;
        }
        
        .section-title {
            font-size: 6pt;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 1mm;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table td {
            padding: 1mm 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        table td:first-child {
            font-size: 5pt;
            color: #666;
            font-weight: bold;
            width: 40%;
        }
        
        table td:last-child {
            font-size: 6pt;
            color: #222;
            font-weight: bold;
            text-align: right;
        }
        
        .row-2cols {
            width: 100%;
        }
        
        .row-2cols td {
            width: 50%;
            vertical-align: top;
            padding-right: 1mm;
        }
        
        .badge {
            background-color: #10b981;
            color: white;
            padding: 0.5mm 2mm;
            font-size: 5pt;
            font-weight: bold;
            display: inline-block;
        }
        
        .highlight {
            background-color: #ffd700;
            padding: 0.5mm 1mm;
            font-weight: bold;
        }
        
        .qr-section {
            text-align: center;
            border: 2px solid #667eea;
            background-color: #f8f9fa;
            padding: 2mm;
            margin-top: 2mm;
        }
        
        .qr-section .qr-title {
            font-size: 6pt;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 2mm;
            text-transform: uppercase;
        }
        
        .qr-code {
            width: 60px;
            height: 60px;
            margin: 0 auto 2mm auto;
            display: block;
            border: 2px solid #fff;
            padding: 1mm;
            background: white;
        }
        
        .barcode {
            font-family: 'Courier New', monospace;
            font-size: 6pt;
            font-weight: bold;
            color: #333;
            background: white;
            padding: 1mm;
            border: 1px dashed #667eea;
            letter-spacing: 0.5px;
        }
        
        .footer {
            margin-top: 2mm;
            padding-top: 1mm;
            border-top: 1px solid #ddd;
            font-size: 4pt;
            color: #888;
        }
        
        .footer table {
            width: 100%;
        }
        
        .footer td {
            border: none;
            padding: 0;
        }
        
        .mini-cards {
            width: 100%;
            margin-bottom: 2mm;
        }
        
        .mini-cards td {
            width: 33.33%;
            text-align: center;
            background-color: white;
            border: 1px solid #e5e7eb;
            padding: 1.5mm;
        }
        
        .mini-label {
            font-size: 4pt;
            color: #888;
            display: block;
            margin-bottom: 0.5mm;
        }
        
        .mini-value {
            font-size: 6pt;
            color: #667eea;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>GESTION ÉQUIPEMENTS</h1>
            <p class="subtitle">INVENTAIRE OFFICIEL</p>
        </div>
        
        <div class="content">
            <!-- Numéro -->
            <div class="numero-box">
                <div class="label">NUMÉRO INVENTAIRE</div>
                <div class="value">{{ $equipement->numero_inventaire }}</div>
            </div>
            
            <!-- Identification -->
            <div class="section">
                <div class="section-title">🔍 IDENTIFICATION</div>
                <table>
                    <tr>
                        <td>MARQUE</td>
                        <td>{{ $equipement->marque }}</td>
                    </tr>
                    <tr>
                        <td>MODÈLE</td>
                        <td>{{ $equipement->modele }}</td>
                    </tr>
                    <tr>
                        <td>N° SÉRIE</td>
                        <td><span class="highlight">{{ $equipement->numero_serie ?? 'N/A' }}</span></td>
                    </tr>
                </table>
            </div>
            
            <!-- Mini cards -->
            <table class="mini-cards" cellspacing="1">
                <tr>
                    <td>
                        <span class="mini-label">TYPE</span>
                        <span class="mini-value">{{ Str::limit($equipement->typeEquipement->libelle ?? 'N/A', 7) }}</span>
                    </td>
                    <td>
                        <span class="mini-label">CLASSE</span>
                        <span class="mini-value">{{ Str::limit($equipement->classe_equipement ?? 'N/A', 7) }}</span>
                    </td>
                    <td>
                        <span class="mini-label">ÉTAT</span>
                        <span class="badge">{{ strtoupper($equipement->etat) }}</span>
                    </td>
                </tr>
            </table>
            
            <!-- Finance & Maintenance -->
            <table class="row-2cols">
                <tr>
                    <td>
                        <div class="section">
                            <div class="section-title">💰 FINANCE</div>
                            <table>
                                <tr>
                                    <td>PRIX</td>
                                    <td>{{ number_format($equipement->prix_achat, 0, ',', ' ') }} F</td>
                                </tr>
                                <tr>
                                    <td>ACHAT</td>
                                    <td>{{ date('d/m/Y', strtotime($equipement->date_achat)) }}</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td>
                        <div class="section">
                            <div class="section-title">⚙️ MAINTENANCE</div>
                            <table>
                                <tr>
                                    <td>TYPE</td>
                                    <td>{{ ucfirst($equipement->type_maintenance) }}</td>
                                </tr>
                                <tr>
                                    <td>GARANTIE</td>
                                    <td>{{ $equipement->duree_garantie }} mois</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            
            <!-- Localisation -->
            <div class="section">
                <div class="section-title">📍 LOCALISATION</div>
                <table>
                    <tr>
                        <td>LIEU</td>
                        <td>{{ Str::limit($equipement->localisation->nom ?? 'N/A', 25) }}</td>
                    </tr>
                    <tr>
                        <td>SERVICE</td>
                        <td>{{ Str::limit($equipement->serviceResponsable->nom ?? 'N/A', 25) }}</td>
                    </tr>
                </table>
            </div>
            
            <!-- QR Code Section -->
            <div class="qr-section">
                <div class="qr-title">📱 SCANNER CE CODE</div>
                <img src="{{ $qrCode }}" alt="QR Code" class="qr-code">
                <div class="barcode">{{ $equipement->code_barres }}</div>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <table>
                    <tr>
                        <td>Généré: {{ $dateGeneration }}</td>
                        <td style="text-align: right;">Par: {{ $generateur }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>