<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Gestión de Compras</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            
            <!-- Historial de Compras -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Historial de Compras</h3>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaCompra">
                        <i class="bi bi-plus-lg me-1"></i> Nueva Compra
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tablaHistorialCompras">
                            <thead>
                                <tr>
                                    <th>N° Compra</th>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Atendido por</th>
                                    <th>Total ($)</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($compras)): ?>
                                    <?php foreach ($compras as $c): ?>
                                        <tr>
                                            <td><?= $c['id_compra'] ?></td>
                                            <td><?= $c['created_at'] ?? $c['fecha'] ?? 'Sin fecha' ?></td>
                                            <td><?= $c['proveedor_nombre'] ?? 'Sin Proveedor' ?></td>
                                            <td><?= $c['usuario_nombre'] ?? 'Administrador' ?></td>
                                            <td>$<?= number_format($c['total'], 2) ?></td>
                                            <td>
                                                <button class="btn btn-info btn-sm text-white btn-ver" data-id="<?= $c['id_compra'] ?>" title="Ver Detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <a href="<?= base_url('admin/compras/pdf/' . $c['id_compra']) ?>" class="btn btn-danger btn-sm" target="_blank" title="Descargar PDF">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- Dejar vacío para DataTables o mostrar fila estándar -->
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<!-- Modal Nueva Compra -->
<div class="modal fade" id="modalNuevaCompra" tabindex="-1" aria-labelledby="modalNuevaCompraLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalNuevaCompraLabel">
                    <i class="bi bi-cart-plus me-2"></i>Nueva Compra de Inventario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <!-- 1. Información del Proveedor -->
                <div class="card card-primary card-outline mb-3">
                    <div class="card-header">
                        <h6 class="card-title fw-bold">1. Información del Proveedor</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="buscar_proveedor" class="form-label">Buscar Proveedor (Nombre / RUC) *</label>
                                <input type="text" id="buscar_proveedor" class="form-control" placeholder="Escriba para buscar proveedor...">
                                <input type="hidden" id="id_proveedor">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Identificación</label>
                                <input type="text" id="prov_identificacion" class="form-control" readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" id="prov_telefono" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Agregar Productos -->
                <div class="card card-primary card-outline mb-3">
                    <div class="card-header">
                        <h6 class="card-title fw-bold">2. Agregar Productos</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="text" id="buscar_producto" class="form-control" placeholder="Escriba para buscar producto por código o nombre...">
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tablaDetalleCompra">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <th>Stock Actual</th>
                                        <th width="120px">Cantidad</th>
                                        <th width="150px">Costo Unit. ($)</th>
                                        <th width="150px">Subtotal ($)</th>
                                        <th width="60px">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No se han agregado productos a la compra.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Totales -->
                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold">Subtotal:</span>
                                    <span id="lblSubtotal">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold">IVA (15%):</span>
                                    <span id="lblIva">$0.00</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fs-5 fw-bold text-primary">
                                    <span>Total a Pagar:</span>
                                    <span id="lblTotal">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnGuardarCompra">
                    <i class="bi bi-save me-1"></i> Guardar Compra
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts de control (Lógica del Carrito / Modales) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let productosCompra = [];

    // Lógica simulada de búsqueda y gestión de carrito de compras
    // (Asegúrate de conectarla con tus rutas AJAX de proveedores y productos si aplican)
    
    const btnGuardar = document.getElementById('btnGuardarCompra');
    if(btnGuardar) {
        btnGuardar.addEventListener('click', function() {
            const idProveedor = document.getElementById('id_proveedor').value;
            if(!idProveedor) {
                alert('Debe seleccionar un proveedor.');
                return;
            }
            if(productosCompra.length === 0) {
                alert('Debe agregar al menos un producto.');
                return;
            }
            // Aquí haces tu petición Fetch/Ajax hacia el controlador de compras->guardar()
        });
    }
});
</script>
<?= $this->endSection() ?>