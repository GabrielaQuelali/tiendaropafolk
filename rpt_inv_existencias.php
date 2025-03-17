<?php
//header("Content-type: application/vnd.ms-excel");
//header("Content-Disposition: attachment; filename=archivo.xls");
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli.php');
require("funciones.php");

$rptOrdenar=$_GET["rpt_ordenar"];
$rptGrupo=$_GET["rpt_grupo"];
$rptMarca=$_GET["rpt_marca"];
$rptFormato=$_GET["rpt_formato"];
$rptBarCode=$_GET["rpt_barcode"];
$rpt_ver=$_GET["rpt_ver"];
$rpt_tipo=$_GET["rpt_tipo"];
$rpt_modelo=$_GET["rpt_modelo"];
//echo "rpt_modelo=".$rpt_modelo;
//echo "rptMarca=".$rptMarca;


$rptFechaInicio="2020-11-20";
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$sqlFuncProv="select * from funcionarios_proveedores where codigo_funcionario=$global_usuario";
$respFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);
$cantFuncProv=mysqli_num_rows($respFuncProv);


//$rpt_fecha=cambia_formatofecha($rpt_fecha);
$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";


	$sql_nombre_territorio="select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
	$resp_nombre_territorio=mysqli_query($enlaceCon,$sql_nombre_territorio);
	$datos_nombre_territorio=mysqli_fetch_array($resp_nombre_territorio);
	$nombre_territorio=$datos_nombre_territorio[0];
	$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
	$resp_nombre_almacen=mysqli_query($enlaceCon,$sql_nombre_almacen);
	$datos_nombre_almacen=mysqli_fetch_array($resp_nombre_almacen);
	$nombre_almacen=$datos_nombre_almacen[0];
	
	echo "<table align='center' class='textotit' width='70%'><tr><td align='center'>Reporte Existencias Almacen
	<br>Territorio: $nombre_territorio <br>Nombre Almacen: <strong>$nombre_almacen</strong>
	<br>Existencias a Fecha: <strong>$rpt_fecha</strong><br>$txt_reporte</td></tr></table>";
	
		//desde esta parte viene el reporte en si
		
$sql_item="select mp.codigo_material,mp.descripcion_material,mp.estado,mp.cod_grupo, g.nombre as nombreGrupo,
mp.cod_tipomaterial,mp.cantidad_presentacion,mp.observaciones,mp.imagen,
mp.cod_unidad, um.nombre as nombreUnidad,mp.peso, mp.cod_subgrupo,
sg.nombre as nombreSubgrupo, 
mp.cod_marca, mar.nombre as nombreMarca, mp.talla,tal.nombre as nombreTalla, mp.color, col.nombre as nombreColor,
mp.codigo_anterior,
mp.codigo2,mp.fecha_creacion,mp.cod_modelo, mo.nombre as nombreModelo, mo.abreviatura as abrevModelo,
mp.cod_material, ma.nombre as nombreMaterial,
mp.cod_genero, ge.nombre as nombreGenero
from material_apoyo mp
left join subgrupos sg on (mp.cod_subgrupo=sg.codigo)
left join grupos g on (sg.cod_grupo=g.codigo)
left join unidades_medida um on (mp.cod_unidad=um.codigo)
left join marcas mar on (mp.cod_marca=mar.codigo)
left join tallas tal on (mp.talla=tal.codigo)
left join colores col on (mp.color=col.codigo)
left join modelos mo on (mp.cod_modelo=mo.codigo)
left join materiales ma on (mp.cod_material=ma.codigo)
left join generos ge on (mp.cod_genero=ge.codigo)
where mp.estado=1";
if($rpt_tipo==1){
	if($rpt_marca!="-1"){
		$sql_item.=" and mp.cod_marca in(".$rptMarca.")";
	}

	if($rpt_modelo!="-1"){
		$sql_item.=" and mp.cod_modelo in(".$rpt_modelo.")";
	}

}
// Filtro Grupo
if($rpt_grupo!="-1"){
	$sql_item.=" and sg.cod_grupo in(".$rpt_grupo.")";
}

if($rptBarCode!=""){
	$sql_item.=" and ma.codigo_barras like '$rptBarCode%' ";
}

$sql_item.=" order by mo.nombre asc ,  sg.nombre asc, ge.nombre asc, col.nombre asc,mp.codigo_material asc";

//echo $sql_item;

			/*if($rptOrdenar==1){
				$sql_item.=" order by ma.descripcion_material";
			}
			if($rptOrdenar==2){
				$sql_item.=" order by 4,2";
			}
			if($rptOrdenar==3){
				$sql_item.=" order by 9,4,2";
			}*/
			
		
		
		
		$resp_item=mysqli_query($enlaceCon,$sql_item);
		
		if($rptOrdenar==1){
			if($rptFormato==1){
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr>
					<th>Nro</th><th>Modelo</th><th>Grupo</th><th>SubGrupo</th>		
	<th>Material</th><th>Genero</th><th>Color</th><th>Talla</th><th>Producto</th><th>Precio Actual</th><th>Cantidad</th>

				
					</tr>
				</thead>";				
			}else{//PARA INVENTARIO
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr>
										<th>Nro</th><th>Modelo</th><th>Grupo</th><th>SubGrupo</th>		
	<th>Material</th><th>Genero</th><th>Color</th><th>Talla</th><th>Producto</th><th>Precio Actual</th><th>Cantidad</th>

				
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					</tr>
				</thead>";
			}

		}else{

			if($rptFormato==1){
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr>
					<th>Nro</th><th>Modelo</th><th>Grupo</th><th>SubGrupo</th>		
	<th>Material</th><th>Genero</th><th>Color</th><th>Talla</th><th>Producto</th><th>Precio Actual</th><th>Cantidad</th>

				
					</tr>
				</thead>";				
			}else{//PARA INVENTARIO
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<th>Nro</th><th>Modelo</th><th>Grupo</th><th>SubGrupo</th>		
	<th>Material</th><th>Genero</th><th>Color</th><th>Talla</th><th>Producto</th><th>Precio Actual</th><th>Cantidad</th>

					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th></tr>
				</thead>";
			}
		}		
		$indice=1;
		$totalStock=0;
		while($datos_item=mysqli_fetch_array($resp_item))
		{	
	
			$codigo_item=$datos_item['codigo_material'];
			
			///////////////
					$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`=$codigo_item and p.cod_ciudad='".$_COOKIE['global_agencia']."'";
					$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
					$numFilas=mysqli_num_rows($respPrecio);
					if($numFilas==1){
						$datPrecio=mysqli_fetch_array($respPrecio);
						$precio0=$datPrecio[0];
						
						$precio0=redondear2($precio0);
					}else{
						$precio0=0;
						$precio0=redondear2($precio0);
					}
			///////////////
			$descripcion_material=$datos_item['descripcion_material'];
			$estado=$datos_item['estado'];
			$cod_grupo=$datos_item['cod_grupo'];
			$nombreGrupo=$datos_item['nombreGrupo'];
			$cod_tipomaterial=$datos_item['cod_tipomaterial'];
			$cantidad_presentacion=$datos_item['cantidad_presentacion'];
			$observaciones=$datos_item['observaciones'];
			$imagen=$datos_item['imagen'];
			$cod_unidad=$datos_item['cod_unidad'];
			$nombreUnidad=$datos_item['nombreUnidad'];
			$peso=$datos_item['peso'];
			$cod_subgrupo=$datos_item['cod_subgrupo'];
			$nombreSubgrupo=$datos_item['nombreSubgrupo'];
			$cod_marca=$datos_item['cod_marca'];
			$nombreMarca=$datos_item['nombreMarca'];
			$talla=$datos_item['talla'];
			$nombreTalla=$datos_item['nombreTalla'];
			$color=$datos_item['color'];
			$nombreColor=$datos_item['nombreColor'];
			$codigo_anterior=$datos_item['codigo_anterior'];
			$codigo2=$datos_item['codigo2'];
			$fecha_creacion=$datos_item['fecha_creacion'];
			$cod_modelo=$datos_item['cod_modelo'];
			$nombreModelo=$datos_item['nombreModelo'];
			$abrevModelo=$datos_item['abrevModelo'];
			$cod_material=$datos_item['cod_material'];
			$nombreMaterial=$datos_item['nombreMaterial'];
			$cod_genero=$datos_item['cod_genero'];
 			$nombreGenero=$datos_item['nombreGenero'];

		
						
			$cadena_mostrar="";
			if($rptOrdenar==1){			
				if($rptFormato==1){
					$cadena_mostrar.="<tr><td>$indice</td><td>$abrevModelo - $nombreModelo</td><td>$nombreGrupo</td><td>$nombreSubgrupo</td>
					<td>$nombreMaterial</td><td>$nombreGenero</td><td>$nombreColor</td><td>$nombreTalla</td>
					<td>$codigo_item - $descripcion_material</td><td>$precio0</td>";
				}else{//para inventario
					$cadena_mostrar.="<tr><td>$indice</td><td>$nombreModelo</td><td>$nombreGrupo</td><td>$nombreSubgrupo</td>
					<td>$nombreMaterial</td><td>$nombreGenero</td><td>$nombreColor</td><td>$nombreTalla</td>
					<td>$codigo_item - $descripcion_material</td><td>$precio0</td>";
				}
			}else{
			
				if($rptFormato==1){
					$cadena_mostrar.="<tr><td>$indice</td><td>$nombreModelo</td><td>$nombreGrupo</td><td>$nombreSubgrupo</td>
					<td>$nombreMaterial</td><td>$nombreGenero</td><td>$nombreColor</td><td>$nombreTalla</td>
					<td>$codigo_item - $descripcion_material</td><td>$precio0</td>";			
				}else{
					$cadena_mostrar.="<tr><td>$indice</td><td>$nombreModelo</td><td>$nombreGrupo</td><td>$nombreSubgrupo</td>
					<td>$nombreMaterial</td><td>$nombreGenero</td><td>$nombreColor</td><td>$nombreTalla</td>
					<td>$codigo_item - $descripcion_material</td><td>$precio0</td>";				
				}
			}
			
			$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha between '$rptFechaInicio 00:00:00' and '$rpt_fecha 23:59:59' and i.cod_almacen='$rpt_almacen'
			and id.cod_material='$codigo_item' and i.ingreso_anulado=1";
			
			//echo $sql_ingresos."<br/>";
			
			$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
			$dat_ingresos=mysqli_fetch_array($resp_ingresos);
			$cant_ingresos=$dat_ingresos[0];
			
			$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha between '$rptFechaInicio 00:00:00' and '$rpt_fecha 23:59:59' and s.cod_almacen='$rpt_almacen'
			and sd.cod_material='$codigo_item' and s.salida_anulada=1";
			
			//echo $sql_salidas;
			
			$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
			$dat_salidas=mysqli_fetch_array($resp_salidas);
			$cant_salidas=$dat_salidas[0];
			$stock2=$cant_ingresos-$cant_salidas;
			//echo $stock2;
			
			$stock_real=$stock2;
			
			if($stock2<0)
			{	$cadena_mostrar.="<td align='center'>0</td>";
				
			}
			else{	
			
				$cadena_mostrar.="<td align='center'>$stock2</td>";
				
			}
			
			if($rptFormato==2){//para inventario
				$cadena_mostrar.="<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
			}
			
			$cadena_mostrar.="</tr>";
			
			$sql_linea="select * from material_apoyo where codigo_material='$codigo_item'";
			$resp_linea=mysqli_query($enlaceCon,$sql_linea);			
			$num_filas=mysqli_num_rows($resp_linea);
			
			
			if($rpt_ver==1)
			{	echo $cadena_mostrar;
				//echo "rptver=".$rpt_ver."<br>";
				$indice++;
				$totalStock=$totalStock+$stock2;
			}
			if($rpt_ver==2 and $stock_real>0)
			{	echo $cadena_mostrar;
				//echo "rptver=".$rpt_ver."<br>";
				$totalStock=$totalStock+$stock2;
				$indice++;
			}
			if($rpt_ver==3 and $stock_real==0)
			{	//echo "rptver=".$rpt_ver."<br>";
		
				echo $cadena_mostrar;
				$totalStock=$totalStock+$stock2;
				$indice++;
			}
			
		}
		echo "<tr><td colspan='9'>&nbsp;</td><td align='right'><strong>Total Productos</strong></td><td align='center'>$totalStock</td></tr>";
		//$cadena_mostrar.="</tbody>";
		echo "</table>";
		
				include("imprimirInc.php");

?>