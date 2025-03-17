<html>
<head>
	<meta charset="utf-8" />
	<title>MinkaSoftware</title> 
	
	<link rel="shortcut icon" href="imagenes/icon_farma.ico" type="image/x-icon">
	<link type="text/css" rel="stylesheet" href="menuLibs/css/demo.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
	<style>  
	.boton-rojo
{
    text-decoration: none !important;
    padding: 10px !important;
    font-weight: 600 !important;
    font-size: 12px !important;
    color: #ffffff !important;
    background-color: #E73024 !important;
    border-radius: 3px !important;
    border: 2px solid #E73024 !important;
}
.boton-rojo:hover{
    color: #000000 !important;
    background-color: #ffffff !important;
  }
</style>
     <link rel="stylesheet" href="dist/css/demo.css" />
     <link rel="stylesheet" href="dist/mmenu.css" />
	 <link rel="stylesheet" href="dist/demo.css" />
		
</head>
<body>
<?php
include("datosUsuario.php");
?>

<div id="page">

	<div class="header">
		<a href="#menu"><span></span></a>
		TuAdmin - <?=$nombreEmpresa;?>
		<div style="position:absolute; width:95%; height:50px; text-align:right; top:0px; font-size: 15px; font-weight: bold; color: #ffff00;">
			[<?=$fechaSistemaSesion;?> <?=$horaSistemaSesion;?>]
			<button onclick="location.href='salir.php'" style="position:relative;z-index:99999;right:0px;" class="boton-rojo" title="Salir">
				<i class="material-icons" style="font-size: 16px">logout</i>
			</button>			
		</div>
		<div style="position:absolute; width:95%; height:50px; text-align:left; top:0px; font-size: 15px; font-weight: bold; color: #ffff00;">
			[<?=$nombreUsuarioSesion;?>]    [<?=$nombreAlmacenSesion;?>]
			<button onclick="window.contenedorPrincipal.location.href='cambiar_almacen_trabajo.php'" style="position:relative;z-index:99999;right:0px;" class="boton-rojo" title="Cambiar Sucursal" formtarget="contenedorPrincipal">
				<i class="material-icons" style="font-size: 16px">swap_horiz</i>
			</button>
			<button onclick="window.contenedorPrincipal.location.href='editPerfil.php'" style="position:relative;z-index:99999;right:0px;" class="boton-rojo" title="Cambiar Clave de Acceso" formtarget="contenedorPrincipal">
				<i class="material-icons" style="font-size: 16px">person</i>
			</button>	
		</div>
	</div>

	<div class="content">
		<iframe src="inicio_almacenes.php" name="contenedorPrincipal" id="mainFrame"  style="top:50px;" border="1"></iframe>	
	</div>
	
	
		<nav id="menu">
		<div id="panel-menu">
		<ul>
			<li><span>Datos Generales</span>
				<ul>
					<li><a href="programas/proveedores/inicioProveedores.php" target="contenedorPrincipal">Proveedores</a></li>
					<li><span>Gestion de Productos</span>
						<ul>
							<!--li><a href="navegador_tiposmaterial.php" target="contenedorPrincipal">Tipos de Producto</a></li-->
							<li><a href="grupos/list.php?tipo=1" target="contenedorPrincipal">Grupos</a></li>
							<li><a href="marcas/list.php" target="contenedorPrincipal">Marcas</a></li>
							<li><a href="modelos/list.php" target="contenedorPrincipal">Modelos</a></li>
							<li><a href="materiales/list.php" target="contenedorPrincipal">Materiales</a></li>
							<li><a href="tallas/list.php" target="contenedorPrincipal">Tallas</a></li>
							<li><a href="colores/list.php" target="contenedorPrincipal">Colores</a></li>
							<li><a href="generos/list.php" target="contenedorPrincipal">Generos</a></li>
							<li><a href="colecciones/list.php" target="contenedorPrincipal">Colecciones</a></li>
							<li><a href="navegador_material.php?estado=-1&tipo=1" target="contenedorPrincipal">Productos</a></li>
							<!--li><a href="stockProductos_pdf.php" target="_blank">PDF Productos</a></li-->
							<li><a href="navegador_productoCosto.php?estado=-1" target="contenedorPrincipal">Productos para Costo</a></li>
							<!--li><a href="procedimientoActualizaNombreProducto.php" target="contenedorPrincipal">Actualiza nombre Producto</a></li-->
							<!--li><a href="tipos_precio/list.php" target="contenedorPrincipal">Precios y Descuentos</a></li-->
							<li><a href="filtroDefinicionPrecios.php" target="contenedorPrincipal" >Definicion de Precios</a></li>								
						</ul>
					</li>
					<li><a href="navegador_funcionarios1.php" target="contenedorPrincipal">Funcionarios</a></li>
					<li><a href="programas/clientes/inicioClientes.php" target="contenedorPrincipal">Clientes</a></li>
					<li><a href="navegador_dosificaciones.php" target="contenedorPrincipal">Dosificaciones de Facturas</a></li>
					<li><span>Gestion de Almacenes</span>
						<ul>
							<li><a href="navegador_almacenes.php" target="contenedorPrincipal">Almacenes</a></li>
							<li><a href="navegador_tiposingreso.php" target="contenedorPrincipal">Tipos de Ingreso</a></li>
							<li><a href="navegador_tipossalida.php" target="contenedorPrincipal">Tipos de Salida</a></li>
							
						</ul>	
					</li>									
				</ul>	
			</li>
			<li><a href="navegador_cotizaciones.php?estado=-1" target="contenedorPrincipal">Cotizaciones</a></li>

			<li><span>Produccion</span>
				<ul>
					<li><a href="grupos/list.php?tipo=2" target="contenedorPrincipal">Grupos</a></li>					
					<li><a href="navegador_insumos.php?tipo=2&estado=-1&grupo=-1" target="contenedorPrincipal">Insumos</a></li>
					<li><a href="navegador_procesosConstruccion.php?estado=-1" target="contenedorPrincipal">Procesos</a></li>
					<li><a href="programas/proveedores/inicioProveedores.php" target="contenedorPrincipal">Proveedores</a></li>
					<li><a href="navegador_ingresoinsumos.php?estado=-1&tipo=2" target="contenedorPrincipal">Ingresos de Insumos</a></li>
					<li><a href="navegador_lotes.php" target="contenedorPrincipal">Lotes de Produccion</a></li>
					<li><a href="navegador_salidaInsumos.php?tipo=2&estado=-1" target="contenedorPrincipal"> Salidas de Insumos</a></li>	
					
					
				</ul>	
			</li>

			<li><span>Ingreso de Productos</span>
				<ul>
					<li><a href="navegador_ingresomateriales.php?estado=-1&tipo=1" target="contenedorPrincipal">Ingreso de Productos</a></li>
					<li><a href="navegador_ingresotransito.php?tipo=1" target="contenedorPrincipal">Ingreso de Transitos</a></li>
					<!--li><a href="navegador_preingreso.php" target="contenedorPrincipal">Pre - Ingresos</a></li-->
					<!--li><a href="navegadorLiquidacionIngresos.php" target="contenedorPrincipal">Liquidacion de Ingresos</a></li-->
				</ul>	
			</li>
	
			<li><span>Salida de Productos</span>
				<ul>
					<li><a href="navegadorVentas2.php" target="contenedorPrincipal">Ventas</a></li>
					<li><a href="navegador_salidamateriales.php?tipo=1" target="contenedorPrincipal">Traspasos y/o Otros</a></li>									
					<!--li><a href="registrar_salidaventas_manuales.php" target="_blank">Factura Manual de Contigencia</a></li-->
				</ul>	
			</li>
			<!--li><a href="registrar_salidaventas.php?tipo=1" target="_blank">Vender / Facturar</a></li-->
			<!--li><span>Otros Ingresos </span>
				<ul>
				<li>
					<li><a href="listaRecibos.php" target="contenedorPrincipal">Recibos</a></li>
					<li><a href="filtroGeneraRecibosAutomaticos.php" target="contenedorPrincipal">Generar Recibos Automaticos</a></li>
					
				</ul>	
			</li-->
			<li><span>Egresos </span>
				<ul>
					<li><a href="tipos_gasto/list.php" target="contenedorPrincipal">Tipos de Gasto</a></li>
					<li><a href="grupos_gasto/list.php" target="contenedorPrincipal">Grupos de Gasto</a></li>
					<li><a href="listaGastos.php" target="contenedorPrincipal">Gastos</a></li>
				</ul>	
			</li>
			<li><li><a href="listadoPagosProveedor.php" target="contenedorPrincipal">Pagos a Proveedor</a></li>
	
			</li>
			<li><span>Reportes</span>
				<ul>
					<li><a href="rptOpProductos.php" target="contenedorPrincipal">Productos x Punto de Venta</a></li>
					<!--li><span>Productos</span>
						<ul>
							<li><a href="rptOpProductos.php" target="contenedorPrincipal">Productos</a></li>
							<li><a href="rptOpPrecios.php" target="contenedorPrincipal">Precios</a></li>
						</ul>
					</li-->	
					<li><span>Movimiento de Almacen</span>
						<ul>
						
							<li><a href="rpt_op_inv_existencias.php?tipo=1" target="contenedorPrincipal">Existencias Productos</a></li>
							<li><a href="rpt_op_inv_existencias.php?tipo=2" target="contenedorPrincipal">Existencias Insumos</a></li>
							<li><a href="rpt_op_inv_ingresos.php" target="contenedorPrincipal">Ingresos</a></li>
							<li><a href="rpt_op_inv_salidas.php" target="contenedorPrincipal">Salidas</a></li>
							
						</ul>
					</li>	
					<!--li><span>Costos</span>
						<ul>
							<li><a href="rptOpKardexCostos.php" target="contenedorPrincipal">Kardex de Movimiento Precio Promedio</a></li>
							<li><a href="rptOpExistenciasCostos.php" target="contenedorPrincipal">Existencias</a></li>							
						</ul>
					</li-->
					<li><span>Ventas</span>
						<ul>
							<!--li><a href="rptOpVentasDocumento.php" target="contenedorPrincipal">Ventas x Documento</a></li>
							<li><a href="rptOpVentasxItem.php" target="contenedorPrincipal">Ranking de Ventas x Item</a></li-->
							<li><a href="rptOpVentasGeneral.php" target="contenedorPrincipal">Ventas x Documento y Producto</a></li>
							<li><a href="rptOpProductosMasVendidos.php" target="contenedorPrincipal">Productos mas Vendidos</a></li>
							<li><a href="rptOpVentasGeneralSucursal.php" target="contenedorPrincipal">Resumen de Ventas x Sucursal</a></li>
							<!--li><a href="rptOpVentasMarcasDet.php" target="contenedorPrincipal">Ventas x Marcas Detallado</a></li>
							<li><a href="rptOpAjusteVentasProv.php" target="contenedorPrincipal">Ajuste de Ventas x Proveedor</a></li>
							<li><a href="rptOpVentasxPersona.php" target="contenedorPrincipal">Ventas x Vendedor Resumido</a></li>
							<li><a href="rptOpVentasxPersonaDetalle.php" target="contenedorPrincipal">Ventas x Vendedor Detallado</a></li-->
						</ul>	
					</li>
					<li><a href="rptOpCtasxPagarSucursal.php" target="contenedorPrincipal"><span>Cuentas x Pagar</span></a></li>


					<li><span>Gastos</span>
						<ul>
							<li><a href="rptOpGastos.php" target="contenedorPrincipal">Gastos</a></li>
						</ul>
					</li>
					<li><span>Reportes Contables</span>
						<ul>
							<li><a href="rptOpLibroVentas.php" target="contenedorPrincipal">Libro de Ventas</a></li>
							<!--li><a href="" target="contenedorPrincipal">Libro de Compras</a></li-->
							<!--li><a href="rptOpKardexCliente.php" target="contenedorPrincipal">Kardex x Cliente</a></li-->
						</ul>	
					</li>
					<li><a href="rptOpAnalisisCostoPrecio.php" target="contenedorPrincipal">Analisis de Costos y Precios</a></li>
					<li><a href="rptOpAnalisisCostoPrecioNuevos.php" target="contenedorPrincipal">Analisis de Costos y Precios Productos Nuevos</a></li>
					<!--li><span>Utilidades</span>
						<ul>
							<li><a href="rptOpUtilidadesDocumento.php" target="contenedorPrincipal">Utilidades x Documento</a></li>
							<li><a href="rptOpUtilidadesxItem.php" target="contenedorPrincipal">Ranking de Utilidades x Item</a></li>
							<li><a href="rptOpUtilidadesDocItem.php" target="contenedorPrincipal">Utilidades x Documento e Item</a></li>
						</ul>	
					</li-->
					<!--li><span>Cobranzas</span>
						<ul>
							<li><a href="rptOpCobranzas.php" target="contenedorPrincipal">Cobranzas</a></li>
							<li><a href="rptOpCuentasCobrar.php" target="contenedorPrincipal">Cuentas por Cobrar</a></li>
						</ul>	
					</li-->
				</ul>
			</li>
			<!--li><span>Utilitarios</span>
				<ul>
					<li><a href="rptOpAnalisisCosto.php" target="contenedorPrincipal">Analisis de Costos</a></li>
					<li><a href="rptOpArqueoDiario.php?variableAdmin=1" target="contenedorPrincipal" >Arqueo de Caja</a></li>
					<li><a href="registrar_cotizacion_dolar.php" target="contenedorPrincipal"><span>Cotización Dolar</span></a>	
					</li>
					<li><a href="cambiar_almacen_trabajo.php" target="contenedorPrincipal"><span>Cambiar Almacen Trabajo</span></a><li><span>SIAT</span>
						<ul>
							<li><a href="siat_folder/siat_facturacion_offline/facturas_sincafc_list.php" target="contenedorPrincipal">Facturas Off-line</a></li>
							<li><a href="siat_folder/siat_facturacion_offline/facturas_cafc_list.php" target="contenedorPrincipal">Facturas Off-line CAFC</a></li>
							<li><a href="siat_folder/siat_sincronizacion/index.php" target="contenedorPrincipal">Sincronización</a></li>
							<li><a href="siat_folder/siat_puntos_venta/index.php" target="contenedorPrincipal">Puntos Venta</a></li>
							<li><a href="siat_folder/siat_cuis_cufd/index.php" target="contenedorPrincipal">Generación CUIS y CUFD</a></li>
							
						</ul>	
					</li>
					</li>	
				</ul>	
			</li-->
				
</div>			
	</nav>
</div>
<script src="dist/mmenu.polyfills.js"></script>
<script src="dist/mmenu.js"></script>
<script src="dist/demo.js"></script>
	</body>
</html>