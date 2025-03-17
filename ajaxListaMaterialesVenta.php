<html>
<body>
<table align='center' class="texto">
<tr>
<th>Cod</th><th>Producto</th><th>Marca</th><th>C</th><th>T</th><th>Stock</th><th>Precio Normal</th><th>Precio Mayor</th></tr>
<?php
require("conexionmysqli2.inc");
require("funciones.php");

$codTipo=$_GET['codTipo'];
$nombreItem=$_GET['nombreItem'];
$codMarca="";
$codModelo="";
$verSoloConStock="";
$codBarraCod2="";
if(isset($_GET['codMarca'])){
	$codMarca=$_GET['codMarca'];	
}
if(isset($_GET['codModelo'])){
	$codModelo=$_GET['codModelo'];	
}
if(isset($_GET['codBarraCod2'])){
	$codBarraCod2=$_GET['codBarraCod2'];
}
if(isset($_GET['stock'])){
	$verSoloConStock=$_GET['stock'];
}
$globalAlmacen=$_COOKIE['global_almacen'];
$itemsNoUtilizar=$_GET['arrayItemsUtilizados'];
$globalAgencia=$_COOKIE["global_agencia"];
 	//echo "globalAgencia".$globalAgencia;

	if($itemsNoUtilizar==""){
		$itemsNoUtilizar=0;
	}

	$sql="select m.codigo_material, m.descripcion_material,
	(select concat(p.nombre_proveedor,' ',pl.abreviatura_linea_proveedor)from proveedores p, proveedores_lineas pl 
	where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor),
	m.cod_marca,m.talla,m.color,m.codigo_barras, m.codigo2
	from material_apoyo m where estado=1 and m.codigo_material not in ($itemsNoUtilizar)";
	if($codBarraCod2!=""){
		$sql=$sql. " and (m.codigo_barras like '%$codBarraCod2%' or  m.codigo2 like '%$codBarraCod2%')";
	}
	if($codMarca!=0){
		$sql=$sql. " and m.cod_marca='$codMarca' ";
	}
	if($nombreItem!=""){
		$sql=$sql. " and m.descripcion_material like '%$nombreItem%'";
	}
	if($codTipo!=0){
		$sql=$sql. " and m.cod_grupo='$codTipo' ";
	}
	if($codModelo!=0){
		$sql=$sql. " and m.cod_modelo='$codModelo' ";
	}
	$sql=$sql." order by 2";
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);

	$numFilas=mysqli_num_rows($resp);
	//echo $numFilas;
	if($numFilas>0){
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$nombre=addslashes($nombre);
			$linea=$dat[2];			
			$stockProducto=stockProducto($enlaceCon,$globalAlmacen, $codigo);
			$marcaProducto=obtieneMarcaProducto($enlaceCon,$dat[3]);
			$tallaProducto=$dat[4];
			$colorProducto=$dat[5];
			$codigoBarras=$dat[6];
			$codigo2=$dat[7];

			$precioProducto=0;
			$precioProductoMayor=0;

			/************  sacamos el precio normal   **************/
			$consulta="select p.precio,p.cant_inicio, p.cant_final from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1' and cod_ciudad='$globalAgencia'";
			$rs=mysqli_query($enlaceCon,$consulta);
			$registro=mysqli_fetch_array($rs);
			$arrayPrecioNormal=[0,0,0];
			if(mysqli_num_rows($rs)>0){
				$arrayPrecioNormal[0]=$registro['cant_inicio'];
				$arrayPrecioNormal[1]=$registro['cant_final'];
				$arrayPrecioNormal[2]=$registro['precio'];
			}
			/************  sacamos el precio por mayor   **************/
			$consulta="select p.precio, p.cant_inicio, p.cant_final from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='2' and cod_ciudad='$globalAgencia'";
			$arrayPrecioMayor=[0,0,0];
			$rs=mysqli_query($enlaceCon,$consulta);
			$registro=mysqli_fetch_array($rs);
			if(mysqli_num_rows($rs)>0){
				$arrayPrecioMayor[0]=$registro['cant_inicio'];
				$arrayPrecioMayor[1]=$registro['cant_final'];
				$arrayPrecioMayor[2]=$registro['precio'];			
			}
			/************  fin sacar precios   **************/

			$nombreEnvio=$codigo2.$codigoBarras." ".$nombre." (<small>".$marcaProducto." ".$colorProducto." ".$tallaProducto."</small>)";

			$precioProductoNormal=$arrayPrecioNormal[2];
			$precioProductoMayor=$arrayPrecioMayor[2];

			$precioProductoNormalF=formatonumeroDec($precioProductoNormal);
			$precioProductoMayorF=formatonumeroDec($precioProductoMayor);

			$nombreEnvio=addslashes($nombreEnvio);

			$arrayPrecioNormalEncode=json_encode($arrayPrecioNormal);
			$arrayPrecioMayorEncode=json_encode($arrayPrecioMayor);

			if( ($verSoloConStock==1 && $stockProducto>0) || $verSoloConStock==0 ){
				echo "<tr><td> $codigo2 $codigoBarras </td>
				<td><div class='textograndenegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombreEnvio\", $stockProducto, $arrayPrecioNormalEncode, $arrayPrecioMayorEncode)'>$nombre</a></div></td>			
				<td>$marcaProducto</td>
				<td>$colorProducto</td>
				<td>$tallaProducto</td>
				<td align='center'><span class='textomedianorojo'>$stockProducto</span></td>
				<td align='right'><span class='textomedianoazul'>$precioProductoNormalF</span></td>
				<td align='right'><span class='textomedianoazul'>$precioProductoMayorF</span></td>
				</tr>";
			}
		}
	}else{
		echo "<tr><td colspan='6'>Sin Resultados en la busqueda.</td></tr>";
	}
	
?>
</table>

</body>
</html>