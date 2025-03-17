<?php
require("conexionmysqli.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
$globalCiudad=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE['global_almacen'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$global_usuario=$_COOKIE['global_usuario'];

if($rpt_territorio==""){
	$rpt_territorio=$globalCiudad;
}
echo "<h1>Reporte Movimiento de Productos</h1>";

echo"<form method='POST' action='rptMovimientoProductos.php'  target='_blank'>";
	
	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<option value='0'>Todos</option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($rpt_territorio==$codigo_ciudad)
		{	echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
		}
		else
		{	echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
		}
	}
	echo "</select></td></tr>";
	
	
	echo "<tr><th align='left'>Almacen</th><td><select name='rpt_almacen' class='texto'>";
	$sql="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$rpt_territorio'";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_almacen=$dat[0];
		$nombre_almacen=$dat[1];
		if($rpt_almacen==$codigo_almacen || $codigo_almacen==$globalAlmacen)
		{	echo "<option value='$codigo_almacen' selected>$nombre_almacen</option>";
		}
		else
		{	echo "<option value='$codigo_almacen'>$nombre_almacen</option>";
		}
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Grupo</th><td><select name='rpt_grupo[]' class='texto' size='8' multiple>";
	$sql="select codigo, nombre from grupos where estado=1 order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Marcas</th><td><select name='rpt_marca[]' class='texto' size='8' multiple>";
	$sqlMarca="select codigo, nombre from marcas where estado=1";
		if($globalTipoFuncionario==2){
		if($cantFuncProv>0){
			$sqlMarca= $sqlMarca." and codigo in( select codigo from proveedores_marcas where cod_proveedor in
			( select cod_proveedor from funcionarios_proveedores where codigo_funcionario=$global_usuario))";
		}
	}
	$sqlMarca= $sqlMarca."  order by 2";
	$respMarca=mysqli_query($enlaceCon,$sqlMarca);
	while($datMarca=mysqli_fetch_array($respMarca))
	{	$codigoMarca=$datMarca[0];
		$nombreMarca=$datMarca[1];
		echo "<option value='$codigoMarca' selected>$nombreMarca</option>";
	}

	echo "</select></td></tr>";
	echo "<tr><th>Talla</th>
		<td>
				<input type='text' name='itemTallaBusqueda' id='itemTallaBusqueda' class='textomedianorojo' value=''>
		</td>
	</tr>

	<tr>
	<th>Color</th>
			<td>
				<input type='text' name='itemColorBusqueda' id='itemColorBusqueda' class='textomedianorojo' value=''>
			</td>
	</tr>
	<tr>
	<th>Nombre Producto (Palabra Clave)</th>
			<td>
				<input type='text' name='nombre_producto' id='nombre_producto' class='textomedianorojo' value=''>
			</td>
	</tr>";

	echo "<tr><th align='left'>Fecha Inicio:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT  type='date' class='text' value='$fecha_rptdefault' id='rpt_ini' name='rpt_ini'>
			</TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha Final:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT  type='date' class='text' value='$fecha_rptdefault' id='rpt_fin' name='rpt_fin'>
			</TD>";
	echo "</tr>";
	
	
	echo"\n </table><br>";
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>