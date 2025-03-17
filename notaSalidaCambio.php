<?php

require("fpdf.php");
require("conexionmysqli2.inc");
require("funciones.php");

/* error_reporting(E_ALL);
 ini_set('display_errors', '1');
*/

date_default_timezone_set('America/La_Paz');

class PDF extends FPDF
{ 	
	
	function Header()
	{
		$codigoVenta=$_GET['codVenta'];
		$sqlEmp="select cod_empresa, nombre, nit, direccion, ciudad from datos_empresa";
		$respEmp=mysqli_query($enlaceCon, $sqlEmp);

		$nombreEmpresa=mysqli_result($respEmp,0,1);
		$nitEmpresa=mysqli_result($respEmp,0,2);
		$direccionEmpresa=mysqli_result($respEmp,0,3);
		$ciudadEmpresa=mysqli_result($respEmp,0,4);
	
		//datos documento				
		$sqlDatosVenta="select concat((DATE_FORMAT(s.fecha, '%d/%m/%Y')),' ',s.hora_salida) as fecha, t.`abreviatura`, 
			(select cl.nombre_cliente from clientes cl where s.cod_cliente=cl.cod_cliente) as nombre_cliente,
			(select cl.telf1_cliente from clientes cl where s.cod_cliente=cl.cod_cliente) as telefonoCli, 			
			s.`nro_correlativo`, s.razon_social, s.nit, s.observaciones, 
			(select concat(f.paterno, ' ', f.nombres) from funcionarios f where codigo_funcionario=s.cod_chofer) as chofer,
			(select celular from funcionarios f where codigo_funcionario=s.cod_chofer) as celular,
			(select v.placa from vehiculos v where v.codigo=s.cod_vehiculo) as placa
			from `salida_almacenes` s, `tipos_docs` t
				where s.`cod_salida_almacenes`='$codigoVenta' and s.`cod_tipo_doc`=t.`codigo`";
		$respDatosVenta=mysqli_query($enlaceCon, $sqlDatosVenta);
		while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
			$fechaVenta=$datDatosVenta[0];
			$nombreTipoDoc=$datDatosVenta[1];
			$nombreCliente=$datDatosVenta[2];
			$telfCliente=$datDatosVenta[3];
			$nroDocVenta=$datDatosVenta[4];
			$razonSocial=$datDatosVenta[5];
			$nitVenta=$datDatosVenta[6];
			$obsNota=$datDatosVenta[7];
			$nombreChofer=$datDatosVenta[8];
			$celularChofer=$datDatosVenta[9];
			$placa=$datDatosVenta[10];
		}
		
		$this->SetFont('Arial','B',13);
		$this->SetXY(95,10);		$this->Cell(0,0,$nombreTipoDoc." ".$nroDocVenta,0,0);

		$this->SetFont('Arial','',10);
		$this->SetXY(10,10);		$this->Cell(0,0,"Cliente: ".$nombreCliente,0,0);
		$this->SetXY(150,10);		$this->Cell(0,0,"Fecha: ".$fechaVenta,0,0);
		
		$this->SetXY(10,15);		$this->Cell(0,0,"R.Social: ".$razonSocial,0,0);
		
		$this->SetXY(90,20);		$this->Cell(0,0,"Observaciones: ".$obsNota,0,0);
		$this->SetXY(10,20);		$this->Cell(0,0,"NIT: $nitVenta",0,0);
		
		
		$this->SetXY(10,25);		$this->Cell(0,0,"Vendedor: ".$nombreChofer,0,0);
		$this->SetXY(90,25);		$this->Cell(0,0,".",0,0);
		$this->SetXY(165,25);		$this->Cell(0,0,"",0,0);
		
		
		$this->Line(5, 30, 210,30);
		
		$this->SetXY(10,33);		$this->Cell(0,0,"Codigo",0,0);
		$this->SetXY(50,33);		$this->Cell(0,0,"Producto",0,0);
		$this->SetXY(135,33);		$this->Cell(0,0,"Color/Talla",0,0);
		$this->SetXY(165,33);		$this->Cell(0,0,"Cantidad",0,0);
		$this->SetXY(190,33);		$this->Cell(0,0,"Monto",0,0);
		
		$this->Line(5, 35, 210,35);
		
		$this->ln(10);
		
 
	}
	
	function Footer()
	{
		global $montoTotalProductos;
		global $montoDevueltos;
		global $montoCambiados;
		global $montoAumento;
		
		$this->Line(5, 115, 210,115);
		
		$this->SetY(-20);
		$this->SetX(150);		$this->Cell(0,0,"Monto Nota",0,0);
		$this->SetX(190);		$this->Cell(15,0,$montoTotalProductos,0,0,"R");
				
		
		$this->SetY(-15);

		$this->SetX(150);		$this->Cell(0,0,"Monto Devuelto",0,0);
		$this->SetX(190);		$this->Cell(15,0,$montoDevueltos,0,0,"R");
		
		$this->SetY(-10);
		$this->SetX(150);		$this->Cell(0,0,"Monto Cambiado",0,0);
		$this->SetX(190);		$this->Cell(15,0,$montoCambiados,0,0,"R");
		
		$this->SetY(-5);
		$this->SetX(150);		$this->Cell(0,0,"Monto Aumento",0,0);
		$this->SetX(190);		$this->Cell(15,0,$montoAumento,0,0,"R");

		$this->SetY(-10);
		// Arial italic 8
		$this->SetFont('Arial','',10);
		// Número de página
		//$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}


$pdf=new PDF('L','mm',array(214,140));

//$pdf=new PDF('P','mm',array(140,214));
$pdf->AliasNbPages();
$pdf->AddPage();
	
$pdf->SetFont('Arial','',10);
			
//AQUI EMPEZAMOS CON EL DETALLE
$codigoVenta=$_GET['codVenta'];

$sql_detalle="select m.codigo_barras, m.`descripcion_material`, 
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total, m.color, m.talla,m.codigo_material
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` 
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and 
	s.cod_salida_almacenes='$codigoVenta'
	group by m.`codigo_material` order by 2 desc;";
	
$resp_detalle=mysqli_query($enlaceCon, $sql_detalle);
$montoTotal=0;
$pesoTotal=0;
$pesoTotalqq=0;
$montoUnitarioTotal=0;
while($dat_detalle=mysqli_fetch_array($resp_detalle))
{	$codItem=$dat_detalle[0];
		$nombreItem=$dat_detalle[1];
		$montoVenta=$dat_detalle[2];
		$cantidad=$dat_detalle[3];
		
		$descuentoVenta=$dat_detalle[4];
		$montoNota=$dat_detalle[5];
		
		$colorItem=$dat_detalle[6];
		$tallaItem=$dat_detalle[7];
		$cod_material=$dat_detalle[8];
		if($descuentoVenta>0){
			$porcentajeVentaProd=($montoVenta/$montoNota);
			$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
			$montoVenta=$montoVenta-$descuentoAdiProducto;
		}
		
		$montoPtr=number_format($montoVenta,2,".",",");
		$cantidadFormat=number_format($cantidad,0,".",",");

	$montoTotalProductos+=$montoPtr;

	$pdf->Cell(0,0,$codItem,0,0);
	$pdf->SetX(40);
	$pdf->Cell(0,0,utf8_decode($nombreItem),0,0);
	$pdf->SetX(140);
	$pdf->Cell(15,0,$colorItem."/".$tallaItem,0,0,"R");
	$pdf->SetX(165);
	$pdf->Cell(15,0,$cantidadFormat,0,0,"R");
	$pdf->SetX(190);
	$pdf->Cell(15,0,$montoPtr,0,0,"R");
	
	$pdf->ln(4);
	
}
 $pdf->ln(5);
 $pdf->SetFont('Arial','B',11);
 $pdf->SetX(79);		$pdf->Cell(0,0,"PRODUCTOS DEVUELTOS",0,0);
 $pdf->SetFont('Arial','',10);

$pdf->ln(5);		
 $pdf->SetX(10);
 $pdf->SetX(10);		$pdf->Cell(0,0,"Codigo",0,0);
 $pdf->SetX(50);		$pdf->Cell(0,0,"Producto",0,0);
 $pdf->SetX(135);		$pdf->Cell(0,0,"Color/Talla",0,0);
 $pdf->SetX(165);		$pdf->Cell(0,0,"Cantidad",0,0);
 $pdf->SetX(190);		$pdf->Cell(0,0,"Monto",0,0);
 $pdf->ln(5);
 $codIngreso=$_GET['codVenta'];
 $sql_detalle="select m.codigo_barras, m.`descripcion_material`, 
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total, m.color, m.talla,m.codigo_material
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	join ingreso_detalle_almacenes i on i.cod_material=m.codigo_material
	join ingreso_almacenes ii on ii.cod_ingreso_almacen=i.cod_ingreso_almacen
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` 
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and 
	s.cod_salida_almacenes='$codigoVenta'
	and ii.cod_cambio='$codigoVenta'
	group by m.`codigo_material` order by 2 desc;";
	
$resp_detalle=mysqli_query($enlaceCon, $sql_detalle);
$indice=1;
$montoTotal=0;
$pesoTotal=0;
$pesoTotalqq=0;
$montoUnitarioTotal=0;
	while($dat_detalle=mysqli_fetch_array($resp_detalle))
	{	$codItem=$dat_detalle[0];
		$nombreItem=$dat_detalle[1];
		$montoVenta=$dat_detalle[2];
		$cantidad=$dat_detalle[3];
		
		$descuentoVenta=$dat_detalle[4];
		$montoNota=$dat_detalle[5];
		
		$colorItem=$dat_detalle[6];
		$tallaItem=$dat_detalle[7];
		$cod_material=$dat_detalle[8];
		if($descuentoVenta>0){
			$porcentajeVentaProd=($montoVenta/$montoNota);
			$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
			$montoVenta=$montoVenta-$descuentoAdiProducto;
		}
		
		$montoPtr=number_format($montoVenta,2,".",",");
		$cantidadFormat=number_format($cantidad,0,".",",");
		
	
	$montoDevueltos+=$montoPtr;

		$pdf->Cell(0,0,$codItem,0,0);
	$pdf->SetX(40);
	$pdf->Cell(0,0,utf8_decode($nombreItem),0,0);
	$pdf->SetX(140);
	$pdf->Cell(15,0,$colorItem."/".$tallaItem,0,0,"R");
	$pdf->SetX(165);
	$pdf->Cell(15,0,$cantidadFormat,0,0,"R");
	$pdf->SetX(190);
	$pdf->Cell(15,0,$montoPtr,0,0,"R");
	
	    $pdf->ln(4);
		$indice++;

	}
$pdf->ln(5);
 $pdf->SetFont('Arial','B',11);
 $pdf->SetX(79);		$pdf->Cell(0,0,"PRODUCTOS CAMBIADOS",0,0);
 $pdf->SetFont('Arial','',10);
$pdf->ln(5);		
 $pdf->SetX(10);
 $pdf->SetX(10);		$pdf->Cell(0,0,"Codigo",0,0);
 $pdf->SetX(50);		$pdf->Cell(0,0,"Producto",0,0);
 $pdf->SetX(135);		$pdf->Cell(0,0,"Color/Talla",0,0);
 $pdf->SetX(165);		$pdf->Cell(0,0,"Cantidad",0,0);
 $pdf->SetX(190);		$pdf->Cell(0,0,"Monto",0,0);
 $pdf->ln(5);
 
$codigoVenta=$_GET['codVenta'];
 $sql_detalle="select m.codigo_barras, m.`descripcion_material`, 
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total, m.color, m.talla,m.codigo_material
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` 
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and 
	s.cod_cambio='$codigoVenta'
	group by m.`codigo_material` order by 2 desc;";
	
$resp_detalle=mysqli_query($enlaceCon, $sql_detalle);
$montoTotal=0;
$pesoTotal=0;
$pesoTotalqq=0;
$montoUnitarioTotal=0;
while($dat_detalle=mysqli_fetch_array($resp_detalle))
{	$codItem=$dat_detalle[0];
		$nombreItem=$dat_detalle[1];
		$montoVenta=$dat_detalle[2];
		$cantidad=$dat_detalle[3];
		
		$descuentoVenta=$dat_detalle[4];
		$montoNota=$dat_detalle[5];
		
		$colorItem=$dat_detalle[6];
		$tallaItem=$dat_detalle[7];
		$cod_material=$dat_detalle[8];
		if($descuentoVenta>0){
			$porcentajeVentaProd=($montoVenta/$montoNota);
			$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
			$montoVenta=$montoVenta-$descuentoAdiProducto;
		}
		
		$montoPtr=number_format($montoVenta,2,".",",");
		$cantidadFormat=number_format($cantidad,0,".",",");
	if($codItem==-100){
	  $montoAumento+=$montoPtr;	
	}else{
	  $montoCambiados+=$montoPtr;
	}	
	
	$pdf->Cell(0,0,$codItem,0,0);
	$pdf->SetX(40);
	$pdf->Cell(0,0,$nombreItem,0,0);
	$pdf->SetX(140);
	$pdf->Cell(15,0,$colorItem."/".$tallaItem,0,0,"R");
	$pdf->SetX(165);
	$pdf->Cell(15,0,$cantidadFormat,0,0,"R");
	$pdf->SetX(190);
	$pdf->Cell(15,0,$montoPtr,0,0,"R");
	
	$pdf->ln(4);  	
}
//FIN DETALLE

$pdf->Output();


?>