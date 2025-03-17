<?php
	require_once("../conexionmysqli.php");
	require_once("../estilos2.inc");
	require_once("configModule.php");


echo "<script language='Javascript'>
		function enviar_nav(f)
		{	
			var tipo=f.tipo.value;
			//alert('tipoo='+tipo);
			location.href='register.php?tipo='+tipo;
		}
		function eliminar_nav(f)
		{

			var tipo=f.tipo.value;
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos un registro para eliminar.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='$urlDelete?datos='+datos+'&tipo='+tipo;
				}
				else
				{
					return(false);
				}
			}
		}

		function editar_nav(f)
		{
			var tipo=f.tipo.value;
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para editar.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para editar.');
				}
				else
				{
					location.href='$urlEdit?codigo_registro='+j_cod_registro+'&tipo='+tipo;
				}
			}
		}
		</script>";
	
	
	echo "<form method='post' action=''>";

	$tipo=$_GET['tipo'];
	echo "<input type='hidden' name='tipo' id='tipo' value='".$tipo."'>";

	
	$sql="select g.codigo, g.nombre, g.abreviatura, g.estado, g.cod_tipo,t.nombre as nombreTipo
	 from grupos g 
	 left join tipos t on(g.cod_tipo=t.codigo)
	 where g.estado=1 and cod_tipo=".$tipo." order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h1>Lista de $moduleNamePlural</h1>";
	
	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav(this.form)'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div> <br> <br>";
	
	
	echo "<center><table class='texto'>";
	echo "<tr>
	<th>&nbsp;</th>
	<th>Nombre</th>
	<th>Abreviatura</th>
	<th>Tipo</th>
	<th>Sub-Grupos</th>

	</tr>";
	while($dat=mysqli_fetch_array($resp)){
		$codigo=$dat[0];
		$nombre=$dat[1];
		$abreviatura=$dat[2];
		$nombreTipo=$dat['nombreTipo'];
		
		echo "<tr>
		<td><input type='checkbox' name='codigo' value='$codigo'></td>
		<td>$nombre</td>
		<td>$abreviatura</td>
		<td>$nombreTipo</td>
		<td><a href='listDetalle.php?codMaestro=$codigo&tipo=$tipo'>Ir a SubGrupos</a></br>";
			
		$sqlSubGrupo="SELECT codigo,nombre, abreviatura FROM `subgrupos` where estado=1 and cod_grupo=".$codigo." order by nombre asc ";
		$respSubGrupo=mysqli_query($enlaceCon,$sqlSubGrupo);
		
		while($datSubGrupo=mysqli_fetch_array($respSubGrupo)){
			$codigoSub=$datSubGrupo[0];
			$nombreSub=$datSubGrupo[1];
		echo "<strong>$codigoSub </strong>$nombreSub</br>";
		}
	
		
		
		echo"</td></tr>";
	}
	echo "</table></center><br>";
	
	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";
	
	echo "</form>";
?>