<?php
	require_once("../conexionmysqli.php");
	require_once("../estilos2.inc");
	require_once("configModule.php");
	require_once("../funcion_nombres.php");
	
	$codMaestro=$_GET['codMaestro'];
	$tipo=$_GET['tipo'];
	$nameMaestro=obtenerNombreMaestro($enlaceCon,$table,$codMaestro);


echo "<script language='Javascript'>
		function enviar_nav(f)
		{	
			var tipo=f.tipo.value;
			var codMaestro=f.codMaestro.value;
			//alert ('tipoooo='+tipo);
			
			location.href='$urlRegisterDet?codMaestro='+codMaestro+'&tipo='+tipo;
		}

		function irGrupos(f)
		{	
			var tipo=f.tipo.value;
			location.href='list.php?tipo='+tipo;
		}
		function eliminar_nav(f)
		{
			
			var tipo=f.tipo.value;
			var codMaestro=f.codMaestro.value;

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
					location.href='$urlDeleteDet?datos='+datos+'&codMaestro='+codMaestro+'&tipo='+tipo;
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
			var codMaestro=f.codMaestro.value;
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
					location.href='$urlEditDet?codigo_registro='+j_cod_registro+'&codMaestro='+codMaestro+'&tipo='+tipo;
				}
			}
		}
		</script>";
	
	
	echo "<form method='post' action=''>";
	echo "<input type='hidden' name='tipo' id='tipo' value='".$tipo."'>";
	echo "<input type='hidden' name='codMaestro' id='codMaestro' value='".$codMaestro."'>";
	
	//echo "tipo=".$tipo." cod_grupo=".$codMaestro;
	$sql="select codigo, nombre, abreviatura, estado from $tableDetalle where estado=1 and $campoForaneo=".$codMaestro." order by 2";
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h1>Lista de $moduleDetNamePlural</h1>";
	
	echo "<h1>$moduleNameSingular $nameMaestro</h1>";
	
	
	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav(this.form)'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	<input type='button' value='Cancelar' name='Editar' class='boton' onclick='irGrupos(this.form)'>
	</div> <br> <br>";
	
	
	echo "<center><table class='texto'>";
	echo "<tr>
	<th>&nbsp;</th>
	<th>Codigo</th>
	<th>Nombre</th>
	<th>Abreviatura</th>
	</tr>";
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombre=$dat[1];
		$abreviatura=$dat[2];
		echo "<tr>
		<td><input type='checkbox' name='codigo' value='$codigo'></td>
		<td>$codigo</td>
		<td>$nombre</td>
		<td>$abreviatura</td>
		</tr>";
	}
	echo "</table></center><br>";
	
	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav(this.form)'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	<input type='button' value='Cancelar' name='Editar' class='boton' onclick='irGrupos(this.form)'>
	</div>";
	
	echo "</form>";
?>