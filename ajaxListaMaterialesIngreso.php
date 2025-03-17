<html>
<body>
<table align='center' class="texto">
<tr>
<th>Cod</th><th>Producto</th><th>Marca</th><th>Costo</th><th>Precio Normal</th>
<th>Precio x Mayor</th></tr>
<?php
require("conexionmysqli.inc");
require("funciones.php");
$tipo=$_GET['tipo'];
echo $tipo;
$codGrupo=$_GET['codGrupo'];

$nombreItem=$_GET['nombreItem'];
$codMarca=$_GET['codMarca'];
$codBarraCod2=$_GET['codBarraCod2'];

$globalAlmacen=$_COOKIE['global_almacen'];
$globalAgencia=$_COOKIE['global_agencia'];
$global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];


$codProveedor=$_GET['codProveedor'];

$itemsNoUtilizar="0";
$cantAux1=0;
if($globalTipoFuncionario==2){
	$sqlAux1="select count(*) from proveedores_marcas 
where cod_proveedor in (select cod_proveedor from funcionarios_proveedores where codigo_funcionario=".$global_usuario.") ";
$respAux1=mysqli_query($enlaceCon,$sqlAux1);
$cantAux1=mysqli_num_rows($respAux1);
}	


	$sql="select ma.codigo_material, ma.descripcion_material, ma.cantidad_presentacion, ma.color,ma.talla, s.nombre,g.nombre,m.nombre, ma.codigo2,
		ma.codigo_barras
	    from material_apoyo ma
		left join subgrupos s on (ma.cod_subgrupo=s.codigo)
		left join grupos g on (s.cod_grupo=g.codigo)
		left join marcas m on (ma.cod_marca=m.codigo)
		where ma.estado=1  and ma.cod_tipo=".$tipo."
		";
	if($globalTipoFuncionario==2 && $cantAux1>0){
		$sql=$sql." and ma.cod_marca in ( select codigo from proveedores_marcas where cod_proveedor=$codProveedor )";
	}

	
	if($codBarraCod2!=""){
		$sql=$sql. " and (ma.codigo_barras like '%$codBarraCod2%' or  ma.codigo2 like '%$codBarraCod2%')";
	}
	if($codMarca!=0){
		$sql=$sql. " and ma.cod_marca='$codMarca' ";
	}
	if($nombreItem!=""){
		$sql=$sql. " and ma.descripcion_material like '%$nombreItem%'";
	}

	if($codGrupo!=0){
		$sql=$sql. " and s.cod_grupo = '$codGrupo' ";
	}
	$sql=$sql." order by 2";

	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);

	$numFilas=mysqli_num_rows($resp);
	if($numFilas>0){
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$nombre=addslashes($nombre);
			$cantidadPresentacion=$dat[2];
			$talla=$dat[3];
			$color=$dat[4];
			$nombre_subgrupo=$dat[5];
			$nombre_grupo=$dat[6];
			$nombre_marca=$dat[7];
			$codigo2=$dat[8];
			$codigoBarras=$dat[9];
						/// SACAMOS PRECIO DE VENTA
			/////////////// PRECIO NORMAL
					$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`=$codigo and p.cod_ciudad='".$globalAgencia."'";
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
		/////////// PRECIO POR MAYOR
					$precio2=0;
					$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=2 and p.`codigo_material`=$codigo and p.cod_ciudad='".$globalAgencia."'";
					$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
					$numFilas=mysqli_num_rows($respPrecio);
					if($numFilas==1){
						$datPrecio=mysqli_fetch_array($respPrecio);
						$precio2=$datPrecio[0];
						
						$precio2=redondear2($precio2);
					}else{
						$precio2=0;
						$precio2=redondear2($precio2);
					}
			///////////////
			
			//SACAMOS EL PRECIO
			
			$costoItem=0;
			
				//SACAMOS EL COSTO REGISTRADO EN LA TABLA DE PRECIOS
				$sqlCosto="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='0' 
				and cod_ciudad='$globalAgencia'";
				$respCosto=mysqli_query($enlaceCon,$sqlCosto);
				$numFilas2=mysqli_num_rows($respCosto);
				if($numFilas2>0){
					$datCosto=mysqli_fetch_array($respCosto);
					$costoItem=$datCosto[0];
				}
		

			$costoItem=round($costoItem);
			$precio0=round($precio0);
			$precio2=round($precio2);

			echo "<tr>
			<td>$codigo2 $codigoBarras</td>
			<td align='left'>
			<div class='texto'><a href='javascript:setMateriales(form1, $codigo, \"<strong>$codigo2 $codigoBarras</strong>$nombre ($nombre_marca)\",$costoItem, $precio0,$precio2)'>$nombre</a></div></td>
			<td>$nombre_marca</td>
		
			<td>$costoItem</td>
			<td>$precio0</td>
				<td>$precio2</td>
			</tr>";
		}
	}else{
		echo "<tr><td colspan='3'>Sin Resultados en la busqueda.</td></tr>";
	}

?>
</table>

</body>
</html>