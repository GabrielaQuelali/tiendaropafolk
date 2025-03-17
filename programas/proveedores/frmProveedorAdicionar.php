<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");

$codProv   = "";
$nomProv   = "";
$email   = "";
$direccion = "";
$telefono1 = "";
$telefono2 = "";
$contacto  = "";

?>
<center>
    <br/>
    <h1>Adicionar Proveedor</h1>
    <table class="texto">
        <tr>
            <th>Codigo</th>
            <th>Nombre</th>
            <th>Direccion</th>
        </tr>
        <tr>
            <td><span id="id"><?php echo "$codProv"; ?></span></td>
            <td><input type="text" id="nompro" size="40"  value="<?php echo "$nomProv"; ?>"/></td>
            <td><input type="text" id="dir" size="50" value="<?php echo "$direccion";  ?>"/></td>
        </tr>
        <tr>
             <th>Tipo Proveedor</th>
		 <th>Ciudad</th>
            <th >Email</th>
           
        </tr>
        <tr>      
<td>    <select name='cod_tipo' id='cod_tipo' class='texto' required>     

<?php
    $sql3="select codigo, nombre,abreviatura from tipos where estado=1 order by nombre asc";
    $resp3=mysqli_query($enlaceCon,$sql3);
    while($dat3=mysqli_fetch_array($resp3)){
        $codigo=$dat3[0];
        $nombre=$dat3[1];
        $abreviatura=$dat3[2];
?>
        <option value="<?php echo $codigo?>"><?php echo $nombre?>&nbsp;</option>
<?php       
    }
?>
<option value="0">PRODUCTO TERMINADO/INSUMO</option>
    </select></td>              
   <td>	<select name='cod_ciu' id='cod_ciu' class='texto' required>		
<?php
	$sql3="select cod_ciu, nombre_ciu,abrev_ciu from ciudades2 where estado=1";
	$resp3=mysqli_query($enlaceCon,$sql3);
	while($dat3=mysqli_fetch_array($resp3)){
		$cod_ciu=$dat3[0];
		$nombre_ciu=$dat3[1];
		$abrev_ciu=$dat3[2];
?>
		<option value="<?php echo $cod_ciu?>"><?php echo $abrev_ciu."-".$nombre_ciu?></option>
<?php		
	}
?>
	</select></td>		
            <td><input type="text" id="email"  size="50" value="<?php echo "$email"; ?>"/></td>
         
        </tr>		
        <tr>
            <th>Telefono 1</th>
            <th>Telefono 2</th>
            <th>Contacto</th>
        </tr>
        <tr>
            <td><input type="text" id="tel1" value="<?php echo "$telefono1"; ?>"/></td>
            <td><input type="text" id="tel2" value="<?php echo "$telefono2"; ?>"/></td>
            <td><input type="text" id="contacto" size="50"  value="<?php echo "$contacto"; ?>"/></td>
        </tr>
    </table>
</center>
<div class="divBotones">
    <input class="boton" type="button" value="Guardar" onclick="javascript:adicionarProveedor();" />
    <input class="boton2" type="button" value="Cancelar" onclick="javascript:listadoProveedores();" />
</div>