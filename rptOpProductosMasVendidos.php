<script language='JavaScript'>
function envia_formulario(f)
{	var fecha_ini, fecha_fin, rpt_ver;
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
	

	var forms = f;
    if(forms.checkValidity()){
		window.open('rptProductosMasVendidos.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
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
echo "<table align='center' class='textotit'><tr><th>Reporte de Productos mas Vendidos</th></tr></table><br>";
echo"<form method='post' action=''>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th>
	<td><select name='rpt_territorio' class='texto' required='true' size='7' multiple>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<option value='-1'>TODOS</option>";
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