<?php
require('fpdf.php');
require('conexionmysqli.php');
require('funciones.php');

$codigoVenta=$_GET["codVenta"];

$sqlNro="select count(*) from `salida_detalle_almacenes` s where s.`cod_salida_almacen`=$codigoVenta";
$respNro=mysqli_query($enlaceCon,$sqlNro);
$datNro=mysqli_fetch_array($respNro);
$nroItems=$datNro[0];
//$nroItems=mysql_result($respNro,0,0);

$tamanoLargo=200+($nroItems*3)-3;

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

$y=0;
$incremento=3;

$sqlEmp="select cod_empresa, nombre, nit, direccion, ciudad from datos_empresa";
$respEmp=mysqli_query($enlaceCon,$sqlEmp);
$datEmp=mysqli_fetch_array($respEmp);
$nombreEmpresa=$datEmp[1];//mysql_result($respEmp,0,1);
$nitEmpresa=$datEmp[2];//mysql_result($respEmp,0,2);
$direccionEmpresa=$datEmp[3];//mysql_result($respEmp,0,3);
$ciudadEmpresa=$datEmp[4];//mysql_result($respEmp,0,4);

//$nombreEmpresa=mysql_result($respEmp,0,1);
//$nitEmpresa=mysql_result($respEmp,0,2);
//$direccionEmpresa=mysql_result($respEmp,0,3);
//$ciudadEmpresa=mysql_result($respEmp,0,4);

		
$sqlDatosVenta="select concat(s.fecha,' ',s.hora_salida) as fecha, t.`abreviatura`, 
(select c.nombre_cliente from clientes c where c.cod_cliente=s.cod_cliente) as nombreCliente, 
s.`nro_correlativo`, s.razon_social, s.observaciones
		from `salida_almacenes` s, `tipos_docs` t
		where s.`cod_salida_almacenes`='$codigoVenta'  and
		s.`cod_tipo_doc`=t.`codigo`";
$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
	$fechaVenta=$datDatosVenta[0];
	$nombreTipoDoc=$datDatosVenta[1];
	$nombreCliente=$datDatosVenta[2];
	$nroDocVenta=$datDatosVenta[3];
	$razonSocial=$datDatosVenta[4];
	$obsVenta=$datDatosVenta[5];
}


$pdf->SetXY(0,$y+3);		$pdf->Cell(0,0,$nombreTxt,0,0,"C");
$pdf->SetXY(0,$y+6);		$pdf->Cell(0,0,$nombreTxt2,0,0,"C");

$pdf->SetXY(0,$y+9);		$pdf->Cell(0,0,"$nombreTipoDoc Nro. $nroDocVenta", 0,0,"C");
$pdf->SetXY(0,$y+12);		$pdf->Cell(0,0,"-------------------------------------------------------------------------------", 0,0,"C");


$pdf->SetXY(0,$y+15);		$pdf->Cell(0,0,"FECHA: $fechaVenta",0,0,"C");
$pdf->SetXY(0,$y+18);		$pdf->Cell(0,0,"Sr(es): $nombreCliente",0,0,"C");
$pdf->SetXY(0,$y+21);		$pdf->Cell(0,0,"R.S.: $razonSocial",0,0,"C");
$pdf->SetXY(0,$y+24);		$pdf->Cell(0,0,utf8_decode("Válido para cambio por 7 días."),0,0,"C");

$pdf->SetXY(0,$y+27);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");
$pdf->SetXY(15,$y+30);		$pdf->Cell(0,0,"ITEM");
$pdf->SetXY(50,$y+30);		$pdf->Cell(0,0,"CANT.");
//$pdf->SetXY(53,$y+30);		$pdf->Cell(0,0,"Importe");
$pdf->SetXY(0,$y+33);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");


$sqlDetalle="select s.`orden_detalle`, s.`cantidad_unitaria`, m.`descripcion_material`, s.`precio_unitario`, 
		s.`descuento_unitario`, s.`monto_unitario`, ss.descuento, m.codigo_barras
		from `salida_detalle_almacenes` s, `material_apoyo` m , salida_almacenes ss
		where 
		m.`codigo_material`=s.`cod_material` and s.`cod_salida_almacen`=$codigoVenta and s.cod_salida_almacen=ss.cod_salida_almacenes order by m.descripcion_material";
		//echo $sqlDetalle;
$respDetalle=mysqli_query($enlaceCon,$sqlDetalle);

$yyy=36;

$montoTotal=0;
$abrevMat="";
while($datDetalle=mysqli_fetch_array($respDetalle)){
	$codInterno=$datDetalle[0];
	$cantUnit=$datDetalle[1];
	$cantUnit=redondear2($cantUnit);
	$nombreMat=$datDetalle[2];
	$precioUnit=$datDetalle[3];
	$precioUnit=redondear2($precioUnit);
	$descUnit=$datDetalle[4];
	$montoUnit=$datDetalle[5];
	$montoUnit=redondear2($montoUnit);
	$descuentoNota=$datDetalle[6];
	$codigoBarras=$datDetalle[7];
	$descuentoNota=redondear2($descuentoNota);
	$cadMaterial="";
	if($abrevMat==""){
		$cadMaterial=$nombreMat;
	}else{
		$cadMaterial=$abrevMat;
	}
	

	$pdf->SetXY(7,$y+$yyy);		$pdf->Cell(20,0,"$codigoBarras",0,0);
	$pdf->SetXY(56,$y+$yyy);		$pdf->Cell(0,0,"$cantUnit");
	//$pdf->SetXY(59,$y+$yyy);		$pdf->Cell(0,0,"$montoUnit");
	$montoTotal=$montoTotal+$montoUnit;
	
	$yyy=$yyy+4;
}
$pdf->SetXY(0,$y+$yyy+2);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");		
$yyy=$yyy+5;


$pdf->Output();
?>