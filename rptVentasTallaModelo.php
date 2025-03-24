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
<tr class='textotit' align='center' ><th  colspan='2'  >REPORTE x TALLAS Y MODELOS</th></tr>
	<tr ><th>Territorio:</th><td><?=$cadenaTerritorio;?></td> </tr>
	<tr><th>De:</th> <td> <?=$fecha_ini;?> A:<?=$fecha_fin;?></td></tr>
	<tr><th>Fecha Reporte:</th> <td><?=$fecha_reporte?></td></tr>	
	</table>
<?php 

$sql="select mat.cod_modelo, sum(cant) vend
from material_apoyo mat 
inner join
(select sda.cod_material,sum(sda.cantidad_unitaria) cant
from salida_almacenes sa 
left join salida_detalle_almacenes sda on (sa.cod_salida_almacenes=sda.cod_salida_almacen)
where sa.cod_tiposalida=1001
and sa.fecha BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."' 
and sa.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad in (".$rptTerritorio."))
and sa.salida_anulada=1
group by sda.cod_material
order by cant desc) pv on (mat.codigo_material= pv.cod_material)
group by mat.cod_modelo
order by vend desc ";
//echo $sql;
$resp=mysqli_query($enlaceCon,$sql);
?>
<br><table align='center' width='70%' border="1">
<tr>
<th>Modelo</th>
<?php
	$sqlTallas=" select codigo,abreviatura from tallas where estado=1 order by orden asc";
	$resptallas=mysqli_query($enlaceCon,$sqlTallas);
	while($datosTallas=mysqli_fetch_array($resptallas)){
		$codTalla=$datosTallas[0];
		$abrevTalla=$datosTallas[1];
		?>
			<td colspan="2" align="center"> <strong><?=$abrevTalla;?> / Bs</strong></td>
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
	$montoTotalMT=0;

	$sqlModelo="select nombre,abreviatura from modelos where codigo=".$codModelo;
	$respModelo=mysqli_query($enlaceCon,$sqlModelo);
	while($datosModelo=mysqli_fetch_array($respModelo)){
		$nombreModelo=$datosModelo[0];
		$abrevModelo=$datosModelo[1];
	}

?>
<tr  >
	<td><?=$nombreModelo." (".$abrevModelo.")".$codModelo;?></td>

	<?php
		$sqlTallas2=" select codigo from tallas where estado=1 order by orden asc";
		$resptallas2=mysqli_query($enlaceCon,$sqlTallas2);
		while($datosTallas2=mysqli_fetch_array($resptallas2)){
			$codTalla=$datosTallas2[0];
			$sqlMT=" select  sum(sda.cantidad_unitaria),sum(sda.monto_unitario -sda.descuento_unitario)
				from salida_almacenes sa 
				left join salida_detalle_almacenes sda on (sa.cod_salida_almacenes=sda.cod_salida_almacen)
				inner join material_apoyo ma on (sda.cod_material=ma.codigo_material)
				where sa.cod_tiposalida=1001
				and ma.talla=".$codTalla."
				and ma.cod_modelo=".$codModelo."
				and sa.fecha BETWEEN  '".$fecha_iniconsulta."' and '".$fecha_finconsulta."' 
				and sa.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad in (".$rptTerritorio."))
				and sa.salida_anulada=1 ";
				$cantMT="";
				$montoMT="";
				$respMT=mysqli_query($enlaceCon,$sqlMT);
				while($datosMT=mysqli_fetch_array($respMT)){
					$cantMT=$datosMT[0];
					$montoMT=$datosMT[1];
					$montoTotalMT=$montoTotalMT+$montoMT;

				}

		if($cantMT>0){
	?>
			<td align="right">
				<strong><?=redondear2($cantMT);?></strong>
				
			</td>
			<td align="right">

				<?=redondear2($montoMT);?>
			</td>

	<?php
			}else{
	?>
<td align="right">&nbsp;</td>
			<td align="right">&nbsp;</td>
		<?php
			}
	?>
	<?php				
		}	
	?>

	<td align="right"><?=redondear2($cantVendidosModelo);?></td>
	<td align="right"><?=redondear2($montoTotalMT);?></td>
	

<tr/>
		<?php
			}
	
?>
<tr>
	<th>Totales</th>
<?php
	$cantTotTallas=0;
	$montoTotTallas=0;
	$sqlTallas=" select codigo,abreviatura from tallas where estado=1 order by orden asc";
	$resptallas=mysqli_query($enlaceCon,$sqlTallas);
	while($datosTallas=mysqli_fetch_array($resptallas)){
		$codTalla=$datosTallas[0];

		$sqlTotalesTallas="select  sum(sda.cantidad_unitaria),sum(sda.monto_unitario-sda.descuento_unitario)
		from salida_almacenes sa 
		left join salida_detalle_almacenes sda on (sa.cod_salida_almacenes=sda.cod_salida_almacen)
		inner join material_apoyo ma on (sda.cod_material=ma.codigo_material)
		where sa.cod_tiposalida=1001
		and ma.talla=".$codTalla."
		and sa.fecha BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."' 
		and sa.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad in (".$rptTerritorio."))
		and sa.salida_anulada=1";
		$respTotalesTallas=mysqli_query($enlaceCon,$sqlTotalesTallas);
		while($datosTotalesTallas=mysqli_fetch_array($respTotalesTallas)){

			$cantTallas=$datosTotalesTallas[0];
			$montoTallas=$datosTotalesTallas[1];
			$cantTotTallas=$cantTotTallas+$cantTallas;
			$montoTotTallas=$montoTotTallas+$montoTallas;
?>

	<td align="right"><strong><?=redondear2($cantTallas);?></strong></td>
	<td align="right"><?=redondear2($montoTallas);?></td>
<?php

		}
	}
?>
<td align="right"><strong><?=redondear2($cantTotTallas);?></strong></td>
	<td align="right"><strong><?=redondear2($montoTotTallas);?></strong></td>
</tr>
<table/>
	
<?php 

//include("imprimirInc.php");
?>