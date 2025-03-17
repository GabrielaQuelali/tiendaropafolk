<?php
require('estilos_reportes_almacencentral.php');
require('conexionmysqli.php');
require('function_formatofecha.php');
require('function_comparafechas.php');
require('funciones.php');

$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";
	$sql_nombre_territorio="select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
	$resp_territorio=mysqli_query($enlaceCon,$sql_nombre_territorio);
	$dat_territorio=mysqli_fetch_array($resp_territorio);
	$nombre_territorio=$dat_territorio[0];
	$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
	$resp_nombre_almacen=mysqli_query($enlaceCon,$sql_nombre_almacen);
	$dat_almacen=mysqli_fetch_array($resp_nombre_almacen);
	$nombre_almacen=$dat_almacen[0];
	
	$sql_item="select descripcion_material from material_apoyo where codigo_material='$rpt_item'";
	
	$resp_item=mysqli_query($enlaceCon,$sql_item);
	$dat_item=mysqli_fetch_array($resp_item);
	$nombre_item="$dat_item[0] $dat_item[1]";
	echo "<table align='center' class='textotit'><tr><td align='center'>Reporte Kardex de Existencia Fisica<br>Territorio: 
	<strong>$nombre_territorio</strong> Almacen: <strong>$nombre_almacen</strong> Fecha inicio: <strong>$fecha_ini</strong> Fecha final: 
	<strong>$fecha_fin</strong>Item: <strong>$nombre_item</strong><br>$txt_reporte</th></tr></table>";

	//SACAMOS LA VARIABLE DE INICIO DE OPERACIONES
	$fechaInicioOperaciones=fechaInicioSistema($enlaceCon);
	
	//desde esta parte viene el reporte en si
	$fecha_iniconsulta=$fecha_ini;
	$fecha_finconsulta=$fecha_fin;
	//aqui sacamos las existencias a una fecha
	$sql="select sum(id.cantidad_unitaria) FROM ingreso_almacenes i, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
	i.ingreso_anulado=0 and id.cod_material='$rpt_item' and i.fecha>='$fechaInicioOperaciones' and i.fecha<'$fecha_iniconsulta'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat_existencias_afecha=mysqli_fetch_array($resp);
	$cantidad_ingresada_afecha=$dat_existencias_afecha[0];
	$sql_salidas_afecha="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
	where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
	s.salida_anulada=0 and sd.cod_material='$rpt_item' and s.fecha>='$fechaInicioOperaciones' and s.fecha<'$fecha_iniconsulta'";
	$resp_salidas_afecha=mysqli_query($enlaceCon,$sql_salidas_afecha);
	$dat_salidas_afecha=mysqli_fetch_array($resp_salidas_afecha);
	$cantidad_sacada_afecha=$dat_salidas_afecha[0];
	$cantidad_inicial_kardex=$cantidad_ingresada_afecha-$cantidad_sacada_afecha;
	echo "<br><table class='texto' align='center'><tr><th>Existencia a fecha inicio reporte:  $cantidad_inicial_kardex</th></tr></table>";
	
	
	echo "<center><br><table class='texto' cellspacing='0' width='100%'>";
	
	echo "<tr class='textomini'><th>Fecha</th><th>Tipo</th>
	<th>Tipo Ingreso/Salida</th>
	<th>Nro. Ingreso/Salida</th>
	<th>Entrada</th><th>Salida</th><th>Saldo</th><th>Destino Salida</th><th>Observaciones</th></tr>";
	
	$sql_fechas_ingresos="select distinct(i.fecha) from ingreso_almacenes i, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
	i.ingreso_anulado=0 and id.cod_material='$rpt_item' and i.fecha>='$fecha_iniconsulta' and i.fecha<='$fecha_finconsulta' order by i.fecha";
	$resp_fechas_ingresos=mysqli_query($enlaceCon,$sql_fechas_ingresos);
	$i=1;
	while($dat_fechas_ingresos=mysqli_fetch_array($resp_fechas_ingresos))
	{	$vector_fechas_ingresos[$i]=$dat_fechas_ingresos[0];
		$i++;
	}
	$sql_fechas_salidas="select distinct(s.fecha) from salida_almacenes s, salida_detalle_almacenes sd
	where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
	s.salida_anulada=0 and sd.cod_material='$rpt_item' and s.fecha>='$fecha_iniconsulta' and s.fecha<='$fecha_finconsulta' order by s.fecha";
	
	//echo $sql_fechas_salidas;
	
	$resp_fechas_salidas=mysqli_query($enlaceCon,$sql_fechas_salidas);
	$j=1;
	while($dat_fechas_salidas=mysqli_fetch_array($resp_fechas_salidas))
	{	$vector_fechas_salidas[$j]=$dat_fechas_salidas[0];
		$j++;
	}
	$i=$i-1;
	$j=$j-1;
	$ii=1;
	$jj=1;
	$zz=1;
	
	//echo "vector: ".var_dump($vector_fechas_salidas);
	$vector_final_fechas=array();
	while($ii<=$i and $jj<=$j)
	{	$fecha_ingresos=$vector_fechas_ingresos[$ii];
		$fecha_salidas=$vector_fechas_salidas[$jj];
		if(compara_fechas($fecha_ingresos,$fecha_salidas)<0)
		{	$vector_final_fechas[$zz]=$fecha_ingresos;
			$ii++;
		}
		if(compara_fechas($fecha_ingresos,$fecha_salidas)==0)
		{	$vector_final_fechas[$zz]=$fecha_ingresos;
			$ii++;
			$jj++;
		}
		if(compara_fechas($fecha_ingresos,$fecha_salidas)>0)
		{	$vector_final_fechas[$zz]=$fecha_salidas;
			$jj++;
		}

		$zz++;
	}
	if($ii==$i+1)
	{	for($kk=$jj;$kk<=$j;$kk++)
		{	$vector_final_fechas[$zz]=$vector_fechas_salidas[$kk];
			$zz++;
		}
	}
	if($jj==$j+1)
	{	for($kk=$ii;$kk<=$i;$kk++)
		{	$vector_final_fechas[$zz]=$vector_fechas_ingresos[$kk];
			$zz++;
		}
	}
	//echo "vector: ".var_dump($vector_final_fechas);
	
	$cantidad_kardex=$cantidad_inicial_kardex;
	$suma_ingresos=0;
	$suma_salidas=0;
	for($indice=1;$indice<=$zz;$indice++)
	{	$fecha_consulta=$vector_final_fechas[$indice];
		//hacemos la consulta para ingresos
		$sql_ingresos="select i.nro_correlativo, id.cantidad_unitaria, i.observaciones, ti.nombre_tipoingreso
		from ingreso_almacenes i, ingreso_detalle_almacenes id, tipos_ingreso ti
		where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
		i.ingreso_anulado=0 and id.cod_material='$rpt_item' and i.fecha='$fecha_consulta'";
		$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
		while($dat_ingresos=mysqli_fetch_array($resp_ingresos))
		{	$nro_ingreso=$dat_ingresos[0];
			$cantidad_ingreso=$dat_ingresos[1];
			$cantidad_ingresoFormato=formatonumeroDec($cantidad_ingreso);
			$obs_ingreso=$dat_ingresos[2];
			$nombre_ingreso=$dat_ingresos[3];
			$suma_ingresos=$suma_ingresos+$cantidad_ingreso;
			$cantidad_kardex=$cantidad_kardex+$cantidad_ingreso;
			$cantidad_kardexFormato=formatonumeroDec($cantidad_kardex);
			
			echo "<tr><td align='center'>$fecha_consulta</td><td>Ingreso</td>
			<td align='left'>$nombre_ingreso</td>
			<td align='center'>$nro_ingreso</td><td align='right'>$cantidad_ingresoFormato</td><td align='right'>0</td><td align='right'>$cantidad_kardexFormato</td><td>&nbsp;</td><td>&nbsp;$obs_ingreso</td></tr>";
		}
		//hacemos la consulta para salidas
		$sql_salidas="select s.nro_correlativo, sum(sd.cantidad_unitaria), ts.nombre_tiposalida, s.observaciones, s.territorio_destino, s.cod_salida_almacenes
		from salida_almacenes s, salida_detalle_almacenes sd, tipos_salida ts
		where s.cod_tiposalida=ts.cod_tiposalida and s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
		s.salida_anulada=0 and sd.cod_material='$rpt_item' and s.fecha='$fecha_consulta' group by s.nro_correlativo, s.cod_salida_almacenes";
		
		//echo $sql_salidas;
		
		$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
		$numeroFilasSalidas=mysqli_num_rows($resp_salidas);
		if($numeroFilasSalidas>0){
			//echo $numeroFilasSalidas;
			while($dat_salidas=mysqli_fetch_array($resp_salidas))
			{	$nro_salida=$dat_salidas[0];
				$cantidad_salida=$dat_salidas[1];
				$cantidad_salidaFormato=formatonumeroDec($cantidad_salida);
				$nombre_salida=$dat_salidas[2];
				$obs_salida=$dat_salidas[3];
				$cod_salida=$dat_salidas[5];

				$territorio_destino=$dat_salidas[4];
					$sql_nombre_territorio_destino="select descripcion from ciudades where cod_ciudad='$territorio_destino'";
					$resp_nombre_territorio_destino=mysqli_query($enlaceCon,$sql_nombre_territorio_destino);
					$dat_nombre_territorio_destino=mysqli_fetch_array($resp_nombre_territorio_destino);
					$nombre_territorio_destino=$dat_nombre_territorio_destino[0];
				
				$cantAntKardex=$cantidad_kardex;
				$cantidad_kardex=$cantidad_kardex-$cantidad_salida;
				$cantidadDespKardex=$cantidad_kardex;
				$cantidad_kardexFormato=formatonumeroDec($cantidad_kardex);
				$suma_salidas=$suma_salidas+$cantidad_salida;
				
				if($cantidad_salida>0){
					echo "<tr><td align='center'>$fecha_consulta</td>
					<td>Salida</td>
					<td align='left'>$nombre_salida</td>
					<td align='center'>$nro_salida</td>
					<td align='right'>0</td><td align='right'>$cantidad_salidaFormato</td><td align='right'>$cantidad_kardexFormato</td><td align='left'>&nbsp;$nombre_territorio_destino</td><td>&nbsp;$obs_salida</td></tr>";
				}
			}
		}
	}
	$suma_saldo_final=$suma_ingresos-$suma_salidas+$cantidad_inicial_kardex;
	$suma_saldo_final=formatonumeroDec($suma_saldo_final);
	$suma_ingresos=formatonumeroDec($suma_ingresos);
	$suma_salidas=formatonumeroDec($suma_salidas);
	echo "<tr><td align='center'>&nbsp;</td>
	<td>&nbsp;</td><td align='center'>Totales:</td><td>&nbsp;</td><td align='right'>$suma_ingresos</td><td align='right'>$suma_salidas</td><td align='right'>$suma_saldo_final</td><td align='left'>&nbsp;</td><td>&nbsp;</td></tr>";
	echo "</table></center><br>";
	
	include("imprimirInc.php");
?>