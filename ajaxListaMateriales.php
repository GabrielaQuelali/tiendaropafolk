<html>
<body>
<table align='center' class="texto">
<tr>
<th>Cod</th><th>Producto</th><th>Marca</th><th>Stock</th><th>Precio</th></tr>
<?php
require("conexionmysqli2.inc");
require("funciones.php");

$tipo=$_GET['tipo'];
$codGrupo=$_GET['codGrupo'];
$nombreItem=$_GET['nombreItem'];
$codMarca="";
$codBarraCod2="";
if(isset($_GET['codMarca'])){
	$codMarca=$_GET['codMarca'];	
}
if(isset($_GET['codBarraCod2'])){
	$codBarraCod2=$_GET['codBarraCod2'];
}
$globalAlmacen=$_COOKIE['global_almacen'];
$itemsNoUtilizar=$_GET['arrayItemsUtilizados'];
$globalAgencia=$_COOKIE["global_agencia"];
 	//echo "globalAgencia".$globalAgencia;

	if($itemsNoUtilizar==""){
		$itemsNoUtilizar=0;
	}

	$sql="select m.codigo_material, m.descripcion_material,
	m.cod_marca,m.talla,m.color,m.codigo_barras, m.codigo2
	from material_apoyo m where estado=1 and cod_tipo=$tipo and m.codigo_material not in ($itemsNoUtilizar)";
	if($codBarraCod2!=""){
		$sql=$sql. " and (m.codigo_barras like '%$codBarraCod2%' or  m.codigo2 like '%$codBarraCod2%')";
	}
	if($codMarca!=0){
		$sql=$sql. " and m.cod_marca='$codMarca' ";
	}
	if($nombreItem!=""){
		$sql=$sql. " and m.descripcion_material like '%$nombreItem%'";
	}
	if($codGrupo!=0){
		$sql=$sql. " and m.cod_grupo='$codGrupo' ";
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
					
			$stockProducto=stockProducto($enlaceCon,$globalAlmacen, $codigo);
			$marcaProducto=obtieneMarcaProducto($enlaceCon,$dat[2]);
			$tallaProducto=$dat[3];
			$colorProducto=$dat[4];
			$codigoBarras=$dat[5];
			$codigo2=$dat[6];

			$precioProducto=0;
			$precioProductoMayor=0;

			/************  sacamos el precio por mayor   **************/
			$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1' and cod_ciudad='$globalAgencia'";
			$rs=mysqli_query($enlaceCon,$consulta);
			$registro=mysqli_fetch_array($rs);
			if(mysqli_num_rows($rs)>0){
				$precioProducto=$registro[0];
			}
			/************  sacamos el precio por mayor   **************/
			$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='2' and cod_ciudad='$globalAgencia'";
			$rs=mysqli_query($enlaceCon,$consulta);
			$registro=mysqli_fetch_array($rs);
			if(mysqli_num_rows($rs)>0){
				$precioProductoMayor=$registro[0];
			}
			/************  fin sacar precios   **************/

			if($precioProducto==""){   
				$precioProducto=0;
			}
			if($precioProductoMayor==""){
				$precioProductoMayor=0;
			}
			$precioProducto=redondear2($precioProducto);
			$precioProductoMayor=redondear2($precioProductoMayor);

			$nombreEnvio=$codigo2.$codigoBarras." ".$nombre." (<small>".$marcaProducto." ".$colorProducto." ".$tallaProducto."</small>)";

			$nombreEnvio=addslashes($nombreEnvio);
			
			echo "<tr><td>$codigo2 $codigoBarras </td>
			<td><div class='textograndenegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombreEnvio\", $stockProducto, $precioProducto, $precioProductoMayor)'>$nombre</a></div></td>			
			<td>$marcaProducto</td>
		
			<td>$stockProducto</td>
			<td>$precioProducto</td>
			</tr>";
		}
	}else{
		echo "<tr><td colspan='6'>Sin Resultados en la busqueda.</td></tr>";
	}
	
?>
</table>

</body>
</html>