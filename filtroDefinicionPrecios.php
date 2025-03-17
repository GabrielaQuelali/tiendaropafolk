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


function ajaxSubGrupo(f){
	//alert(f);
	//var cod_grupo=combo.value;
	var rpt_grupo=new Array();

	var i=0;
			for(j=0;j<=f.rpt_grupo.options.length-1;j++)
			{	if(f.rpt_grupo.options[j].selected)
				{	rpt_grupo[i]=f.rpt_grupo.options[j].value;
					i++;
				}
			}
	//alert(rpt_grupo);
	var contenedor;
	contenedor = document.getElementById('divSubGrupo');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxSubGrupoMultiple.php?cod_grupo='+rpt_grupo+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
function envia_formulario(f)
{	var rpt_territorio,rpt_marca;
	var rpt_modelo=new Array();
	var rpt_grupo=new Array();
	var rpt_subgrupo=new Array();
	var rpt_genero=new Array();
	var rpt_talla=new Array();
	var rpt_material=new Array();
	var rpt_color=new Array();
	rpt_territorio=f.rpt_territorio.value;
	rpt_marca=f.rpt_marca.value;

	var a=0;
			for(b=0;b<=f.rpt_modelo.options.length-1;b++)
			{	if(f.rpt_modelo.options[b].selected)
				{	rpt_modelo[a]=f.rpt_modelo.options[b].value;
					a++;
				}
			}
	//alert("modelo="+rpt_modelo);

	var e=0;
			for(g=0;g<=f.rpt_grupo.options.length-1;g++)
			{	if(f.rpt_grupo.options[g].selected)
				{	rpt_grupo[e]=f.rpt_grupo.options[g].value;
					e++;
				}
			}
	//alert("rpt_grupo="+rpt_grupo);

	var i=0;
			for(j=0;j<=f.rpt_subgrupo.options.length-1;j++)
			{	if(f.rpt_subgrupo.options[j].selected)
				{	rpt_subgrupo[i]=f.rpt_subgrupo.options[j].value;
					i++;
				}
			}
	//alert("rpt_subgrupo="+rpt_subgrupo);

	var c=0;
			for(d=0;d<=f.rpt_genero.options.length-1;d++)
			{	if(f.rpt_genero.options[d].selected)
				{	rpt_genero[c]=f.rpt_genero.options[d].value;
					c++;
				}
			}
	//alert("rpt_genero="+rpt_genero);

	var n=0;
			for(m=0;m<=f.rpt_talla.options.length-1;m++)
			{	if(f.rpt_talla.options[m].selected)
				{	rpt_talla[n]=f.rpt_talla.options[m].value;
					n++;
				}
			}
	//alert("rpt_talla="+rpt_talla);

	var p=0;
			for(q=0;q<=f.rpt_material.options.length-1;q++)
			{	if(f.rpt_material.options[q].selected)
				{	rpt_material[p]=f.rpt_material.options[q].value;
					p++;
				}
			}			
	//alert("rpt_material="+rpt_material);

	var y=0;
			for(z=0;z<=f.rpt_color.options.length-1;z++)
			{	if(f.rpt_color.options[z].selected)
				{	rpt_color[y]=f.rpt_color.options[z].value;
					y++;
				}
			}			
	//alert("rpt_color="+rpt_color);

 var mensaje='';
	var forms = f;
	location.href='edicionPreciosGral.php?rpt_territorio='+rpt_territorio+'&rpt_marca='+rpt_marca+'&rpt_modelo='+rpt_modelo+'&rpt_grupo='+rpt_grupo+'&rpt_subgrupo='+rpt_subgrupo+'&rpt_genero='+rpt_genero+'&rpt_talla='+rpt_talla+'&rpt_material='+rpt_material+'&rpt_color='+rpt_color+'&mensaje='+mensaje;
	/*window.open('edicionPreciosGral.php?rpt_territorio='+rpt_territorio+'&rpt_marca='+rpt_marca+'&rpt_modelo='+rpt_modelo+'&rpt_grupo='+rpt_grupo+'&rpt_subgrupo='+rpt_subgrupo+'&rpt_genero='+rpt_genero+'&rpt_talla='+rpt_talla+'&rpt_material='+rpt_material+'&rpt_color='+rpt_color+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');	*/


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
echo "<table align='center' class='textotit'><tr><th>FILTRO PARA DEFINICION DE PRECIOS</th></tr></table><br>";
echo"<form method='post' action=''>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th>
	<td ><select name='rpt_territorio' id='rpt_territorio' class='texto' required>";
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
	echo "</select></td>";

	echo "<th align='left'>Marcas</th>
	<td colspan='3'>
	<select name='rpt_marca' id='rpt_marca' class='texto' required>";

	$sqlMarca="select codigo, nombre from marcas where estado=1  order by nombre asc";
	$respMarca=mysqli_query($enlaceCon,$sqlMarca);
	while($datMarca=mysqli_fetch_array($respMarca))
	{	$codMarca=$datMarca[0];
		$nombreMarca=$datMarca[1];
		echo "<option value='$codMarca' selected>$nombreMarca</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Modelos</th><td colspan='3'>
	<select name='rpt_modelo' id='rpt_modelo' class='texto' size='6' multiple>
	<option value='-1' selected>TODOS</option>";
	$sqlModelo="select codigo, nombre,abreviatura from modelos where estado=1  order by nombre asc";
	$respModelo=mysqli_query($enlaceCon,$sqlModelo);
	while($datModelo=mysqli_fetch_array($respModelo))
	{	$codModelo=$datModelo[0];
		$nombreModelo=$datModelo[1];
		$abrevModelo=$datModelo[2];
		echo "<option value='$codModelo' >$nombreModelo ($abrevModelo)</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Grupos</th><td >
	<select name='rpt_grupo' id='rpt_grupo' class='texto' size='8' multiple onChange='ajaxSubGrupo(this.form);'>
	<option value='-1' selected>TODOS</option>";
	$sqlGrupo="select codigo, nombre from grupos where estado=1  order by nombre asc";
	$respGrupo=mysqli_query($enlaceCon,$sqlGrupo);
	while($datGrupo=mysqli_fetch_array($respGrupo))
	{	$codGrupo=$datGrupo['codigo'];
		$nombreGrupo=$datGrupo['nombre'];
		
		echo "<option value='$codGrupo' >$nombreGrupo </option>";
	}
	echo "</select></td>";


	echo "<th align='left'>SubGrupos</th><td ><div id='divSubGrupo'>
	<select name='rpt_subgrupo' id='rpt_subgrupo' class='texto' size='8' multiple>
	<option value='-1' selected>TODOS</option>";
	$sqlSubgrupo="select sub.codigo, sub.nombre, sub.cod_grupo, g.nombre as nombre_grupo
	from subgrupos sub left join grupos g on(sub.cod_grupo=g.codigo)
	where sub.estado=1  order by nombre_grupo asc, sub.nombre asc";
	$respSubgrupo=mysqli_query($enlaceCon,$sqlSubgrupo);
	while($datSubgrupo=mysqli_fetch_array($respSubgrupo))
	{	$codSubgrupo=$datSubgrupo['codigo'];
		$nombreSubgrupo=$datSubgrupo['nombre'];
		$codGrupo=$datSubgrupo['cod_grupo'];
		$nombreGrupo=$datSubgrupo['nombre_grupo'];
		echo "<option value='$codSubgrupo' >$nombreGrupo - $nombreSubgrupo </option>";
	}
	echo "</select></div></td></tr>";

	echo "<tr><th align='left'>Genero</th><td>
	<select name='rpt_genero' id='rpt_genero' class='texto' size='6' multiple>
	<option value='-1' selected>TODOS</option>";
	$sqlGenero="select codigo, nombre from generos where estado=1  order by nombre asc";
	$respGenero=mysqli_query($enlaceCon,$sqlGenero);
	while($datGenero=mysqli_fetch_array($respGenero))
	{	$codGenero=$datGenero[0];
		$nombreGenero=$datGenero[1];
		echo "<option value='$codGenero' >$nombreGenero</option>";
	}
	echo "</select></td>";

echo "<th align='left'>Talla</th><td>
	<select name='rpt_talla' id='rpt_talla' class='texto' size='6' multiple>
	<option value='-1' selected>TODOS</option>";
	$sqlTalla="select codigo, nombre from tallas where estado=1  order by nombre asc";
	$respTalla=mysqli_query($enlaceCon,$sqlTalla);
	while($datTalla=mysqli_fetch_array($respTalla))
	{	$codTalla=$datTalla[0];
		$nombreTalla=$datTalla[1];
		echo "<option value='$codTalla'>$nombreTalla</option>";
	}
	echo "</select></td>";


	echo "<tr><th align='left'>Material</th><td>
	<select name='rpt_material' id='rpt_material' class='texto' size='7' multiple>
	<option value='-1' selected>TODOS</option>";
	$sqlMaterial="select codigo, nombre from materiales where estado=1  order by nombre asc";
	$respMaterial=mysqli_query($enlaceCon,$sqlMaterial);
	while($datMaterial=mysqli_fetch_array($respMaterial))
	{	$codMaterial=$datMaterial[0];
		$nombreMaterial=$datMaterial[1];
		echo "<option value='$codMaterial' >$nombreMaterial</option>";
	}
	echo "</select></td>";


	echo "<th align='left'>Colores</th><td>
	<select name='rpt_color' id='rpt_color' class='texto' size='7' multiple>
	<option value='-1' selected>TODOS</option>";
	$sqlColor="select codigo, nombre from colores where estado=1  order by nombre asc";
	$respColor=mysqli_query($enlaceCon,$sqlColor);
	while($datColor=mysqli_fetch_array($respColor))
	{	$codColor=$datColor[0];
		$nombreColor=$datColor[1];
		echo "<option value='$codColor' >$nombreColor</option>";
	}
	echo "</select></td></tr>";

	

	
	
	
	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='SIGUIENTE' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>