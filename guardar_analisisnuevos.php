<?php
require ('conexionmysqli.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

if (isset($_POST['guardar']) && !empty($_POST['guardar'])) {
    $selectedItems = $_POST['guardar'];
    $fechaProceso = date("Y-m-d");
    $glosa = $_POST['glosa'];
    
    // Leer si hay un código de análisis ya existente (para actualizar)
    $idAnalisis = isset($_POST['codigoAnalisis']) ? $_POST['codigoAnalisis'] : 0;

    if ($idAnalisis > 0) {
        // Ya existe, entonces actualizamos cabecera y eliminamos detalle anterior
        $sqlCab = "UPDATE analisis_costos_nuevos 
                    SET fecha_proceso='$fechaProceso', 
                    glosa='$glosa',
                    estado = 1 
                    WHERE id = '$idAnalisis'";
        mysqli_query($enlaceCon, $sqlCab);

        // Eliminar detalle anterior
        $sqlDel = "DELETE FROM analisis_costos_nuevos_detalle WHERE analisis_costo_id = '$idAnalisis'";
        mysqli_query($enlaceCon, $sqlDel);
    } else {
        // No hay código, se inserta uno nuevo
        $sqlCab = "INSERT INTO analisis_costos_nuevos (fecha_proceso, glosa, estado) 
                   VALUES ('$fechaProceso', '$glosa', 1)";
        mysqli_query($enlaceCon, $sqlCab);
        $idAnalisis = mysqli_insert_id($enlaceCon);
    }

    // Recorrer todos los datos enviados
    foreach($_POST['codigo'] as $index => $codigo) {
        if (in_array($codigo, $selectedItems)) {
            $codigoProducto = $codigo;
            $nombreItem = $_POST['nombre_producto'][$index];
            $costoInsumos = $_POST['costo_insumos'][$index];
            $costoProcesos = $_POST['costo_procesos'][$index];
            $totalCostoDirecto = $_POST['total_costo_directo'][$index];

            $cantidadProducir = $_POST['cantidad-producir'][$index];
            $horasProduccion = $_POST['horas-produccion'][$index];

            $costo_indirecto_distribuido = $_POST['costo_indirecto_distribuido'][$index];
            $costo_indirecto_unitario = $_POST['costo_indirecto_unitario'][$index];
            $costo_total_unitario = $_POST['costo_total_unitario'][$index];

            $porcentajeCostoUnitario = $_POST['porcentaje-costo-unitario'][$index];

            $precio_consignacion_sf = $_POST['precio-consignacion-sf'][$index];
            $precio_ventadirecta_sf = $_POST['precio-ventadirecta-sf'][$index];
            $precio_sugerido_sf = $_POST['precio-sugerido-sf'][$index];

            $sql = "INSERT INTO analisis_costos_nuevos_detalle
                (analisis_costo_id, codigo_producto, nombre_producto, costo_insumos,
                 costo_procesos, total_costo_directo, cantidad_producir, horas_producir,
                 porcentaje_costodirecto, porcentaje_cantidadproducir, porcentaje_costoindirecto,
                 costo_indirectounitario, costo_unitariototal,
                 precio_consignacion_sf, precio_ventadirecta_sf, precio_sugerido_sf)
                VALUES
                ('$idAnalisis', '$codigoProducto', '$nombreItem', '$costoInsumos',
                 '$costoProcesos', '$totalCostoDirecto', '$cantidadProducir', '$horasProduccion',
                 '$porcentajeCostoUnitario', '0', '$costo_indirecto_distribuido',
                 '$costo_indirecto_unitario', '$costo_total_unitario',
                 '$precio_consignacion_sf', '$precio_ventadirecta_sf', '$precio_sugerido_sf')";
            mysqli_query($enlaceCon, $sql);
        }
    }
    ob_clean();
    echo "<script>
        alert('Los datos se actualizaron correctamente');
        location.href='navegador_analisiscostosnuevo.php';
    </script>";
} else {
    ob_clean();
    echo "No se seleccionaron items para guardar.";
}
?>
