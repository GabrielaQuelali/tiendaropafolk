<?php
function obtenerValorConfiguracion($enlaceCon,$id){
	$estilosVenta=1;
	//require("conexionmysqli2.inc");
	$sql = "SELECT valor_configuracion from configuraciones c where id_configuracion=$id";
	$resp=mysqli_query($enlaceCon,$sql);
	$codigo=0;
	while ($dat = mysqli_fetch_array($resp)) {
	  $codigo=$dat['valor_configuracion'];
	}
	return($codigo);
}

function numeroCorrelativoCUFD($enlaceCon,$tipoDoc){
	//require("conexionmysqli2.inc");
	$globalCiudad=$_COOKIE['global_agencia'];
	//echo "GlobalCiudad".$globalCiudad;
	$globalAlmacen=$_COOKIE['global_almacen'];	 
	//echo "GlobalAlmacen".$globalAlmacen;
  $fechaActual=date("Y-m-d");
  $anioActual=date("Y");
  //$sqlCufd="select cufd FROM siat_cufd where cod_ciudad='$globalCiudad' and estado=1 and fecha='$fechaActual'";

   $sqlCufd="select cufd from siat_cufd where cod_ciudad=$globalCiudad and fecha = '$fechaActual' and estado=1 AND (cufd <> '' or cufd <> null) and cuis in (select cuis from siat_cuis where cod_ciudad='$globalCiudad' and estado=1 and cod_gestion='$anioActual')";
	 
  $respCufd=mysqli_query($enlaceCon,$sqlCufd);
  $datCufd=mysqli_fetch_array($respCufd);
  $cufd=$datCufd[0];//$cufd=mysqli_result($respCufd,0,0);
  $nro_correlativo="CUFD INCORRECTO / VENCIDO";
  $bandera=1;

  
  $sqlCuis="select cuis FROM siat_cuis where cod_ciudad='$globalCiudad' and estado=1 and cod_gestion='$anioActual'";  
  $respCuis=mysqli_query($enlaceCon,$sqlCuis);
  $datCuis=mysqli_fetch_array($respCuis);
  $cuis=$datCuis[0];//$cuis=mysqli_result($respCuis,0,0);
  if($cuis==""){
  		$nro_correlativo="CUIS INCORRECTO / VENCIDO";$bandera=1;	
  } 
  //$nro_correlativo.=" CUIS".$cufd; 
  if($cufd!=""&&$cuis!=""){
    $sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' 
				and siat_cuis='$cuis' and cod_almacen='$globalAlmacen' ";		
	$resp=mysqli_query($enlaceCon,$sql);
    while($row=mysqli_fetch_array($resp)){  
       $nro_correlativo=$row[0];   
       $bandera=0;
    }
  }
  return array($nro_correlativo,$bandera,'');  
}

function numeroCorrelativoCUFD2($tipoDoc){
	require("conexionmysqli2.inc");
	$globalCiudad=$_COOKIE['global_agencia'];
	$globalAlmacen=$_COOKIE['global_almacen'];	 

  $fechaActual=date("Y-m-d");
  $sqlCufd="select cufd FROM siat_cufd where cod_ciudad='$globalCiudad' and estado=1 and fecha='$fechaActual'";
	// echo $sqlCufd;
  $respCufd=mysqli_query($enlaceCon,$sqlCufd);
  $cufd=mysqli_result($respCufd,0,0);
  $nro_correlativo="CUFD INCORRECTO / VENCIDO";$bandera=1;

  $anioActual=date("Y");
	$sqlCuis="select cuis FROM siat_cuis where cod_ciudad='$globalCiudad' and estado=1 and cod_gestion='$anioActual'";
  $respCuis=mysqli_query($enlaceCon,$sqlCuis);
  $cuis=mysqli_result($respCuis,0,0);
  if($cuis==""){
  		$nro_correlativo="CUIS INCORRECTO / VENCIDO";$bandera=1;	
  } 
  //$nro_correlativo.=" CUIS".$cufd; 
  if($cufd!=""&&$cuis!=""){
    $sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' 
				and siat_cuis='$cuis' and cod_almacen='$globalAlmacen' ";				
				$resp=mysqli_query($enlaceCon,$sql);
    while($row=mysqli_fetch_array($resp)){  
       $nro_correlativo=$row[0];   
       $bandera=0;
    }
  }
  return array($nro_correlativo,$bandera);  
}

function obtenerCorreosListaCliente($id_proveedor){	
  	require("conexionmysqli2.inc");
  	$sql_detalle="SELECT DISTINCT email_cliente FROM `clientes` where cod_cliente='$id_proveedor'";
  	$correosProveedor="";  
  	$resp=mysqli_query($enlaceCon,$sql_detalle);  
  	while($detalle=mysqli_fetch_array($resp)){  
       $correo=$detalle[0];
       $correosProveedor.=$correo.",";
	} 
	$correosProveedor=trim($correosProveedor,",");
  	mysqli_close($enlaceCon); 
  	return $correosProveedor;
}

function actualizaNombreProducto($enlaceCon,$codProducto){
	$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		m.cod_grupo,gru.nombre as nombreGrupo, m.cod_subgrupo,sgru.nombre as nombreSubgrupo,
		m.cod_marca, mar.nombre as nombreMarca,
		m.observaciones, imagen,
		 m.color, col.nombre as nombreColor,col.abreviatura as abrevColor,
		 m.talla, tal.nombre as nombreTalla,tal.abreviatura as abrevTalla,
		 m.codigo_barras, m.codigo2, m.fecha_creacion,
		m.cod_modelo,mo.nombre as nombreModelo, mo.abreviatura as abrevModelo,
		m.cod_material, mat.nombre as nombreMaterial, mat.abreviatura as abrevMaterial, 
		m.cod_genero, gen.nombre as nombreGenero,
		m.cod_coleccion,cole.nombre as nombreColeccion
		from material_apoyo m
		left join grupos gru on ( gru.codigo=m.cod_grupo)
		left join subgrupos sgru on ( sgru.codigo=m.cod_subgrupo)
		left join marcas mar on ( mar.codigo=m.cod_marca)
		left join modelos mo on ( mo.codigo=m.cod_modelo)
		left join materiales mat on ( mat.codigo=m.cod_material)
		left join generos gen on ( gen.codigo=m.cod_genero)
		left join colores col on ( col.codigo=m.color)
		left join tallas tal on ( tal.codigo=m.talla)
		left join colecciones cole  on ( cole.codigo=m.cod_coleccion)
		where m.codigo_material=".$codProducto;
		echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{

		
		$nombreProd=$dat['descripcion_material'];
		$estado=$dat['estado'];
		$grupo=$dat['nombreGrupo'];
		$subgrupo=$dat['nombreSubgrupo'];
		$marca=$dat['nombreMarca'];		
	
		$observaciones=$dat['observaciones'];
		$imagen=$dat['imagen'];
		$nombreColor=$dat['nombreColor'];
		$abrevColor=$dat['abrevColor'];
		$talla=$dat['talla'];
		$nombreTalla=$dat['nombreTalla'];
		$abrevTalla=$dat['abrevTalla'];
		$codigoBarras=$dat['codigo_barras'];
		$codigo2=$dat['codigo2'];
		$fechaCreacion=$dat['fecha_creacion'];
		$nombreModelo=$dat['nombreModelo'];
		$abrevModelo=$dat['abrevModelo'];
		$nombreMaterial=$dat['nombreMaterial'];
		$abrevMaterial=$dat['abrevMaterial'];
		$nombreGenero=$dat['nombreGenero'];
		$nombreColeccion=$dat['nombreColeccion'];
		$descModelo="";
		if($nombreModelo==$abrevModelo){
			$descModelo=$abrevModelo;
		}else{
			$descModelo=$abrevModelo." ".$nombreModelo;
		}
		$sqlActualizanombre="update material_apoyo set descripcion_material='".$grupo." ".$descModelo." ".$nombreGenero." ".$subgrupo." ".$abrevMaterial." ".$nombreColor." ".$abrevColor." ".$abrevTalla." (".$codProducto.")' where codigo_material=".$codProducto;
		//echo $sqlActualizanombre."<br>";
		$resp2=mysqli_query($enlaceCon,$sqlActualizanombre);
	}
	return($resp);
}

function actualizaNombreProducto2($enlaceCon,$codProducto){
	$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		m.cod_grupo,gru.nombre as nombreGrupo, m.cod_subgrupo,sgru.nombre as nombreSubgrupo,
		m.cod_marca, mar.nombre as nombreMarca,
		m.observaciones, imagen,
		 m.color, col.nombre as nombreColor,
		 m.talla, tal.nombre as nombreTalla, tal.abreviatura as abrevTalla,
		 m.codigo_barras, m.codigo2, m.fecha_creacion,
		m.cod_modelo,mo.nombre as nombreModelo, 
		m.cod_material, mat.nombre as nombreMaterial, 
		m.cod_genero, gen.nombre as nombreGenero,gen.abreviatura as abrevGenero,
		m.cod_coleccion,cole.nombre as nombreColeccion
		from material_apoyo m
		left join grupos gru on ( gru.codigo=m.cod_grupo)
		left join subgrupos sgru on ( sgru.codigo=m.cod_subgrupo)
		left join marcas mar on ( mar.codigo=m.cod_marca)
		left join modelos mo on ( mo.codigo=m.cod_modelo)
		left join materiales mat on ( mat.codigo=m.cod_material)
		left join generos gen on ( gen.codigo=m.cod_genero)
		left join colores col on ( col.codigo=m.color)
		left join tallas tal on ( tal.codigo=m.talla)
		left join colecciones cole  on ( cole.codigo=m.cod_coleccion)
		where m.codigo_material=".$codProducto;
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{

		
		$nombreProd=$dat['descripcion_material'];
		$estado=$dat['estado'];
		$grupo=$dat['nombreGrupo'];
		$subgrupo=$dat['nombreSubgrupo'];
		$marca=$dat['nombreMarca'];		
	
		$observaciones=$dat['observaciones'];
		$imagen=$dat['imagen'];
		$color=$dat['color'];
		$nombreColor=$dat['nombreColor'];
		$talla=$dat['talla'];
		$nombreTalla=$dat['nombreTalla'];
		$abrevTalla=$dat['abrevTalla'];
		$codigoBarras=$dat['codigo_barras'];
		$codigo2=$dat['codigo2'];
		$fechaCreacion=$dat['fecha_creacion'];
		$nombreModelo=$dat['nombreModelo'];
		$nombreMaterial=$dat['nombreMaterial'];
		$nombreGenero=$dat['nombreGenero'];
		$abrevGenero=$dat['abrevGenero'];
		$nombreColeccion=$dat['nombreColeccion'];
		$sqlActualizanombre="update material_apoyo set 
		descripcion_material='".$nombreModelo." ".$abrevGenero." ".$nombreMaterial." ".$nombreColor." ".$abrevTalla." ".$nombreColeccion." (".$codProducto.")' where codigo_material=".$codProducto;
		$resp=mysqli_query($enlaceCon,$sqlActualizanombre);
	}
	return($resp);
}

function cargarDocumentosPDF($codigoVenta){
	$home=1;
	ob_start();
	require "conexionmysqli2.inc";
	include "dFacturaElectronicaAllPdf.php";
	$html = ob_get_clean();
	//error_reporting(E_ALL);
	$sqlDatosVenta="select s.siat_cuf
	        from `salida_almacenes` s
	        where s.`cod_salida_almacenes`='$codigoVenta'";
	$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
	$cuf="";
	while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
	    $cuf=$datDatosVenta['siat_cuf'];
	}
	$nombreFile="siat_folder/Siat/temp/Facturas-XML/$cuf.pdf";
	unlink($nombreFile);	

	guardarPDFArqueoCajaVerticalFactura($cuf,$html,$nombreFile);
	return $cuf.".pdf";

}
function cargarDocumentosXML($codSalida){
	// $codSalida=$_GET['codVenta'];
	require "conexionmysqli2.inc";
	require_once "siat_folder/funciones_siat.php";  
	$facturaImpuestos=generarXMLFacturaVentaImpuestos($codSalida);

	$sqlDatosVenta="select s.siat_cuf
	        from `salida_almacenes` s
	        where s.`cod_salida_almacenes`='$codSalida'";
	$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
	$cuf="";
	while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
	    $cuf=$datDatosVenta['siat_cuf'];

	}
	$nombreFile="siat_folder/Siat/temp/Facturas-XML/$cuf.xml";
	unlink($nombreFile);	
	$archivo = fopen($nombreFile,'a');    
	fputs($archivo,$facturaImpuestos);
	fclose($archivo);

	// if($email==1){
		// header("Content-Type: application/force-download");
		// header("Content-Disposition: attachment; filename=\"$cuf.xml\"");
		// readfile($nombreFile);	
	// }else{
		return $cuf.".xml";
	// }
}


function redondear2($valor) { 
   $float_redondeado=round($valor * 100) / 100; 
   return $float_redondeado; 
}

function formatonumero($valor) { 
   $float_redondeado=number_format($valor, 0); 
   return $float_redondeado; 
}

function formatonumeroDec($valor) { 
   $float_redondeado=number_format($valor, 2); 
   return $float_redondeado; 
}

function formateaFechaVista($cadena_fecha)
{	$cadena_formatonuevo=$cadena_fecha[6].$cadena_fecha[7].$cadena_fecha[8].$cadena_fecha[9]."-".$cadena_fecha[3].$cadena_fecha[4]."-".$cadena_fecha[0].$cadena_fecha[1];
	return($cadena_formatonuevo);
}

function formatearFecha2($cadena_fecha)
{	$cadena_formatonuevo=$cadena_fecha[8].$cadena_fecha[9]."/".$cadena_fecha[5].$cadena_fecha[6]."/".$cadena_fecha[0].$cadena_fecha[1].$cadena_fecha[2].$cadena_fecha[3];
	return($cadena_formatonuevo);
}

function UltimoDiaMes($cadena_fecha)
{	
	list($anioX, $mesX, $diaX)=explode("-",$cadena_fecha);
	$fechaNuevaX=$anioX."-".$mesX."-01";
	
	$fechaNuevaX=date('Y-m-d',strtotime($fechaNuevaX.'+1 month'));
	$fechaNuevaX=date('Y-m-d',strtotime($fechaNuevaX.'-1 day'));

	return($fechaNuevaX);
}

function obtenerCodigo($enlaceCon,$sql)
{	//require("conexion.inc");
	$resp=mysqli_query($enlaceCon,$sql);
	$nro_filas_sql = mysqli_num_rows($resp);
	if($nro_filas_sql==0){
		$codigo=1;
	}else{
		while($dat=mysqli_fetch_array($resp))
		{	$codigo =$dat[0];
		}
			$codigo = $codigo+1;
	}
	return($codigo);
}

function stockProducto($enlaceCon,$almacen, $item){
	//
	//require("conexion.inc");
	$fechaActual=date("Y-m-d");
	
	//SACAMOS LA FECHA EN QUE INICIAMOS OPERACIONES ESTE DATO ES MUY IMPORTANTE
	$fechaInicioSistema=obtenerValorConfiguracion($enlaceCon,6);
	
	$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha between '$fechaInicioSistema' and '$fechaActual' and i.cod_almacen='$almacen'
			and id.cod_material='$item' and i.ingreso_anulado=1";

			//echo $sql_ingresos;

			$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
			$dat_ingresos=mysqli_fetch_array($resp_ingresos);
			$cant_ingresos=$dat_ingresos[0];
			$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha between '$fechaInicioSistema' and '$fechaActual' and s.cod_almacen='$almacen'
			and sd.cod_material='$item' and s.salida_anulada=1";
			//echo $sql_salidas;
			$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
			$dat_salidas=mysqli_fetch_array($resp_salidas);
			$cant_salidas=$dat_salidas[0];
			$stock2=$cant_ingresos-$cant_salidas;
	return($stock2);
}
function stockProductoIngreso($enlaceCon,$almacen, $item,$codIngreso){
	//
	//require("conexion.inc");
	$fechaActual=date("Y-m-d");
	
	//SACAMOS LA FECHA EN QUE INICIAMOS OPERACIONES ESTE DATO ES MUY IMPORTANTE
	$fechaInicioSistema=obtenerValorConfiguracion($enlaceCon,6);
	
	$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha between '$fechaInicioSistema' and '$fechaActual' and i.cod_almacen='$almacen'
			and id.cod_material='$item' and i.ingreso_anulado=1 and i.cod_ingreso_almacen='$codIngreso'";

			//echo $sql_ingresos;

			$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
			$dat_ingresos=mysqli_fetch_array($resp_ingresos);
			$cant_ingresos=$dat_ingresos[0];
			$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha between '$fechaInicioSistema' and '$fechaActual' and s.cod_almacen='$almacen'
			and sd.cod_material='$item' and s.salida_anulada=1 and sd.cod_ingreso_almacen='$codIngreso'";
			//echo $sql_salidas;
			$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
			$dat_salidas=mysqli_fetch_array($resp_salidas);
			$cant_salidas=$dat_salidas[0];
			$stock2=$cant_ingresos-$cant_salidas;
	return($stock2);
}

function stockProductoAFecha($enlaceCon, $almacen, $item, $fechaInventario){
	$fechaActual=$fechaInventario;
	$fechaInicioSistema=obtenerValorConfiguracion($enlaceCon,6);
   $sql_ingresos="select IFNULL(sum(id.cantidad_unitaria),0) from ingreso_almacenes i, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha between '$fechaInicioSistema' and '$fechaActual' and i.cod_almacen='$almacen'
	and id.cod_material='$item' and i.ingreso_anulado=0";
	$cant_ingresos=0;
	$cant_salidas=0;
	$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
	if($dat_ingresos=mysqli_fetch_array($resp_ingresos)){
		$cant_ingresos=$dat_ingresos[0];	
	}
	$sql_salidas="select IFNULL(sum(sd.cantidad_unitaria),0) from salida_almacenes s, salida_detalle_almacenes sd
	where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha between '$fechaInicioSistema' and '$fechaActual' and s.cod_almacen='$almacen'
	and sd.cod_material='$item' and s.salida_anulada=0";
	$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
	if($dat_salidas=mysqli_fetch_array($resp_salidas)){
		$cant_salidas=$dat_salidas[0];
	}
	$stock2=$cant_ingresos-$cant_salidas;
	return($stock2);
}
function ingresosItemPeriodo($enlaceCon, $almacen, $item, $fechaInicio, $fechaFinal){
	$sql_ingresos="select IFNULL(sum(id.cantidad_unitaria),0) from ingreso_almacenes i, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha between '$fechaInicio' and '$fechaFinal' and i.cod_almacen='$almacen'
	and id.cod_material='$item' and i.ingreso_anulado=0";
	$cant_ingresos=0;
	$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
	if($dat_ingresos=mysqli_fetch_array($resp_ingresos)){
		$cant_ingresos=$dat_ingresos[0];	
	}
	return($cant_ingresos);
}
function salidasItemPeriodo($enlaceCon, $almacen, $item, $fechaInicio, $fechaFinal){
	$cant_salidas=0;
	$sql_salidas="select IFNULL(sum(sd.cantidad_unitaria),0) from salida_almacenes s, salida_detalle_almacenes sd
	where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha between '$fechaInicio' and '$fechaFinal' and s.cod_almacen='$almacen'
	and sd.cod_material='$item' and s.salida_anulada=0";
	$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
	if($dat_salidas=mysqli_fetch_array($resp_salidas)){
		$cant_salidas=$dat_salidas[0];
	}
	return($cant_salidas);
}
function stockMaterialesEdit($enlaceCon,$almacen, $item, $cantidad){
	//
	//require("conexion.inc");
	$cadRespuesta="";
	$consulta="
	    SELECT SUM(id.cantidad_restante) as total
	    FROM ingreso_detalle_almacenes id, ingreso_almacenes i
	    WHERE id.cod_material='$item' AND i.cod_ingreso_almacen=id.cod_ingreso_almacen AND i.ingreso_anulado=0 AND i.cod_almacen='$almacen'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$cadRespuesta=$registro[0];
	if($cadRespuesta=="")
	{   $cadRespuesta=0;
	}
	$cadRespuesta=$cadRespuesta+$cantidad;
	$cadRespuesta=redondear2($cadRespuesta);
	return($cadRespuesta);
}
function restauraCantidades($enlaceCon,$codigo_registro){
	$sql_detalle="select cod_ingreso_almacen, material, cantidad_unitaria
				from salida_detalle_ingreso
				where cod_salida_almacen='$codigo_registro'";
	$resp_detalle=mysqli_query($enlaceCon,$sql_detalle);
	while($dat_detalle=mysqli_fetch_array($resp_detalle))
	{	$codigo_ingreso=$dat_detalle[0];
		$material=$dat_detalle[1];
		$cantidad=$dat_detalle[2];
		$nro_lote=$dat_detalle[3];
		$sql_ingreso_cantidad="select cantidad_restante from ingreso_detalle_almacenes
								where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		$resp_ingreso_cantidad=mysqli_query($enlaceCon,$sql_ingreso_cantidad);
		$dat_ingreso_cantidad=mysqli_fetch_array($resp_ingreso_cantidad);
		$cantidad_restante=$dat_ingreso_cantidad[0];
		$cantidad_restante_actualizada=$cantidad_restante+$cantidad;
		$sql_actualiza="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_restante_actualizada
						where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		
		$resp_actualiza=mysqli_query($enlaceCon,$sql_actualiza);			
	}
	return(1);
}
function numeroCorrelativo($enlaceCon,$tipoDoc,$tipo='1'){
	//require("conexion.inc");
	$banderaErrorFacturacion=0;

	$fechaActual=date("Y-m-d");
	$globalAgencia=$_COOKIE['global_agencia'];
	$globalAlmacen=$_COOKIE['global_almacen'];
	
	if( $tipoDoc==2 || $tipoDoc==3 ){
		$sql="select IFNULL(max(nro_correlativo)+1,1) 
		from salida_almacenes 
		where cod_tipo_doc='$tipoDoc' and cod_almacen='$globalAlmacen' and cod_tipo='$tipo' ";
		//echo $sql;
		$resp=mysqli_query($enlaceCon,$sql);
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$vectorCodigo = array($codigo,$banderaErrorFacturacion,0);
			return $vectorCodigo;
		}
	}
}

function unidadMedida($enlaceCon,$codigo){
	
	$consulta="select u.abreviatura from material_apoyo m, unidades_medida u
		where m.cod_unidad=u.codigo and m.codigo_material='$codigo'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$unidadMedida=$registro[0];

	return $unidadMedida;
}


function nombreTipoDoc($enlaceCon,$codigo){
	$consulta="select u.abreviatura from tipos_docs u
		where u.codigo='$codigo'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$nombre=$registro[0];

	return $nombre;
}


function precioVenta($enlaceCon,$codigo,$agencia){
	
	$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1' and p.cod_ciudad='$agencia'";
	$rs=mysqli_query($enlaceCon,$consulta);
		$precioVenta=0;
		while($registro=mysqli_fetch_array($rs))
		{ $precioVenta=$registro[0];
		}
if($precioVenta==NULL){
			$precioVenta=0;
		}
		if($precioVenta>0){
		$precioVenta=redondear2($precioVenta);
		}

	return $precioVenta;
}
//COSTO 
function costoVentaFalse($enlaceCon,$codigo,$agencia){	
	$consulta="select sd.costo_almacen from salida_almacenes s, salida_detalle_almacenes sd where 
		s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen in  
		(select a.cod_almacen from almacenes a where a.cod_ciudad='$agencia') and s.salida_anulada=0 and 
		sd.cod_material='$codigo' limit 0,1";
		$rs=mysqli_query($enlaceCon,$consulta);
		$costoVenta=0;
		while($registro=mysqli_fetch_array($rs))
		{ $costoVenta=$registro[0];
		}
		if($costoVenta==NULL){
			$costoVenta=0;
		}
		if($costoVenta>0){
		$costoVenta=redondear2($costoVenta);
		}
	return $costoVenta;
}

function costoVenta($enlaceCon,$codigo,$agencia){	
	$consulta="select id.costo_almacen from ingreso_almacenes i, ingreso_detalle_almacenes id where 
	i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen in  
			(select a.cod_almacen from almacenes a where a.cod_ciudad='$agencia') and i.ingreso_anulado=1 
	and id.cod_material='$codigo' order by i.cod_ingreso_almacen desc limit 0,1";
	//echo $consulta;
	$costoVenta=0;
	$rs=mysqli_query($enlaceCon,$consulta);
		while($registro=mysqli_fetch_array($rs))
		{ $costoVenta=$registro[0];
		}
		if($costoVenta==NULL){
			$costoVenta=0;
		}
		if($costoVenta>0){
		$costoVenta=redondear2($costoVenta);
		}
	return $costoVenta;

}


function codigoSalida($enlaceCon,$cod_almacen){	
	$consulta="select IFNULL(max(s.cod_salida_almacenes)+1,1) as codigo from salida_almacenes s";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$codigo=$registro[0];

	return $codigo;
}

function obtieneIdProducto($enlaceCon,$idProducto){
	$sql="select m.codigo_material from material_apoyo m where m.codigo_anterior='$idProducto'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$idProducto=$dat[0];
	return($idProducto);	
}

function obtieneMarcaProducto($enlaceCon,$idMarca){
	$sql="select m.nombre from marcas m where m.codigo='$idMarca'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombreMarca=$dat[0];
	return($nombreMarca);	
}
function fechaInicioSistema($enlaceCon){
	//6 FECHA DE INICIO DE OPERACIONES
	$sqlConf="select valor_configuracion from configuraciones where id_configuracion=6";
	$respConf=mysqli_query($enlaceCon,$sqlConf);
	$datConf=mysqli_fetch_array($respConf);
	$fechaInicioOperaciones=$datConf[0];
	//$fechaInicioOperaciones=mysqli_result($respConf,0,0);	
	return($fechaInicioOperaciones);
}

function montoVentaDocumento($enlaceCon,$codVenta){
	$sql="select (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total
	from `salida_almacenes` s, `salida_detalle_almacenes` sd 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.cod_salida_almacenes=$codVenta";
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);

	$totalVenta=0;
	while($datos=mysqli_fetch_array($resp)){	
		
		$montoVenta=$datos[0];
		$cantidad=$datos[1];

		$descuentoVenta=$datos[2];
		$montoNota=$datos[3];
		
		if($descuentoVenta>0){
			$porcentajeVentaProd=($montoVenta/$montoNota);
			$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
			$montoVenta=$montoVenta-$descuentoAdiProducto;
		}
		$totalVenta=$totalVenta+$montoVenta;
	}
	return($totalVenta);	
}


function obtenerEstadoSalida($codSalida){
  	$estilosVenta=1;
  	require("conexionmysqli2.inc");
  	$sql_detalle="SELECT salida_anulada FROM salida_almacenes where cod_salida_almacenes='$codSalida'";
  	$estado=0;	
  	$resp=mysqli_query($enlaceCon,$sql_detalle);
  	while($detalle=mysqli_fetch_array($resp)){	
       $estado=$detalle[0]; 	
  	} 
  	mysqli_close($enlaceCon); 
  	return $estado;
}

  function guardarPDFArqueoCajaVerticalFactura($nom,$html,$rutaGuardado,$codSalida){
    //aumentamos la memoria  
    ini_set("memory_limit", "128M");
    // Cargamos DOMPDF
    require_once 'assets/libraries/dompdf/dompdf_config.inc.php';
    $mydompdf = new DOMPDF();
    $mydompdf->set_paper('letter', 'portrait');    

    ob_clean();
    $mydompdf->load_html($html);
    $mydompdf->render();
    $canvas = $mydompdf->get_canvas();
    $canvas->page_text(540, 750, "{PAGE_NUM}/{PAGE_COUNT}", Font_Metrics::get_font("sans-serif"), 7, array(0,0,0)); 

    $estado=obtenerEstadoSalida($codSalida);
    if($estado!=0){ //facturas anuladas MARCA DE AGUA ANULADO
      //marca de agua
      $canvas2 = $mydompdf->get_canvas(); 
      $w = $canvas2->get_width(); 
      $h = $canvas2->get_height(); 
      $font = Font_Metrics::get_font("times"); 
      $text = "ANULADO"; 
      $txtHeight = -100; 
      $textWidth = 250; 
      $canvas2->set_opacity(.5); 
      $x = (($w-$textWidth)/2); 
      $y = (($h-$txtHeight)/2); 
      $canvas2->text($x, $y, $text, $font, 100, $color = array(100,0,0), $word_space = 0.0, $char_space = 0.0, $angle = -45);
    //fin marca agua
     }

    $output = $mydompdf->output();    
    file_put_contents($rutaGuardado, $output);
  }

  function obtenerCostoInsumosProducto($codProducto, $fechaInicio, $fechaFinal, $ciudades) {
	$estilosVenta=1;
	require("conexionmysqli2.inc");
	
	$sqlGrupos="SELECT m.codigo_material, m.descripcion_material,
		(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total
		from salida_almacenes s
		INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
		INNER JOIN material_apoyo m ON m.codigo_material=sd.cod_material
		where s.`fecha` BETWEEN '$fechaInicio' and '$fechaFinal'
		and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and sd.cod_material = '$codProducto' and 
		s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($ciudades) )
		group by m.codigo_material 
		limit 0,1 ";
	//echo $sqlGrupos."<br>";
	$respGrupos=mysqli_query($enlaceCon,$sqlGrupos);

	$costoProducto=0;
	$obsCosto=0;
	$jsonInsumosProductos="";
	$arrayProductos = array();
	while($datGrupos=mysqli_fetch_array($respGrupos)) {
		$codProductoX=$datGrupos[0];
		$nombreProductoX=$datGrupos[1];
		$montoVentaX=$datGrupos[2];
		$cantidadProductoX=$datGrupos[3];
		
		$sql="SELECT ip.cod_insumo,mi.descripcion_material as nombreinsumo,u.nombre,ip.cant, pr.precio
		FROM insumos_productos ip 
		LEFT JOIN material_apoyo m ON m.codigo_material=ip.cod_producto 
		LEFT JOIN material_apoyo mi ON mi.codigo_material=ip.cod_insumo 
		LEFT JOIN unidades_medida u ON u.codigo=ip.cod_unidad_medida 
		LEFT JOIN precios pr ON pr.codigo_material=ip.cod_insumo
		WHERE ip.cod_producto IN ($codProductoX) and pr.cod_precio=0 and pr.cod_ciudad=1";
		//echo "<br>".$sql."<br>";
		$resp=mysqli_query($enlaceCon,$sql);
		$costoInsumosX=0;
		$arrayInsumos=array();
		while($dat=mysqli_fetch_assoc($resp)){	
			$codInsumoX=$dat['cod_insumo'];
			$nombreInsumoX=$dat['nombreinsumo'];
			$unidadMedidaX=$dat['nombre'];
			$cantidadInsumoX=$dat['cant'];
			$precioInsumoX=$dat['precio'];
			$costoInsumosX+=$cantidadInsumoX*$precioInsumoX;
			if($cantidadInsumoX==0 || $precioInsumoX==0){
				$obsCosto=1;
			}
			$arrayInsumos[]= array(
				"nombreInsumo" => $nombreInsumoX,
				"unidadMedida" => $unidadMedidaX,
				"cantidadInsumo" => $cantidadInsumoX,
				"precioInsumo" => $precioInsumoX,
				"costoInsumo" => $costoProducto				
			);
		}
		$costoProducto+=$costoInsumosX;
		$arrayProductos[] = array(
			"nombreProducto" => $nombreProductoX,
			"montoVentaProducto"=> $montoVentaX,
			"cantidadProducto" => $cantidadProductoX,
			"detalleInsumos" => $arrayInsumos
		);
		//echo "<br>COSTO: ".$costoInsumosX." CANTIDAD: ".$cantidadProductoX."<br>";
	}
	//DEVOLVEMOS EL ARRAY CON EL COSTO PRODUCTO  EL JSON PARA LISTAR EL DETALLE Y LAS OBSERVACIONES SI HAY PRECIO O CANTIDAD EN 0
	$jsonInsumosProductos = json_encode($arrayProductos);
	$array = array($costoProducto, $jsonInsumosProductos, $obsCosto);
	mysqli_close($enlaceCon); 
	return $array;
} 

function obtenerCostoInsumosProductoUnitario($codProducto, $ciudades) {
	$estilosVenta = 1;
	require("conexionmysqli2.inc");

	error_reporting(E_ALL);
	ini_set('display_errors', '1');


	$codProductoX = $codProducto;
	$costoInsumosX = 0;
	$insumos = [];
	$sql = "SELECT ip.cod_insumo, mi.descripcion_material as nombreinsumo, u.nombre as unidad, ip.cant, pr.precio
			FROM insumos_productos ip 
			LEFT JOIN material_apoyo m ON m.codigo_material=ip.cod_producto 
			LEFT JOIN material_apoyo mi ON mi.codigo_material=ip.cod_insumo 
			LEFT JOIN unidades_medida u ON u.codigo=ip.cod_unidad_medida 
			LEFT JOIN precios pr ON pr.codigo_material=ip.cod_insumo
			WHERE ip.cod_producto IN ($codProductoX) 
			AND pr.cod_precio=0 
			AND pr.cod_ciudad=1";
	$resp = mysqli_query($enlaceCon, $sql);
	while ($dat = mysqli_fetch_assoc($resp)) {	
		$codInsumoX     = $dat['cod_insumo'];
		$nombreInsumoX  = $dat['nombreinsumo'];
		$unidadMedidaX  = $dat['unidad'];
		$cantidadInsumoX = $dat['cant'];
		$precioInsumoX  = $dat['precio'];
		$subtotal       = $cantidadInsumoX * $precioInsumoX;
		$costoInsumosX += $subtotal;
			$insumos[] = [
				'cod_insumo'   => $codInsumoX,
				'nombre'       => $nombreInsumoX,
				'unidad'       => $unidadMedidaX,
				'cantidad'     => $cantidadInsumoX,
				'precio'       => $precioInsumoX,
				'subtotal'     => $subtotal
			];
	}
	mysqli_close($enlaceCon); 
		$json = json_encode([
			'producto' => $codProductoX,
			'costo_total' => $costoInsumosX,
			'insumos' => $insumos
		]);
	$arrayInsumos = array($costoInsumosX, $json);
	return $arrayInsumos;
}


function obtenerCostoInsumosProductoFake($codProducto, $fechaInicio, $fechaFinal, $ciudades) {
	$estilosVenta=1;
	require("conexionmysqli2.inc");

	$jsonInsumosProductos="";
	$arrayProductos = array();
	$indice=1;
	if($indice==1) {
		$codProductoX=$codProducto;
		$nombreProductoX="producto";
		$montoVentaX=1;
		$cantidadProductoX=1;
		
		$sql="SELECT ip.cod_insumo,mi.descripcion_material as nombreinsumo,u.nombre,ip.cant, pr.precio
		FROM insumos_productos ip 
		LEFT JOIN material_apoyo m ON m.codigo_material=ip.cod_producto 
		LEFT JOIN material_apoyo mi ON mi.codigo_material=ip.cod_insumo 
		LEFT JOIN unidades_medida u ON u.codigo=ip.cod_unidad_medida 
		LEFT JOIN precios pr ON pr.codigo_material=ip.cod_insumo
		WHERE ip.cod_producto IN ($codProductoX) and pr.cod_precio=0 and pr.cod_ciudad=1";
		
		//echo "<br>".$sql."<br>";
		
		$resp=mysqli_query($enlaceCon,$sql);
		$costoInsumosX=0;
		$arrayInsumos=array();
		while($dat=mysqli_fetch_assoc($resp)){	
			$codInsumoX=$dat['cod_insumo'];
			$nombreInsumoX=$dat['nombreinsumo'];
			$unidadMedidaX=$dat['nombre'];
			$cantidadInsumoX=$dat['cant'];
			$precioInsumoX=$dat['precio'];
			$costoInsumosX+=$cantidadInsumoX*$precioInsumoX;
			if($cantidadInsumoX==0 || $precioInsumoX==0){
				$obsCosto=1;
			}
			$arrayInsumos[]= array(
				"nombreInsumo" => $nombreInsumoX,
				"unidadMedida" => $unidadMedidaX,
				"cantidadInsumo" => $cantidadInsumoX,
				"precioInsumo" => $precioInsumoX,
				"costoInsumo" => $costoProducto				
			);
		}
		$costoProducto+=$costoInsumosX;
		$arrayProductos[] = array(
			"nombreProducto" => $nombreProductoX,
			"montoVentaProducto"=> $montoVentaX,
			"cantidadProducto" => $cantidadProductoX,
			"detalleInsumos" => $arrayInsumos
		);
		$indice++;
		//echo "<br>COSTO: ".$costoInsumosX." CANTIDAD: ".$cantidadProductoX."<br>";
	}
	//DEVOLVEMOS EL ARRAY CON EL COSTO PRODUCTO  EL JSON PARA LISTAR EL DETALLE Y LAS OBSERVACIONES SI HAY PRECIO O CANTIDAD EN 0
	$jsonInsumosProductos = json_encode($arrayProductos);
	$array = array($costoProducto, $jsonInsumosProductos, $obsCosto);
	mysqli_close($enlaceCon); 
	return $array;
}  

// function obtenerCostoInsumosProducto($codProducto) {
// 	$estilosVenta=1;
// 	require("conexionmysqli2.inc");
// 	$sql="SELECT ip.cod_insumo,mi.descripcion_material as nombreinsumo,u.nombre,ip.cant, pr.precio, m.descripcion_material as nombre_producto
// 	FROM insumos_productos ip 
// 	LEFT JOIN material_apoyo m ON m.codigo_material=ip.cod_producto 
// 	LEFT JOIN material_apoyo mi ON mi.codigo_material=ip.cod_insumo 
// 	LEFT JOIN unidades_medida u ON u.codigo=ip.cod_unidad_medida 
// 	LEFT JOIN precios pr ON pr.codigo_material=ip.cod_insumo
// 	WHERE ip.cod_producto IN ($codProducto) and pr.cod_precio=0;";
// 	$costoProducto=0;
// 	$obsCosto=0;
// 	$json_array = array();
// 	$resp=mysqli_query($enlaceCon,$sql);
// 	$costoInsumoX=0;
// 	while($dat=mysqli_fetch_assoc($resp)){	
// 		$codInsumoX=$dat['cod_insumo'];
// 		$nombreInsumoX=$dat['nombreinsumo'];
// 		$unidadMedidaX=$dat['nombre'];
// 		$cantidadInsumoX=$dat['cant'];
// 		$precioInsumoX=$dat['precio'];
// 		$nombreProductoX=$dat['nombre_producto'];
// 		$costoInsumoX=$cantidadInsumoX*$precioInsumoX;
// 		$costoProducto+=$cantidadInsumoX*$precioInsumoX;
// 		if($cantidadInsumoX==0 || $precioInsumoX==0){
// 			$obsCosto=1;
// 		}
// 		$json_array[] = array(
// 			"nombreInsumo" => $nombreInsumoX,
// 			"nombreProducto" => $nombreProductoX,
// 			"unidadMedida" => $unidadMedidaX,
// 			"cantidadInsumo" => $cantidadInsumoX,
// 			"precioInsumo" => $precioInsumoX,
// 			"costoInsumo" => $costoInsumoX
// 		);
// 	}
// 	$jsonInsumosProductos = json_encode($json_array);
// 	//DEVOLVEMOS EL ARRAY CON EL COSTO PRODUCTO  EL JSON PARA LISTAR EL DETALLE Y LAS OBSERVACIONES SI HAY PRECIO O CANTIDAD EN 0
// 	$array = array($costoProducto, $jsonInsumosProductos, $obsCosto);
// 	mysqli_close($enlaceCon); 
// 	return $array;
// }

function obtenerCostosTotalesUnitariosProducto($codigoProductos, $ciudades){
	$estilosVenta=1;
	require("conexionmysqli2.inc");
	$sql = "SELECT m.codigo_material, m.descripcion_material
	from material_apoyo m 
	LEFT JOIN producto_costo_detalle pcd ON pcd.cod_producto=m.codigo_material 
	LEFT JOIN producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo 
	where m.codigo_material in ($codigoProductos) and pcd.cod_producto is null 
	GROUP BY m.codigo_material";
	$resp = mysqli_query($enlaceCon, $sql);
	$costoInsumos=0;
	$costoProcesos=0;
	while ($datos = mysqli_fetch_array($resp)) {
		$codProductoFinal = $datos[0];
		$nombreItem = $datos[1];
		$arrayInsumos = obtenerCostoInsumosProductoUnitario($codProductoFinal, $rptTerritorioString);
		$costoInsumos += $arrayInsumos[0];

		$costoProcesos += obtenerCostoProcesosUnitario($codProductoFinal);
	}
	return ($costoInsumos + $costoProcesos);
}

function obtenerCostoInsumosGrupo($codGrupo, $fechaInicio, $fechaFinal, $ciudades) {
	$estilosVenta=1;
	require("conexionmysqli2.inc");
	
	$sqlGrupos="SELECT m.codigo_material, m.descripcion_material,
		(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total
		from salida_almacenes s
		INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
		INNER JOIN material_apoyo m ON m.codigo_material=sd.cod_material
		INNER JOIN producto_costo_detalle pcd ON pcd.cod_producto=sd.cod_material
		INNER JOIN producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
		where s.`fecha` BETWEEN '$fechaInicio' and '$fechaFinal'
		and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and pcd.cod_producto_costo='$codGrupo' and 
		s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($ciudades) )
		group by m.codigo_material 

		limit 0,1";
	//echo $sqlGrupos."<br>";
	$respGrupos=mysqli_query($enlaceCon,$sqlGrupos);

	$costoProducto=0;
	$obsCosto=0;
	$jsonInsumosProductos="";
	$arrayProductos = array();
	while($datGrupos=mysqli_fetch_array($respGrupos)) {
		$codProductoX=$datGrupos[0];
		$nombreProductoX=$datGrupos[1];
		$montoVentaX=$datGrupos[2];
		$cantidadProductoX=$datGrupos[3];
		
		$sql="SELECT ip.cod_insumo,mi.descripcion_material as nombreinsumo,u.nombre,ip.cant, pr.precio
		FROM insumos_productos ip 
		LEFT JOIN material_apoyo m ON m.codigo_material=ip.cod_producto 
		LEFT JOIN material_apoyo mi ON mi.codigo_material=ip.cod_insumo 
		LEFT JOIN unidades_medida u ON u.codigo=ip.cod_unidad_medida 
		LEFT JOIN precios pr ON pr.codigo_material=ip.cod_insumo
		WHERE ip.cod_producto IN ($codProductoX) and pr.cod_precio=0 and pr.cod_ciudad=1";
		//echo "<br>".$sql."<br>";
		$resp=mysqli_query($enlaceCon,$sql);
		$costoInsumosX=0;
		$arrayInsumos=array();
		while($dat=mysqli_fetch_assoc($resp)){	
			$codInsumoX=$dat['cod_insumo'];
			$nombreInsumoX=$dat['nombreinsumo'];
			$unidadMedidaX=$dat['nombre'];
			$cantidadInsumoX=$dat['cant'];
			$precioInsumoX=$dat['precio'];
			$costoInsumosX+=$cantidadInsumoX*$precioInsumoX;
			if($cantidadInsumoX==0 || $precioInsumoX==0){
				$obsCosto=1;
			}
			$arrayInsumos[]= array(
				"nombreInsumo" => $nombreInsumoX,
				"unidadMedida" => $unidadMedidaX,
				"cantidadInsumo" => $cantidadInsumoX,
				"precioInsumo" => $precioInsumoX,
				"costoInsumo" => $costoProducto				
			);
		}
		$costoProducto+=($costoInsumosX*$cantidadProductoX);
		$arrayProductos[] = array(
			"nombreProducto" => $nombreProductoX,
			"montoVentaProducto"=> $montoVentaX,
			"cantidadProducto" => $cantidadProductoX,
			"detalleInsumos" => $arrayInsumos
		);
		//echo "<br>COSTO: ".$costoInsumosX." CANTIDAD: ".$cantidadProductoX."<br>";
	}
	//DEVOLVEMOS EL ARRAY CON EL COSTO PRODUCTO  EL JSON PARA LISTAR EL DETALLE Y LAS OBSERVACIONES SI HAY PRECIO O CANTIDAD EN 0
	$jsonInsumosProductos = json_encode($arrayProductos);
	$array = array($costoProducto, $jsonInsumosProductos, $obsCosto);
	mysqli_close($enlaceCon); 
	return $array;
}

function generarTablaHTML($json) {
    $datos = json_decode($json, true);
    // Verificar si el JSON se decodificó correctamente
    if ($datos === null) {
        return 'Error: JSON no válido.';
    }
    // Inicializar la tabla HTML
    $tablaHTML = '<center><table class=\"texto\" width=\"100%\">';
    // Agregar encabezados de columna
    $tablaHTML .= '<thead><tr>';
    foreach ($datos[0] as $clave => $valor) {
        $tablaHTML .= '<th>' . htmlspecialchars($clave) . '</th>';
    }
    $tablaHTML .= '</tr></thead>';
    // Agregar filas de datos
    $tablaHTML .= '<tbody>';
    foreach ($datos as $fila) {
        $tablaHTML .= '<tr>';
        foreach ($fila as $valor) {
            $tablaHTML .= '<td>' . htmlspecialchars($valor) . '</td>';
        }
        $tablaHTML .= '</tr>';
    }
    $tablaHTML .= '</tbody>';
    // Cerrar la tabla HTML
    $tablaHTML .= '</table></center>';
    // Devolver la tabla HTML generada
    return $tablaHTML;
}

function obtenerCostoProcesos($codProducto, $fechaInicio, $fechaFin) {
	require("conexionmysqlipdf.inc");
	
	$sql="SELECT s.fecha, a.nombre_almacen, m.codigo_material, m.descripcion_material, sd.cantidad_unitaria, id.lote
	from salida_almacenes s
	INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
	LEFT JOIN ingreso_detalle_almacenes id ON id.cod_ingreso_almacen=sd.cod_ingreso_almacen and id.cod_material=sd.cod_material
	LEFT JOIN ingreso_almacenes i ON i.cod_ingreso_almacen=id.cod_ingreso_almacen
	LEFT JOIN almacenes a ON a.cod_almacen=s.cod_almacen 
	INNER JOIN material_apoyo m ON m.codigo_material=sd.cod_material
	WHERE s.salida_anulada=1 and s.cod_tiposalida=1001 and sd.cod_material='$codProducto' and s.fecha between '$fechaInicio' and '$fechaFin'	

	limit 0,1";
	//echo $sql."<br><br>";
	$costoProcesoTotal=0;
	$obsCosto=0;
	$json_array = array();
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_assoc($resp)){	
		$fechaX=$dat["fecha"];
		$nombreAlmacenX=$dat["nombre_almacen"];
		$codigoProductoX=$dat["codigo_material"];
		$nombreProductoX=$dat["descripcion_material"];
		$cantidadProductoX=$dat["cantidad_unitaria"];
		$loteProductoX=$dat["lote"];
		if($loteProductoX==0 || $loteProductoX==''){
			/*Cuando la obs es 2 es cuando no tiene salidas por lote y tomamos un lote generico*/ 
			$obsCosto=2;
		}

		$arrayCostoProceso=obtenerCostoProcesoLote($codProducto,$loteProductoX);
		list($costoSubProceso, $jsonSubProceso, $banderaObsProcesos) = $arrayCostoProceso;
		if($banderaObsProcesos==1){
			$obsCosto=1;
		}
		$costoProcesoTotal=$costoProcesoTotal+$costoSubProceso;

		$json_array[]= array(
			"nombreAlmacen" => $nombreAlmacenX,
			"fecha" => $fechaX,
			"codigoProducto" => $codigoProductoX,
			"nombreProducto" => $nombreProductoX,
			"cantidadProducto" => $cantidadProductoX,
			"loteProducto" => $loteProductoX,
			"detalleProcesos" => $jsonSubProceso			
		);
	}
	$jsonProcesos = json_encode($json_array);
	//DEVOLVEMOS EL ARRAY CON EL COSTO PRODUCTO  EL JSON PARA LISTAR EL DETALLE Y LAS OBSERVACIONES SI HAY PRECIO O CANTIDAD EN 0
	$array = array($costoProcesoTotal, $jsonProcesos, $obsCosto);
	mysqli_close($enlaceCon); 
	return $array;
}


function obtenerCostoProcesosUnitarioBase($codProducto) {
	require("conexionmysqlipdf.inc");
	$sqlCostoProceso="SELECT sum(pcp.costo_proceso_const) from procesos_construccion pc, procesos_construccion_producto pcp
		where pc.cod_proceso_const=pcp.cod_proceso_const and pcp.cod_producto=$codProducto";
	$respCostoProceso=mysqli_query($enlaceCon, $sqlCostoProceso);

	$costoProducto=0;
	while($datCostoProceso=mysqli_fetch_array($respCostoProceso)){	
		$costoProducto+=$datCostoProceso[0];
	}
	return $costoProducto;
}

function obtenerCostoProcesosUnitarioBaseJson($codProducto) {
    require("conexionmysqlipdf.inc");
    $sqlCostoProceso = "
        SELECT pc.nombre_proceso_const, pcp.costo_proceso_const
        FROM procesos_construccion pc
        JOIN procesos_construccion_producto pcp ON pc.cod_proceso_const = pcp.cod_proceso_const
        WHERE pcp.cod_producto = $codProducto
    ";
    $respCostoProceso = mysqli_query($enlaceCon, $sqlCostoProceso);
    if (!$respCostoProceso) {
        // En caso de error en la consulta, devolver un JSON con el mensaje de error
        return json_encode(["error" => "Error en la consulta: " . mysqli_error($enlaceCon)]);
    }
    $procesos = [];
    while ($fila = mysqli_fetch_assoc($respCostoProceso)) {
        $procesos[] = [
            "nombre_proceso" => $fila['nombre_proceso_const'],
            "costo_proceso" => (float)$fila['costo_proceso_const']
        ];
    }
    mysqli_close($enlaceCon);
    return json_encode($procesos);
}

function obtenerCostoProcesosUnitario($codProducto) {
	require("conexionmysqlipdf.inc");
	
	$sqlLoteX="SELECT l.codigo_material, l.cod_lote from lotes_produccion l where l.codigo_material=$codProducto;";
	$respLoteX=mysqli_query($enlaceCon, $sqlLoteX);

	$costoProcesoTotal=0;
	$obsCosto=0;
	$json_array = array();
	$resp=mysqli_query($enlaceCon,$sql);
	$indice=1;
	if($datLoteX=mysqli_fetch_array($respLoteX)){	
		$fechaX="2024-01-01";
		$nombreAlmacenX="Principal";
		$codigoProductoX=$$datLoteX[0];
		$nombreProductoX="nombrePRod";
		$cantidadProductoX="1";
		$loteProductoX=$datLoteX[1];
		if($loteProductoX==0 || $loteProductoX==''){
			/*Cuando la obs es 2 es cuando no tiene salidas por lote y tomamos un lote generico*/ 
			$obsCosto=2;
		}

		$arrayCostoProceso=obtenerCostoProcesoLote($codProducto,$loteProductoX);
		list($costoSubProceso, $jsonSubProceso, $banderaObsProcesos) = $arrayCostoProceso;
		if($banderaObsProcesos==1){
			$obsCosto=1;
		}
		$costoProcesoTotal=$costoProcesoTotal+$costoSubProceso;
	}
	mysqli_close($enlaceCon); 
	return $costoProcesoTotal;
}

function obtenerCostoProcesosFake($codProducto, $fechaInicio, $fechaFin) {
	require("conexionmysqlipdf.inc");
	
	$sqlLoteX="SELECT l.codigo_material, l.cod_lote from lotes_produccion l where l.codigo_material=$codProducto;";
	$respLoteX=mysqli_query($enlaceCon, $sqlLoteX);

	$costoProcesoTotal=0;
	$obsCosto=0;
	$json_array = array();
	$resp=mysqli_query($enlaceCon,$sql);
	$indice=1;
	if($datLoteX=mysqli_fetch_array($respLoteX)){	
		$fechaX="2024-01-01";
		$nombreAlmacenX="Principal";
		$codigoProductoX=$$datLoteX[0];
		$nombreProductoX="nombrePRod";
		$cantidadProductoX="1";
		$loteProductoX=$datLoteX[1];
		if($loteProductoX==0 || $loteProductoX==''){
			/*Cuando la obs es 2 es cuando no tiene salidas por lote y tomamos un lote generico*/ 
			$obsCosto=2;
		}

		$arrayCostoProceso=obtenerCostoProcesoLote($codProducto,$loteProductoX);
		list($costoSubProceso, $jsonSubProceso, $banderaObsProcesos) = $arrayCostoProceso;
		if($banderaObsProcesos==1){
			$obsCosto=1;
		}
		$costoProcesoTotal=$costoProcesoTotal+$costoSubProceso;

		$json_array[]= array(
			"nombreAlmacen" => $nombreAlmacenX,
			"fecha" => $fechaX,
			"codigoProducto" => $codigoProductoX,
			"nombreProducto" => $nombreProductoX,
			"cantidadProducto" => $cantidadProductoX,
			"loteProducto" => $loteProductoX,
			"detalleProcesos" => $jsonSubProceso			
		);
		$indice++;
	}
	$jsonProcesos = json_encode($json_array);
	//DEVOLVEMOS EL ARRAY CON EL COSTO PRODUCTO  EL JSON PARA LISTAR EL DETALLE Y LAS OBSERVACIONES SI HAY PRECIO O CANTIDAD EN 0
	$array = array($costoProcesoTotal, $jsonProcesos, $obsCosto);
	mysqli_close($enlaceCon); 
	return $array;
}

function obtenerCostoProcesosGrupo($codGrupo, $fechaInicio, $fechaFinal, $ciudades) {
	require("conexionmysqlipdf.inc");

	$sqlGrupos="SELECT m.codigo_material, m.descripcion_material,
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total
	from salida_almacenes s
	INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
	INNER JOIN material_apoyo m ON m.codigo_material=sd.cod_material
	INNER JOIN producto_costo_detalle pcd ON pcd.cod_producto=sd.cod_material
	INNER JOIN producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
	where s.`fecha` BETWEEN '$fechaInicio' and '$fechaFinal'
	and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and pcd.cod_producto_costo='$codGrupo' and 
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($ciudades) )
	group by m.codigo_material 
	limit 0,1";
	//echo "<br>".$sqlGrupos."<br>";
	$respGrupos=mysqli_query($enlaceCon,$sqlGrupos);
	$costoProductoProcesos=0;
	$obsCosto=0;
	$jsonInsumosProductos="";
	$json_array = array();
	while($datGrupos=mysqli_fetch_array($respGrupos)) {
		$codProductoX=$datGrupos[0];
		$cantidadProductoX=$datGrupos[3];

		$sql="SELECT s.fecha, a.nombre_almacen, m.codigo_material, m.descripcion_material, sd.cantidad_unitaria, id.lote
		from salida_almacenes s
		INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
		LEFT JOIN ingreso_detalle_almacenes id ON id.cod_ingreso_almacen=sd.cod_ingreso_almacen and id.cod_material=sd.cod_material
		LEFT JOIN ingreso_almacenes i ON i.cod_ingreso_almacen=id.cod_ingreso_almacen
		LEFT JOIN almacenes a ON a.cod_almacen=s.cod_almacen 
		INNER JOIN material_apoyo m ON m.codigo_material=sd.cod_material
		WHERE s.salida_anulada=1 and s.cod_tiposalida=1001 and sd.cod_material='$codProductoX' and s.fecha between '$fechaInicio' and '$fechaFinal' 
		limit 0,1";

		$costoProcesoTotal=0;
		$obsCosto=0;
		$resp=mysqli_query($enlaceCon,$sql);
		while($dat=mysqli_fetch_assoc($resp)){	
			$fechaX=$dat["fecha"];
			$nombreAlmacenX=$dat["nombre_almacen"];
			$codigoProductoX=$dat["codigo_material"];
			$nombreProductoX=$dat["descripcion_material"];
			$cantidadProductoX=$dat["cantidad_unitaria"];
			$loteProductoX=$dat["lote"];
			if($loteProductoX==0 || $loteProductoX==''){
				/*Cuando la obs es 2 es cuando no tiene salidas por lote y tomamos un lote generico*/ 
				$obsCosto=2;
			}
			$arrayCostoProceso=obtenerCostoProcesoLote($codProductoX,$loteProductoX);
			list($costoSubProceso, $jsonSubProceso, $banderaObsProcesos) = $arrayCostoProceso;
			if($banderaObsProcesos==1){
				$obsCosto=1;
			}

			$json_array[]= array(
				"nombreAlmacen" => $nombreAlmacenX,
				"fecha" => $fechaX,
				"codigoProducto" => $codigoProductoX,
				"nombreProducto" => $nombreProductoX,
				"cantidadProducto" => $cantidadProductoX,
				"loteProducto" => $loteProductoX,
				"detalleProcesos" => $jsonSubProceso			
			);
			
			$costoProcesoTotal=$costoProcesoTotal+$costoSubProceso;
		}	
		$costoProductoProcesos=$costoProductoProcesos + ($costoProcesoTotal*$cantidadProductoX);
	}
	$jsonProcesos = json_encode($json_array);
	$array = array($costoProductoProcesos, $jsonProcesos, $obsCosto);
	mysqli_close($enlaceCon); 
	return $array;
}



function obtenerCostoProcesoLote($codProducto,$loteProducto) {
	require("conexionmysqlipdf.inc");
	$codigoLoteProducto=0;
	/* 
	TEMPORALMENTE 
	*/
	//	$loteProducto=0;
	
	if($loteProducto==0 || $loteProducto==''){
		$sqlCodLote="select max(l.cod_lote)as lote from lotes_produccion l where l.codigo_material='$codProducto'";
		//echo $sqlCodLote."<br>";
		$respCodLote=mysqli_query($enlaceCon,$sqlCodLote);
		while($datCodLote=mysqli_fetch_assoc($respCodLote)){
			$codigoLoteProducto=$datCodLote['lote'];
		}
	}else{
		$codigoLoteProducto=$loteProducto;
	}

	$sqlProcesos="SELECT l.cod_lote, l.nro_lote, lpc.cod_proceso_const, pc.nombre_proceso_const, 1 as cantidad, lpc.precio  
	from lotes_produccion l
	INNER JOIN lote_procesoconst lpc ON lpc.cod_lote=l.cod_lote
	LEFT JOIN proveedores p ON p.cod_proveedor=lpc.cod_proveedor
	LEFT JOIN procesos_construccion pc ON pc.cod_proceso_const=lpc.cod_proceso_const
	where l.codigo_material='$codProducto' and l.cod_lote='$codigoLoteProducto'";
	
	//echo $sqlProcesos."<br>";	
	
	$costoProceso=0;
	$obsCosto=0;
	$json_array = array();
	$respProcesos=mysqli_query($enlaceCon,$sqlProcesos);
	while($datProcesos=mysqli_fetch_assoc($respProcesos)){	
		$codLoteX=$datProcesos['cod_lote'];
		$nroLoteX=$datProcesos['nro_lote'];
		$codProcesoX=$datProcesos['cod_proceso_const'];
		$nombreProcesoX=$datProcesos['nombre_proceso_const'];
		$cantidadX=$datProcesos['cantidad'];
		
		//Para el calculo de los costos de proceso tomamos cantidad 1 porque es unitario y no por Lote
		$cantidadX=1;
		
		$precioX=$datProcesos['precio'];
		if($cantidadX==0 || $precioX==0){
			$obsCosto=1;
		}
		$costoProceso=$costoProceso + ($cantidadX*$precioX);
		$json_array[] = $datProcesos;
	}
	$jsonProcesos = json_encode($json_array);
	//DEVOLVEMOS EL ARRAY CON EL COSTO PRODUCTO  EL JSON PARA LISTAR EL DETALLE Y LAS OBSERVACIONES SI HAY PRECIO O CANTIDAD EN 0
	$array = array($costoProceso, $jsonProcesos, $obsCosto);
	mysqli_close($enlaceCon); 
	return $array;
}

function montoVentasSucursal($sucursal,$desde,$hasta){
	require("conexionmysqlipdf.inc");
	$sql="SELECT sd.cod_material, (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total
	from `salida_almacenes` s, `salida_detalle_almacenes` sd
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$desde' and '$hasta'
	and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and  
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($sucursal) )
	group by sd.cod_material";
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
  	$montoTotal=0;		
  	$cantidadTotal=0;		
  	while($datos=mysqli_fetch_array($resp)){		
		$codigoProducto=$datos[0];
		$montoVentaProducto=$datos[1];
		$cantidad=$datos[2];
		$descuentoVenta=$datos[3];
		$montoNota=$datos[4];	
		if($descuentoVenta>0){
			$porcentajeVentaProd=($montoVentaProducto/$montoNota);
			$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
			$montoVentaProducto=$montoVentaProducto-$descuentoAdiProducto;
		}
		$montoTotal=$montoTotal+$montoVentaProducto;
		$cantidadTotal += $cantidad;
	}	  
  	mysqli_close($enlaceCon);
  	$arrayMontosCantidadesTotales[0]=$montoTotal;
  	$arrayMontosCantidadesTotales[1]=$cantidadTotal;

  	return $arrayMontosCantidadesTotales;
}

function montoVentasSucursalExcepciones($sucursal,$desde,$hasta){
	require("conexionmysqlipdf.inc");
	$sql = "SELECT pc.cod_producto_costo, (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, 
		s.monto_total, 'GRUPO' as tipoagrupacion, pc.costo_si_no
	   from salida_almacenes s
	   INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
	   INNER JOIN producto_costo_detalle pcd ON pcd.cod_producto=sd.cod_material
	   INNER JOIN producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
	   where s.`fecha` BETWEEN '$desde' and '$hasta'
	   and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and  
	   s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($sucursal) )
		and pc.costo_si_no = 1	
		GROUP BY pc.cod_producto_costo
	   UNION 
	   SELECT m.codigo_material,
	   (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total,'PRODUCTO'  as tipoagrupacion, m.costo_si_no
	   from salida_almacenes s
	   INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
	   INNER JOIN material_apoyo m ON sd.cod_material=m.codigo_material
	   LEFT JOIN producto_costo_detalle pcd ON pcd.cod_producto=m.codigo_material
	   LEFT JOIN  producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
	   where s.`fecha` BETWEEN '$desde' and '$hasta'
	   and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and  
	   s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($sucursal) ) and 
	   pcd.cod_producto is null 
	   and m.costo_si_no=1 
    	GROUP BY m.codigo_material
    	order by montoVenta desc";
   //echo $sql;
   $resp = mysqli_query($enlaceCon, $sql);
   $montoVenta=0;
   while($dat=mysqli_fetch_array($resp)){
   	$montoVenta+=$dat[1];
   }
   mysqli_close($enlaceCon);
   return $montoVenta;
}

function montoCostosInsumosProcesosTotal($rptTerritorioString,$fecha_iniconsulta,$fecha_finconsulta){
	require("conexionmysqlipdf.inc");
	$sql = "SELECT pc.cod_producto_costo, pc.nombre_producto_costo,
	    (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total, 'GRUPO' as tipoagrupacion, pc.costo_si_no
	    from salida_almacenes s
	    INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
	    INNER JOIN producto_costo_detalle pcd ON pcd.cod_producto=sd.cod_material
	    INNER JOIN producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
	    where s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	    and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and  
	    s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($rptTerritorioString) )";
	$sql.=" GROUP BY pc.cod_producto_costo
	    UNION 
	    SELECT m.codigo_material, m.descripcion_material,
	    (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total,'PRODUCTO'  as tipoagrupacion, m.costo_si_no
	    from salida_almacenes s
	    INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
	    INNER JOIN material_apoyo m ON sd.cod_material=m.codigo_material
	    LEFT JOIN producto_costo_detalle pcd ON pcd.cod_producto=m.codigo_material
	    LEFT JOIN  producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
	    where s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	    and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and  
	    s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($rptTerritorioString) ) and 
	    pcd.cod_producto is null "; 
	    $sql.=" GROUP BY m.codigo_material
	    order by montoVenta desc";
	$totalmontoInsumosReporte=0;
	$totalmontoProcesosReporte=0;
	$resp = mysqli_query($enlaceCon, $sql);
	while ($datos = mysqli_fetch_array($resp)) {
		$codProductoFinal = $datos[0];
		$cantidad = $datos[3];
		$tipoAgrupacion = $datos[6];

		/*INSUMOS*/
		if ($tipoAgrupacion == 'GRUPO') {
			$arrayCostoInsumos = obtenerCostoInsumosGrupo($codProductoFinal, $fecha_iniconsulta, $fecha_finconsulta, $rptTerritorioString);
			list($costoInsumos, $jsonInsumos, $banderaObsInsumos) = $arrayCostoInsumos;
			$totalmontoInsumosReporte += ($costoInsumos);
		} else {
			$arrayCostoInsumos = obtenerCostoInsumosProducto($codProductoFinal, $fecha_iniconsulta, $fecha_finconsulta, $rptTerritorioString);
			list($costoInsumos, $jsonInsumos, $banderaObsInsumos) = $arrayCostoInsumos;
			$totalmontoInsumosReporte += ($costoInsumos * $cantidad);
		}
		/*FIN INSUMOS*/
		
		/* INICIO PROCESOS*/
		if ($tipoAgrupacion == 'GRUPO') {
			$arrayCostoProcesos = obtenerCostoProcesosGrupo($codProductoFinal, $fecha_iniconsulta, $fecha_finconsulta, $rptTerritorioString);
			list($costoProcesos, $jsonProcesos, $banderaObsProcesos) = $arrayCostoProcesos;
		} else {
			$arrayCostoProcesos = obtenerCostoProcesos($codProductoFinal, $fecha_iniconsulta, $fecha_finconsulta);
			list($costoProcesos, $jsonProcesos, $banderaObsProcesos) = $arrayCostoProcesos;
		}
		$totalmontoProcesosReporte += $costoProcesos * $cantidad;		
		/* FIN PROCESOS*/
	}
	$arrayTotalesCostos[0]=$totalmontoInsumosReporte;
	$arrayTotalesCostos[1]=$totalmontoProcesosReporte;

	return ($arrayTotalesCostos);
}




function preciosVentaDistintos($sucursales, $codProducto, $fechaIni, $fechaFin){
	require("conexionmysqlipdf.inc");
	/*ESTA PARTE SACA LOS PRECIOS DISTINTOS QUE HAY EN EL SISTEMA*/
	$sqlPrecios="";
	if($tipoAgrupacion == 'GRUPO'){
		$sqlPrecios="SELECT pc.cod_producto_costo, sum(sd.cantidad_unitaria), sd.precio_unitario
	    from salida_almacenes s
	    INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
	    INNER JOIN producto_costo_detalle pcd ON pcd.cod_producto=sd.cod_material
	    INNER JOIN producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
	    where s.`fecha` BETWEEN '$fechaIni' and '$fechaFin'
	    and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and  
	    s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($sucursales) ) and pc.cod_producto_costo='$codProducto' ";
		$sqlPrecios.=" GROUP BY pc.cod_producto_costo, sd.precio_unitario";
	}else{
		$sqlPrecios="SELECT m.codigo_material, sum(sd.cantidad_unitaria), sd.precio_unitario
	    from salida_almacenes s
	    INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
	    INNER JOIN material_apoyo m ON sd.cod_material=m.codigo_material
	    LEFT JOIN producto_costo_detalle pcd ON pcd.cod_producto=m.codigo_material
	    LEFT JOIN  producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
	    where s.`fecha` BETWEEN '$fechaIni' and '$fechaFin'
	    and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and  
	    s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($sucursales) ) and 
	    pcd.cod_producto is null and m.codigo_material='$codProducto'"; 
    	$sqlPrecios.=" GROUP BY m.codigo_material, sd.precio_unitario ";

	}
	$respPrecios=mysqli_query($enlaceCon, $sqlPrecios);
	$titlePreciosDetalle="";
	while($datPrecios=mysqli_fetch_array($respPrecios)){
		$titlePreciosDetalle.="Cant. ".$datPrecios[1]." Precio: ".$datPrecios[2]."\n";
	}
	return ($titlePreciosDetalle);
	/*FIN PRECIOS*/
}

function preciosMayorProducto($sucursales, $codProducto, $fechaIni, $fechaFin, $tipoAgrupacion){
	require("conexionmysqlipdf.inc");
	/*ESTA PARTE SACA LOS PRECIOS DISTINTOS QUE HAY EN EL SISTEMA*/
	$sqlPrecios="";
	if($tipoAgrupacion == 'GRUPO'){
		$sqlPrecios="SELECT pc.cod_producto_costo, sum(sd.cantidad_unitaria), sd.precio_unitario
	    from salida_almacenes s
	    INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
	    INNER JOIN producto_costo_detalle pcd ON pcd.cod_producto=sd.cod_material
	    INNER JOIN producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
	    where s.`fecha` BETWEEN '$fechaIni' and '$fechaFin'
	    and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and  
	    s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($sucursales) ) and pc.cod_producto_costo='$codProducto' ";
		$sqlPrecios.=" GROUP BY pc.cod_producto_costo, sd.precio_unitario";
	}else{
		$sqlPrecios="SELECT m.codigo_material, sum(sd.cantidad_unitaria), sd.precio_unitario
	    from salida_almacenes s
	    INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
	    INNER JOIN material_apoyo m ON sd.cod_material=m.codigo_material
	    LEFT JOIN producto_costo_detalle pcd ON pcd.cod_producto=m.codigo_material
	    LEFT JOIN  producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
	    where s.`fecha` BETWEEN '$fechaIni' and '$fechaFin'
	    and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and  
	    s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($sucursales) ) and 
	    pcd.cod_producto is null and m.codigo_material='$codProducto'"; 
    	$sqlPrecios.=" GROUP BY m.codigo_material, sd.precio_unitario ";

	}
	$respPrecios=mysqli_query($enlaceCon, $sqlPrecios);
	$titlePreciosDetalle="";
	$precioMayor=0;
	$cantidadMayor=0;
	while($datPrecios=mysqli_fetch_array($respPrecios)){
		if($datPrecios[1]>$cantidadMayor){
			$cantidadMayor=$datPrecios[1];
			$precioMayor=$datPrecios[2];
		}
	}
	return ($precioMayor);
	/*FIN PRECIOS*/
}

function obtenerDetalleGastos($sucursal,$desde,$hasta){
	require("conexionmysqlipdf.inc");
	$sql="SELECT g.fecha_gasto, g.descripcion_gasto, gg.nombre_grupogasto, g.monto from gastos g
	INNER JOIN tipos_gasto tg ON tg.cod_tipogasto=g.cod_tipogasto
	INNER JOIN grupos_gasto gg ON gg.cod_grupogasto=g.cod_grupogasto
	where g.cod_ciudad in ($sucursal) and g.fecha_gasto BETWEEN '$desde' and '$hasta' 
	and g.gasto_anulado=1";
	$resp=mysqli_query($enlaceCon,$sql);
  	$montoTotalGastos=0;				
  	while($datos=mysqli_fetch_array($resp)){		
		$fechaGastoX=$datos[0];
		$descripcionGastoX=$datos[1];
		$grupoGasto=$datos[2];
		$montoGasto=$datos[3];
		$montoTotalGastos=$montoTotalGastos+$montoGasto;
		$json_array[] = $datos;
	}	
	$jsonGastos = json_encode($json_array);
	$array = array($montoTotalGastos, $jsonGastos);  
  	mysqli_close($enlaceCon);
  	return $array;
}

function obtenerDetalleGastosMensuales($sucursal,$desde,$hasta){
	require("conexionmysqlipdf.inc");
	$sql="SELECT g.fecha_gasto, g.descripcion_gasto, gg.nombre_grupogasto, g.monto from gastos g
	INNER JOIN tipos_gasto tg ON tg.cod_tipogasto=g.cod_tipogasto
	INNER JOIN grupos_gasto gg ON gg.cod_grupogasto=g.cod_grupogasto
	where g.cod_ciudad in ($sucursal) and g.fecha_gasto BETWEEN '$desde' and '$hasta' 
	and g.gasto_anulado=1 and g.cod_tipo_distribucion_costo in (1);";
	$resp=mysqli_query($enlaceCon,$sql);
  	$montoTotalGastos=0;				
  	while($datos=mysqli_fetch_array($resp)){		
		$fechaGastoX=$datos[0];
		$descripcionGastoX=$datos[1];
		$grupoGasto=$datos[2];
		$montoGasto=$datos[3];
		$montoTotalGastos=$montoTotalGastos+$montoGasto;
		$json_array[] = $datos;
	}	
	$jsonGastos = json_encode($json_array);
	$array = array($montoTotalGastos, $jsonGastos);  
  	mysqli_close($enlaceCon);
  	return $array;
}


function generarTablaProcesos($json) {
    $datos = json_decode($json, true);
    if ($datos === null) {
        return 'Error: JSON no válido.';
    }
    $tablaHTML = '<center><table class=\"texto\" width=\"100%\">';
    $tablaHTML .= '<thead><tr>';

	$tablaHTML .= '<th>Fecha</th>';
	$tablaHTML .= '<th>Sucursal</th>';
	$tablaHTML .= '<th>Codigo</th>';
	$tablaHTML .= '<th>Producto</th>';
	$tablaHTML .= '<th>Cantidad</th>';
	$tablaHTML .= '<th>Lote</th>';
	$tablaHTML .= '<th>Procesos</th>';

	$tablaHTML .= '</tr></thead>';

	$tablaHTML .= '<tbody>';
    foreach ($datos as $fila) {
        $tablaHTML .= '<tr>';
        foreach ($fila as $valor) {
            $tablaHTML .= '<td>' . htmlspecialchars($valor) . '</td>';
        }
        $tablaHTML .= '</tr>';
    }
    $tablaHTML .= '</tbody>';
    $tablaHTML .= '</table></center>';

    return $tablaHTML;
}


function generarTablaGenerica($json) {
    // Decodificar el JSON
    $data = json_decode($json, true);
    
    // Iniciar la salida de la tabla HTML
    $html = '<table border="1" cellpadding="10" cellspacing="0">';
    
    // Encabezados de la tabla
    $html .= '<tr>
                <th>Producto</th>
                <th>Monto</th>
                <th>Cantidad</th>
                <th>Detalle</th>
              </tr>';
    
    // Recorrer cada producto
    foreach ($data as $producto) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($producto['nombreProducto']) . '</td>';
        $html .= '<td>' . htmlspecialchars($producto['montoVentaProducto']) . '</td>';
        $html .= '<td>' . htmlspecialchars($producto['cantidadProducto']) . '</td>';
        
        // Detalle de Insumos
        $html .= '<td><table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<tr>
                    <th>Nombre del Insumo</th>
                    <th>Unidad de Medida</th>
                    <th>Cantidad de Insumo</th>
                    <th>Precio del Insumo</th>
                    <th>Costo del Insumo</th>
                  </tr>';
        
        foreach ($producto['detalleInsumos'] as $insumo) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($insumo['nombreInsumo']) . '</td>';
            $html .= '<td>' . htmlspecialchars($insumo['unidadMedida']) . '</td>';
            $html .= '<td>' . htmlspecialchars($insumo['cantidadInsumo']) . '</td>';
            $html .= '<td>' . htmlspecialchars($insumo['precioInsumo']) . '</td>';
            $html .= '<td>' . htmlspecialchars($insumo['costoInsumo']) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table></td>';
        $html .= '</tr>';
    }
    
    // Cerrar la tabla HTML
    $html .= '</table>';
    
    // Devolver la tabla HTML generada
    return $html;
}

function generarTablaGenerica2($jsonString) {
    // Decodificar el JSON
    $data = json_decode($jsonString, true);

    // Verificar si el JSON es válido
    if (json_last_error() !== JSON_ERROR_NONE) {
        return "Error al decodificar JSON: " . json_last_error_msg();
    }

    // Iniciar la tabla HTML
    $html = '<table border="1" cellpadding="5" cellspacing="0">';
    $html .= '<thead>
                <tr>
                    <th>Nombre Almacén</th>
                    <th>Fecha</th>
                    <th>Código Producto</th>
                    <th>Nombre Producto</th>
                    <th>Cantidad Producto</th>
                    <th>Lote Producto</th>
                    <th>Detalle de Procesos</th>
                </tr>
              </thead><tbody>';

    // Iterar sobre cada almacén en el JSON
    foreach ($data as $almacen) {
        // Decodificar el JSON del detalle de procesos
        $detalleProcesos = json_decode($almacen['detalleProcesos'], true);
        
        // Verificar si el detalle de procesos es válido
        if (json_last_error() !== JSON_ERROR_NONE) {
            return "Error al decodificar detalle de procesos JSON: " . json_last_error_msg();
        }

        // Añadir una fila para los datos principales
        $html .= '<tr>
                    <td>' . htmlspecialchars($almacen['nombreAlmacen']) . '</td>
                    <td>' . htmlspecialchars($almacen['fecha']) . '</td>
                    <td>' . htmlspecialchars($almacen['codigoProducto']) . '</td>
                    <td>' . htmlspecialchars($almacen['nombreProducto']) . '</td>
                    <td>' . htmlspecialchars($almacen['cantidadProducto']) . '</td>
                    <td>' . htmlspecialchars($almacen['loteProducto']) . '</td>
                    <td>
                        <table border="1" cellpadding="3" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Código Lote</th>
                                    <th>Número Lote</th>
                                    <th>Código Proceso</th>
                                    <th>Nombre Proceso</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>';
        
        // Añadir filas para el detalle de procesos
        foreach ($detalleProcesos as $proceso) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($proceso['cod_lote']) . '</td>
                        <td>' . htmlspecialchars($proceso['nro_lote']) . '</td>
                        <td>' . htmlspecialchars($proceso['cod_proceso_const']) . '</td>
                        <td>' . htmlspecialchars($proceso['nombre_proceso_const']) . '</td>
                        <td>' . htmlspecialchars($proceso['cantidad']) . '</td>
                        <td>' . htmlspecialchars($proceso['precio']) . '</td>
                      </tr>';
        }

        $html .= '      </tbody>
                        </table>
                    </td>
                </tr>';
    }

    // Cerrar la tabla HTML
    $html .= '</tbody></table>';

    // Devolver el HTML generado
    return $html;
}


?>