<script language='JavaScript'>
function envia_formulario(f)
{	var fecha_ini, fecha_fin;
	var rpt_gruporecibo=new Array();

	
	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	var k=0;
			for(m=0;m<=f.rpt_gruporecibo.options.length-1;m++)
			{	if(f.rpt_gruporecibo.options[m].selected)
				{	rpt_gruporecibo[k]=f.rpt_gruporecibo.options[m].value;
					k++;
				}
			}
	
	
alert("hola");
	var forms = f;
    if(forms.checkValidity()){
		 location.href='generaRecibosAutomaticos.php?rpt_gruporecibo='+rpt_gruporecibo+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin;
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
?>
<table align='center' class='textotit'><tr><th>Parametros para la Generacion Automatica de Recibos</th></tr></table>
<br>
<form method='post' action=''>

<table class='texto' align='center' cellSpacing='0' width='50%'>



	<tr><th align='left'>Grupo de Recibo</th><td>
	<select name='rpt_gruporecibo' id='rpt_gruporecibo' class='texto' size='10' multiple>
	<option value='-1'>TODOS</option>";
<?php
	$sqlGrupoRecibo="select cod_gruporecibo, nombre_gruporecibo from grupos_recibo where automatico=1 and estado=1 order by cod_gruporecibo asc";
	$respGrupoRecibo=mysqli_query($enlaceCon,$sqlGrupoRecibo);
	while($datGrupoRecibo=mysqli_fetch_array($respGrupoRecibo))
	{	$codGruporecibo=$datGrupoRecibo[0];
		$nombreGruporecibo=$datGrupoRecibo[1];
?>
		
		<option value="<?=$codGruporecibo;?>" selected><?=$nombreGruporecibo;?></option>
<?php
	}
	
	?>
	</select></td></tr>


	<tr><th align='left'>Fecha inicio:</th>
	 <TD bgcolor='#ffffff'>
			<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exafinicial' name='exafinicial' required>
    		 </TD>
	</tr>
	<tr><th align='left'>Fecha final:</th>
	 <TD bgcolor='#ffffff'>
			<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal' required>
     </TD>
	</tr>
	
	 </table><br>
	 <?php
	require('home_almacen.php');
	?>
	<center><input type='button' name='reporte' value='Siguiente' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>
	</form>
	</div>
	<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>

