<script language='JavaScript'>
function envia_formulario(f)
{	var rpt_territorio,fecha_ini, fecha_fin, rpt_ver;
	var rpt_proveedor=new Array();
	var rpt_tipoPago=new Array();
	rpt_territorio=f.rpt_territorio.value;
	
	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	var k=0;
			for(m=0;m<=f.rpt_prov.options.length-1;m++)
			{	if(f.rpt_prov.options[m].selected)
				{	rpt_proveedor[k]=f.rpt_prov.options[m].value;
					k++;
				}
			}
	
	var i=0;
			for(j=0;j<=f.rpt_tipoPago.options.length-1;j++)
			{	if(f.rpt_tipoPago.options[j].selected)
				{	rpt_tipoPago[i]=f.rpt_tipoPago.options[j].value;
					i++;
				}
			}
	//alert("marcas="+rpt_marca+"tipo_pago="+rpt_tipoPago);
	var forms = f;
    if(forms.checkValidity()){
		window.open('rptAjusteVentasProv.php?rpt_territorio='+rpt_territorio+'&rpt_proveedor='+rpt_proveedor+'&rpt_tipoPago='+rpt_tipoPago+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
		return(true);    
	} else{
        alert("Debe seleccionar todos los campos del reporte.");
    }

}
</script>
<?php

require("conexionmysqli.php");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
$globalCiudad=$_COOKIE['global_agencia'];
$global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$sqlFuncProv="select * from funcionarios_proveedores where codigo_funcionario=$global_usuario";
$respFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);
$cantFuncProv=mysqli_num_rows($respFuncProv);
echo "<table align='center' class='textotit'><tr><th>Reporte Ventas x Marcas Detallado</th></tr></table><br>";
echo"<form method='post' action=''>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th>
	<td><select name='rpt_territorio' class='texto' required>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($globalCiudad==$codigo_ciudad){
			echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";			
		}else{
			echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
		}
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Tipo Pago</th><td>
	<select name='rpt_tipoPago' id='rpt_tipoPago' class='texto' size='10' multiple>
	<option value='-1'>TODOS</option>";
	$sqlTipoPago="select cod_tipopago, nombre_tipopago from tipos_pago where estado=1  order by cod_tipopago asc";
	$respTipoPago=mysqli_query($enlaceCon,$sqlTipoPago);
	while($datTipoPago=mysqli_fetch_array($respTipoPago))
	{	$codTipopago=$datTipoPago[0];
		$nombreTipopago=$datTipoPago[1];
		echo "<option value='$codTipopago' selected>$nombreTipopago</option>";
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Proveedores</th><td>
	<select name='rpt_prov' id='rpt_prov' class='texto' size='10' multiple>
	<option value='-1'>TODOS</option>";
	$sqlProv="select cod_proveedor, nombre_proveedor from proveedores where estado=1";
	$sqlProv= $sqlProv."  order by 2";
	$respProv=mysqli_query($enlaceCon,$sqlProv);
	while($datProv=mysqli_fetch_array($respProv))
	{	$codProveedor=$datProv[0];
		$nombreProveedor=$datProv[1];
		echo "<option value='$codProveedor' selected>$nombreProveedor</option>";
	}
	echo "</select></td></tr>";
	
	
	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exafinicial' name='exafinicial' required>";
    		echo" </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal' required>";
    		echo" </TD>";
	echo "</tr>";
	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>