<?php namespace App\Controllers;

use App\Models\CompraModel;
use App\Models\ProveedorModel;
use App\Models\ProductoModel;

class ComprasController extends BaseController {

    public function index() {
        $proveedorModel = new ProveedorModel();
        $productoModel = new ProductoModel();
        $compraModel = new CompraModel();

        // Obtener historial de compras haciendo join con proveedores y usuarios
        $db = \Config\Database::connect();
        $builder = $db->table('compra');
        $builder->select('compra.*, proveedor.nombre as proveedor_nombre, usuario.nombre as usuario_nombre');
        $builder->join('proveedor', 'proveedor.id_proveedor = compra.id_proveedor', 'left');
        $builder->join('usuario', 'usuario.id_usuario = compra.id_usuario', 'left');
        $builder->orderBy('compra.id_compra', 'DESC');
        $compras = $builder->get()->getResultArray();

        $data = [
            'proveedores' => $proveedorModel->findAll(),
            'productos'   => $productoModel->findAll(),
            'compras'     => $compras
        ];

        return view('compras/index', $data);
    }

    public function guardar() {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $json = $this->request->getJSON(true);

        if (empty($json['detalles']) || empty($json['id_proveedor'])) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Faltan datos obligatorios (proveedor o productos).'
            ]);
        }

        $compraModel = new CompraModel();
        
        $dataCompra = [
            'id_proveedor' => $json['id_proveedor'],
            'id_usuario'   => session()->get('id_usuario'), 
            'total'        => $json['total']
        ];

        $resultado = $compraModel->registrarCompra($dataCompra, $json['detalles']);

        if ($resultado) {
            return $this->response->setJSON([
                'status' => 'success', 
                'message' => '¡Compra registrada correctamente y stock actualizado!'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Error al registrar la compra en la base de datos.'
            ]);
        }
    }
}