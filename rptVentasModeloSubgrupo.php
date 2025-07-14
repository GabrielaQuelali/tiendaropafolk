<?php


require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli.inc');
require('funcion_nombres.php');
require('funciones.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$globalAlmacen=$_COOKIE['global_almacen'];

$sqlFuncProv="select * from funcionarios_proveedores where codigo_funcionario=$global_usuario";
$respFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);
$cantFuncProv=mysqli_num_rows($respFuncProv);
$global_agencia=$_COOKIE['global_agencia'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=($fecha_ini);
$fecha_finconsulta=($fecha_fin);

$rptTerritorio=$_GET['rpt_territorio'];
//echo "rpt_territorio=".$rptTerritorio;
$rptMarca=$_GET["rpt_marca"];
$rptTipoPago=$_GET["rpt_tipoPago"];

$cadenaTerritorio="TODOS";	
if($rptTerritorio=="-1"){
	$cadenaTerritorio="TODOS";
	$rptTerritorio=""; $swTerritorio=0;	 
	$sqlTerritorio="select cod_ciudad, descripcion from ciudades order by descripcion asc";
	$respTerritorio=mysqli_query($enlaceCon,$sqlTerritorio);
	while($datTerritorio=mysqli_fetch_array($respTerritorio))
	{	$codTerritorio=$datTerritorio[0];
		if($swTerritorio==0){
			$rptTerritorio=$datTerritorio[0];
			$swTerritorio=1;
		}else{
			$rptTerritorio=$rptTerritorio.",";
			$rptTerritorio=$rptTerritorio.$datTerritorio[0];
		}
	}
	//echo "rptTerritorio".$rptTerritorio."<br>";;
}else{
	$swCadenaTerritorio=0;	
	$sqlTerritorio="select cod_ciudad, descripcion from ciudades where cod_ciudad in(".$rptTerritorio.")	order by descripcion asc";
	//echo $sqlTerritorio;
	$respTerritorio=mysqli_query($enlaceCon,$sqlTerritorio);
	while($datTerritorio=mysqli_fetch_array($respTerritorio)){	
		if($swCadenaTerritorio==0){
			$cadenaTerritorio=$datTerritorio[1];
			$swCadenaTerritorio=1;
		}else{
			$cadenaTerritorio=$cadenaTerritorio.";";
			$cadenaTerritorio=$cadenaTerritorio.$datTerritorio[1];
		}
		
	}

	
}
//echo "holaaaaaaa";
$global_agencia=$rptTerritorio;


$fecha_reporte=date("d/m/Y");
?>

<table align='center'  >
<tr class='textotit' align='center' ><th  colspan='2'  >REPORTE DE VENTAS x MODELO Y SUBGRUPO</th></tr>
	<tr ><th>Territorio:</th><td><?=$cadenaTerritorio;?></td> </tr>
	<tr><th>De:</th> <td> <?=$fecha_ini;?> A:<?=$fecha_fin;?></td></tr>
	<tr><th>Fecha Reporte:</th> <td><?=$fecha_reporte?></td></tr>	
	</table>
<?php 

$sql="select ma.cod_modelo, sum(sda.cantidad_unitaria) cant ,sum(sda.monto_unitario-sda.descuento_unitario) monto
from salida_almacenes sa 
left join salida_detalle_almacenes sda on (sa.cod_salida_almacenes=sda.cod_salida_almacen)
inner join material_apoyo ma on (sda.cod_material=ma.codigo_material)
where sa.cod_tiposalida=1001
and sa.fecha BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."' 
and sa.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad in (".$rptTerritorio."))
and sa.salida_anulada=1
group by ma.cod_modelo
order by cant desc";

$resp=mysqli_query($enlaceCon,$sql);
?>
<br>
<table align='center' width='70%' border="1" style="font-size:9" >
<tr>
<th>Modelo</th>
<?php
	$sqlSubGrupo="select ma.cod_subgrupo,sub.nombre,sub.abreviatura,  
		sum(sda.cantidad_unitaria) cant ,sum(sda.monto_unitario-sda.descuento_unitario) monto 
		from salida_almacenes sa 
		left join salida_detalle_almacenes sda on (sa.cod_salida_almacenes=sda.cod_salida_almacen) 
		inner join material_apoyo ma on (sda.cod_material=ma.codigo_material) 
		inner join subgrupos sub on (ma.cod_subgrupo=sub.codigo)
		where sa.cod_tiposalida=1001 and sa.fecha BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'
		and sa.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad in (".$rptTerritorio.")) 
		and sa.salida_anulada=1 
		group by ma.cod_subgrupo,sub.nombre,sub.abreviatura
		order by cant desc";


		$respSubGrupo=mysqli_query($enlaceCon,$sqlSubGrupo);
		while($datosSubGrupo=mysqli_fetch_array($respSubGrupo)){
			$codSubGrupo=$datosSubGrupo[0];
			$nombreSubGrupo=$datosSubGrupo[1];
			$abrevSubGrupo=$datosSubGrupo[2];
		?>
			<td colspan="2" align="center"><?=$nombreSubGrupo;?>/ Bs</td>

	<?php 

		}
	?>
<th>Total<br>Prendas</th>
<th>Total<br>Monto Bs</th>
</tr>
<?php
while($datos=mysqli_fetch_array($resp)){	
	$codModelo=$datos[0];
	$cantVendidosModelo=$datos[1];
	$montoTotalVendidosModelo=$datos[2];

	$sqlModelos="select nombre,abreviatura from modelos where codigo=".$codModelo;
	$respModelos=mysqli_query($enlaceCon,$sqlModelos);
	while($datosModelos=mysqli_fetch_array($respModelos)){
		$nombreModelo=$datosModelos[0];
		$abrevModelo=$datosModelos[1];
	}

?>
<tr >
	<td><?=$abrevModelo." (".$nombreModelo.")";?></td>
<?php

	$sqlSubGrupo="select ma.cod_subgrupo,sub.nombre,sub.abreviatura,  
		sum(sda.cantidad_unitaria) cant ,sum(sda.monto_unitario-sda.descuento_unitario) monto 
		from salida_almacenes sa 
		left join salida_detalle_almacenes sda on (sa.cod_salida_almacenes=sda.cod_salida_almacen) 
		inner join material_apoyo ma on (sda.cod_material=ma.codigo_material) 
		inner join subgrupos sub on (ma.cod_subgrupo=sub.codigo)
		where sa.cod_tiposalida=1001 and sa.fecha BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'
		and sa.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad in (".$rptTerritorio.")) 
		and sa.salida_anulada=1 
		group by ma.cod_subgrupo,sub.nombre,sub.abreviatura
		order by cant desc";


		$respSubGrupo=mysqli_query($enlaceCon,$sqlSubGrupo);
		while($datosSubGrupo=mysqli_fetch_array($respSubGrupo)){
		
			$codSubGrupo=$datosSubGrupo[0];


		$sqlMS="select sum(sda.cantidad_unitaria) cant ,sum(sda.monto_unitario-sda.descuento_unitario) monto
		from salida_almacenes sa 
		left join salida_detalle_almacenes sda on (sa.cod_salida_almacenes=sda.cod_salida_almacen)
		inner join material_apoyo ma on (sda.cod_material=ma.codigo_material)
		where sa.cod_tiposalida=1001
		and ma.cod_subgrupo=".$codSubGrupo."
		and ma.cod_modelo=".$codModelo."
		and sa.fecha BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'
		and sa.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad in (".$rptTerritorio."))
		and sa.salida_anulada=1";
		$respMS=mysqli_query($enlaceCon,$sqlMS);
		while($datosMS=mysqli_fetch_array($respMS)){
			$cantMS=$datosMS[0];
			$montoMS=$datosMS[1];
?>
		<td align="right" ><strong><?=redondear2($cantMS);?></strong></td>
	<td align="right"><?=redondear2($montoMS);?></td>

<?php
		}
	}
?>

	<td align="right"><?=redondear2($cantVendidosModelo);?></td>
	<td align="right"><?=redondear2($montoTotalVendidosModelo);?></td>
	

<tr/>
<?php
			}
	
?>
<tr>
	<td align="right">TOTALES</td>
<?php
	$totalCant=0;
	$totalMonto=0;

	$sqlTotSub="select ma.cod_subgrupo, sum(sda.cantidad_unitaria) cant ,sum(sda.monto_unitario-sda.descuento_unitario) monto
	from salida_almacenes sa 
	left join salida_detalle_almacenes sda on (sa.cod_salida_almacenes=sda.cod_salida_almacen)
	inner join material_apoyo ma on (sda.cod_material=ma.codigo_material)
	inner join modelos mo on (ma.cod_modelo=mo.codigo)
	where sa.cod_tiposalida=1001
	and sa.fecha BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'
	and sa.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad in (".$rptTerritorio."))
	and sa.salida_anulada=1
	group by ma.cod_subgrupo
	order by cant desc";
	$respTotSub=mysqli_query($enlaceCon,$sqlTotSub);
	while($datosTotSub=mysqli_fetch_array($respTotSub)){
			$cantTotSub=$datosTotSub[1];
			$montoTotSub=$datosTotSub[2];
			$totalCant=$totalCant+$cantTotSub;
			$totalMonto=$totalMonto+$montoTotSub;


?>
	<td align="right"><strong><?=redondear2($cantTotSub);?></strong></td>
	<td align="right"><?=redondear2($montoTotSub);?></td>
<?php
	}

?>

<td align="right"><strong><?=redondear2($totalCant);?></strong></td>
	<td align="right"><strong><?=redondear2($totalMonto);?></strong></td>
</tr>

<table/>
<br/>
<?php 

//include("imprimirInc.php");
?>