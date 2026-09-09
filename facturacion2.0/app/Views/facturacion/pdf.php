<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #<?= $factura['id'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; margin: 20px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; margin-top: 20px; }
        table th { background: #f3f3f3; padding: 10px; border-bottom: 1px solid #ddd; }
        table td { padding: 10px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .header { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h2>Factura de Venta #<?= $factura['id'] ?></h2>
            <p><strong>Fecha:</strong> <?= $factura['created_at'] ?></p>
            <p><strong>Cliente:</strong> <?= $factura['cliente'] ?> (CI/RUC: <?= $factura['identificacion'] ?>)</p>
            <p><strong>Atendido por:</strong> <?= $factura['atendido'] ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Producto / Código</th>
                    <th>Cant.</th>
                    <th class="text-right">Precio Unit.</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $det): ?>
                <tr>
                    <td><?= $det['producto_nombre'] ?? $det['nombre'] ?></td>
                    <td><?= $det['cantidad'] ?></td>
                    <td class="text-right">$<?= number_format($det['precio_unitario'], 2) ?></td>
                    <td class="text-right">$<?= number_format($det['subtotal'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="text-right" style="margin-top: 20px;">Total: $<?= number_format($factura['total'], 2) ?></h3>
    </div>
</body>
</html>