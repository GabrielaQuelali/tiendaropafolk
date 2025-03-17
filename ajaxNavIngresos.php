<?php
require("conexionmysqli.php");
require("funciones.php");
require('function_formatofecha.php');
require("estilos_almacenes.inc");

$fechaIniBusqueda=$_GET['fechaIniBusqueda'];
$fechaFinBusqueda=$_GET['fechaFinBusqueda'];
$notaIngreso=$_GET['notaIngreso'];
$global_almacen=$_GET['global_almacen'];
$provBusqueda=$_GET['provBusqueda'];
$tipo=$_GET['tipo'];
$estado=$_GET['estado'];


if(!empty($fechaIniBusqueda) && !empty($fechaFinBusqueda) ){
$fechaIniBusqueda=formateaFechaVista($fechaIniBusqueda);
$fechaFinBusqueda=formateaFechaVista($fechaFinBusqueda);
}
echo "<br/><center><table class='texto' width='100%'>";
echo "<tr><th>&nbsp;</th><th>Nro. Ingreso</th><th>Factura o Nota de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th>
<th>Proveedor</th>
<th>Observaciones</th>
<th>Registro</th>
<th>Ult. Edicion</th>
<th>&nbsp;</th><th>&nbsp;</th><th>Nro PreIngreso</th></tr>";
	
$consulta = "SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso,
 i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado,p.nombre_proveedor,
	i.nro_factura_proveedor, i.created_by,i.created_date, i.modified_by, i.modified_date 
	FROM ingreso_almacenes i
	left join tipos_ingreso ti  on (i.cod_tipoingreso=ti.cod_tipoingreso )
	left join proveedores p  on (i.cod_proveedor=p.cod_proveedor)	 
	WHERE i.cod_tipo=".$tipo;
if($estado<>'-1'){
 $consulta =$consulta." and i.ingreso_anulado='".$estado."'";

}
$consulta =$consulta." AND i.cod_almacen='".$global_almacen."' ";

if($notaIngreso!="")
   {$consulta = $consulta." AND i.nro_correlativo='$notaIngreso' ";
   }
if(!empty($fechaIniBusqueda) && !empty($fechaFinBusqueda) ){
	$consulta = $consulta." AND '$fechaIniBusqueda'<=i.fecha AND i.fecha<='$fechaFinBusqueda' ";
   }
if($provBusqueda!=0){
	$consulta=$consulta." and i.cod_proveedor='$provBusqueda' ";
}   
$consulta = $consulta." order by i.nro_correlativo DESC limit 0, 70";
//echo $consulta;

$resp = mysqli_query($enlaceCon,$consulta);
	
while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_ingreso = $dat[1];
    $fecha_ingreso_mostrar = "$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
    $hora_ingreso = $dat[2];
    $nombre_tipoingreso = $dat[3];
    $obs_ingreso = $dat[4];
    $nota_entrega = $dat[5];
    $nro_correlativo = $dat[6];
    $anulado = $dat['ingreso_anulado'];
	$proveedor=$dat[8];
	$nroFacturaProveedor=$dat[9];
	
		$created_by=$dat[10];
	$sqlRegUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$created_by;
	$respRegUsu=mysqli_query($enlaceCon,$sqlRegUsu);
	$usuReg =" ";
	while($datRegUsu=mysqli_fetch_array($respRegUsu)){
		$usuReg =$datRegUsu['nombres'][0].$datRegUsu['paterno'];		
	}
	$created_date=$dat[11];
	$modified_by=$dat[12];
	$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
	$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
	$usuMod ="";
	while($datModUsu=mysqli_fetch_array($respModUsu)){
		$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
	}
	$modified_date=$dat[13];
	
	$sqlAux=" select IFNULL(codigo_ingreso,0),nro_correlativo from  preingreso_almacenes  where codigo_ingreso=$codigo";
	$respAux= mysqli_query($enlaceCon,$sqlAux);
	$datAux=mysqli_fetch_array($respAux);
	$num_filas_preingreso = mysqli_num_rows($respAux);


    echo "<input type='hidden' name='fecha_ingreso' value='$fecha_ingreso_mostrar'>";
    $sql_verifica_movimiento = "select * from salida_almacenes s, salida_detalle_almacenes sd, ingreso_almacenes i
		where s.cod_salida_almacenes=sd.cod_salida_almacen  and sd.cod_ingreso_almacen=i.cod_ingreso_almacen and s.salida_anulada=0 and i.cod_ingreso_almacen='$codigo'";
	//echo $sql_verifica_movimiento;
    $resp_verifica_movimiento = mysqli_query($enlaceCon,$sql_verifica_movimiento);
    $num_filas_movimiento = mysqli_num_rows($resp_verifica_movimiento);
    if ($num_filas_movimiento > 0) {
        $color_fondo = "#ffff99";
        $chkbox = "";
    }
    if ($anulado == 2) {
        $color_fondo = "#ff8080";
        $chkbox = "";
    }
    if ($num_filas_movimiento == 0 and $anulado == 1) {
        $color_fondo = "";
        $chkbox = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
    echo "<tr bgcolor='$color_fondo'><td align='center'>$chkbox</td><td align='center'>$nro_correlativo</td><td align='center'>$nroFacturaProveedor</td>
	<td align='center'>$fecha_ingreso_mostrar $hora_ingreso</td><td>$nombre_tipoingreso</td>
	<td>&nbsp;$proveedor</td>
	<td>&nbsp;$obs_ingreso</td>
	<td>&nbsp;$usuReg<br>$created_date</td>";
	if(empty($usuMod)){
	echo "<td>&nbsp</td>";
	}else{
		echo "<td>&nbsp;$usuMod<br>$modified_date</td>";
    }				
	 
	 echo "	<td align='center'>
		<a target='_BLANK' href='navegador_detalleingresomateriales.php?codigo_ingreso=$codigo'><img src='imagenes/detalles.png' border='0' width='30' heigth='30' title='Ver Detalles del Ingreso'></a>
	</td>
	<td align='center'>
		<a href='#' onclick='javascript:editarIngresoTipoProv($codigo)' > 
			<img src='imagenes/edit.png' border='0' width='30' heigth='30' title='Editar Tipo & Proveedor'>
		</a>
	</td>";
	if($num_filas_preingreso>0){
		echo"<td align='center'>$datAux[1]</td>";
	}else{
		echo"<td align='center'>&nbsp;</td>";
	}
	echo"</tr>";
}
echo "</table></center><br>";


?>
