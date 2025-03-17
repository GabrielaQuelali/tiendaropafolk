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
		<td><select name='rpt_territorio[]' class='selectpicker' data-style='btn-success' data-live-search='true' multiple required>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon, $sql);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Productos Creados despues de:</th>";
			echo" <td>
			<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='fecha_ini' size='10' name='fecha_ini' required>";
    		echo" <input type='button' onClick='buscarProductosFecha(this.form)' value='Ver Productos' class='boton-azul'></td>";
	echo "</tr>";
	
	echo "<tr><th align='left'>Productos</th>
		<td><div id='div_productos'></div></td></tr>";

	echo"\n </table><br>";

	echo "<center>
	<input type='button' name='reporte' value='Ver Reporte' onclick='enviarFormDetallado(this.form, 1);' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>