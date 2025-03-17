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
<tr class='textotit' align='center' ><th  colspan='2'  >REPORTE PRODUCTOS MAS VENDIDOS</th></tr>
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
<th>MODELO</th>


<th>SUBGRUPO</th>

<th>GENERO</th>
<th>COLOR</th>

</tr>
<?php

while($datos=mysqli_fetch_array($resp)){	
	$codModelo=$datos[0];
	$cantVendidosModelo=$datos[1];

	$sqlModelo="select nombre,abreviatura from modelos where codigo=".$codModelo;
	$respModelo=mysqli_query($enlaceCon,$sqlModelo);
	while($datosModelo=mysqli_fetch_array($respModelo)){
		$nombreModelo=$datosModelo[0];
		$abrevModelo=$datosModelo[1];
	}


?>
<tr  bgcolor="#A9CCE3">
	<td><?=$nombreModelo." (".$abrevModelo.")";?><strong> <?=redondear2($cantVendidosModelo);?></strong></td>
	
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	
	
<?php
	$sqlTallas=" select count(*) from tallas where estado=1 order by orden asc";
	$resptallas=mysqli_query($enlaceCon,$sqlTallas);
	while($datosTallas=mysqli_fetch_array($resptallas)){
		$countTallas=$datosTallas[0];
		
?>
		<td colspan="<?=$countTallas?>" align="center"><strong>TALLAS</strong></td>
<?php
	}
	
	
?>
</tr>
	<?php
	$sqlCantSugrupo="select mat.cod_subgrupo, sum(cant) cantSubgrupo
	from material_apoyo mat inner join 
	(select sda.cod_material,sum(sda.cantidad_unitaria) cant from salida_almacenes sa left join salida_detalle_almacenes sda on (sa.cod_salida_almacenes=sda.cod_salida_almacen) where sa.cod_tiposalida=1001 and sa.fecha 
	BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'  
	and sa.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad in (".$rptTerritorio.")) and sa.salida_anulada=1 group by sda.cod_material order by cant desc) pv 
	on (mat.codigo_material= pv.cod_material) 
	where mat.cod_modelo=".$codModelo."
	group by mat.cod_subgrupo
	order by cantSubgrupo desc";
	//echo $sqlCantSugrupo;
	$respCantSugrupo=mysqli_query($enlaceCon,$sqlCantSugrupo);
	while($datosCantSugrupo=mysqli_fetch_array($respCantSugrupo)){
		$codSubgrupo=$datosCantSugrupo[0];
		$cantSubgrupo=$datosCantSugrupo[1];
		$sqlSubGrupo="select nombre,abreviatura from subgrupos where codigo=".$codSubgrupo;
		$respSubgrupo=mysqli_query($enlaceCon,$sqlSubGrupo);
		while($datosSubgrupo=mysqli_fetch_array($respSubgrupo)){
			$nombreSubgrupo=$datosSubgrupo[0];
			$abrevSubgrupo=$datosSubgrupo[1];
		}

	?>
	<tr>
		<td>&nbsp;</td>
		
		
		<td><?=$nombreSubgrupo;?> <?="(".redondear2($cantSubgrupo).")";?></td>
		<td>&nbsp;</td>
	<td>&nbsp;</td>

</tr>
	
<?php
	$sqlCantGeneros="select map.cod_genero, sum(sda.cantidad_unitaria)
	from salida_almacenes sa 
	left join salida_detalle_almacenes sda on (sa.cod_salida_almacenes=sda.cod_salida_almacen) 
	left join material_apoyo map on (sda.cod_material=map.codigo_material) 
	where sa.cod_tiposalida=1001 
	and sa.fecha BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'   
	and sa.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad in (".$rptTerritorio.")) 
	and sa.salida_anulada=1 
	and map.cod_modelo=".$codModelo."
	and map.cod_subgrupo=".$codSubgrupo."
	group by map.cod_genero";
	//echo $sqlCantGeneros;
	$respCantGeneros=mysqli_query($enlaceCon,$sqlCantGeneros);
	while($datosCantGeneros=mysqli_fetch_array($respCantGeneros)){

		$codGenero=$datosCantGeneros[0];
		$cantGenero=$datosCantGeneros[1];

		$sqlGenero="select nombre,abreviatura from generos where codigo=".$codGenero;
		$respGenero=mysqli_query($enlaceCon,$sqlGenero);
		while($datosGenero=mysqli_fetch_array($respGenero)){
			$nombreGenero=$datosGenero[0];
			$abrevGenero=$datosGenero[1];
		}

?>

	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><?=$nombreGenero;?><?=" (".redondear2($cantGenero).")";?></td>
		<td>&nbsp;</td>
		<?php
	$sqlTallas=" select codigo,abreviatura from tallas where estado=1 order by orden asc";
	$resptallas=mysqli_query($enlaceCon,$sqlTallas);
	while($datosTallas=mysqli_fetch_array($resptallas)){
		$codTalla=$datosTallas[0];
		$abrevTalla=$datosTallas[1];
?>
		<td><strong><?=$abrevTalla;?></strong></td>
<?php
	}
	
	
?>
	

	</tr>

<?php
	$sqlCantColores="select map.color, sum(sda.cantidad_unitaria) cant
	from salida_almacenes sa 
	left join salida_detalle_almacenes sda on (sa.cod_salida_almacenes=sda.cod_salida_almacen) 
	left join material_apoyo map on (sda.cod_material=map.codigo_material) 
	where sa.cod_tiposalida=1001 
	and sa.fecha  BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."' 
	and sa.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad in (".$rptTerritorio.")) 
	and sa.salida_anulada=1 
	and map.cod_modelo=".$codModelo."
	and map.cod_subgrupo=".$codSubgrupo."
	and map.cod_genero=".$codGenero."
	group by map.color
	order by cant desc";
	$respCantColores=mysqli_query($enlaceCon,$sqlCantColores);
	while($datosCantColores=mysqli_fetch_array($respCantColores)){

		$codColor=$datosCantColores[0];
		$cantColor=$datosCantColores[1];

		$sqlColor="select nombre,abreviatura from colores where codigo=".$codColor;
		$respColor=mysqli_query($enlaceCon,$sqlColor);
		while($datosColor=mysqli_fetch_array($respColor)){
			$nombreColor=$datosColor[0];
			$abrevColor=$datosColor[1];
		}

?>
		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><?=$nombreColor;?><?=" (".redondear2($cantColor).")";?></td>

		<?php

			$sqlTallas2=" select codigo,abreviatura from tallas where estado=1 order by orden asc";
			$resptallas2=mysqli_query($enlaceCon,$sqlTallas2);
			while($datosTallas2=mysqli_fetch_array($resptallas2)){
				$codTalla=$datosTallas2[0];
				$cantTalla=0;

				$sqlCantTalla="select talla,cant  from (select map.talla, sum(sda.cantidad_unitaria) cant
				from salida_almacenes sa 
				left join salida_detalle_almacenes sda on (sa.cod_salida_almacenes=sda.cod_salida_almacen) 
				left join material_apoyo map on (sda.cod_material=map.codigo_material) 
				where sa.cod_tiposalida=1001 
				and sa.fecha BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'  
				and sa.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad in (".$rptTerritorio.")) 
				and sa.salida_anulada=1 
				and map.cod_modelo=".$codModelo."
				and map.cod_subgrupo=".$codSubgrupo."
				and map.cod_genero=".$codGenero."
				and map.color=".$codColor."
				group by map.talla
				order by cant desc) prodTallas
				where prodTallas.talla=".$codTalla;
				//echo $sqlCantTalla;
				$cantTalla=0;
				$respCantTalla=mysqli_query($enlaceCon,$sqlCantTalla);
				while($datosCantTalla=mysqli_fetch_array($respCantTalla)){
					$cantTalla=$datosCantTalla[1];
				}

		?>
		<td><?=redondear2($cantTalla);?></td>


		<?php
			}
		?>


		</tr>
<?php	
	}	
	
?>

<?php		
	}
?>

<?php
	}
}


?>
</table>
	
<?php 


//include("imprimirInc.php");
?>