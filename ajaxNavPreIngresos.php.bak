<?php
require("conexionmysqli.php");
require("funciones.php");
require('function_formatofecha.php');
require("estilos_almacenes.inc");

$fechaIniBusqueda=$_GET['fechaIniBusqueda'];
$fechaFinBusqueda=$_GET['fechaFinBusqueda'];

$global_almacen=$_GET['global_almacen'];
$provBusqueda=$_GET['provBusqueda'];

$fechaIniBusqueda=formateaFechaVista($fechaIniBusqueda);
$fechaFinBusqueda=formateaFechaVista($fechaFinBusqueda);
 $global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];

echo "<br/><center><table class='texto' width='100%'>";
echo "<tr><th>&nbsp;</th><th>Nro. Pre Ingreso</th><th>Nro.Factura</th><th>Fecha</th><th>Tipo de Ingreso</th>
<th>Proveedor</th>
<th>Observaciones</th><th>Registro</th>
<th>Ult. Edicion</th>
<th>&nbsp;</th><th>&nbsp;</th><th>Nro. Ingreso</th></tr>";
$sqlFuncProv="select * from funcionarios_proveedores where codigo_funcionario=$global_usuario";
$respFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);
$cantFuncProv=mysqli_num_rows($respFuncProv);

$consulta = "
    SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado,
	(select p.nombre_proveedor from proveedores p where p.cod_proveedor=i.cod_proveedor) as proveedor, i.nro_factura_proveedor,
	i.created_by,i.created_date, i.modified_by, i.modified_date
    FROM preingreso_almacenes i, tipos_ingreso ti
	
    WHERE i.cod_tipoingreso=ti.cod_tipoingreso";
	if($globalTipoFuncionario==2){
		if($cantFuncProv>0){
	$consulta= $consulta." and i.cod_proveedor in( select cod_proveedor from funcionarios_proveedores where codigo_funcionario=$global_usuario)";
	}
	}
    $consulta= $consulta." AND i.cod_almacen='$global_almacen'";

if($fechaIniBusqueda!="--" && $fechaFinBusqueda!="--")
   {$consulta = $consulta."AND '$fechaIniBusqueda'<=i.fecha AND i.fecha<='$fechaFinBusqueda' ";
   }
if($provBusqueda!=0){
	$consulta=$consulta." and cod_proveedor='$provBusqueda' ";
}   
$consulta = $consulta."ORDER BY i.nro_correlativo DESC";

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
    $anulado = $dat[7];
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
	

        echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";

    if ($anulado == 1) {
        $color_fondo = "#ff8080";
        $chkbox = "";
    }
    if ( $anulado == 0) {
        $color_fondo = "";
        $chkbox = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
   $sqlAux=" select IFNULL(codigo_ingreso,0) from  preingreso_almacenes  where cod_ingreso_almacen=$codigo";
	$respAux= mysqli_query($enlaceCon,$sqlAux);
	$datAux=mysqli_fetch_array($respAux);
	if($datAux[0]<>0){
		$chkbox = "";
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
	 echo "
	<td align='center'><a target='_BLANK' href='navegador_predetalleingresomateriales.php?codigo_ingreso=$codigo'>
	<img src='imagenes/detalles.png' border='0' width='30' heigth='30' title='Ver Detalles del Ingreso'></a>
	</td>";
	if($anulado==0){
	echo "<td align='center'>
		<a href='#' onclick='javascript:editarPreIngresoTipoProv($codigo)' > 
		<img src='imagenes/edit.png' border='0' width='30' heigth='30' title='Editar Tipo & Proveedor'>
		</a>
	</td>";
	}else{
		echo "<td align='center'>

	</td>";
	}	

	if($datAux[0]==0){
		if($globalTipoFuncionario==1){
			if($anulado==0){
				echo "<td align='center'>
				<a href='#' onclick='javascript:ingresarProductosAlmacen($codigo,$nro_correlativo);' > 
				<img src='imagenes/ingreso2.png' border='0' width='40' heigth='40' title='Ingresar Productos a Almacen'>
				</a>
				</td>";
			}else{ 
				echo "<td align='center'></td>";
			}
		}else{ 
			echo "<td align='center'></td>";
		} 	
	}else{ 
		$sqlAux2=" select i.nro_correlativo,i.cod_almacen , a.nombre_almacen
		from  ingreso_almacenes i inner join almacenes a on( i.cod_almacen =a.cod_almacen )
		where i.cod_ingreso_almacen=".$datAux[0];
		//echo  $sqlAux2;
	$respAux2= mysqli_query($enlaceCon,$sqlAux2);
	$datAux2=mysqli_fetch_array($respAux2);
			echo "<td align='center'>$datAux2[0]<br/>$datAux2[2]</td>";
	} 
	echo "</tr>";
}

echo "</table></center><br>";


?>
