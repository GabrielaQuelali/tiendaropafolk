<?php
require("conexionmysqli2.inc");
require("estilos_almacenes.inc");

//recogemos variables
$rpt_territorio=$_POST['rpt_territorio'];
$rpt_marca=$_POST['rpt_marca'];
$rpt_modelo=$_POST['rpt_modelo'];
$rpt_grupo=$_POST['rpt_grupo'];
$rpt_subgrupo=$_POST['rpt_subgrupo'];
$rpt_genero=$_POST['rpt_genero'];
$rpt_talla=$_POST['rpt_talla'];
$rpt_material=$_POST['rpt_material'];
$rpt_color=$_POST['rpt_color'];

/*echo "rpt_territorio=".$rpt_territorio."<br/>";
echo "rpt_marca=".$rpt_marca."<br/>";
echo "rpt_modelo=".$rpt_modelo."<br/>";
echo "rpt_grupo=".$rpt_grupo."<br/>";
echo "rpt_subgrupo=".$rpt_subgrupo."<br/>";
echo "rpt_genero=".$rpt_genero."<br/>";
echo "rpt_talla=".$rpt_talla."<br/>";
echo "rpt_material=".$rpt_material."<br/>";
echo "rpt_color=".$rpt_color."<br/>";*/
$sql="select mp.codigo_material,mp.descripcion_material,mp.estado,mp.cod_grupo, g.nombre as nombreGrupo,
mp.cod_tipomaterial,mp.cantidad_presentacion,mp.observaciones,mp.imagen,
mp.cod_unidad, um.nombre as nombreUnidad,mp.peso, mp.cod_subgrupo,
sg.nombre as nombreSubgrupo, 
mp.cod_marca, mar.nombre as nombreMarca, mp.talla,tal.nombre as nombreTalla, mp.color, col.nombre as nombreColor,
mp.codigo_anterior,
mp.codigo2,mp.fecha_creacion,mp.cod_modelo, mo.nombre as nombreModelo,
mp.cod_material, ma.nombre as nombreMaterial,
mp.cod_genero, ge.nombre as nombreGenero
from material_apoyo mp
left join subgrupos sg on (mp.cod_subgrupo=sg.codigo)
left join grupos g on (sg.cod_grupo=g.codigo)
left join unidades_medida um on (mp.cod_unidad=um.codigo)
left join marcas mar on (mp.cod_marca=mar.codigo)
left join tallas tal on (mp.talla=tal.codigo)
left join colores col on (mp.color=col.codigo)
left join modelos mo on (mp.cod_modelo=mo.codigo)
left join materiales ma on (mp.cod_material=ma.codigo)
left join generos ge on (mp.cod_genero=ge.codigo)
where mp.estado=1

";

$sql.=" and mp.cod_marca in(".$rpt_marca.")";

// Filtro Grupo
if($rpt_grupo!="-1"){
	$sql.=" and sg.cod_grupo in(".$rpt_grupo.")";
}
// Fin Filtro Grupo
// Filtro SubGrupo
if($rpt_subgrupo!="-1"){
	$sql.=" and mp.cod_subgrupo in(".$rpt_subgrupo.")";
}
// Fin Filtro SubGrupo
// Filtro Modelo
if($rpt_modelo!="-1"){
	$sql.=" and mp.cod_modelo in(".$rpt_modelo.")";
}
// Fin Filtro Modelo
// Filtro Modelo
if($rpt_modelo!="-1"){
	$sql.=" and mp.cod_modelo in(".$rpt_modelo.")";
}
// Fin Filtro Modelo
// Filtro Genero
if($rpt_genero!="-1"){
	$sql.=" and mp.cod_genero in(".$rpt_genero.")";
}
// Fin Filtro Genero
// Filtro Talla
if($rpt_talla!="-1"){
	$sql.=" and mp.talla in(".$rpt_talla.")";
}
// Fin Filtro Talla
// Filtro Color
if($rpt_color!="-1"){
	$sql.=" and mp.color in(".$rpt_color.")";
}
// Fin Filtro Color
// Filtro Material
if($rpt_material!="-1"){
	$sql.=" and mp.cod_material in(".$rpt_material.")";
}
// Fin Filtro Material


$sql.=" order by mo.nombre asc ,  sg.nombre asc, ge.nombre asc, col.nombre asc,mp.codigo_material asc";

//echo $sql."<br/>";
$resp=mysqli_query($enlaceCon,$sql);
		
while($dat=mysqli_fetch_array($resp)){

	$codigo_material=$dat['codigo_material'];
	$descripcion_material=$dat['descripcion_material'];
	$estado=$dat['estado'];
	$cod_grupo=$dat['cod_grupo'];
	$nombreGrupo=$dat['nombreGrupo'];
	$cod_tipomaterial=$dat['cod_tipomaterial'];
	$cantidad_presentacion=$dat['cantidad_presentacion'];
	$observaciones=$dat['observaciones'];
	$imagen=$dat['imagen'];
	$cod_unidad=$dat['cod_unidad'];
	$nombreUnidad=$dat['nombreUnidad'];
	$peso=$dat['peso'];
	$cod_subgrupo=$dat['cod_subgrupo'];
	$nombreSubgrupo=$dat['nombreSubgrupo'];
	$cod_marca=$dat['cod_marca'];
	$nombreMarca=$dat['nombreMarca'];
	$talla=$dat['talla'];
	$nombreTalla=$dat['nombreTalla'];
	$color=$dat['color'];
	$nombreColor=$dat['nombreColor'];
	$codigo_anterior=$dat['codigo_anterior'];
	$codigo2=$dat['codigo2'];
	$fecha_creacion=$dat['fecha_creacion'];
	$cod_modelo=$dat['cod_modelo'];
	$nombreModelo=$dat['nombreModelo'];
	$cod_material=$dat['cod_material'];
	$nombreMaterial=$dat['nombreMaterial'];
	$cod_genero=$dat['cod_genero'];
 	$nombreGenero=$dat['nombreGenero'];

 	$sqlGrupoPrecio="select codigo,nombre from grupos_precio where  estado=1 order by codigo asc";

	$respGrupoPrecio=mysqli_query($enlaceCon,$sqlGrupoPrecio);
		
		while($datGrupoPrecio=mysqli_fetch_array($respGrupoPrecio)){

			$codGrupoPrecio=$datGrupoPrecio['codigo'];
			$nomGrupoPrecio=$datGrupoPrecio['nombre'];	
			$sqlPrecio="delete from precios where codigo_material='".$codigo_material."' 
						and cod_ciudad='".$rpt_territorio."' and cod_precio='".$codGrupoPrecio."'";
						//echo $sqlPrecio."<br/>";
			mysqli_query($enlaceCon,$sqlPrecio);
		
			//echo " se elimino item:".$codigo_material." grupoPrecio=".$codGrupoPrecio;
			if($_POST['precio'.$codGrupoPrecio.$codigo_material]){

				$precio=$_POST['precio'.$codGrupoPrecio.$codigo_material];
				//echo "nuevo regisro ".$precio;
				$fechaCreacion=date("Y-m-d-H-i-s");
				/////
				$cant_ini=0; $cant_fin=0;
				if($codGrupoPrecio==0){
					$cant_ini=0; $cant_fin=0;
				}
				if($codGrupoPrecio==1){
					$cant_ini=1; $cant_fin=1000;
				}
				if($codGrupoPrecio==2){
					$cant_ini=1001; $cant_fin=10000;
				}
				////
				$sqlInsert="insert into precios (codigo_material,cod_precio,precio,cod_ciudad,cant_inicio,cant_final,
					created_by,created_date)values('".$codigo_material."','".$codGrupoPrecio."',
					'".$precio."','".$rpt_territorio."','".$cant_ini."','".$cant_fin."','".$_COOKIE['global_usuario']."','".$fechaCreacion."')";
					//echo $sqlInsert."<br/>";
					mysqli_query($enlaceCon,$sqlInsert);
			}
		}
}
$mensaje="LOS PRECIOS FUERON MODIFICADOS CORRECTAMENTE!";

//echo "Los datos fueron modificados correctamente.";


	//	//alert('Los datos fueron modificados correctamente.');

	echo "<script language='Javascript'>
		
			location.href='edicionPreciosGral.php?rpt_territorio=".$rpt_territorio."&rpt_marca=".$rpt_marca."&rpt_modelo=".$rpt_modelo."&rpt_grupo=".$rpt_grupo."&rpt_subgrupo=".$rpt_subgrupo."&rpt_genero=".$rpt_genero."&rpt_talla=".$rpt_talla."&rpt_material=".$rpt_material."&rpt_color=".$rpt_color."&mensaje=".$mensaje."';		
			</script>";

	

?>