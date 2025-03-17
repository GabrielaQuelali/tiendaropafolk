<?php
require("conexionmysqli2.inc");
require("estilos.inc");
require("funciones.php");

error_reporting(E_ALL);
 ini_set('display_errors', '1');


//recogemos variables
$nombreProductoCosto=$_POST['nombreProductoCosto'];
$indice_tabla=$_POST['indice_tabla'];
$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d");
// echo "nombreProductoCosto=".$nombreProductoCosto;
//echo "indice_tabla=".$indice_tabla;
$sql = "select IFNULL(MAX(cod_producto_costo)+1,1) from producto_costo order by cod_producto_costo desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$codigo=$dat[0];

$sqlInsert="insert into producto_costo (cod_producto_costo,nombre_producto_costo,cod_estado,created_by,created_date) 
values(".$codigo.",'".strtoupper($nombreProductoCosto)."',1,".$createdBy.",'".$createdDate."')";
//echo $sqlInsert."<br/>";
$respInsert= mysqli_query($enlaceCon,$sqlInsert);

if($respInsert==1){
 for ($i=1;$i<=$indice_tabla;$i++){

	if(isset($_POST['codigoMaterial'.$i])){

	$sqlInsertDetalle="insert into producto_costo_detalle (cod_producto_costo,cod_producto) 
values(".$codigo.",".$_POST['codigoMaterial'.$i].")";
	mysqli_query($enlaceCon,$sqlInsertDetalle);
	//echo $sqlInsertDetalle."<br/>";
 		//echo "si existe valor=".$_POST['codigoMaterial'.$i];
 	}
 }
 ?>
 <script language='Javascript'>
		alert('Los datos fueron insertados correctamente.');
		location.href='navegador_productoCosto.php?&estado=-1'
		</script>
<?php		
}

?>