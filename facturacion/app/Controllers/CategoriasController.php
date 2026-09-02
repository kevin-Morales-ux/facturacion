<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;

class CategoriasController extends BaseController
{
    protected $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    public function index()
    {
        $data['categorias'] = $this->categoriaModel->findAll();
        return view('categorias/index', $data);
    }

    // Guardar (Crear o Actualizar) vía AJAX
    public function guardar()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $id = $this->request->getPost('id_categoria');
        $data = [
            'nombre' => trim($this->request->getPost('nombre'))
        ];

        if (!empty($id)) {
            $data['id_categoria'] = $id;
        }

        if ($this->categoriaModel->save($data)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Categoría guardada con éxito.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->categoriaModel->errors()
            ]);
        }
    }

    // Obtener datos para editar vía AJAX
    public function obtener($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $categoria = $this->categoriaModel->find($id);

        if ($categoria) {
            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $categoria
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Categoría no encontrada.'
        ]);
    }

    // Eliminar vía AJAX
    public function eliminar($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        if ($this->categoriaModel->delete($id)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Categoría eliminada con éxito.'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'No se pudo eliminar la categoría.'
        ]);
    }
}