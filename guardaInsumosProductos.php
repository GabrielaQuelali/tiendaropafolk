<?php
require("conexionmysqli.php");
require("estilos.inc");

//recogemos variables
$codProducto=$_POST['codProducto'];
$nombreProducto=$_POST['nombreProducto'];

$tipo=$_POST['tipo'];
$estado=$_POST['estado'];

$sqlDelete="delete from insumos_productos where cod_producto='".$codProducto."'";
//echo $sqlDelete;
mysqli_query($enlaceCon,$sqlDelete);

$sql="select ma.codigo_material,ma.descripcion_material,ma.estado as cod_estado, es.nombre_estado,ma.cod_linea_proveedor,ma.cod_grupo,sub.cod_grupo as cod_grupo2,gru.nombre as grupo, ma.cod_tipomaterial,ma.cantidad_presentacion,ma.observaciones,ma.imagen,ma.cod_unidad,um.abreviatura as nombre_unidad_medida,ma.peso,ma.cod_subgrupo,sub.nombre as subgrupo,ma.cod_marca,ma.codigo_barras,
		ma.talla,ma.color,ma.codigo_anterior,ma.codigo2,ma.fecha_creacion,ma.creado_por,
		concat(f.paterno,' ',f.materno,' ',f.nombres) funcionario,
		ma.cod_modelo, ma.cod_material,ma.cod_genero,ma.cod_tipo,es.nombre_estado
 		from material_apoyo ma
		left join estados es on (ma.estado=es.cod_estado)
		left join subgrupos sub on (ma.cod_subgrupo=sub.codigo)
		left join grupos gru on (sub.cod_grupo=gru.codigo)
		left join unidades_medida um on (ma.cod_unidad=um.codigo)
		left join funcionarios f on (ma.creado_por=f.codigo_funcionario)
		where ma.cod_tipo=2 and ma.estado=1 
		order by gru.nombre asc, sub.nombre asc, ma.descripcion_material asc ";
		//echo $sql;
		$resp=mysqli_query($enlaceCon,$sql);
		while($dat=mysqli_fetch_array($resp)){
			$codigoInsumo=$dat['codigo_material'];
			$cod_unidad_medida=$dat['cod_unidad'];
			//echo "codigoInsumo=".$codigoInsumo."<br/>";

			if(isset($_POST['codigo_material'.$codigoInsumo])){

				/*echo "codigoInsumo=".$codigoInsumo."<br/> codigoInsumo2=".$_POST['codigo_material'.$codigoInsumo];

				echo "cantidad=".$_POST['cantidad'.$codigoInsumo]."<br/>";
				echo "unidad medida=".$_POST['cod_unidad_medida'.$codigoInsumo]."<br/>";*/

				if($_POST['cantidad'.$codigoInsumo]>0){
					$cantidad=$_POST['cantidad'.$codigoInsumo];


					$sqlInsert="insert into insumos_productos (cod_producto,cod_insumo,cod_unidad_medida,cant) values('".$codProducto."','".$codigoInsumo."','".$cod_unidad_medida."','".$cantidad."')";
					mysqli_query($enlaceCon,$sqlInsert);

				}


			}
		}







	echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='registroInsumosProductos.php?codigo=".$codProducto."&nombre=".$nombreProducto."&tipo=".$tipo."&estado=".$estado."'";
			
			echo"</script>";

	

?>