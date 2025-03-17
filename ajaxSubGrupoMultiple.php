<?php
require("conexionmysqli.php");
$codGrupo=$_GET['cod_grupo'];


echo"<select name='rpt_subgrupo' id='rpt_subgrupo' class='texto' size='6' multiple>
	<option value='-1' selected>TODOS</option>";
	$sqlSubgrupo="select sub.codigo, sub.nombre, sub.cod_grupo, g.nombre as nombre_grupo
	from subgrupos sub left join grupos g on(sub.cod_grupo=g.codigo)
	where sub.estado=1 ";
	if($codGrupo!="-1"){
		$sqlSubgrupo.=" and cod_grupo in (".$codGrupo.")";
	}
	$sqlSubgrupo.=" order by nombre_grupo asc, sub.nombre asc";
	echo $sqlSubgrupo;
	$respSubgrupo=mysqli_query($enlaceCon,$sqlSubgrupo);
	while($datSubgrupo=mysqli_fetch_array($respSubgrupo))
	{	$codSubgrupo=$datSubgrupo['codigo'];
		$nombreSubgrupo=$datSubgrupo['nombre'];
		$codGrupo=$datSubgrupo['cod_grupo'];
		$nombreGrupo=$datSubgrupo['nombre_grupo'];
		echo "<option value='$codSubgrupo' >$nombreGrupo - $nombreSubgrupo </option>";
	}
	echo "</select>";




?>
