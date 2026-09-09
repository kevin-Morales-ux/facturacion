<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Decisiones con datos claros</h1>
                <p class="text-muted text-sm">Revisa el pulso del negocio y detecta lo que necesita atención hoy.</p>
            </div>
            <div class="col-sm-6 text-right">
                <span class="badge badge-light p-2 shadow-sm border"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y') ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        
        <!-- Fila 1: Tarjetas KPI (Indicadores Clave) -->
        <div class="row">
            <!-- Ventas de hoy -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-white border-bottom border-primary shadow-sm">
                    <div class="inner">
                        <h3><?= $ventasHoy ?? 0 ?></h3>
                        <p class="text-muted mb-0">Ventas de hoy</p>
                        <small class="text-muted">transacciones registradas</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice text-primary"></i>
                    </div>
                </div>
            </div>

            <!-- Ingresos del mes -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-white border-bottom border-success shadow-sm">
                    <div class="inner">
                        <h3>$<?= number_format($ingresosMes ?? 0, 2) ?></h3>
                        <p class="text-muted mb-0">Ingresos del mes</p>
                        <small class="text-muted">acumulado mensual</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-wallet text-success"></i>
                    </div>
                </div>
            </div>

            <!-- Clientes registrados -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-white border-bottom border-warning shadow-sm">
                    <div class="inner">
                        <h3><?= $totalClientes ?? 0 ?></h3>
                        <p class="text-muted mb-0">Clientes registrados</p>
                        <small class="text-muted">base de clientes</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users text-warning"></i>
                    </div>
                </div>
            </div>

            <!-- Stock por revisar (Alertas) -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-white border-bottom border-danger shadow-sm">
                    <div class="inner">
                        <h3><?= count($stockCritico ?? []) ?></h3>
                        <p class="text-muted mb-0">Stock por revisar</p>
                        <small class="text-muted">productos con 5 o menos unidades</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fila 2: Gráficos de Tendencia e Ingresos -->
        <div class="row">
            <!-- Gráfico de Líneas: Actividad últimos 7 días -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold">Actividad de los últimos 7 días</h3>
                        <div class="card-tools">
                            <span class="badge badge-info"><i class="fas fa-chart-line"></i> Tendencia en tiempo real</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="salesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Barras: Ingresos Mensuales -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold">Ingresos mensuales</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="monthlyIncomeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fila 3: Productos más vendidos y Alertas de Inventario -->
        <div class="row">
            <!-- Tabla: Productos más vendidos -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold">Productos más vendidos</h3>
                        <div class="card-tools">
                            <i class="fas fa-chart-bar text-muted"></i>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>PRODUCTO</th>
                                    <th>UNIDADES</th>
                                    <th>INGRESOS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($productosTop)): ?>
                                    <?php foreach ($productosTop as $prod): ?>
                                    <tr>
                                        <td><?= esc($prod['nombre']) ?></td>
                                        <td><span class="badge bg-info"><?= $prod['total_unidades'] ?></span></td>
                                        <td>$<?= number_format($prod['total_ingresos'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay registros de ventas aún.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Panel de Alertas de Inventario -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold">Alertas de inventario</h3>
                        <div class="card-tools">
                            <i class="fas fa-box text-warning"></i>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            <?php if (!empty($stockCritico)): ?>
                                <?php foreach ($stockCritico as $item): ?>
                                <li class="item py-2 border-bottom">
                                    <div class="product-info ml-0">
                                        <a href="javascript:void(0)" class="product-title font-weight-bold text-dark">
                                            <?= esc($item['nombre']) ?>
                                            <span class="badge badge-warning float-right"><?= $item['stock'] ?> unid.</span>
                                        </a>
                                        <span class="product-description text-muted text-sm">
                                            Precio: $<?= number_format($item['precio_venta'], 2) ?>
                                        </span>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="item text-center p-3 text-muted">
                                    <i class="fas fa-check-circle text-success fa-2x mb-2"></i><br>
                                    Stock en niveles óptimos.
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<?= $this->endSection() ?>

<!-- Scripts para inicializar Chart.js -->
<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Datos pasados desde el controlador de CodeIgniter en formato JSON
    const diasLabels = <?= json_encode($diasLabels ?? []) ?>;
    const ventasData = <?= json_encode($ventasData ?? []) ?>;
    const ingresosData = <?= json_encode($ingresosData ?? []) ?>;

    // 1. Gráfico de Líneas (Actividad 7 días)
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: diasLabels,
            datasets: [
                {
                    label: 'Ventas (Transacciones)',
                    data: ventasData,
                    borderColor: '#17a2b8',
                    backgroundColor: 'rgba(23, 162, 184, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Ingresos ($)',
                    data: ingresosData,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // 2. Gráfico de Barras (Ingresos Mensuales)
    const monthlyCtx = document.getElementById('monthlyIncomeChart').getContext('2d');
    const mesesLabels = <?= json_encode($mesesLabels ?? ['Sep 2026']) ?>;
    const mesesIngresos = <?= json_encode($mesesIngresos ?? [0]) ?>;
    
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: mesesLabels,
            datasets: [{
                label: 'Ingresos ($)',
                data: mesesIngresos,
                backgroundColor: '#bce8f0',
                borderColor: '#17a2b8',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>