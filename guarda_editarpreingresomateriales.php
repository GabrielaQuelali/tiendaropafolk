<?php

require("conexionmysqli.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");


$codIngreso=$_POST["codIngreso"];
$tipo_ingreso=$_POST['tipo_ingreso'];
$nota_entrega=$_POST['nota_entrega'];
$nro_factura=$_POST['nro_factura'];
$observaciones=$_POST['observaciones'];
$codSalida=$_POST['codSalida'];
$fecha_real=date("Y-m-d");


//$consulta="insert into ingreso_almacenes values($codigo,$global_almacen,$tipo_ingreso,'$fecha_real','$hora_sistema','$observaciones',0,'$nota_entrega','$nro_correlativo',0,0,0,$nro_factura)";
$consulta="update preingreso_almacenes set cod_tipoingreso='$tipo_ingreso', nro_factura_proveedor='$nro_factura', 
		observaciones='$observaciones' where cod_ingreso_almacen='$codIngreso'";
$sql_inserta = mysqli_query($enlaceCon,$consulta);

//echo "aaaa:$consulta";

$sqlDel="delete from preingreso_detalle_almacenes where cod_ingreso_almacen=$codIngreso";
$respDel=mysqli_query($enlaceCon,$sqlDel);

for ($i = 1; $i <= $cantidad_material; $i++) {
	$cod_material = $_POST["material$i"];
    if($cod_material!=0){
		$cantidad=$_POST["cantidad_unitaria$i"];
		$lote=$_POST["lote$i"];
		//$fechaVenc=$_POST["fechaVenc$i"];
		$precioBruto=$_POST["precio$i"];
		$precioVenta=$_POST["precioVenta$i"];
		
		$fechaVenc=UltimoDiaMes($fechaVenc);
		
		$consulta="insert into preingreso_detalle_almacenes (cod_ingreso_almacen, cod_material, cantidad_unitaria, cantidad_restante, lote, precio_bruto, costo_almacen, costo_actualizado, costo_actualizado_final, 
		costo_promedio, precio_neto, precio_venta) 
		values($codIngreso,'$cod_material',$cantidad,$cantidad,'$lote','$precioBruto','$precioBruto','$precioBruto','$precioBruto','$precioBruto','$precioBruto','$precioVenta')";
		
		//echo "bbb:$consulta";
		$sql_inserta2 = mysqli_query($enlaceCon,$consulta);
	}

}
$modifiedBy=$_COOKIE['global_usuario'];
$modifiedDate=date("Y-m-d H:i:s");

$consulta=" update preingreso_almacenes set modified_by='".$modifiedBy."', modified_date='".$modifiedDate."' where cod_ingreso_almacen=".$codIngreso;
//echo $consulta;
mysqli_query($enlaceCon,$consulta);

	echo "<script language='Javascript'>
			Swal.fire('Los datos fueron modificados correctamente.')
		    .then(() => {
				 location.href='navegador_preingreso.php';
		    });
		</script>";
		
/*echo "<script language='Javascript'>
    alert('Los datos fueron modificados correctamente.');
    location.href='navegador_preingreso.php';
    </script>";*/

?>