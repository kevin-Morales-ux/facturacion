<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Categorías
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Administración de Categorías
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <button type="button" class="btn btn-primary" onclick="nuevaCategoria()">
                <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
            </button>
            
            <div class="input-group" style="width: 250px;">
                <input type="text" id="tablaFiltro" class="form-control" placeholder="Buscar categoría...">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
            </div>
        </div>
        
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaCategorias">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;" class="text-center">#</th>
                        <th>Nombre de Categoría</th>
                        <th style="width: 120px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categorias)): ?>
                        <?php foreach ($categorias as $key => $c): ?>
                            <tr id="row-<?= $c['id_categoria'] ?>">
                                <td class="text-center fw-bold"><?= $key + 1 ?></td>
                                <td class="col-nombre"><?= esc($c['nombre']) ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning me-1" onclick="editarCategoria(<?= $c['id_categoria'] ?>)" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarCategoria(<?= $c['id_categoria'] ?>)" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="sinRegistros">
                            <td colspan="3" class="text-center text-muted py-4">No hay categorías registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Emergente para Registro / Edición -->
<div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCategoriaLabel">Nueva Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formCategoria">
                <div class="modal-body">
                    <input type="hidden" id="id_categoria" name="id_categoria">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Categoría <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. Bebidas, Lácteos, Baterías..." required>
                        <div class="invalid-feedback" id="error-nombre"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts interactivos (AJAX y Búsqueda en tiempo real) -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modalElement = document.getElementById('modalCategoria');
    const modalObj = new bootstrap.Modal(modalElement);
    const form = document.getElementById('formCategoria');
    const inputFiltro = document.getElementById('tablaFiltro');

    // Resetear formulario al abrir modal para "Nueva Categoría"
    window.nuevaCategoria = function() {
        form.reset();
        document.getElementById('id_categoria').value = '';
        document.getElementById('modalCategoriaLabel').textContent = 'Nueva Categoría';
        limpiarErrores();
        modalObj.show();
    };

    // Filtro en tiempo real en la tabla
    inputFiltro.addEventListener('keyup', function() {
        const query = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tablaCategorias tbody tr');

        filas.forEach(fila => {
            const nombreCol = fila.querySelector('.col-nombre');
            if (nombreCol) {
                const text = nombreCol.textContent.toLowerCase();
                fila.style.display = text.includes(query) ? '' : 'none';
            }
        });
    });

    // Guardar mediante AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        limpiarErrores();

        const formData = new FormData(form);

        fetch('<?= base_url('categorias/guardar') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                modalObj.hide();
                location.reload(); // Recarga la vista para refrescar los datos
            } else if (data.status === 'error' && data.errors) {
                mostrarErrores(data.errors);
            }
        })
        .catch(err => console.error("Error al procesar:", err));
    });

    // Obtener datos para editar
    window.editarCategoria = function(id) {
        limpiarErrores();
        fetch(`<?= base_url('categorias/obtener/') ?>${id}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                document.getElementById('id_categoria').value = res.data.id_categoria;
                document.getElementById('nombre').value = res.data.nombre;
                document.getElementById('modalCategoriaLabel').textContent = 'Editar Categoría';
                modalObj.show();
            } else {
                alert(res.message);
            }
        });
    };

    // Eliminar registro
    window.eliminarCategoria = function(id) {
        if (confirm('¿Está seguro de eliminar esta categoría?')) {
            fetch(`<?= base_url('categorias/eliminar/') ?>${id}`, {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    const fila = document.getElementById(`row-${id}`);
                    if (fila) fila.remove();
                } else {
                    alert(res.message);
                }
            });
        }
    };

    function limpiarErrores() {
        document.getElementById('nombre').classList.remove('is-invalid');
        document.getElementById('error-nombre').textContent = '';
    }

    function mostrarErrores(errors) {
        if (errors.nombre) {
            const inputNombre = document.getElementById('nombre');
            inputNombre.classList.add('is-invalid');
            document.getElementById('error-nombre').textContent = errors.nombre;
        }
    }
});
</script>
<?= $this->endSection() ?>