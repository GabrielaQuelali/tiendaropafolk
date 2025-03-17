<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");

$tipo=$_GET['tipo'];

//echo "tipo=hola".$tipo."hola";

echo "<center>";
echo "<h3>Proveedores</h3>";
echo "<table align='center' class='texto'><tr><th>Tipos de Proveedores:
	<select name='tipo' id='tipo'class='texto' onChange='cambiar_vista()'>";
			
			$sql2="select codigo, nombre from tipos  order by nombre asc";
			$resp2=mysqli_query($enlaceCon,$sql2);
			if($tipo==-1){
				echo"	<option value='-1' selected>TODOS</option>";
			}else{
				echo"<option value='-1' >TODOS</option>";
			}
			while($dat2=mysqli_fetch_array($resp2)){
				$codigoTipoX=$dat2['codigo'];
				$nombreTipoX=$dat2['nombre'];
				if($codigoTipoX==$tipo){
				  echo "<option value=$codigoTipoX selected>$nombreTipoX</option>";	
				}else{
					echo "<option value=$codigoTipoX>$nombreTipoX</option>";
				}
			}

			echo " </select>
	</th>
	</tr></table><br>";

echo "<div class='divBotones'><input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'></div><br><br>";


echo "<table class='texto'>";
echo "<tr>";
echo "<th>&nbsp;</th><th>Nombre</th><th>Ciudad</th><th>Direccion</th><th>Correo</th><th>Telefono </th><th>Contacto</th><th>Tipo</th><th>Marcas</th><th>Estado</th>";
echo "</tr>";
$consulta="
    SELECT p.cod_proveedor, p.nombre_proveedor, p.direccion, p.telefono1, p.telefono2,
	p.contacto, p.correo,p.cod_ciu,c2.nombre_ciu,c2.abrev_ciu,p.cod_tipo,t.nombre as nombreTipo,
	p.estado,e.nombre_estado
   FROM proveedores  p 
   left join  ciudades2 c2 on (p.cod_ciu=c2.cod_ciu)
   left join tipos t on (p.cod_tipo=t.codigo)
   left join estados e on (p.estado=e.cod_estado)     
   WHERE p.estado >-1 ";
	if($tipo<>-1){
 	$consulta=$consulta." and p.cod_tipo='".$tipo."'";

}
     $consulta=$consulta." ORDER BY p.nombre_proveedor ASC";
 // echo $consulta;
$rs=mysqli_query($enlaceCon,$consulta);
$cont=0;
while($reg=mysqli_fetch_array($rs))
   {$cont++;
    $codProv = $reg["cod_proveedor"];
    $nomProv = $reg["nombre_proveedor"];
    $direccion = $reg["direccion"];
    $telefono1 = $reg["telefono1"];
    $telefono2 = $reg["telefono2"];
    $contacto  = $reg["contacto"];
	$correo  = $reg["correo"];
	$abrev_ciu  = $reg["abrev_ciu"];
	$nombre_ciu  = $reg["nombre_ciu"];
	$cod_tipo  = $reg["cod_tipo"];
	$nombreTipo  = $reg["nombreTipo"];
	if($cod_tipo==0){
		$nombreTipo="INSUMO/PRODUCTOS TERMINADOS";
	}
	$estado = $reg["estado"];
	$nombre_estado= $reg["nombre_estado"];	
    echo "<tr>";
    echo "<td>";
    if($estado<>0){
  	  echo " <input type='checkbox' id='idchk$cont' value='$codProv' >";
   }
    echo "</td>";
    echo "<td>$nomProv</td><td>$nombre_ciu</td><td>$direccion</td>";
	 echo "<td>$correo</td><td>$telefono1 $telefono2</td><td>$contacto</td>";
		echo "<td>$nombreTipo &nbsp;</td>";
    echo "<td><table class='texto'><tr><td><a href='proveedorMarcas.php?codProveedor=$codProv'><img src='../../imagenes/etiqueta3.png' width='20'></a></td>";
	echo "<td><table class='texto'>";
		$sqlProvMarcas=" select pm.codigo, m.nombre, m.abreviatura from proveedores_marcas pm			
							inner join marcas m on(pm.codigo=m.codigo) where pm.cod_proveedor='".$codProv."' order by m.nombre asc";
			    $respProvMarcas=mysqli_query($enlaceCon,$sqlProvMarcas);
				while($datProvMarcas=mysqli_fetch_array($respProvMarcas)){
						$cod_marca=$datProvMarcas['codigo'];
						$nombre_marca=$datProvMarcas['nombre'];
						$abrev_marca=$datProvMarcas['abreviatura'];
					echo "<tr><td>$cod_marca - $abrev_marca - $nombre_marca.</td></tr>";	
				}	
			echo"</table></td></tr></table>";
			echo "</td>";
			echo"<td>$nombre_estado</td>";		
	echo "</tr>";
   }

echo "</table>";

echo "</center>";
?>
<input type="hidden" id="idtotal" name="idtotal" value="<?php echo $cont;?>">

<?php
echo "<div class='divBotones'><input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'></div>";


?>
