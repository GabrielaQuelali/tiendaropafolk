<?php
	require_once("../conexionmysqli.php");
	require_once("../estilos2.inc");
	require_once("configModule.php");



	
	echo "<form method='post' action=''>";
	$sql="select codigo, nombre, abreviatura, estado from $table where estado=1 order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h1>Lista de $moduleNamePlural</h1>";
	

	
	echo "<center><table class='texto'>";
	echo "<tr>

	<th>Nombre</th>
	<th>Abreviatura</th>
		<th>&nbsp;</th>
	</tr>";
	while($dat=mysqli_fetch_array($resp)){
		$codigo=$dat[0];
		$nombre=$dat[1];
		$abreviatura=$dat[2];
		
		echo "<tr>
		
		<td>$nombre</td>

		<td>";
			
		$sqlSubGrupo="SELECT codigo,nombre, abreviatura FROM `subgrupos` where estado=1 and cod_grupo=".$codigo." order by nombre asc ";
		$respSubGrupo=mysqli_query($enlaceCon,$sqlSubGrupo);
		
		while($datSubGrupo=mysqli_fetch_array($respSubGrupo)){
			$codigoSub=$datSubGrupo[0];
			$nombreSub=$datSubGrupo[1];
			echo "<strong>$codigoSub </strong>$nombreSub</br>";
		}
		
		
		
		echo"</td></tr>";
	}
	echo "</table></center><br>";

	echo "</form>";
?>