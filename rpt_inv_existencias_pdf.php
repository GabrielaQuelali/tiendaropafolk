<?php

require('fpdf186/fpdf.php');
require('conexionmysqli2.inc');
require('funciones.php');
require('NumeroALetras.php');

mysqli_query($enlaceCon,"SET NAMES utf8");

error_reporting(E_ALL);
ini_set('display_errors', '1');
$ciudad=$_COOKIE['global_agencia'];

class PDF extends FPDF{ 
	public $titulo = 'Otro Titulo';
	function Header()
	{
		
		// Logo
   // $this->Image('logo.png',10,8,33);
    // Arial bold 15
		 //$this->SetXY(10,65);		
	//	 $this->Cell(30,8,'Concepto',1,0,'C');
	//	  $this->Cell(30,10,'Title',1,0,'C');
		/*$this->SetXY(93,65);		$this->Cell(10,8,"Cant.",'C');
		$this->SetXY(106,65);		$this->Cell(20,8,"Precio",'C');
		$this->SetXY(129,65);		$this->Cell(20,8,"SubTotal",'C');
		$this->SetXY(152,65);		$this->Cell(20,8,"Impuesto",'C');
		$this->SetXY(175,65);		$this->Cell(20,8,"Total",'C');*/

		$this->SetXY(150,10);
		$this->SetFont('Times','B',12);
		$this->Cell(200,0,"PRODUCTOS",0,0,"L");	

global $tallas;
global 	$varX;
global 	$array_tallas;
global $descTalla;

$this->SetFont('Times','B',8);
$this->Line(3,20,325,20);
$this->Line(3,28,325,28);
$this->Line(3,206,325,206);

	$this->Line(3,28,3,206);
	$this->Line(10,28,10,206);
	$this->Line(35,28,35,206);
	$this->Line(80,28,80,206);
	$this->Line(325,20,325,206);
	
	$this->SetXY(3,20);		$this->Cell(7,8,"Nro",'L', True);
	 
	$this->SetXY(10,20);		$this->Cell(25,8,"Grupo",'L', True);
	
	$this->SetXY(35,20);		$this->Cell(45,8,"Modelo",'L', True);
	
	$this->SetXY(80,20);		$this->Cell(80,8,"Producto",'L', True);
	

		$array_tallas = explode("|", $tallas);
		$varX=160;
		foreach ($array_tallas as &$descTalla) {
				$this->Line($varX,28,$varX,206);
  
			$this->SetXY($varX,20);		$this->Cell(10,8,$descTalla,'L', True);

			$varX=$varX+10;
		
		}
		$this->Line($varX,28,$varX,206);

		$this->SetXY($varX,20);		$this->Cell(10,8,"Total",'L', True);

	

    
	}	

	function Footer()
	{
			/*global $montoTotal;
			$euro=" €";
			//$this->Line(10,210,200,210);
			$this->SetXY(175,210);	
			$this->SetFont('Times','B',9);
			$this->Cell(10,6,"SUBTOTAL:",0,0,'R',false);
			$this->SetXY(187,210);	
			$this->Cell(10,6,$montoTotal.iconv('UTF-8', 'windows-1252', $euro),0,0,'R',false);

			$this->SetXY(175,216);	
			$this->SetFont('Times','B',9);
			$this->Cell(10,6,"IVA(21%):",0,0,'R',false);
			$this->SetXY(187,216);	
			$this->Cell(10,6,(($montoTotal*21)/100).iconv('UTF-8', 'windows-1252', $euro),0,0,'R',false);
		
			$this->Line(10,226,200,226);
			$this->SetXY(10,228);
			$this->SetFont('Times','B',12);
			$this->Cell(194,6,"TOTAL A PAGAR: ".((($montoTotal*21)/100)+$montoTotal).iconv('UTF-8', 'windows-1252', $euro),0,0,'C',false);
			$this->Line(10,236,200,236);*/
	}	
}


////////////////////////////



///////////////////////
	$tallas="";

	$sqlTallas="select codigo,nombre,abreviatura,estado,orden from tallas order by orden asc";
		$respTallas=mysqli_query($enlaceCon,$sqlTallas);
		while($datTallas=mysqli_fetch_array($respTallas)){
			$cod_talla=$datTallas['codigo'];
			$nombre_talla=$datTallas['abreviatura'];
			if($tallas==""){
				$tallas=$nombre_talla;
			}else{
				$tallas=$tallas."|".$nombre_talla;
			}
		}
/******** Iniciando FPDF ******/
	$pdf=new PDF('L','mm',array(216,330));
	$pdf->SetAutoPageBreak(true,10);
	$pdf->AliasNbPages();
	$pdf->AddPage();

	$pdf->SetFont('Times','',7);

	$contadorProducto=0;
	$nro=0;
	$yyy=20;
	$pdf->SetY(28);	
	$auxY=28;

	$sql2="select nt.cod_marca,mar.nombre as nomMarca,
nt.cod_grupo, g.nombre as nomGrupo, 
nt.cod_modelo, mo.nombre as nomModelo, mo.abreviatura as abrevModelo,
nt.cod_genero, gen.nombre as nomGenero, gen.abreviatura as abrevGenero,
nt.cod_subgrupo,subg.nombre as nomSubgrupo, subg.abreviatura as abrevSubgrupo,
nt.cod_material, mat.nombre as nomMaterial, mat.abreviatura as abrevMaterial,
nt.cod_color, col.nombre as nomColor, col.abreviatura as abrevColor
from (select m.cod_marca,sg.cod_grupo,m.cod_modelo,m.cod_genero,m.cod_subgrupo,m.cod_material,m.color as cod_color
from material_apoyo m left join subgrupos sg on (m.cod_subgrupo=sg.codigo)
group by m.cod_marca,sg.cod_grupo, m.cod_modelo,m.cod_genero,m.cod_subgrupo,m.cod_material,m.color) nt
left join grupos g on (nt.cod_grupo=g.codigo)
left join subgrupos subg on (nt.cod_subgrupo=subg.codigo)
left join modelos mo on (nt.cod_modelo=mo.codigo)
left join generos gen on (nt.cod_genero= gen.codigo)
left join materiales mat on (nt.cod_material=mat.codigo)
left join colores col on (nt.cod_color=col.codigo)
left join marcas mar on (nt.cod_marca=mar.codigo)
order by nomMarca asc, nomGrupo asc, abrevModelo asc, nomGenero asc, nomSubgrupo asc, 
nomMaterial asc, nomColor asc";
//echo $sql2;
	$cantTotalProductos=0;
	$resp2=mysqli_query($enlaceCon,$sql2);
	while($dat2=mysqli_fetch_array($resp2)){
		$nro++;
		$cod_marca=$dat2['cod_marca'];
		$nomMarca=$dat2['nomMarca'];
		$cod_grupo=$dat2['cod_grupo'];
		$nomGrupo=$dat2['nomGrupo'];
		$cod_modelo=$dat2['cod_modelo'];
		$nomModelo=$dat2['nomModelo'];
		$abrevModelo=$dat2['abrevModelo'];
		$cod_genero=$dat2['cod_genero'];
		$nomGenero=$dat2['nomGenero'];
		$abrevGenero=$dat2['abrevGenero'];
		$cod_subgrupo=$dat2['cod_subgrupo'];
		$nomSubgrupo=$dat2['nomSubgrupo'];
		$abrevSubgrupo=$dat2['abrevSubgrupo'];
		$cod_material=$dat2['cod_material'];
		$nomMaterial=$dat2['nomMaterial'];
		$abrevMaterial=$dat2['abrevMaterial'];
		$cod_color=$dat2['cod_color'];
		$nomColor=$dat2['nomColor'];
		$abrevColor=$dat2['abrevColor'];
		$descModelo="";
		if($nomModelo==$abrevModelo){
			$descModelo=$abrevModelo;
		}else{
			$descModelo=$abrevModelo." ".$nomModelo;
		}
			
		$pdf->SetX(3);		$pdf->Cell(7,4,$nro,0,0,"L");		
		$pdf->SetX(10);		$pdf->Cell(25,4,$nomGrupo,0,0,"L");		
		$pdf->SetX(35);		$pdf->Cell(45,4,$descModelo,0,0,"L");
			

		$auxY=$pdf->GetY();	
					$xxx=160;
			$cantTalla=0;
			$sqlTalla="select codigo,nombre,abreviatura,estado,orden from tallas order by orden asc";
			$respTalla=mysqli_query($enlaceCon,$sqlTalla);
			while($datTalla=mysqli_fetch_array($respTalla)){
				$codTalla=$datTalla['codigo'];
				$nombreTalla=$datTalla['nombre'];
				$sqlProducto="select ma.codigo_material,ma.descripcion_material,ma.estado,
				subg.cod_grupo,ma.cod_subgrupo,ma.cod_marca,ma.talla,ma.color,ma.cod_modelo,
				ma.cod_material,ma.cod_genero,ma.cod_coleccion,ma.estado,es.nombre_estado
				from material_apoyo ma
				left join subgrupos subg on (ma.cod_subgrupo=subg.codigo)
				left join estados es on (ma.estado=es.cod_estado)
				where ma.cod_tipo=1
				and ma.cod_marca=".$cod_marca."
				and subg.cod_grupo=".$cod_grupo."
				and ma.cod_modelo=".$cod_modelo."
				and ma.cod_genero=".$cod_genero."
				and ma.cod_subgrupo=".$cod_subgrupo."
				and ma.cod_material=".$cod_material."
				and ma.color=".$cod_color."
				and ma.talla=".$codTalla;
				$respProducto=mysqli_query($enlaceCon,$sqlProducto);
				$contProd=0;
				$codProducto=0;								
				while($datProducto=mysqli_fetch_array($respProducto)){
					$contProd++;
					$contadorProducto++;
					////////////////////////
					$codProducto=$datProducto['codigo_material'];
					$estado=$datProducto['estado'];
					$nombre_estado=$datProducto['nombre_estado'];					
					////////////////////////
					$cantidadProducto=0;
					$sqlStock="select cantidad,fecha,codigo_funcionario from inventarioinicial where codigo_material=".$codProducto." and cod_ciudad=".$ciudad;
					$respStock=mysqli_query($enlaceCon,$sqlStock);
					while($datStock=mysqli_fetch_array($respStock)){
						$cantidadProducto=$datStock['cantidad'];
						$cantTalla=$cantTalla+$cantidadProducto;
					}

					if($estado==1){

						$pdf->SetXY($xxx,$auxY);	$pdf->Cell(10,4,$cantidadProducto."|".$codProducto,'L', True);
						//	$pdf->Line($xxx,$auxY,$xxx,$auxY+8);
												
					}else{
										
					}		

				}
				$xxx=$xxx+10;

				if($contProd>0){
					if( $contProd==1){
						//echo $contProd;
					}else{

						//echo "<strong style='color:BLUE'>Repetido:</strong>". $contProd;
					}
					
				}else{
					//echo "-";
				}

				
			}

	if($cantTalla>0){
	$pdf->SetXY($xxx,$auxY);	$pdf->Cell(10,4,$cantTalla,'L', True);	
	//<td align="right" bgcolor="#CDFAFC" ><strong><?=number_format($cantTalla,2,'.',',');

	}else{
	
	}
	$pdf->Line(3,$auxY,325,$auxY);	

	$pdf->SetXY(80,$auxY);		
		$pdf->MultiCell(80,4,iconv('UTF-8', 'windows-1252',$nomGenero." ".$nomSubgrupo." ".$abrevMaterial." ".$nomColor." ".$abrevColor));	
	
	$pdf->Ln();


	$cantTotalProductos=$cantTotalProductos+$cantTalla;
}
$pdf->SetFont('Times','B',10);
$pdf->SetX($xxx);		$pdf->Cell(10,4,number_format($cantTotalProductos,2,'.',','),0,0,"L");
$pdf->Ln();
$auxY=$pdf->GetY();	
//$pdf->SetFont('Times','B',10);
$pdf->SetXY(80,$auxY);		$pdf->Cell(80,4,"VARIEDAD DE PRODUCTOS:".number_format($contadorProducto,2,'.',','),0,0,"L");


//number_format($contadorProducto,2,'.',',')







$pdf->Output();



?>