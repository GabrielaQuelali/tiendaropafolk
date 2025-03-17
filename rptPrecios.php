<script language='JavaScript'>

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

function cambiaPrecio(f, id, codigo, precio, tipoPrecio){
	var contenedor;
	contenedor = document.getElementById(id);
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxCambiaPrecio.php?codigo='+codigo+'&precio='+precio+'&id='+id+'&tipoPrecio='+tipoPrecio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function guardaAjaxPrecio(combo, codigo, id, tipoPrecio){
	var contenedor;
	var precio=combo.value;
	contenedor = document.getElementById(id);
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxGuardaPrecio.php?codigo='+codigo+'&precio='+precio+'&tipoPrecio='+tipoPrecio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function ShowBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
}

function HiddenBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
}		

function ajaxBuscarItems(f){
	var nombreItem, tipoItem;
	nombreItem=document.getElementById("nombreItem").value;
	tipoItem=document.getElementById("tipo_material").value;

	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxBuscarItems.php?nombreItem="+nombreItem+"&tipoItem="+tipoItem,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null)
}

</script>
<?php

	require("conexionmysqli.inc");
	require("estilos_reportes_almacencentral.php");
	require("funciones.php");
	require("funcion_nombres.php");
	
	$global_almacen=$_COOKIE["global_almacen"];
	
	$global_agencia=$_GET['rpt_territorio'];
	$rpt_grupo=$_GET['rpt_grupo'];
	
	$nombreAgencia=nombreTerritorio($enlaceCon,$global_agencia);

	echo "<h1>Reporte de Precios</h1>";
	echo "<h2>Agencia: $nombreAgencia</h2>";
	
	
	echo "<div id='divCuerpo'>";
	$sql="select codigo_material, descripcion_material, (select g.nombre from grupos g where g.codigo=ma.cod_grupo)grupo, color, talla,
	(select mar.nombre from marcas mar where mar.codigo=ma.cod_marca)as marca, ma.codigo_barras
	from material_apoyo ma
			where ma.estado=1 and ma.cod_grupo in ($rpt_grupo) order by 3,2";
	$resp=mysqli_query($enlaceCon,$sql);
	
	echo "<center><table class='texto'>";
	echo "<tr>
	<th>CodBarras</th>
	<th>Grupo</th>
	<th>Marca</th>
	<th>Producto</th>
	<th>Color</th>
	<th>Talla</th>
	<!--th>Existencias</th-->
	<th>Costo</th>
	<th>Precio Normal</th>
	<!--th>Precio Descuento1</th>
	<th>Precio Descuento2</th>
	<th>Precio Descuento3</th-->
	</tr>";
	$indice=1;
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreMaterial=$dat[1];
		$nombreGrupo=$dat[2];
		$color=$dat[3];
		$talla=$dat[4];
		$nombreMarca=$dat[5];
		$codigoBarras=$dat[6];
		
		//sacamos existencias
		$rpt_fecha=date("Y-m-d");
		$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<='$rpt_fecha' and i.cod_almacen='$global_almacen'
		and id.cod_material='$codigo' and i.ingreso_anulado=0";
		$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
		$dat_ingresos=mysqli_fetch_array($resp_ingresos);
		$cant_ingresos=$dat_ingresos[0];
		$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
		where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<='$rpt_fecha' and s.cod_almacen='$global_almacen'
		and sd.cod_material='$codigo' and s.salida_anulada=0";
		$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
		$dat_salidas=mysqli_fetch_array($resp_salidas);
		$cant_salidas=$dat_salidas[0];
		$stock2=$cant_ingresos-$cant_salidas;

		
		echo "<tr>
		<td>$codigoBarras</td>
		<td>$nombreGrupo</td>
		<td>$nombreMarca</td>
		<td>$nombreMaterial </td>
		<td>$color</td>
		<td>$talla</td>";
		echo "<!--td align='center'>$stock2</td-->";
//<<<<<<< HEAD

		/*$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=0 and p.`codigo_material`=$codigo and p.cod_ciudad='$global_agencia'";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$costo=mysql_result($respPrecio,0,0);
			$costo=redondear2($costo);
		}else{
			$costo=0;
			$costo=redondear2($costo);
		}*/
//=======
// fa73857d749ed2032248cb8f08cdb257652af16c

		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`=$codigo and p.cod_ciudad='$global_agencia'";
		$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
		$numFilas=mysqli_num_rows($respPrecio);
		if($numFilas==1){
			$datPrecio=mysqli_fetch_array($respPrecio);
			$precio0=$datPrecio[0];
			//$precio0=mysql_result($respPrecio,0,0);
			$precio0=redondear2($precio0);
		}else{
			$precio0=0;
			$precio0=redondear2($precio0);
		}
		
		$precio1=$precio0;
		$sqlTipoPrecio="select abreviatura from tipos_precio where codigo='1'";
		$rsTipoPrecio=mysqli_query($enlaceCon,$sqlTipoPrecio);
		$datTipoPrecio=mysqli_fetch_array($rsTipoPrecio);
		$descuentoPrecio=$datTipoPrecio[0];
		$indiceConversion=0;
		if($descuentoPrecio>0){
			$indiceConversion=($descuentoPrecio/100);
			$precio1=$precio1-($precio1*($indiceConversion));
		}
		$precio1=round($precio1);

		$precio2=$precio0;
		$sqlTipoPrecio="select abreviatura from tipos_precio where codigo='2'";
		$rsTipoPrecio=mysqli_query($enlaceCon,$sqlTipoPrecio);
		$datTipoPrecio=mysqli_fetch_array($rsTipoPrecio);
		$descuentoPrecio=$datTipoPrecio[0];
		$indiceConversion=0;
		if($descuentoPrecio>0){
			$indiceConversion=($descuentoPrecio/100);
			$precio2=$precio2-($precio2*($indiceConversion));
		}
		$precio2=round($precio2);

		$precio3=$precio0;
		$sqlTipoPrecio="select abreviatura from tipos_precio where codigo='3'";
		$rsTipoPrecio=mysqli_query($enlaceCon,$sqlTipoPrecio);
		$datTipoPrecio=mysqli_fetch_array($rsTipoPrecio);
		$descuentoPrecio=$datTipoPrecio[0];
		$indiceConversion=0;
		if($descuentoPrecio>0){
			$indiceConversion=($descuentoPrecio/100);
			$precio3=$precio3-($precio3*($indiceConversion));
		}
		$precio3=round($precio3);


		$precio4=$precio0;
		$sqlTipoPrecio="select abreviatura from tipos_precio where codigo='4'";
		$rsTipoPrecio=mysqli_query($enlaceCon,$sqlTipoPrecio);
		$datTipoPrecio=mysqli_fetch_array($rsTipoPrecio);
		$descuentoPrecio=$datTipoPrecio[0];
		$indiceConversion=0;
		if($descuentoPrecio>0){
			$indiceConversion=($descuentoPrecio/100);
			$precio4=$precio4-($precio4*($indiceConversion));
		}
		$precio4=round($precio4);




		$indice++;
		echo "<td align='center'>$costo</td>";
		echo "<td align='center'>$precio0</td>";
		//echo "<td align='center'><div id='1$codigo' onDblClick='cambiaPrecio(this.form, this.id, $codigo, $precio1, 1)';>$precio1</div></td>";
		//echo "<td align='center'><div id='2$codigo' onDblClick='cambiaPrecio(this.form, this.id, $codigo, $precio2, 2)';>$precio2</div></td>";
		//echo "<td align='center'><div id='3$codigo' onDblClick='cambiaPrecio(this.form, this.id, $codigo, $precio3, 3)';>$precio3</div></td>";
		//echo "<td align='center'><div id='4$codigo' onDblClick='cambiaPrecio(this.form, this.id, $codigo, $precio4, 4)';>$precio4</div></td>";
		echo "</tr>";
	}
	echo "</table></center><br>";
	echo "</div>";
	
?>
</form>

