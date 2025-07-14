<?php
require 'conexionmysqli.php'; // Asegúrate de que este archivo existe y tiene la conexión

$idAnalisis = $_GET['idanalisis'];


$sqlCab="select fecha_inicio, fecha_final from analisis_costos where id='$idAnalisis'";
$respCab=mysqli_query($enlaceCon, $sqlCab);
$fechaInicio=""; $fechaFinal="";
while ($filaCab = mysqli_fetch_array($respCab)) {
    $fechaInicio=$filaCab[0];
    $fechaFinal=$filaCab[1];
}


// Consulta SQL para obtener los detalles donde analisis_costo_id = 1
$sql = "SELECT 
          a.id,
          a.analisis_costo_id,
          a.codigo_producto,
          m.descripcion_material as nombre_producto,
          a.cantidad,
          a.monto_venta,
          a.costo_insumos,
          a.costo_procesos,
          a.total_costo_directo,
          a.comision,
          a.porcentaje_cantidad,
          a.porcentaje_costo,
          a.porcentaje_venta,
          a.porcentaje_promedio,
          a.otros_go,
          a.costo_directo_unitario,
          a.costo_indirecto_unitario,
          a.costo_unitario,
          a.precio_venta,
          a.ganancia,
          a.margen,
          a.precio_consignacion_sf,
          a.precio_ventadirecta_sf,
          a.precio_sugerido_sf
        FROM 
          analisis_costos_detalle a
        INNER JOIN material_apoyo m ON m.codigo_material = a.codigo_producto
        WHERE 
          a.analisis_costo_id = '$idAnalisis'";

$resultado = mysqli_query($enlaceCon, $sql);

// Verificar si hay resultados
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($enlaceCon));
}

// Función para formatear números como moneda
function formatCurrency($value) {
    return 'Bs. ' . number_format($value, 2, '.', ',');
}

// Función para formatear porcentajes
function formatPercentage($value) {
    return number_format($value, 2) . '%';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Análisis de Costos ID: 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .table-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 30px auto 0;
            width: 90%;
        }
        @media (min-width: 1200px) {
            .table-container {
                max-width: 1400px;
            }
        }
        .table-header {
            background-color: #6c757d;
            color: white;
        }
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        .table td {
            vertical-align: middle;
        }
        .positive {
            color: #28a745;
            font-weight: bold;
        }
        .negative {
            color: #dc3545;
            font-weight: bold;
        }
        .highlight {
            background-color: #fff8e1;
        }
        .summary-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #6c757d;
        }
        .badge-custom {
            background-color: #6c757d;
            font-weight: normal;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="bi bi-calculator"></i> Detalle de Análisis de Costos 
                    <span class="badge bg-primary">ID: <?=$idAnalisis;?></span>
                </h2>
                <div>
                    <button class="btn btn-outline-secondary btn-sm me-2">
                        <i class="bi bi-download"></i> Exportar
                    </button>
                </div>
            </div>

            <?php
            // Calcular totales
            $totalVentas = 0;
            $totalGanancia = 0;
            $totalMargen = 0;
            $totalItems = mysqli_num_rows($resultado);
            
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $totalVentas += $fila['monto_venta'];
                $totalGanancia += $fila['ganancia'] * $fila['cantidad'];
                $totalMargen += $fila['margen'];
            }
            
            // Resetear el puntero del resultado para poder volver a recorrerlo
            mysqli_data_seek($resultado, 0);
            ?>

            <div class="summary-card">
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-calendar-date fs-3 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Fecha Inicio Analisis</h6>
                                <h4 class="mb-0"><?php echo ($fechaInicio); ?></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-calendar-date fs-3 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Fecha Final Analisis</h6>
                                <h4 class="mb-0"><?php echo ($fechaFinal); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!--div class="row">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-currency-dollar fs-3 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Ventas Totales</h6>
                                <h4 class="mb-0"><?php echo formatCurrency($totalVentas); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-graph-up fs-3 text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Ganancia Total</h6>
                                <h4 class="mb-0"><?php echo formatCurrency($totalGanancia); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-percent fs-3 text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Margen Promedio</h6>
                                <h4 class="mb-0"><?php echo $totalItems > 0 ? formatPercentage($totalMargen / $totalItems) : '0%'; ?></h4>
                            </div>
                        </div>
                    </div>
                </div-->
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-header">
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Venta</th>
                            <th>Costo Directo</th>
                            <th>Comisión</th>
                            <th>% Prom.</th>
                            <th>Costo Unit.</th>
                            <th>Precio Venta</th>
                            <th>Ganancia</th>
                            <th>Margen</th>
                            <th>Precios</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($fila = mysqli_fetch_assoc($resultado)) {
                            $gananciaClass = $fila['ganancia'] >= 0 ? 'positive' : 'negative';
                            $margenClass = $fila['margen'] >= 0 ? 'positive' : 'negative';
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fila['codigo_producto']); ?></td>
                            <td><?php echo htmlspecialchars($fila['nombre_producto']); ?></td>
                            <td class="text-end"><?php echo $fila['cantidad']; ?></td>
                            <td class="text-end"><?php echo ($fila['monto_venta']); ?></td>
                            <td class="text-end"><?php echo ($fila['total_costo_directo']); ?></td>
                            <td class="text-end"><?php echo ($fila['comision']); ?></td>
                            <td class="text-end"><?php echo formatPercentage($fila['porcentaje_promedio']); ?></td>
                            <td class="text-end"><?php echo ($fila['costo_unitario']); ?></td>
                            <td class="text-end"><?php echo ($fila['precio_venta']); ?></td>
                            <td class="text-end <?php echo $gananciaClass; ?>"><?php echo ($fila['ganancia']); ?></td>
                            <td class="text-end <?php echo $margenClass; ?>"><?php echo formatPercentage($fila['margen']); ?></td>
                            <td>
                                <div class="d-flex flex-column small">
                                    <span><strong>Consig.:</strong> <?php echo ($fila['precio_consignacion_sf']); ?></span>
                                    <span><strong>Venta D.:</strong> <?php echo ($fila['precio_ventadirecta_sf']); ?></span>
                                    <span><strong>Sugerido:</strong> <?php echo ($fila['precio_sugerido_sf']); ?></span>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <div class="text-muted">
                    Mostrando <?php echo $totalItems; ?> registros
                </div>
                <!--div>
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                </div-->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Cerrar la conexión
mysqli_close($enlaceCon);
?>