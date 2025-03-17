<?php
require("conexionmysqlipdf.inc");
require("funciones.php");
require("estilos_almacenesAjax.php");

$codProducto=$_GET["codProducto"];
$jsonProducto=$_GET["jsonProducto"];

//$data = json_decode($jsonProducto);

echo $jsonProducto;
/*
// Verifica si la decodificación del JSON fue exitosa
if ($data !== null) {
    // Recorre el array de personas
    foreach ($data->personas as $persona) {
        // Accede a los datos de cada persona
        $nombre = $persona->nombre;
        $edad = $persona->edad;
        
        // Haz lo que quieras con los datos (por ejemplo, imprimirlos)
        echo "Nombre: $nombre, Edad: $edad <br>";
    }
} else {
    // Si la decodificación falla, imprime un mensaje de error
    echo "Error: No se pudo decodificar el JSON.";
}
*/

?>
