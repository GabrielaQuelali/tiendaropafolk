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

$idRecibo=$_GET["idRecibo"];
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

$sqlRecibo = " select r.id_recibo,r.fecha_recibo,r.cod_ciudad,ciu.descripcion,
r.nombre_recibo,r.desc_recibo,r.monto_recibo,
r.created_by,r.modified_by,r.created_date,r.modified_date, r.cel_recibo,r.recibo_anulado,r.cod_tipopago, tp.nombre_tipopago
from recibos r inner join ciudades ciu on (r.cod_ciudad=ciu.cod_ciudad)
inner join tipos_pago tp on(r.cod_tipopago=tp.cod_tipopago)
where r.id_recibo=".$idRecibo." and r.cod_ciudad=".$cod_ciudad." order by r.id_recibo DESC,r.cod_ciudad desc";
//echo "consulta=".$sqlRecibo;

$respRecibo=mysqli_query($enlaceCon,$sqlRecibo);
while ($dat = mysqli_fetch_array($respRecibo)) {
	$id_recibo= $dat['id_recibo'];
	$fecha_recibo= $dat['fecha_recibo'];
	$vector_fecha_recibo=explode("-",$fecha_recibo);
	$fecha_recibo_mostrar=$vector_fecha_recibo[2]."/".$vector_fecha_recibo[1]."/".$vector_fecha_recibo[0];
	$cod_ciudad= $dat['cod_ciudad'];
	$descripcion= $dat['descripcion'];
	$nombre_recibo= $dat['nombre_recibo'];
	$desc_recibo= $dat['desc_recibo'];
	$monto_recibo= $dat['monto_recibo'];
	$created_by= $dat['created_by'];
	$modified_by= $dat['modified_by'];
	$created_date= $dat['created_date'];
	$modified_date= $dat['modified_date'];
	$cel_recibo = $dat['cel_recibo'];
	$recibo_anulado= $dat['recibo_anulado'];
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
	$modified_date= $dat['modified_date'];
	$cel_recibo = $dat['cel_recibo'];
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
$pdf->SetXY(4,$y+3);		$pdf->Cell(68,0,"RECIBO NRO. ".$id_recibo, 0,0,"C");
$pdf->SetFont('Arial','',8);
$pdf->SetXY(4,$y+7);		$pdf->Cell(68,0,utf8_decode("(Sin Derecho a Crédito Fiscal)"),0,0,"C");
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(4,$y+11);		$pdf->Cell(68,0,utf8_decode("Cliente: ").utf8_decode($nombre_recibo),0,0,"C");
$pdf->SetXY(4,$y+15);		$pdf->Cell(68,0,utf8_decode("Telf. Cliente: ").utf8_decode($cel_recibo),0,0,"C");
$pdf->SetXY(4,$y+19);		$pdf->Cell(68,0,utf8_decode("Fecha: ").$fecha_recibo_mostrar,0,0,"C");
$pdf->SetXY(4,$y+23);		$pdf->Cell(68,0,utf8_decode("Tipo Pago: ").$nombre_tipopago,0,0,"C");
$pdf->SetFont('Arial','',8);
$pdf->SetXY(4,$y+26);		$pdf->Cell(68,0,"---------------------------------------------------------------------------", 0,0,"C");
$pdf->SetFont('Arial','',8);

$pdf->SetXY(4,$y+30);		$pdf->MultiCell(68,3,utf8_decode($direccionTxt), 0,"C");
$pdf->SetXY(4,$y+38);		$pdf->Cell(68,0,"Telefono:  ".$telefonoTxt,0,0,"C");
$pdf->SetXY(4,$y+42);		$pdf->Cell(68,0,$ciudadTxt,0,0,"C");
$pdf->SetXY(4,$y+46);		$pdf->Cell(68,0,"---------------------------------------------------------------------------", 0,0,"C");
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(4,$y+52);		$pdf->Cell(15,0,"DETALLE:",0,0,"L");
$pdf->SetFont('Arial','',8);

$pdf->SetXY(4,$y+56);		$pdf->MultiCell(68,3,utf8_decode($desc_recibo), 0,"L");
$auxY=$pdf->GetY();
$pdf->SetXY(4,$auxY+2);		$pdf->Cell(68,0,"---------------------------------------------------------------------------", 0,0,"C");
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(4,$auxY+6);		$pdf->Cell(68,0,"Monto Recibido:".$monto_recibo,0,0,"R");
$arrayDecimal=explode('.', $monto_recibo);
if(count($arrayDecimal)>1){
	list($montoEntero, $montoDecimal) = explode('.', $monto_recibo);
}else{
	list($montoEntero,$montoDecimal)=array($monto_recibo,0);
}

if($montoDecimal==""){
	$montoDecimal="00";
}
$txtMonto=NumeroALetras::convertir($montoEntero);
/////////////////////

$pdf->SetXY(4,$auxY+10);		$pdf->MultiCell(68,3,"Son:  $txtMonto"." ".$montoDecimal."/100 Bolivianos",0,"L");
$pdf->SetFont('Arial','',8);
$pdf->SetXY(4,$auxY+14);		$pdf->Cell(68,0,"---------------------------------------------------------------------------",0,0,"C");
$pdf->SetXY(4,$auxY+18);		$pdf->Cell(68,0,"Responsable: $usuReg",0,0,"C");
$pdf->SetXY(4,$auxY+22);		$pdf->Cell(68,0,"---------------------------------------------------------------------------",0,0,"C");

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