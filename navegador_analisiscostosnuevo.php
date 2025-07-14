<?php
require("conexionmysqli.inc");
require('function_formatofecha.php');
require("estilos_almacenes.inc");
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <link href="lib/css/paneles.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.button.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.mouse.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.draggable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.resizable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.dialog.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript" src="lib/js/xlibPrototipo-v0.1.js"></script>
        <script type='text/javascript' language='javascript'>
        </script>
    </head>
    <body>

<?php

echo "<h1>Analisis de Costos y Precios</h1>";

// echo "<div class='divBotones'>
// 	<input type='button' value='Registrar Nuevo Analisis' name='adicionar' class='boton' onclick='location.href='rptOpAnalisisCostoPrecio.php'>
// </div>";

echo "<center><table class='texto'>";
echo "<tr><th>Cod. Analisis</th><th>Fecha Proceso Analisis</th><th>-</th>
</tr>";
$consulta = "SELECT id, fecha_proceso from analisis_costos_nuevos order by id desc";
//echo $consulta;
$resp = mysqli_query($enlaceCon,$consulta);
while ($dat = mysqli_fetch_array($resp)) {

	$idanalisis=$dat[0];
	$fechainicio=$dat[1];

    echo "<tr>
    	<td align='center'>$idanalisis</td>
    	<td align='center'>$fechainicio</td>";
	 
	 echo "	<td align='center'>
		<a target='_BLANK' href='detalleAnalisisCostosPreciosNuevo.php?idanalisis=$idanalisis'>
			<img src='imagenes/detalles.png' border='0' width='30' heigth='30' title='Ver Detalle'>
		</a>
	</td></tr>";
}
echo "</table></center><br>";
echo "</div>";

echo "<div class='divBotones'>
	<a href='rptOpAnalisisCostoPrecioNuevos.php' class='boton2' target='_BLANK'><span style='color:orange;'>Registrar Nuevo Analisis</span></a>
</div>";


echo "</form>";
?>