<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		var cod_ciudad, nombre_almacen, cod_funcionario;
		if(f.nombre_almacen.value=='')
		{	alert('El campo Nombre de Almacen esta vacio.');
			f.nombre_almacen.focus();
			return(false);
		}
		nombre_almacen=f.nombre_almacen.value;
		cod_ciudad=f.cod_ciudad.value;
		cod_funcionario=f.cod_funcionario.value;
		location.href='guarda_almacenes.php?nombre_almacen='+nombre_almacen+'&cod_ciudad='+cod_ciudad+'&cod_funcionario='+cod_funcionario+'';
	}
	function envia_form(f)
	{	f.submit();
	}
	</script>";
	require("conexionmysqli2.inc");
	require("estilos_almacenes.inc");
	require("funciones.php");

echo "<form action='' method='post'>";
echo "<h1>Adicionar Almacen</h1>";



echo "<center><table class='texto'>";
echo "<tr><th>Nombre Almacen</th><th>Territorio</th><th>Responsable</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto'  name='nombre_almacen' id='nombre_almacen' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>";

$sql1="select * from ciudades order by descripcion";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td><select name='cod_ciudad' id='cod_ciudad' class='texto' OnChange='envia_form(this.form)'>";
while($dat1=mysqli_fetch_array($resp1))
{	$cod_ciudad=$dat1[0];
	$nombre_ciudad=$dat1[1];
	if($cod_ciudad==$territorio)
	{	echo "<option value='$cod_ciudad' selected>$nombre_ciudad</option>";
	}
	else
	{	echo "<option value='$cod_ciudad'>$nombre_ciudad</option>";
	}
}
echo "</select></td>";
$sql2="select codigo_funcionario, paterno, materno, nombres from funcionarios order by paterno, materno";

$resp2=mysqli_query($enlaceCon,$sql2);
echo "<td><select id='cod_funcionario' name='cod_funcionario' class='texto'>";
while($dat2=mysqli_fetch_array($resp2))
{	$cod_funcionario=$dat2[0];
	$nombre_funcionario="$dat2[1] $dat2[2] $dat2[3]";
	if($responsable==$cod_funcionario)
	{	echo "<option value='$cod_funcionario' selected>$nombre_funcionario</option>";
	}
	else
	{	echo "<option value='$cod_funcionario'>$nombre_funcionario</option>";
	}
}
echo "</select></td>";
echo "</table></center>";

echo "<div class='divBotones'><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'>
<input type='button' class='boton2' value='Cancelar' onClick='javascript:location.href=\"navegador_almacenes.php\"'>
</div>";

echo "</form>";
?>