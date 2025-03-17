<?php
require("conexionmysqli.php");
require("estilos_administracion.inc");
?>
<script language='Javascript'>
  function validar(f)
                {
                        var i;
                        var j=0;
                        datos=new Array();
                        for(i=0;i<=f.length-1;i++)
                        {
                                if(f.elements[i].type=='checkbox')
                                {       if(f.elements[i].checked==true)
                                        {       datos[j]=f.elements[i].value;
                                                j=j+1;
                                        }
                                }
                        }						
                        if(j==0)
                        {       alert('Debe seleccionar al menos un Proveedor.');
                        }
                        else
                        {	
                                if(confirm('El Funcionario externo podra ver Informacion del Proveedor o Proveedores seleccionados.'))
                                { 	f.datos_prov.value=datos;                                        
                                }
                                else
                                {
                                        return(false);
                                }
								f.submit();
                        }
						
                }
	</script>
<?php
	$txtCab="select f.paterno, f.materno, f.nombres from funcionarios f
		where f.codigo_funcionario='".$_GET['codigo_funcionario']."' ";
		
	$resp_cab=mysqli_query($enlaceCon,$txtCab);	
	$dat_cab=mysqli_fetch_array($resp_cab);
	$nombre_funcionario="$dat_cab[2] $dat_cab[0] $dat_cab[1]";

	
echo "<form action='guarda_vincular_proveedor.php' method='POST'>";
echo "<h1>Vincular con Proveedor<br>Funcionario: $nombre_funcionario</h1>";

echo "<center><table class='texto'>";
echo "<tr><th>Proveedor</th><th>Direccion</th><th>Telefono 1</th><th>Telefono 2</th><th>Contacto</th><th>Marcas</th></tr>";
	 $sql="	select cod_proveedor, nombre_proveedor, direccion, telefono1, telefono2, contacto 
 from proveedores order by nombre_proveedor asc";
$rs=mysqli_query($enlaceCon,$sql);
$cont=0;
while($reg=mysqli_fetch_array($rs))
   {$cont++;
    $codProv = $reg["cod_proveedor"];
    $nomProv = $reg["nombre_proveedor"];
    $direccion = $reg["direccion"];
    $telefono1 = $reg["telefono1"];
    $telefono2 = $reg["telefono2"];
    $contacto  = $reg["contacto"];
	$sqlFuncProv="select cod_proveedor from funcionarios_proveedores where codigo_funcionario=".$_GET['codigo_funcionario']." and cod_proveedor=".$codProv;

	$rsFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);	
	$cod_proveedor="";
	while($datFuncProv=mysqli_fetch_array($rsFuncProv)){
		$cod_proveedor = $datFuncProv['cod_proveedor'];
	}
    echo "<tr>";
	if($codProv==$cod_proveedor){
    echo "<td><input type='checkbox' id='idchk$cont' value='$codProv'  checked></td><td>$nomProv</td><td>$direccion</td><td>$telefono1</td><td>$telefono2</td><td>$contacto</td>";
	}else {
		echo "<td><input type='checkbox' id='idchk$cont' value='$codProv' ></td><td>$nomProv</td><td>$direccion</td><td>$telefono1</td><td>$telefono2</td><td>$contacto</td>";
	}
	echo "</tr>";
   }
echo "</table><br>";

	
echo "<input type='hidden' id='codigo_funcionario' name='codigo_funcionario' value='".$_GET['codigo_funcionario']."'>";
echo "<input type='hidden' id='cod_territorio' name='cod_territorio' value='".$_GET['cod_territorio']."'>";
echo "<input type='hidden' id='datos_prov' name='datos_prov' >";

echo"\n<table align='center'><tr><td><a href='navegador_funcionarios.php?cod_ciudad=$cod_territorio'>
<img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";

?>