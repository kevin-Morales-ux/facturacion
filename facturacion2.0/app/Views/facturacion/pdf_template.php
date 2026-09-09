<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { text-align: right; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Factura de Venta</h2>
        <p>Nro: <?= $factura['id'] ?? '' ?></p>
    </div>
    
    <p><strong>Fecha:</strong> <?= $factura['created_at'] ?? date('Y-m-d') ?></p>
    
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $detalle): ?>
            <tr>
                <td><?= $detalle['producto_nombre'] ?? 'Item' ?></td>
                <td><?= $detalle['cantidad'] ?? 1 ?></td>
                <td>$<?= number_format($detalle['precio'] ?? 0, 2) ?></td>
                <td>$<?= number_format(($detalle['cantidad'] ?? 1) * ($detalle['precio'] ?? 0), 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">
        Total: $<?= number_format($factura['total'] ?? 0, 2) ?>
    </div>
</body>
</html>