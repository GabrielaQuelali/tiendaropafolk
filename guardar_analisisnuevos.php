<?php
require ('conexionmysqli.php');


 error_reporting(E_ALL);
 ini_set('display_errors', '1');

// Verificar si hay elementos seleccionados
if(isset($_POST['guardar']) && !empty($_POST['guardar'])) {
    $selectedItems = $_POST['guardar'];
    
    $fechaProceso = date("Y-m-d");

    $glosa = $_POST['glosa'];


    $sqlCab = "INSERT INTO analisis_costos_nuevos (fecha_proceso,glosa) 
        VALUES ('$fechaProceso','$glosa')";
    $respCab = mysqli_query($enlaceCon, $sqlCab);
    $idAnalisis = mysqli_insert_id($enlaceCon);

    // Recorrer todos los datos enviados
    foreach($_POST['codigo'] as $index => $codigo) {
        // Verificar si este item fue seleccionado
        if(in_array($codigo, $selectedItems)) {
            // Obtener todos los datos de esta fila
            $codProductoFinal = $codigo;
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
            
            
            // Aquí puedes procesar los datos o guardarlos en la base de datos
            // Por ejemplo:
            $sql = "INSERT INTO `analisis_costos_nuevos_detalle`
                    (`analisis_costo_id`, `codigo_producto`, `nombre_producto`, `costo_insumos`,
                     `costo_procesos`, `total_costo_directo`, `cantidad_producir`, `horas_producir`, 
                    `porcentaje_costodirecto`, `porcentaje_cantidadproducir`, `porcentaje_costoindirecto`, `costo_indirectounitario`, 
                    `costo_unitariototal`, `precio_consignacion_sf`, `precio_ventadirecta_sf`, `precio_sugerido_sf`) 
                    VALUES ('$idAnalisis', '$codProductoFinal', '$nombreItem', '$costoInsumos', 
                        '$costoProcesos', '$totalCostoDirecto', '$cantidadProducir', '$horasProduccion', 
                        '$porcentajeCostoUnitario', '0', '$costo_indirecto_distribuido', '$costo_indirecto_unitario',
                        '$costo_total_unitario', '$precio_consignacion_sf', '$precio_ventadirecta_sf', '$precio_sugerido_sf')";
            echo $sql."<br>";
            $resp = mysqli_query($enlaceCon, $sql);
        }
    }
    //echo "normal fin guardado";
    echo "<script>
        alert('Los Datos se guardaron correctamente');
        location.href='navegador_analisiscostosnuevo.php';
    </script>";
} else {
    echo "No se seleccionaron items para guardar.";
}
?>