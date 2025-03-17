<?php
require("conexionmysqli.php");
require('estilos.inc');
require('funciones.php');

$codProducto=$_GET['codigo'];
$nombreProducto=$_GET['nombre'];
$tipo=$_GET['tipo'];
$estado=$_GET['estado'];

echo "<form  action='actualizaPreciosMaterial.php' method='post' name='form1'>";

echo "<h1>Lista de Precios</h1><h2>$nombreProducto</h2>";


echo "<input type='hidden' name='codProducto' id='codProducto' value='$codProducto'>";
echo "<input type='hidden' name='nombreProducto' id='nombreProducto' value='$nombreProducto'>";
echo "<input type='hidden' name='tipo' id='tipo' value='$tipo'>";
echo "<input type='hidden' name='estado' id='estado' value='$estado'>";


echo "<center><table class='texto'>";
	echo "<tr>
	<th>Nro</th><th>Sucursal</th><th>&nbsp;</th><th>Precio</th><th>Cantidad Inicio</th><th>Cantidad Final</th>		
		</tr>";

		$sqlCiu="select cod_ciudad,descripcion as desc_ciudad,nombre_ciudad 
		from ciudades order by cod_ciudad asc";

		$respCiu=mysqli_query($enlaceCon,$sqlCiu);
		
		while($datCiu=mysqli_fetch_array($respCiu)){

			$cod_ciudad=$datCiu['cod_ciudad'];
			$desc_ciudad=$datCiu['desc_ciudad'];
			$nombre_ciudad=$datCiu['nombre_ciudad'];		

		$sqlGrupoPrecio="select codigo,nombre,cant_inicio,cant_final from grupos_precio where  estado=1 order by codigo asc";

		$respGrupoPrecio=mysqli_query($enlaceCon,$sqlGrupoPrecio);
		
			while($datGrupoPrecio=mysqli_fetch_array($respGrupoPrecio)){

				$codGrupoPrecio=$datGrupoPrecio['codigo'];
				$nomGrupoPrecio=$datGrupoPrecio['nombre'];		
				$cant_inicio=$datGrupoPrecio['cant_inicio'];		
				$cant_final	=$datGrupoPrecio['cant_final'];		

			$sqlPrecio="select p.codigo_material,p.precio, p.cant_inicio,p.cant_final, 
			p.created_by, concat(f.nombres,' ',f.paterno,' ',f.materno) as creado_por, p.created_date
			from precios p		
			left join funcionarios f on (p.created_by=f.codigo_funcionario)
			
			where p.codigo_material='".$codigo."' and p.cod_ciudad='".$cod_ciudad."' and p.cod_precio='".$codGrupoPrecio."'";
			
			$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
			$cantSqlPrecio=mysqli_num_rows($respPrecio);

	 		if($cantSqlPrecio>0){
				while($datPrecio=mysqli_fetch_array($respPrecio)){
					$precio=$datPrecio['precio'];
					$cant_inicio=$datPrecio['cant_inicio'];
					$cant_final=$datPrecio['cant_final'];
					echo "<tr>";
					echo "<td>&nbsp;</td>";
					echo "<td>".$desc_ciudad."</td>";
					echo "<td>".$nomGrupoPrecio."</td>";
					echo "<td><input type='number' class='inputnumber'  id='precio".$cod_ciudad.$codGrupoPrecio."' name='precio".$cod_ciudad.$codGrupoPrecio."' size='6' min='0.1' step='0.01'  value='".redondear2($precio)."'></td>";
					echo "<td><input class='inputnumber' type='number'  id='cant_ini".$cod_ciudad.$codGrupoPrecio."'  name='cant_ini".$cod_ciudad.$codGrupoPrecio."'  value='".$cant_inicio."' required></td>";
					echo " <td><input class='inputnumber' type='number'  id='cant_fin".$cod_ciudad.$codGrupoPrecio."'  name='cant_fin".$cod_ciudad.$codGrupoPrecio."'  value='".$cant_final."' required></td>";
					echo "</tr>";
				}
			}else{


					echo "<tr>";
					echo "<td>&nbsp;</td>";
					echo "<td>".$desc_ciudad."</td>";
					echo "<td>".$nomGrupoPrecio."</td>";
					echo "<td><input type='number' class='inputnumber'  id='precio".$cod_ciudad.$codGrupoPrecio."' name='precio".$cod_ciudad.$codGrupoPrecio."' size='6' min='0.1' step='0.01'  value='0'></td>";
					echo " <td><input class='inputnumber' type='number' id='cant_ini".$cod_ciudad.$codGrupoPrecio."'  name='cant_ini".$cod_ciudad.$codGrupoPrecio."' value='".$cant_inicio."' required></td>";
					echo " <td><input class='inputnumber' type='number' id='cant_fin".$cod_ciudad.$codGrupoPrecio."'  name='cant_fin".$cod_ciudad.$codGrupoPrecio."'  value='".$cant_final."' required></td>";
					echo "</tr>";

			}	
		}
	}			
	
echo"</table></center>";


echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php?estado=$estado&tipo=$tipo\"'>
</div>";
echo "</form>";

?>
