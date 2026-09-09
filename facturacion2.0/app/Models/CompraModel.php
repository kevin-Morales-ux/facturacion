<?php namespace App\Models;

use CodeIgniter\Model;

class CompraModel extends Model {
    protected $table = 'compra';
    protected $primaryKey = 'id_compra';
    protected $allowedFields = ['id_proveedor', 'id_usuario', 'total'];
    protected $useTimestamps = false; // Como usas TIMESTAMP DEFAULT CURRENT_TIMESTAMP en la BD

    public function registrarCompra($dataCompra, $detalles) {
        $db = \Config\Database::connect();
        $db->transStart(); // Iniciar transacción segura

        // 1. Insertar la cabecera de la compra
        $this->insert($dataCompra);
        $compraId = $this->insertID();

        $detalleCompraModel = new \App\Models\DetalleCompraModel();
        $productoModel = new \App\Models\ProductoModel();

        foreach ($detalles as $item) {
            // 2. Insertar cada detalle de la compra
            $detalleCompraModel->insert([
                'id_compra'      => $compraId,
                'id_producto'    => $item['id_producto'],
                'cantidad'       => $item['cantidad'],
                'costo_unitario' => $item['costo_unitario'],
                'subtotal'       => $item['cantidad'] * $item['costo_unitario']
            ]);

            // 3. Aumentar el stock del producto automáticamente en inventario
            $producto = $productoModel->find($item['id_producto']);
            if ($producto) {
                $nuevoStock = $producto['stock'] + $item['cantidad'];
                $productoModel->update($item['id_producto'], ['stock' => $nuevoStock]);
            }
        }

        $db->transComplete(); // Completar transacción
        return $db->transStatus(); // Retorna true si todo fue exitoso
    }
}