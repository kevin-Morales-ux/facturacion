<?php
namespace App\Controllers;

use App\Models\VentaModel;
use App\Models\ClienteModel;
use App\Models\ProductoModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $ventaModel = new VentaModel();
        $clienteModel = new ClienteModel();
        $productoModel = new ProductoModel();

        // 1. KPIs Principales
        $hoy = date('Y-m-d');
        $ventasHoy = $ventaModel->where("DATE(fecha)", $hoy)->countAllResults();
        
        $ingresosMesResult = $ventaModel->selectSum('total')
                                        ->where("MONTH(fecha)", date('m'))
                                        ->where("YEAR(fecha)", date('Y'))
                                        ->first();
        $ingresosMes = $ingresosMesResult['total'] ?? 0;

        $totalClientes = $clienteModel->countAll();

        // 2. Alertas de inventario (Stock <= 5)
        $stockCritico = $productoModel->where('stock <=', 5)->findAll();

        // 3. Productos más vendidos
        $db = \Config\Database::connect();
        $productosTop = $db->table('detalle_venta dv')
            ->select('p.nombre, SUM(dv.cantidad) as total_unidades, SUM(dv.subtotal) as total_ingresos')
            ->join('producto p', 'p.id_producto = dv.id_producto')
            ->groupBy('dv.id_producto')
            ->orderBy('total_unidades', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // 4. Datos para los últimos 7 días (Gráfico de líneas)
        $diasLabels = [];
        $ventasData = [];
        $ingresosData = [];

        for ($i = 6; $i >= 0; $i--) {
            $fechaDia = date('Y-m-d', strtotime("-$i days"));
            $diasLabels[] = date('d/m', strtotime($fechaDia));

            // Consultar transacciones y dinero del día
            $statsDia = $db->table('venta')
                ->select('COUNT(id_venta) as num_ventas, SUM(total) as suma_total')
                ->where("DATE(fecha)", $fechaDia)
                ->get()
                ->getRowArray();

            $ventasData[] = (int)($statsDia['num_ventas'] ?? 0);
            $ingresosData[] = (float)($statsDia['suma_total'] ?? 0);
        }

        // 5. Ingresos Mensuales (Últimos meses)
        $mesesLabels = ['Jul', 'Ago', 'Sep']; // Dinámico o estático según prefieras
        $mesesIngresos = [0, 0, $ingresosMes]; // Ejemplo mapeado

        $data = [
            'ventasHoy'     => $ventasHoy,
            'ingresosMes'   => $ingresosMes,
            'totalClientes' => $totalClientes,
            'stockCritico'  => $stockCritico,
            'productosTop'  => $productosTop,
            'diasLabels'    => $diasLabels,
            'ventasData'    => $ventasData,
            'ingresosData'  => $ingresosData,
            'mesesLabels'   => $mesesLabels,
            'mesesIngresos' => $mesesIngresos
        ];

        return view('dashboard/index', $data);
    }
}