<?php
require("conexionmysqli.php");
$codGrupo=$_GET['codGrupo'];
$sql_item="select codigo_material, concat(descripcion_material,'-',color,' ',talla) from material_apoyo where  codigo_material<>0 ";
if($codGrupo!=-1){
   $sql_item.="and cod_grupo='$codGrupo'";
}

$sql_item.="order by descripcion_material";
	echo "<select name='rpt_item' class='texto'>";
	
	
	
	$resp=mysqli_query($enlaceCon,$sql_item);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_item=$dat[0];
		if($tipo_item==1)
		{	$nombre_item="$dat[1] $dat[2]";
		}
		else
		{	$nombre_item=$dat[1];
		}
		if($rpt_item==$codigo_item)
		{	echo "<option value='$codigo_item' selected>$nombre_item</option>";
		}
		else
		{	echo "<option value='$codigo_item'>$nombre_item</option>";
		}
	}
	echo "</select>";

?>
