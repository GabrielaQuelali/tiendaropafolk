<html>
<body>
<table align='center' class="texto">
<tr>
<th>&nbsp;</th><th>Nro. Doc</th><th>Fecha/hora<br>Registro Salida</th><th>FormaPago</th><th>Razon Social</th><th>NIT</th><th>Monto Final</th><th>Vendedor</th></tr>
<?php

require("conexionmysqli.inc");
require("funciones.php");
require('function_formatofecha.php');
require("estilos_almacenes.inc");

$fechaIniBusqueda2=$_GET['fechaIniBusqueda2'];
//echo "fechaIniBusqueda2=".$fechaIniBusqueda2;
$fechaFinBusqueda2=$_GET['fechaFinBusqueda2'];
//echo "fechaFinBusqueda2=".$fechaFinBusqueda2;
$nroCorrelativoBusqueda=$_GET['nroCorrelativoBusqueda'];
//echo "nroCorrelativoBusqueda=".$nroCorrelativoBusqueda;
$vendedorBusqueda=$_GET['vendedorBusqueda'];
//echo "vendedorBusqueda=".$vendedorBusqueda;
$tipoPagoBusqueda=$_GET['tipoPagoBusqueda'];
$recibo=$_GET['recibo'];
//echo "tipoPagoBusqueda=".$tipoPagoBusqueda;
if(!empty($fechaIniBusqueda2) && !empty($fechaFinBusqueda2) ){
	$fechaIniBusqueda2=formateaFechaVista($fechaIniBusqueda2);
	$fechaFinBusqueda2=formateaFechaVista($fechaFinBusqueda2);
}

$globalAlmacen=$_COOKIE['global_almacen'];
$globalAgencia=$_COOKIE['global_agencia'];
$global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];

$consulta = "
    SELECT s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, 
    (select a.nombre_almacen from almacenes a where a.`cod_almacen`=s.almacen_destino), s.observaciones, 
    s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino, 
    (select c.nombre_cliente from clientes c where c.cod_cliente = s.cod_cliente), s.cod_tipo_doc, razon_social, nit,
    (select t.nombre_tipopago from tipos_pago t where t.cod_tipopago=s.cod_tipopago)as tipopago,siat_estado_facturacion,s.monto_total,s.monto_final,
	s.cod_chofer
    FROM salida_almacenes s, tipos_salida ts 
    WHERE s.cod_tiposalida = ts.cod_tiposalida AND s.cod_almacen = '".$globalAlmacen."' and s.cod_tiposalida=1001 
    and salida_anulada=0 and s.cod_tipo_doc in (1,2,4)";


if(!empty($nroCorrelativoBusqueda)){
		$consulta = $consulta." and s.nro_correlativo='".$nroCorrelativoBusqueda."' ";
 }
if(!empty($fechaIniBusqueda2) && !empty($fechaFinBusqueda2) ){
		$consulta = $consulta." and '$fechaIniBusqueda2'<=s.fecha AND s.fecha<='$fechaFinBusqueda2' ";
}

if(!empty($vendedorBusqueda)){
	$consulta=$consulta." and s.cod_chofer = '".$vendedorBusqueda."' ";
} 
if(!empty($tipoPagoBusqueda)){
	$consulta=$consulta." and s.cod_tipopago = '".$tipoPagoBusqueda."' ";
} 
  
$consulta = $consulta."ORDER BY s.fecha desc, s.hora_salida desc limit 0, 70 ";

//echo $consulta;
//
$resp = mysqli_query($enlaceCon,$consulta);
while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_salida = $dat[1];
    $fecha_salida_mostrar = "$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
    $hora_salida = $dat[2];
    $nombre_tiposalida = $dat[3];
    $nombre_almacen = $dat[4];
    $obs_salida = $dat[5];
    $estado_almacen = $dat[6];
    $nro_correlativo = $dat[7];
    $salida_anulada = $dat[8];
    $cod_almacen_destino = $dat[9];
    $nombreCliente=$dat[10];
    $codTipoDoc=$dat[11];
    $nombreTipoDoc=nombreTipoDoc($enlaceCon,$codTipoDoc);
    $razonSocial=$dat[12];
    $razonSocial=strtoupper($razonSocial);
    $nitCli=$dat[13];
    $tipoPago=$dat[14];
	$siat_estado_facturacion=$dat[15];
	$montoTotal=$dat[16];
	$montoFinal=$dat[17];
	$codChofer=$dat[18];
	$sqlVendedor="SELECT concat(paterno,' ',materno,' ',nombres) as personal from  funcionarios  where codigo_funcionario=".$codChofer;
	
    $respVendedor=mysqli_query($enlaceCon,$sqlVendedor);
     while($datVendedor=mysqli_fetch_array($respVendedor)){
			$nombreVendedor=$datVendedor[0];
	 }
	?>
	<tr>
    <td>&nbsp;</td>
   <td>
   <div class="texto"><a href="javascript:setVenta('<?=$recibo;?>','<?=$codigo;?>','<?=$nombreTipoDoc;?>','<?=$nro_correlativo;?>','<?=$fecha_salida_mostrar." ".$hora_salida;?>','<?=$montoFinal;?>','<?=$nombreVendedor;?>')"><?=$nombreTipoDoc."-".$nro_correlativo;?></a></div></td>
    <td><?=$fecha_salida_mostrar."-".$hora_salida;?></td>  
   <td><?=$tipoPago;?></td>
   <td><?=$razonSocial;?></td>
   <td><?=$nitCli;?></td>
   <td><?=$montoFinal;?></td>
   <td><?=$nombreVendedor;?></td>
   </tr>
	<?php
}
    


?>
</table>

</body>
</html>