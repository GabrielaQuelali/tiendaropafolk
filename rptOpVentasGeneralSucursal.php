<script language='JavaScript'>
function envia_formulario(f)
{	var rpt_territorio,fecha_ini, fecha_fin, rpt_ver;
	//var rpt_marca=new Array();
	var rpt_tipoPago=new Array();
	var rpt_territorio=new Array();
	
	
	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	var k=0;
			for(m=0;m<=f.rpt_territorio.options.length-1;m++)
			{	if(f.rpt_territorio.options[m].selected)
				{	rpt_territorio[k]=f.rpt_territorio.options[m].value;
					k++;
				}
			}

	/*var i=0;
			for(j=0;j<=f.rpt_tipoPago.options.length-1;j++)
			{	if(f.rpt_tipoPago.options[j].selected)
				{	rpt_tipoPago[i]=f.rpt_tipoPago.options[j].value;
					i++;
				}
			}
	//alert("tipo_pago="+rpt_tipoPago);*/
	var forms = f;
    if(forms.checkValidity()){
		window.open('rptVentasGeneralSucursal.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
		return(true);    
	} else{
        alert("Debe seleccionar todos los campos del reporte.");
    }

}
</script>
<?php

require("conexionmysqli.php");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m");
$globalCiudad=$_COOKIE['global_agencia'];
$global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$sqlFuncProv="select * from funcionarios_proveedores where codigo_funcionario=$global_usuario";
$respFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);
$cantFuncProv=mysqli_num_rows($respFuncProv);
echo "<h1>Reporte Ventas por Sucursal, Documento y Producto</h1><br>";
echo"<form method='post' action=''>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th>
	<td><select name='rpt_territorio' class='selectpicker' data-width='600px' data-live-search='true' data-style='btn btn-success' data-actions-box='true' required='true' size='7' multiple required>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];

			echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
	
	}
	echo "</select></td></tr>";
		
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT  type='month' class='texto' value='$fecha_rptdefault' id='exafinicial' name='exafinicial' required>";
    		echo" </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT  type='month' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal' required>";
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