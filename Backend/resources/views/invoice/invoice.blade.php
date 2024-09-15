<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        .header { text-align: center; margin-bottom: 40px; }
    </style>
</head>
<body>
<div class="header">
    <h1>Facture #{{ $order->id }}</h1>
    <p>Date: {{ $order->created_at->format('d/m/Y') }}</p>
</div>

<table>
    <thead>
    <tr>
        <th>Produit</th>
        <th>Quantité</th>
        <th>Prix unitaire</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @dump($order->demande)
        <tr>

        </tr>
    <tr>
        <td colspan="3"><strong>Total</strong></td>
        <td>{{ $order->total }} MAD</td>
    </tr>
    </tbody>
</table>
</body>
</html>
