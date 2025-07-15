<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
	function enviarFormDetallado(f, tiporeporte){
		if(tiporeporte==1){
			f.action='rptAnalisisCostosPreciosProductosNuevos.php';
		}
		f.submit();
	}

	function buscarProductosFecha(form) {
		// Obtener los datos del formulario
		var formData = new FormData(form);

		// Realizar la solicitud AJAX
		$.ajax({
			url: 'ajaxProductosFecha.php', // URL a la que se enviarán los datos
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				// Mostrar la respuesta en el div con id div_productos
				$('#div_productos').html(response);
			},
			error: function(xhr, status, error) {
				// Manejar errores
				console.error(error);
			}
		});
	}
</script>
<?php
require('conexionmysqli.php');
require("estilos_almacenes.inc");


$fecha_rptdefault=date("Y-m-d");

echo "<h1>Analisis de Costos y Precios Productos Nuevos</h1><br>";

echo"<form method='post' action='' target='_blank'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th>
		<td><select name='rpt_territorio[]' class='selectpicker' data-actions-box='true' data-style='btn-success' data-live-search='true' multiple required>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon, $sql);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	
	// echo "<tr><th align='left'>Productos Creados despues de:</th>";
	// 		echo" <td>
	// 		<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='fecha_ini' size='10' name='fecha_ini' required>";
    // 		echo" <input type='button' onClick='buscarProductosFecha(this.form)' value='Ver Productos' class='boton-azul'></td>";
	// echo "</tr>";
?>
<?php
// Consultas para Grupo
$sqlGrupos = "SELECT g.codigo, g.nombre, g.abreviatura FROM grupos g WHERE g.estado = 1 ORDER BY g.nombre";
$respGrupos = mysqli_query($enlaceCon, $sqlGrupos);

// Consultas para Modelo
$sqlModelos = "SELECT codigo, nombre, abreviatura FROM modelos WHERE estado = 1 ORDER BY nombre";
$respModelos = mysqli_query($enlaceCon, $sqlModelos);
?>

<tr>
	<th align='left'>Grupo:</th>
	<td>
		<select name="cod_grupo[]" id="cod_grupo" class='selectpicker' data-actions-box='true' data-style='btn-primary' data-live-search='true' multiple>
			<?php
			while ($row = mysqli_fetch_array($respGrupos)) {
				echo "<option value='{$row['codigo']}'>{$row['nombre']}</option>";
			}
			?>
		</select>
	</td>
</tr>

<tr>
	<th align='left'>Modelo:</th>
	<td>
		<select name="cod_modelo[]" id="cod_modelo" class='selectpicker' data-actions-box='true'  data-style='btn-warning' data-live-search='true' multiple>
			<?php
			while ($row = mysqli_fetch_array($respModelos)) {
				echo "<option value='{$row['codigo']}'>{$row['nombre']}</option>";
			}
			?>
		</select>
	</td>
</tr>

<tr>
	<th align='left'>Nombre Producto:</th>
	<td>
		<input type="text" class="texto" name="nombre_producto" id="nombre_producto" placeholder="Ingrese nombre o parte del producto">
		<input type='button' onClick='buscarProductosFecha(this.form)' value='Ver Productos' class='boton-azul'>
	</td>
</tr>

<?php

	
	echo "<tr><th align='left'>Productos</th>
		<td><div id='div_productos'></div></td></tr>";

	echo"\n </table><br>";

	echo "<center>
	<input type='button' name='reporte' value='Ver Analisis' onclick='enviarFormDetallado(this.form, 1);' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>