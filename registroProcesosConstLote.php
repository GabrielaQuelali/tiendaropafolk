<?php
$indexGerencia=1;
require_once 'conexionmysqli.inc';
require_once 'estilos_almacenes.inc';


$global_almacen=$_COOKIE['global_almacen'];

?>
<html>
    <head>
        <title>MinkaSoftware</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <script type="text/javascript" src="functionsGeneral.js"></script>
		
		
		        <script type='text/javascript' language='javascript'>
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function calcular(f,indice){

document.getElementById("total"+indice).value=document.getElementById("cant"+indice).value*document.getElementById("precio"+indice).value;
 calculaTotalGeneral(f);
}

function calculaTotalGeneral(f){
	//alert("hola1");	
	var cantidad, precio, precioTotal;
	precioTotal=0;
	for( i=1; i<=f.elements.length - 1; i++){	
		if(f.elements[i].type=="checkbox"){
				
				isChecked = f.elements[i].checked;
         	if(isChecked) {	
         	//alert(f.elements[i+2].value+""+f.elements[i+3].value);		
				cantidad=f.elements[i+2].value;
				precio=f.elements[i+3].value;
				precioTotal=precioTotal+parseFloat(cantidad*precio);
						
			}
		}
         
	}	
		
	document.getElementById('totalGeneral').innerHTML=precioTotal;
}

function validar(f){
	
f.submit();
}
	
	
</script>

	<body>	
<?php


$fechaActual=date("Y-m-d");
$obligacionxpagar_si_no=1;
$codLote=$_GET['codLote'];

$sql="select lp.cod_lote,lp.nro_lote,lp.nombre_lote,lp.obs_lote,lp.codigo_material,
mp.descripcion_material, lp.cant_lote,
lp.cod_estado_lote, lp.created_by,lp.created_date,
 lp.fecha_inicio_lote,lp.fecha_fin_lote 
from lotes_produccion lp
left join material_apoyo mp on(lp.codigo_material=mp.codigo_material) 
where lp.cod_lote=".$codLote;
$resp=mysqli_query($enlaceCon,$sql);
while($dat=mysqli_fetch_array($resp)){
		$cod_lote=$dat['cod_lote'];
		$nro_lote=$dat['nro_lote'];
		$nombre_lote=$dat['nombre_lote'];
		$obs_lote=$dat['obs_lote'];
		$codigo_material=$dat['codigo_material'];
		$descripcion_material=$dat['descripcion_material'];
		$cant_lote=$dat['cant_lote'];
		$cod_estado_lote=$dat['cod_estado_lote'];
		$nombre_estado_lote=$dat['nombre_estado_lote'];
		$created_by=$dat['created_by'];
		$created_date=$dat['created_date'];
	}


?>
<form action="guardaProcesosConstLote.php" method="POST" name="form1">
<input type="hidden" name="cod_lote" id="cod_lote" value="<?=$codLote;?>">	


<h1>Registrar Procesos<br/> LOTE:<?=$nombre_lote;?> CANT:<?=$cant_lote;?> </h1>
<br/>
<fieldset id="fiel" style="width:100%;border:0;">
	<table border="0" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;" id="data">
	<tr align="left">
		<td width="5%">-</th>
		<th width="25%">Proceso</th>
		<th width="20%">Proveedor</th>
		<th width="10%">Cantidad</th>
		<th width="10%">Precio Unidad</th>
		<th width="10%">Total</th>
		<th width="10%">Obligacion x Pagar</th>
		<th width="10%">Fecha</th>
	</tr>
	
	<?php
	///// Revision si existe otro lote donde se haya producido el mismo Producto
	 $cantReg=0;
	 $sqlAuxiliar="select count(*) from lote_procesoconst lpc where lpc.cod_lote=".$codLote;
	 $respAuxiliar=mysqli_query($enlaceCon,$sqlAuxiliar);     
	 while($datAuxiliar=mysqli_fetch_array($respAuxiliar)){
	 	 $cantReg=$datAuxiliar[0];
	 }
	 $cod_lote_anterior=0;
	 if($cantReg==0){
 		$sqlRev="select lp.cod_lote from lotes_produccion lp
            where lp.codigo_material=".$codigo_material." and lp.cod_lote<>".$codLote."
            and lp.cod_estado_lote<>4 order by lp.cod_lote desc, lp.fecha_lote desc limit 1; ";
     	$cod_lote_anterior=0;
     	$resp_rev=mysqli_query($enlaceCon,$sqlRev);     
	 	while($datRev=mysqli_fetch_array($resp_rev)){
			$cod_lote_anterior=$datRev['cod_lote'];
	 	}
	}
	// echo "cod_lote_anterior".$cod_lote_anterior;
	// Fin Revision si existe otro lote donde se haya producido el mismo Producto

	$total=0;
	$sqlDetalle="select pcp.cod_proceso_const,pc.nombre_proceso_const 
	from procesos_construccion_producto pcp
	left join procesos_construccion pc on (pcp.cod_proceso_const=pc.cod_proceso_const)
	where pcp.cod_producto=".$codigo_material." order by pc.nombre_proceso_const asc";
	//echo $sqlDetalle."<br>";
				
			$respDetalle=mysqli_query($enlaceCon,$sqlDetalle);
			
			while($datDetalle=mysqli_fetch_array($respDetalle)){
				$cod_proceso_const=$datDetalle['cod_proceso_const'];
				$nombre_proceso_const=$datDetalle['nombre_proceso_const'];
				$cod_proveedor_anterior=0;
				$precio_anterior=0;
				$obligacionxpagar_si_no=1;
				$fechaActual=date("Y-m-d");
				if($cod_lote_anterior<>0){
					$sqlRev2="select lpc.cod_proveedor, lpc.precio 
					from lote_procesoconst lpc
					where lpc.cod_lote=".$cod_lote_anterior." and lpc.cod_proceso_const=".$cod_proceso_const;
					$respRev2=mysqli_query($enlaceCon,$sqlRev2);     
	 				while($datRev2=mysqli_fetch_array($respRev2)){
	 					$cod_proveedor_anterior=$datRev2['cod_proveedor'];
	 					$precio_anterior =$datRev2['precio'];
	 				}
				}

				$sqlLoteProceso="select cod_proveedor,cantidad,precio,
				obligacionxpagar_si_no,obligacionxpagar_fecha from lote_procesoconst 
				where cod_lote='".$codLote."' and cod_proceso_const='".$cod_proceso_const."'";
				$respLoteProceso=mysqli_query($enlaceCon,$sqlLoteProceso);
				$codProveedor=$cod_proveedor_anterior;
				$cantidad=$cant_lote;
				$precio=$precio_anterior;			
				while($datLoteProceso=mysqli_fetch_array($respLoteProceso)){

					$codProveedor=$datLoteProceso['cod_proveedor'];
					$cantidad=$datLoteProceso['cantidad'];
					$precio=$datLoteProceso['precio'];
					$obligacionxpagar_si_no=$datLoteProceso['obligacionxpagar_si_no'];
					$obligacionxpagar_fecha=$datLoteProceso['obligacionxpagar_fecha'];
					$fechaActual=$obligacionxpagar_fecha;
				}
				
				$total=$total+($cantidad*$precio);
			?>



<tr bgcolor="#FFFFFF">
<td width="5%" align="center">
<input type="checkbox" name="procesConst<?=$cod_proceso_const;?>"
 id="procesConst<?=$cod_proceso_const;?> value="<?=$cod_proceso_const;?>" checked>	
</td>

<td width="25%" align="left"><?=$nombre_proceso_const;?></td>
<td width="20%" align="left">
<?php
$sql1="select cod_proveedor, nombre_proveedor from proveedores  order by nombre_proveedor";
$resp1=mysqli_query($enlaceCon,$sql1);
?>

	<select name='proveedor<?=$cod_proceso_const;?>' id='proveedor<?=$cod_proceso_const;?>' class='texto'>
<?php
while($dat1=mysqli_fetch_array($resp1))
{   $codigo=$dat1[0];
    $nombre=$dat1[1];
 ?>
    <option value='<?=$codigo;?>' <?php if($codProveedor==$codigo) { echo "selected";}?> ><?=$nombre;?></option>
<?php
}
?>
</select>
</td>
<td align="left" width="10%">
<input type="number" class="inputnumber" 
id="cant<?=$cod_proceso_const;?>" name="cant<?=$cod_proceso_const;?>" size="5"  value="<?=$cantidad;?>"  onchange='calcular(this.form,<?php echo $cod_proceso_const;?>)' onkeyup='calcular(this.form,<?php echo $cod_proceso_const;?>)' required>
</td>
<td align="left" width="10%">
<input type="number" class="inputnumber"  max="1000000" 
id="precio<?=$cod_proceso_const;?>" name="precio<?=$cod_proceso_const;?>" size="5"  value="<?=$precio;?>" step="0.001" onchange='calcular(this.form,<?php echo $cod_proceso_const;?>)' onkeyup='calcular(this.form,<?php echo $cod_proceso_const;?>)' required>
</td>
<td width="10%"><input type="number"  name="total<?=$cod_proceso_const;?>" id="total<?=$cod_proceso_const;?>" 
	value="<?= $cantidad*$precio;?>" size="5" readonly></td>
	<td width="10%" align="center">	
	<select name='obligacionxpagar_sino<?=$cod_proceso_const;?>' id='obligacionxpagar_sino<?=$cod_proceso_const;?>' class='texto'>
    <option value='1' <?php if($obligacionxpagar_si_no==1) { echo "selected";}?>  >SI</option>
    <option value='0' <?php if($obligacionxpagar_si_no==0) { echo "selected";}?>  >NO</option>

</select>
</td>
<td width="10%" align="center">
	<input  type="date" class="texto" value="<?=$fechaActual;?>" id="fechaobligacionxpagar<?=$cod_proceso_const;?>"
 name="fechaobligacionxpagar<?=$cod_proceso_const;?>" required>
</td>
</tr>

<?php
				
	}

	$sqlDetalle2="select pc.cod_proceso_const,pc.nombre_proceso_const,pc.descripcion_proceso_const 
					from procesos_construccion pc where pc.cod_estado=1
					and pc.cod_proceso_const not in 
					(select cod_proceso_const from procesos_construccion_producto where cod_producto=".$codigo_material.")
					order by pc.nombre_proceso_const asc";
	//echo $sqlDetalle2."<br>";
			$respDetalle2=mysqli_query($enlaceCon,$sqlDetalle2);
			
			while($datDetalle2=mysqli_fetch_array($respDetalle2)){	
				$cod_proceso_const=$datDetalle2['cod_proceso_const'];
				$nombre_proceso_const=$datDetalle2['nombre_proceso_const'];	

				$sqlLoteProceso="select cod_proveedor,cantidad,precio from lote_procesoconst 
				where cod_lote='".$codLote."' and cod_proceso_const='".$cod_proceso_const."'";
				$respLoteProceso=mysqli_query($enlaceCon,$sqlLoteProceso);
				$codProveedor=0;
				$cantidad=$cant_lote;
				$precio=0;		
				$sw=0;	
				while($datLoteProceso=mysqli_fetch_array($respLoteProceso)){
					$sw=1;	
					$codProveedor=$datLoteProceso['cod_proveedor'];
					$cantidad=$datLoteProceso['cantidad'];
					$precio=$datLoteProceso['precio'];
				}
				$total=$total+($cantidad*$precio);
?>
		<tr bgcolor="#FFFFFF">
		<td width="5%" align="center">
		<input type="checkbox" name="procesConst<?=$cod_proceso_const;?>"
 		id="procesConst<?=$cod_proceso_const;?> value="<?=$cod_proceso_const;?>"   <?php if($sw==1){echo "checked";}?>  >	
		</td>

		<td width="25%" align="left"><?=$nombre_proceso_const;?></td>
<td width="20%" align="left">
<?php
$sql2="select cod_proveedor, nombre_proveedor from proveedores  order by nombre_proveedor";
$resp2=mysqli_query($enlaceCon,$sql2);
?>

	<select name='proveedor<?=$cod_proceso_const;?>' id='proveedor<?=$cod_proceso_const;?>' class='texto'>
<?php
while($dat2=mysqli_fetch_array($resp2))
{   $codigo=$dat2[0];
    $nombre=$dat2[1];
 ?>
    <option value='<?=$codigo;?>' <?php if($codProveedor==$codigo) { echo "selected";}?>  ><?=$nombre;?></option>
<?php
}
?>
</select>
</td>
<td align="left" width="10%">
<input type="number" class="inputnumber" 
id="cant<?=$cod_proceso_const;?>" name="cant<?=$cod_proceso_const;?>" size="5"  value="<?=$cantidad;?>"  onchange='calcular(this.form,<?php echo $cod_proceso_const;?>)' onkeyup='calcular(this.form,<?php echo $cod_proceso_const;?>)' required>
</td>
<td align="left" width="10%">
<input type="number" class="inputnumber"  max="1000000" 
id="precio<?=$cod_proceso_const;?>" name="precio<?=$cod_proceso_const;?>" size="5"  value="<?=$precio;?>" step="0.001" onchange='calcular(this.form,<?php echo $cod_proceso_const;?>)' onkeyup='calcular(this.form,<?php echo $cod_proceso_const;?>)' required>
</td>
<td width="10%"><input type="number" size="5" name="total<?=$cod_proceso_const;?>" id="total<?=$cod_proceso_const;?>"
value="<?= $cantidad*$precio;?>" readonly>
</td>
<td width="10%" align="center">	
	<select name='obligacionxpagar_sino<?=$cod_proceso_const;?>' id='obligacionxpagar_sino<?=$cod_proceso_const;?>' class='texto'>
    <option value='1' >SI</option>
    <option value='0' >NO</option>

</select>
</td>
<td width="10%" align="center"><input  type="date" class="texto" value="<?=$fechaActual;?>" id="fechaobligacionxpagar<?=$cod_proceso_const;?>"
 name="fechaobligacionxpagar<?=$cod_proceso_const;?>" required>
</td>
</tr>
<?php
			}

?>
<tr><td colspan="5" align="right">Total:</td><td><div name="totalGeneral" id="totalGeneral"><?=$total;?></div></td></tr>
</table>
</fieldset>
<div class="divBotones">
	<input type="submit" class="boton"  value="Guardar" onClick="return validar(this.form);">
	<input type="button" class="boton2"  value="Cancelar" onClick="location.href='navegador_lotes.php'">
</div>


</form>
</body>
</html