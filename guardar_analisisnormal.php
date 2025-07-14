<?php
require ('conexionmysqli.php');

// Verificar si hay elementos seleccionados
if(isset($_POST['guardar']) && !empty($_POST['guardar'])) {
    $selectedItems = $_POST['guardar'];
    
    $fechaInicio = $_POST['fecha_inicio'];
    $fechaFinal = $_POST['fecha_final'];

    $sql = "INSERT INTO analisis_costos (fecha_inicio, fecha_final) 
        VALUES ('$fechaInicio', '$fechaFinal')";
    $resp = mysqli_query($enlaceCon, $sql);

    $idAnalisis = mysqli_insert_id($enlaceCon);

    // Recorrer todos los datos enviados
    foreach($_POST['codigo'] as $index => $codigo) {
        // Verificar si este item fue seleccionado
        if(in_array($codigo, $selectedItems)) {
            // Obtener todos los datos de esta fila
            $codProductoFinal = $codigo;
            $nombreItem = $_POST['nombre_producto'][$index];
            $cantidad = $_POST['cantidad'][$index];
            $montoVenta = $_POST['monto_venta'][$index];
            $costoInsumos = $_POST['costo_insumos'][$index];
            $costoProcesos = $_POST['costo_procesos'][$index];
            $totalCostoDirecto = $_POST['total_costo_directo'][$index];
            $comision = $_POST['comision'][$index];
            $porcentajeCantidad = $_POST['porcentaje_cantidad'][$index];
            $porcentajeCosto = $_POST['porcentaje_costo'][$index];
            $porcentajeVenta = $_POST['porcentaje_venta'][$index];
            $porcentajePromedio = $_POST['porcentaje_promedio'][$index];
            $otrosGO = $_POST['otros_go'][$index];
            $costoDirectoUnitario = $_POST['costo_directo_unitario'][$index];
            $costoIndirectoUnitario = $_POST['costo_indirecto_unitario'][$index];
            $costoUnitario = $_POST['costo_unitario'][$index];
            $precioVenta = $_POST['precio_venta'][$index];
            $ganancia = $_POST['ganancia'][$index];
            $margen = $_POST['margen'][$index];
            $precio_consignacion_sf = $_POST['precio-consignacion-sf'][$index];
            $precio_ventadirecta_sf = $_POST['precio-ventadirecta-sf'][$index];
            $precio_sugerido_sf = $_POST['precio-sugerido-sf'][$index];
            
            
            // Aquí puedes procesar los datos o guardarlos en la base de datos
            // Por ejemplo:
            $sql = "INSERT INTO `analisis_costos_detalle` (
                  `analisis_costo_id`, `codigo_producto`, `nombre_producto`, `cantidad`,
                  `monto_venta`, `costo_insumos`, `costo_procesos`, `total_costo_directo`,
                  `comision`, `porcentaje_cantidad`, `porcentaje_costo`, `porcentaje_venta`,
                  `porcentaje_promedio`, `otros_go`, `costo_directo_unitario`, `costo_indirecto_unitario`,
                  `costo_unitario`, `precio_venta`, `ganancia`, `margen`, `precio_consignacion_sf`, `precio_ventadirecta_sf`, `precio_sugerido_sf`) 
                VALUES (
                '$idAnalisis', '$codProductoFinal', '$nombreItem', '$cantidad', '$montoVenta', 
                '$costoInsumos', '$costoProcesos', '$totalCostoDirecto', 
                '$comision', '$porcentajeCantidad', '$porcentajeCosto', 
                '$porcentajeVenta', '$porcentajePromedio', '$otrosGO', 
                '$costoDirectoUnitario', '$costoIndirectoUnitario', 
                '$costoUnitario', '$precioVenta', '$ganancia', '$margen', 
                '$precio_consignacion_sf', '$precio_ventadirecta_sf', '$precio_sugerido_sf'
            )";
            
            $resp = mysqli_query($enlaceCon, $sql);
        }
    }
    echo "Los Datos se guardaron correctamente.";
} else {
    echo "No se seleccionaron items para guardar.";
}
?>