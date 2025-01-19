<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de caisse</title>
    <style>
        @page {
    size: 80mm 297mm; /* Taille adaptée au papier */
    margin: 0; /* Supprimer les marges */
}

body {
    margin: 0;
    padding: 0;
    font-family: 'Courier New', Courier, monospace;
    font-size: 12px;
    background: #fff;
    color: #000;
    text-align: left; /* Alignement à droite global */
}

.ticket {
    width: 72mm; /* Réduire légèrement pour s'adapter aux marges de l'imprimante */
    margin: 0 auto; /* Centrer horizontalement */
    padding: 5px;
    box-sizing: border-box;
    text-align: left; /* Alignement à droite pour le contenu */
}

.header, .footer {
    text-align: left; /* Alignement à droite pour l'en-tête et le pied de page */
    margin-bottom: 10px;
}

.header h1 {
    font-size: 16px;
    margin: 0;
}

.header p, .footer p {
    margin: 3px 0;
    font-size: 12px;
}

.info {
    margin-bottom: 10px;
    text-align: left;
}

.info p {
    margin: 3px 0;
    font-size: 12px;
}

.items {
    border-top: 1px dashed #000;
    border-bottom: 1px dashed #000;
    padding: 10px 0;
    margin: 10px 0;
    text-align: left;
}

.items .item {
    display: flex;
    justify-content: space-between;
}

.items .item p {
    margin: 3px 0;
    font-size: 12px;
}

.totals {
    margin: 10px 0;
    text-align: left;
}

.totals p {
    display: flex;
    justify-content: space-between;
    margin: 5px 0;
    font-weight: bold;
    font-size: 14px;
}

@media print {
    body {
        margin: 0;
        padding: 0;
    }

    .ticket {
        width: 80mm; /* Utiliser toute la largeur disponible */
        margin: 0 auto; /* Centrer horizontalement */
        padding: 5px;
        page-break-after: avoid;
    }
}

    </style>

</head>

<body>
    <div class="ticket">
        <!-- En-tête -->
        <div class="header">
            <h1>WAKEUR ALY CISSE</h1>
            <p>TELEPHONE:77 570 65 97</p>
            <p>NINEA:011426881 2M6</p>
            <p>RCCM:SN KLK 2024 C 3685</p>
        </div>

        <!-- Informations client -->
        <div class="info">
            <p>CLIENT:{{ $client->nomClient ?? 'Non spécifié' }}</p>
            <p>REFERENCE:{{ $reference ?? 'N/A' }}</p>
            <p>DATE:{{ $date ?? 'Non spécifiée' }}</p>
        </div>

        <!-- Articles -->
        <div class="items">
            @forelse ($facture as $items)
                <div class="item">
                    <p>{{ $items->nom }} x {{ $items->quantite }}</p>
                    <p>{{ number_format($items->montant, 2) }} FCFA</p>
                </div>
            @empty
                <p>Aucun article.</p>
            @endforelse
        </div>

        <!-- Totaux -->
        <div class="totals">
            <p>Total : <span>{{ number_format($totalMontants, 2) }} FCFA</span></p>
            @if($avance > 0)
                <p>Acompte : <span>{{ number_format($avance, 2) }} FCFA</span></p>
            @endif
            @if($reste > 0)
                <p>Reste : <span>{{ number_format($reste, 2) }} FCFA</span></p>
            @endif
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p>Merci pour votre visite !</p>
            <p>Contactez-nous à cisse@gmail.com</p>
        </div>
    </div>
</body>
</html>
