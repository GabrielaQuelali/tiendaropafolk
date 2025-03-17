<?php
require('fpdf.php');
require('conexionmysqli.php');
require('funciones.php');
require('NumeroALetras.php');
include('phpqrcode/qrlib.php'); 

$codigoVenta=$_GET["codVenta"];

//consulta cuantos items tiene el detalle
$sqlNro="select count(*) from `salida_detalle_almacenes` s where s.`cod_salida_almacen`=$codigoVenta";
$respNro=mysqli_query($enlaceCon,$sqlNro);
$datNro=mysqli_fetch_array($respNro);
$nroItems=$datNro[0];
//$nroItems=mysql_result($respNro,0,0);

$tamanoLargo=230+($nroItems*5)-5;

$pdf=new FPDF('P','mm',array(76,$tamanoLargo));

//header("Content-Type: text/html; charset=iso-8859-1 ");

$pdf->SetMargins(0,0,0);
$pdf->AddPage(); 
$pdf->SetFont('Arial','',8);


$sqlConf="select id, valor from configuracion_facturas where id=1";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$nombreTxt=$datConf[1];
//$nombreTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=10";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$nombreTxt2=$datConf[1];
//$nombreTxt2=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=2";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$sucursalTxt=$datConf[1];
//$sucursalTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=3";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$direccionTxt=$datConf[1];
//$direccionTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=4";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$telefonoTxt=$datConf[1];
//$telefonoTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=5";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$ciudadTxt=$datConf[1];
//$ciudadTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=6";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$txt1=$datConf[1];
//$txt1=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=7";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$txt2=$datConf[1];
//$txt2=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=8";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$txt3=$datConf[1];
//$txt3=mysql_result($respConf,0,1);


$sqlConf="select id, valor from configuracion_facturas where id=9";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$nitTxt=$datConf[1];
//$nitTxt=mysql_result($respConf,0,1);

$sqlDatosFactura="select d.nro_autorizacion, DATE_FORMAT(d.fecha_limite_emision, '%d/%m/%Y'), f.codigo_control, f.nit, f.razon_social, DATE_FORMAT(f.fecha, '%d/%m/%Y') from facturas_venta f, dosificaciones d
	where f.cod_dosificacion=d.cod_dosificacion and f.cod_venta=$codigoVenta";
//echo $sqlDatosFactura;
$respDatosFactura=mysqli_query($enlaceCon,$sqlDatosFactura);
$datDatosFactura=mysqli_fetch_array($respDatosFactura);
$nroAutorizacion=$datDatosFactura[0];//mysql_result($respDatosFactura,0,0);
$fechaLimiteEmision=$datDatosFactura[1];//mysql_result($respDatosFactura,0,1);
$codigoControl=$datDatosFactura[2];//mysql_result($respDatosFactura,0,2);
$nitCliente=$datDatosFactura[3];//mysql_result($respDatosFactura,0,3);
$razonSocialCliente=$datDatosFactura[4];//mysql_result($respDatosFactura,0,4);
$razonSocialCliente=strtoupper($razonSocialCliente);
$fechaFactura=$datDatosFactura[5];//mysql_result($respDatosFactura,0,5);

/*$nroAutorizacion=mysql_result($respDatosFactura,0,0);
$fechaLimiteEmision=mysql_result($respDatosFactura,0,1);
$codigoControl=mysql_result($respDatosFactura,0,2);
$nitCliente=mysql_result($respDatosFactura,0,3);
$razonSocialCliente=mysql_result($respDatosFactura,0,4);
$razonSocialCliente=strtoupper($razonSocialCliente);
$fechaFactura=mysql_result($respDatosFactura,0,5);*/


//datos documento
$sqlDatosVenta="select DATE_FORMAT(s.fecha, '%d/%m/%Y'), t.`nombre`, c.`nombre_cliente`, s.`nro_correlativo`, s.descuento, s.hora_salida
		from `salida_almacenes` s, `tipos_docs` t, `clientes` c
		where s.`cod_salida_almacenes`='$codigoVenta' and s.`cod_cliente`=c.`cod_cliente` and
		s.`cod_tipo_doc`=t.`codigo`";
$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
	$fechaVenta=$datDatosVenta[0];
	$nombreTipoDoc=$datDatosVenta[1];
	$nombreCliente=$datDatosVenta[2];
	$nroDocVenta=$datDatosVenta[3];
	$descuentoVenta=$datDatosVenta[4];
	$descuentoVenta=redondear2($descuentoVenta);
	$horaFactura=$datDatosVenta[5];
}

$y=5;
$incremento=3;

$pdf->SetXY(0,$y+3);		$pdf->Cell(0,0,$nombreTxt,0,0,"C");
$y=$y+3;
$pdf->SetXY(0,$y+3);		$pdf->Cell(0,0,$nombreTxt2,0,0,"C");
$pdf->SetXY(0,$y+6);		$pdf->Cell(0,0,$sucursalTxt,0,0,"C");
$pdf->SetXY(5,$y+9);		$pdf->MultiCell(70,3,$direccionTxt, 0,"C");
$y=$y+6;
$pdf->SetXY(0,$y+12);		$pdf->Cell(0,0,"FACTURA", 0,0,"C");
$pdf->SetXY(0,$y+15);		$pdf->Cell(0,0,$ciudadTxt,0,0,"C");
$pdf->SetXY(0,$y+18);		$pdf->Cell(0,0,"-------------------------------------------------------------------------------", 0,0,"C");
$pdf->SetXY(0,$y+21);		$pdf->Cell(0,0,"NIT: $nitTxt", 0,0,"C");
$pdf->SetXY(0,$y+24);		$pdf->Cell(0,0,"$nombreTipoDoc Nro. $nroDocVenta", 0,0,"C");
$pdf->SetXY(0,$y+27);		$pdf->Cell(0,0,"Autorizacion Nro. $nroAutorizacion", 0,0,"C");


$pdf->SetXY(0,$y+30);		$pdf->Cell(0,0,"-------------------------------------------------------------------------------", 0,0,"C");
$pdf->SetXY(0,$y+32);		$pdf->MultiCell(0,3,utf8_decode($txt1),0,"C");
$pdf->SetXY(0,$y+39);		$pdf->Cell(0,0,"-------------------------------------------------------------------------------", 0,0,"C");

$y=$y+7;
$pdf->SetXY(0,$y+36);		$pdf->Cell(0,0,"FECHA: $fechaFactura $horaFactura",0,0,"C");
$pdf->SetXY(0,$y+39);		$pdf->Cell(0,0,"Sr(es): ".utf8_decode($razonSocialCliente)."",0,0,"C");
$pdf->SetXY(0,$y+42);		$pdf->Cell(0,0,"NIT/CI:	$nitCliente",0,0,"C");

$y=$y+3;
$pdf->SetXY(0,$y+45);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");
$pdf->SetXY(15,$y+48);		$pdf->Cell(0,0,"CANT.");
$pdf->SetXY(40,$y+48);		$pdf->Cell(0,0,"P.U.");
$pdf->SetXY(58,$y+48);		$pdf->Cell(0,0,"IMPORTE");
$pdf->SetXY(0,$y+52);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");


$sqlDetalle="select m.codigo_material, sum(s.`cantidad_unitaria`), m.`descripcion_material`, s.`precio_unitario`, 
		sum(s.`descuento_unitario`), sum(s.`monto_unitario`) from `salida_detalle_almacenes` s, `material_apoyo` m where 
		m.`codigo_material`=s.`cod_material` and s.`cod_salida_almacen`=$codigoVenta 
		group by s.cod_material
		order by s.orden_detalle";
$respDetalle=mysqli_query($enlaceCon,$sqlDetalle);

$yyy=55;

$montoTotal=0;
while($datDetalle=mysqli_fetch_array($respDetalle)){
	$codInterno=$datDetalle[0];
	$cantUnit=$datDetalle[1];
	$nombreMat=$datDetalle[2];
	$precioUnit=$datDetalle[3];
	$descUnit=$datDetalle[4];
	//$montoUnit=$datDetalle[5];
	$montoUnit=($cantUnit*$precioUnit)-$descUnit;
	
	//recalculamos el precio unitario para mostrar en la factura.
	$precioUnitFactura=$montoUnit/$cantUnit;
	
	$cantUnit=redondear2($cantUnit);
	$precioUnit=redondear2($precioUnit);
	$montoUnit=redondear2($montoUnit);
	
	$precioUnitFactura=redondear2($precioUnitFactura);
	
	
	$pdf->SetXY(5,$y+$yyy);		$pdf->MultiCell(50,3,utf8_decode($nombreMat),"C");
	$pdf->SetXY(20,$y+$yyy+4);		$pdf->Cell(0,0,"$cantUnit");
	$pdf->SetXY(40,$y+$yyy+4);		$pdf->Cell(0,0,"$precioUnitFactura");
	$pdf->SetXY(61,$y+$yyy+4);		$pdf->Cell(0,0,"$montoUnit");
	$montoTotal=$montoTotal+$montoUnit;
	
	$yyy=$yyy+6;
}
$pdf->SetXY(0,$y+$yyy+2);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");		
$yyy=$yyy+5;

$montoFinal=$montoTotal-$descuentoVenta;

$pdf->SetXY(42,$y+$yyy);		$pdf->Cell(0,0,"Total Venta:  $montoTotal",0,0);
$pdf->SetXY(44,$y+$yyy+4);		$pdf->Cell(0,0,"Descuento:  $descuentoVenta",0,0);
$pdf->SetXY(43,$y+$yyy+8);		$pdf->Cell(0,0,"Total Final:  $montoFinal",0,0);

list($montoEntero, $montoDecimal) = explode('.', $montoFinal);
if($montoDecimal==""){
	$montoDecimal="00";
}
$txtMonto=NumeroALetras::convertir($montoEntero);
$pdf->SetXY(5,$y+$yyy+11);		$pdf->MultiCell(0,3,"Son:  $txtMonto"." ".$montoDecimal."/100 Bolivianos",0,"L");
$pdf->SetXY(0,$y+$yyy+19);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");		

$yyy=$yyy+10;
$pdf->SetXY(5,$y+$yyy+16);		$pdf->Cell(0,0,"CODIGO DE CONTROL: $codigoControl",0,0,"C");
$pdf->SetXY(5,$y+$yyy+20);		$pdf->Cell(0,0,"FECHA LIMITE DE EMISION: $fechaLimiteEmision",0,0,"C");
$pdf->SetXY(5,$y+$yyy+23);		$pdf->Cell(0,0,"-------------------------------------------------------------------------------",0,0,"C");


$pdf->SetXY(10,$y+$yyy+25);		$pdf->MultiCell(60,3,$txt2,0,"C");

//GENERAMOS LA CADENA DEL QR
$cadenaQR=$nitTxt."|".$nroDocVenta."|".$nroAutorizacion."|".$fechaVenta."|".$montoTotal."|".$montoTotal."|".$codigoControl."|".$nitCliente."|0|0|0|0";
$codeContents = $cadenaQR; 

$fechahora=date("dmy.His");
$fileName="qrs/".$fechahora.$nroDocVenta.".png"; 
    
QRcode::png($codeContents, $fileName,QR_ECLEVEL_L, 4);

$pdf->Image($fileName , 23 ,$y+$yyy+38, 30, 30,'PNG');

$pdf->SetXY(5,$y+$yyy+68);		$pdf->MultiCell(60,3,$txt3,0,"C");
//$pdf->Output();







$tamanoLargo=200+($nroItems*3)-3;

//$pdf=new FPDF('P','mm',array(76,$tamanoLargo));
//$pdf->SetMargins(0,0,0);
$pdf->AddPage(); 
$pdf->SetFont('Arial','',8);

$y=0;
$incremento=3;

$y=$y+(-12);
$pdf->SetXY(0,$y+21);		$pdf->Cell(0,0,$nombreTxt,0,0,"C");
$pdf->SetXY(0,$y+24);		$pdf->Cell(0,0,"$nombreTipoDoc Nro. $nroDocVenta", 0,0,"C");

$y=$y+(-8);
$pdf->SetXY(0,$y+36);		$pdf->Cell(0,0,"FECHA: $fechaVenta",0,0,"C");
$pdf->SetXY(0,$y+39);		$pdf->Cell(0,0,"Sr(es): $razonSocialCliente",0,0,"C");

$pdf->SetXY(0,$y+42);		$pdf->Cell(0,0,utf8_decode("Válido para cambio por 7 días."),0,0,"C");


$y=$y+(2);
$pdf->SetXY(0,$y+45);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");
$pdf->SetXY(15,$y+48);		$pdf->Cell(0,0,"ITEM");
$pdf->SetXY(50,$y+48);		$pdf->Cell(0,0,"Cant.");
//$pdf->SetXY(58,$y+48);		$pdf->Cell(0,0,"Importe");
$pdf->SetXY(0,$y+52);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");


$sqlDetalle="select m.codigo_material, sum(s.`cantidad_unitaria`), m.`descripcion_material`, s.`precio_unitario`, 
		sum(s.`descuento_unitario`), sum(s.`monto_unitario`), m.codigo_barras from `salida_detalle_almacenes` s, `material_apoyo` m where 
		m.`codigo_material`=s.`cod_material` and s.`cod_salida_almacen`=$codigoVenta 
		group by s.cod_material
		order by 3";
$respDetalle=mysqli_query($enlaceCon,$sqlDetalle);

$yyy=55;

$montoTotal=0;
while($datDetalle=mysqli_fetch_array($respDetalle)){
	$codInterno=$datDetalle[0];
	$cantUnit=$datDetalle[1];
	$cantUnit=redondear2($cantUnit);
	$nombreMat=$datDetalle[2];
	$precioUnit=$datDetalle[3];
	$precioUnit=redondear2($precioUnit);
	$descUnit=$datDetalle[4];
	$montoUnit=$datDetalle[5];
	$codigoBarras=$datDetalle[6];
	$montoUnit=redondear2($montoUnit);
	
	$pdf->SetXY(5,$y+$yyy);		$pdf->MultiCell(50,3,"$codigoBarras","C");
	$pdf->SetXY(56,$y+$yyy+1);		$pdf->Cell(0,0,"$cantUnit");
	//$pdf->SetXY(61,$y+$yyy+1);		$pdf->Cell(0,0,"$montoUnit");
	$montoTotal=$montoTotal+$montoUnit;
	
	$yyy=$yyy+6;
}
$pdf->SetXY(0,$y+$yyy+2);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");		
$yyy=$yyy+5;



$pdf->Output();
?>