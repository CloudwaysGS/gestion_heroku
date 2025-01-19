<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            padding: 20px;
        }

        h3 {
            text-align: center;
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .info-section .client, .info-section .vendeur {
            width: 48%;
            font-size: 14px;
        }

        .info-section p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            color: #2c3e50;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .totals {
            margin-top: 20px;
        }

        .totals p {
            font-size: 16px;
            margin: 5px 0;
            display: flex;
            justify-content: space-between;
        }

        .totals .highlight {
            font-weight: bold;
            color: #e67e22;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #555;
        }

        @media (max-width: 768px) {
            .info-section {
                flex-direction: column;
            }

            .info-section .client, .info-section .vendeur {
                width: 100%;
            }

            table th, table td {
                font-size: 13px;
            }

            .totals p {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Facture</h3>

        <div class="info-section">
            <div class="client">
                <p><strong>CLIENT :</strong> {{ $client->nomClient ?? 'Non spécifié' }}</p>
                <p><strong>REFERENCE :</strong> {{ $reference ?? 'N/A' }}</p>
                <p><strong>DATE :</strong> {{ $date ?? 'Non spécifiée' }}</p>
            </div>
            <hr>
            <div class="vendeur">
                <p><strong>GERANT :</strong> {{ $vendeur->name ?? 'Non spécifié' }}</p>
                <p><strong>ADRESSE :</strong>WAKEUR ALY CISSE</p>
                <p><strong>TELEPHONE :</strong>77 570 65 97 - 77 578 00 49</p>
                <p><strong>NINEA :</strong>011426881 2M6</p>
                <p><strong>RCCM :</strong>SN KLK 2024 C 3685</p>
            </div>
        </div>

        <table aria-label="Détails des articles de la facture">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($facture as $items)
                    <tr>
                        <td>{{ $items->nom }}</td>
                        <td>{{ $items->quantite }}</td>
                        <td>{{ number_format($items->prix, 2) }} FCFA</td>
                        <td>{{ number_format($items->montant, 2) }} FCFA</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">Aucune facture trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="totals">
            <p>Total Montant : <span class="highlight">{{ number_format($totalMontants, 2) }} FCFA</span></p>
            @if($avance > 0)
                <p>Acompte : <span class="highlight">{{ number_format($avance, 2) }} FCFA</span></p>
            @endif
            @if($reste > 0)
                <p>Reste : <span class="highlight">{{ number_format($reste, 2) }} FCFA</span></p>
            @endif
            @if($depot > 0)
                <p>Dépot : <span class="highlight">{{ number_format($depot, 2) }} FCFA</span></p>
            @endif
        </div>

        <div class="footer">
            <p>Merci pour votre confiance !</p>
            <p>Pour toute question, contactez-nous à support@example.com</p>
        </div>
    </div>
</body>
</html>
