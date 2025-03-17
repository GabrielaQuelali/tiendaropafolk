<?php
//header('Content-Type: text/html; charset=ISO-8859-1');

require('fpdf.php');
require('conexionmysqli2.inc');
require('funciones.php');
require('funcion_nombres.php');
require('NumeroALetras.php');
include('phpqrcode/qrlib.php');
//header("Content-Type: text/html; charset=iso-8859-1 ");
mysqli_query($enlaceCon,"SET NAMES utf8");

$idGasto=$_GET["idGasto"];
$cod_ciudad=$_COOKIE["global_agencia"];



$tamanoLargo=120+(1*3)-3;

$pdf=new FPDF('P','mm',array(76,$tamanoLargo));
$pdf->SetMargins(0,0,0);
$pdf->AddPage(); 
$pdf->SetFont('Arial','',8);

$sqlConf="select id, valor from configuracion_facturas where id=1 and cod_ciudad='".$cod_ciudad."'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$nombreTxt=$datConf[1];//$nombreTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=10 and cod_ciudad='".$cod_ciudad."'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$nombreTxt2=$datConf[1];//$nombreTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=2 and cod_ciudad='".$cod_ciudad."'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$sucursalTxt=$datConf[1];//$sucursalTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=3 and cod_ciudad='".$cod_ciudad."'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$direccionTxt=$datConf[1];//$direccionTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=4 and cod_ciudad='".$cod_ciudad."'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$telefonoTxt=$datConf[1];//$telefonoTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=5 and cod_ciudad='".$cod_ciudad."'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$ciudadTxt=$datConf[1];//$ciudadTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=6 and cod_ciudad='".$cod_ciudad."'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$txt1=$datConf[1];//$txt1=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=9 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$nitTxt=$datConf[1];//$nitTxt=mysql_result($respConf,0,1);



$sqlGasto="select g.descripcion_gasto,g.cod_tipogasto,tg.nombre_tipogasto,g.fecha_gasto,g.monto,g.cod_ciudad,
g.created_by,g.modified_by,g.created_date,g.modified_date,g.gasto_anulado,g.cod_proveedor, p.nombre_proveedor,g.cod_grupogasto, gg.nombre_grupogasto,
g.cod_tipopago, tp.nombre_tipopago
from gastos g
inner join tipos_gasto tg on (g.cod_tipogasto=tg.cod_tipogasto)
inner join grupos_gasto gg on (g.cod_grupogasto=gg.cod_grupogasto)
inner join tipos_pago tp on (g.cod_tipopago=tp.cod_tipopago)
left  join proveedores p on (g.cod_proveedor=p.cod_proveedor)
where  g.cod_gasto=".$idGasto." and  g.cod_ciudad=".$cod_ciudad." order by g.cod_gasto desc";
//echo "consulta=".$consulta;
$respGasto = mysqli_query($enlaceCon,$sqlGasto);
while ($dat = mysqli_fetch_array($respGasto)) {
	
	$descripcion_gasto= $dat['descripcion_gasto'];
	$cod_tipogasto= $dat['cod_tipogasto'];
	$nombre_tipogasto= $dat['nombre_tipogasto'];
	$fecha_gasto= $dat['fecha_gasto'];	
	$vector_fecha_gasto=explode("-",$fecha_gasto);
	$fecha_gasto_mostrar=$vector_fecha_gasto[2]."/".$vector_fecha_gasto[1]."/".$vector_fecha_gasto[0];
	$monto= $dat['monto'];
	$cod_ciudad= $dat['cod_ciudad'];
	$created_by= $dat['created_by'];
	$modified_by= $dat['modified_by'];
	$created_date= $dat['created_date'];
	$modified_date= $dat['modified_date'];
	$gasto_anulado= $dat['gasto_anulado'];
	$cod_proveedor= $dat['cod_proveedor'];
	$nombre_proveedor= $dat['nombre_proveedor'];
	$cod_grupogasto= $dat['cod_grupogasto'];
	$nombre_grupogasto= $dat['nombre_grupogasto'];
	$cod_tipopago= $dat['cod_tipopago'];
	$nombre_tipopago= $dat['nombre_tipopago'];

	$created_date_mostrar="";
	// formatoFechaHora
	if(!empty($created_date)){
		$vector_created_date = explode(" ",$created_date);
		$fechaReg=explode("-",$vector_created_date[0]);
		$created_date_mostrar = $fechaReg[2]."/".$fechaReg[1]."/".$fechaReg[0]." ".$vector_created_date[1];
	}
	// fin formatoFechaHora

	$modified_date_mostrar="";
	// formatoFechaHora
	if(!empty($modified_date)){
		$vector_modified_date = explode(" ",$modified_date);
		$fechaEdit=explode("-",$vector_modified_date[0]);
		$modified_date_mostrar = $fechaEdit[2]."/".$fechaEdit[1]."/".$fechaEdit[0]." ".$vector_modified_date[1];
	}
	// fin formatoFechaHora
	
	/////	
		$sqlRegUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$created_by;
		$respRegUsu=mysqli_query($enlaceCon,$sqlRegUsu);
		$usuReg =" ";
		while($datRegUsu=mysqli_fetch_array($respRegUsu)){
			$usuReg =$datRegUsu['nombres'][0].$datRegUsu['paterno'];		
		}
	//////
		$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
		$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
		$usuMod ="";
		while($datModUsu=mysqli_fetch_array($respModUsu)){
			$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
		}
	////////////
}


$y=5;
$incremento=3;
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(4,$y+3);		$pdf->Cell(68,0,"PAGO NRO. ".$idGasto, 0,0,"C");

$pdf->SetFont('Arial','B',8);

$pdf->SetXY(4,$y+7);		$pdf->Cell(68,0,utf8_decode("Fecha: ").$fecha_gasto_mostrar,0,0,"C");

$pdf->SetFont('Arial','',8);
$pdf->SetXY(4,$y+11);		$pdf->Cell(68,0,"---------------------------------------------------------------------------", 0,0,"C");
$pdf->SetFont('Arial','',8);

$pdf->SetXY(4,$y+15);		$pdf->MultiCell(68,3,utf8_decode($direccionTxt), 0,"C");
$pdf->SetXY(4,$y+23);		$pdf->Cell(68,0,"Telefono:  ".$telefonoTxt,0,0,"C");
$pdf->SetXY(4,$y+27);		$pdf->Cell(68,0,$ciudadTxt,0,0,"C");
$pdf->SetXY(4,$y+31);		$pdf->Cell(68,0,"---------------------------------------------------------------------------", 0,0,"C");

$pdf->SetXY(4,$y+35);		$pdf->Cell(68,0,utf8_decode("Tipo: ").utf8_decode($nombre_tipogasto),0,0,"C");
$pdf->SetXY(4,$y+39);		$pdf->Cell(68,0,utf8_decode("Grupo: ").utf8_decode($nombre_grupogasto),0,0,"C");
$pdf->SetXY(4,$y+43);		$pdf->Cell(68,0,utf8_decode("Proveedor: ").utf8_decode($nombre_proveedor),0,0,"C");
$pdf->SetXY(4,$y+47);		$pdf->Cell(68,0,utf8_decode("Forma Pago: ").$nombre_tipopago,0,0,"C");
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(4,$y+52);		$pdf->Cell(15,0,"DETALLE:",0,0,"L");
$pdf->SetFont('Arial','',8);

$pdf->SetXY(4,$y+56);		$pdf->MultiCell(68,3,utf8_decode($descripcion_gasto), 0,"L");
$auxY=$pdf->GetY();
$pdf->SetXY(4,$auxY+2);		$pdf->Cell(68,0,"---------------------------------------------------------------------------", 0,0,"C");
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(4,$auxY+6);		$pdf->Cell(68,0,"Monto Pagado:".$monto,0,0,"R");
$arrayDecimal=explode('.', $monto);
if(count($arrayDecimal)>1){
	list($montoEntero, $montoDecimal) = explode('.', $monto);
}else{
	list($montoEntero,$montoDecimal)=array($monto,0);
}

if($montoDecimal==""){
	$montoDecimal="00";
}
$txtMonto=NumeroALetras::convertir($montoEntero);
/////////////////////

$pdf->SetXY(4,$auxY+10);		$pdf->MultiCell(68,3,"Son:  $txtMonto"." ".$montoDecimal."/100 Bolivianos",0,"L");
$auxY=$pdf->GetY();
$pdf->SetFont('Arial','',8);
$pdf->SetXY(4,$auxY+2);		$pdf->Cell(68,0,"---------------------------------------------------------------------------",0,0,"C");
$pdf->SetXY(4,$auxY+6);		$pdf->Cell(68,0,"Responsable: $usuReg",0,0,"C");
$pdf->SetXY(4,$auxY+10);		$pdf->Cell(68,0,"---------------------------------------------------------------------------",0,0,"C");

	/*	
	$pdf->SetXY(4,$y+$yyy);		$pdf->MultiCell(68,3,utf8_decode("($codInterno) $nombreMat"),"C");
	$yyy=$yyy+3; 
	$pdf->SetXY(4,$y+$yyy+2);		$pdf->Cell(15,0,"$cantUnit",0,0,"R");
	$pdf->SetXY(19,$y+$yyy+2);		$pdf->Cell(15,0,"$precioUnitFactura",0,0,"R");
	$pdf->SetXY(34,$y+$yyy+2);		$pdf->Cell(15,0,"$descUnit",0,0,"R");
	$pdf->SetXY(49,$y+$yyy+2);		$pdf->Cell(23,0,"$montoUnitProdDesc",0,0,"R");
	
*/


	$yyy=$yyy+5; 


//$pdf->SetXY(4,$y+$yyy+1);		$pdf->Cell(68,0,"---------------------------------------------------------------------------",0,0,"C");		


/*



$pdf->SetXY(4,$y+$yyy);		$pdf->Cell(68,0,"Subtotal Bs. $montoTotal",0,0,"R");
$pdf->SetXY(4,$y+$yyy+4);		$pdf->Cell(68,0,"Descuento Bs. $descuentoVenta",0,0,"R");
$pdf->SetXY(4,$y+$yyy+8);		$pdf->Cell(68,0,"Total Bs. $montoFinal",0,0,"R");
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(4,$y+$yyy+12);		$pdf->Cell(68,0,"Monto a Pagar Bs. $montoFinal",0,0,"R");
$pdf->SetFont('Arial','',8);
$pdf->SetXY(4,$y+$yyy+16);		$pdf->Cell(68,0,"Importe Base Credito Fiscal Bs. $montoFinal",0,0,"R");
$pdf->SetXY(4,$y+$yyy+19);		$pdf->Cell(68,0,"---------------------------------------------------------------------------",0,0,"C");	
	



	$pdf->SetXY(0,$y+$yyy+32);		$pdf->Cell(0,0,"PAGO CON TARJETA",0,0,"C");	


//$yyy=$yyy+1;
$pdf->SetXY(4,$y+$yyy+35);		$pdf->Cell(68,0,"---------------------------------------------------------------------------",0,0,"C");
$pdf->SetXY(4,$y+$yyy+38);		$pdf->Cell(68,0,"Proceso: $codigoVenta",0,0,"C");
$pdf->SetXY(4,$y+$yyy+41);		$pdf->Cell(68,0,"Cajero(a): $nombreFuncionario",0,0,"C");
$pdf->SetXY(4,$y+$yyy+44);		$pdf->Cell(68,0,"---------------------------------------------------------------------------",0,0,"C");
*/



$pdf->Output();

?>