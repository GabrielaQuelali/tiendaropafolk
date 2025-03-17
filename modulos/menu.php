<!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../modules.php">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-print"></i>
        </div>
        <div class="sidebar-brand-text mx-3">IMPRENTA <sup>2</sup></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <!--<li class="nav-item active">
        <a class="nav-link" href="index.html">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Inicio</span></a>
      </li>-->

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Modulos Habilitados
      </div>
     <?php

switch ($_GET["cod_modulo"]) {
  
  case 1:
  $index_frame="../".$carpeta."/listCotizaciones.php";
    ?>
    <li class="nav-item">
        <a class="nav-link" href="../<?=$carpeta?>/listClientesProveedores.php" target="cuerpo">
          <i class="fas fa-fw fa-table"></i>
          <span>Clientes y Marcas</span></a>
    </li>
    <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages1" aria-expanded="true" aria-controls="collapsePages1">
          <i class="fas fa-fw fa-folder"></i>
          <span>Cotizaciones</span>
        </a>
        <div id="collapsePages1" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>

            <a class="collapse-item" href="../<?=$carpeta?>/registrarCotizacion.php" target="cuerpo">Registro Cotizaci&oacute;n</a>
            <a class="collapse-item" href="../<?=$carpeta?>/listCotizaciones.php" target="cuerpo">Cotizaciones</a>
          </div>
        </div>
      </li>

    <li class="nav-item">
        <a class="nav-link" href="../<?=$carpeta?>/listHojasRutas.php" target="cuerpo">
          <i class="fas fa-fw fa-table"></i>
          <span>Hojas de Ruta</span></a>
    </li>      
  
  <li class="nav-item">
        <a class="nav-link" href="../<?=$carpeta?>/listNotasRemision.php" target="cuerpo">
          <i class="fas fa-fw fa-table"></i>
          <span>Notas Remision</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages2" aria-expanded="true" aria-controls="collapsePages2">
          <i class="fas fa-fw fa-folder"></i>
          <span>Ordenes de Trabajo</span>
        </a>
        <div id="collapsePages2" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>

            <a class="collapse-item" href="../<?=$carpeta?>/newOrdenTrabajo.php" target="cuerpo">Registro de O.T.</a>
            <a class="collapse-item" href="../<?=$carpeta?>/listOrdenTrabajo.php" target="cuerpo">Listado de O.T.</a>
          </div>
        </div>
      </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages3" aria-expanded="true" aria-controls="collapsePages3">
          <i class="fas fa-fw fa-folder"></i>
          <span>Reportes</span>
        </a>
        <div id="collapsePages3" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>

            <a class="collapse-item" href="../administracion/filtroRptCuentasCobrar.php" target="cuerpo">Cuentas por Cobrar</a>
            <a class="collapse-item" href="../administracion/filtroRptCotizacionesItem.php" target="cuerpo">Reporte Cotizaciones por Item</a>
            <a class="collapse-item" href="../<?=$carpeta?>/selectAlmacen.php" target="cuerpo">Stock Materiales</a>
          </div>
        </div>
      </li>
    <?php
  break; //fin 1
  case 2:
  $index_frame="../".$carpeta."/registrarIngresoExterno.php";
    ?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages1" aria-expanded="true" aria-controls="collapsePages1">
          <i class="fas fa-fw fa-folder"></i>
          <span>Datos Generales</span>
        </a>
        <div id="collapsePages1" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>

            <a class="collapse-item" href="../<?=$carpeta?>/navegadorGrupos.php" target="cuerpo">Grupos</a>
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorMateriales.php" target="cuerpo">Materiales</a>
            <a class="collapse-item" href="../<?=$carpeta?>/filtroCambioPreciosMateriales.php" target="cuerpo">Cambio Precio Venta Materiales</a>
            <a class="collapse-item" href="../<?=$carpeta?>/listProveedores.php" target="cuerpo">Marcas</a>
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorUnidadesMedida.php" target="cuerpo">Unidades de Medida</a>
          </div>
        </div>
      </li>
    <li class="nav-item">
        <a class="nav-link" href="../cotizaciones/listClientesProveedores.php" target="cuerpo">
          <i class="fas fa-fw fa-table"></i>
          <span>Clientes y Marcas</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages2" aria-expanded="true" aria-controls="collapsePages2">
          <i class="fas fa-fw fa-folder"></i>
          <span>Ingresos</span>
        </a>
        <div id="collapsePages2" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>

            <a class="collapse-item" href="../<?=$carpeta?>/registrarIngresoExterno.php" target="cuerpo">Registro de Ingreso</a>
            <a class="collapse-item" href="../<?=$carpeta?>/listIngresos.php" target="cuerpo">Listado de Ingresos</a>
          </div>
        </div>
      </li>
     
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages3" aria-expanded="true" aria-controls="collapsePages3">
          <i class="fas fa-fw fa-folder"></i>
          <span>Salida</span>
        </a>
        <div id="collapsePages3" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>

            <a class="collapse-item" href="../<?=$carpeta?>/registrarSalida.php" target="cuerpo">Registro de Salida</a>
            <a class="collapse-item" href="../<?=$carpeta?>/listSalidas.php" target="cuerpo">Listado de Salidas</a>
          </div>
        </div>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="../<?=$carpeta?>/listHojasRutas.php" target="cuerpo">
          <i class="fas fa-fw fa-table"></i>
          <span>Hojas de Ruta</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="../<?=$carpeta?>/listOrdenTrabajo.php" target="cuerpo">
          <i class="fas fa-fw fa-table"></i>
          <span>Orden de Trabajo</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages4" aria-expanded="true" aria-controls="collapsePages4">
          <i class="fas fa-fw fa-folder"></i>
          <span>Reportes</span>
        </a>
        <div id="collapsePages4" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>

            <a class="collapse-item" href="../<?=$carpeta?>/filtroRptExistencias.php" target="cuerpo">Reporte de Existencias Con Stock</a>
            <a class="collapse-item" href="../<?=$carpeta?>/filtroRptExistenciasSinStock.php" target="cuerpo">Reporte de Existencias Sin Stock</a>
            <a class="collapse-item" href="../<?=$carpeta?>/filtroRptKardexMovimiento.php" target="cuerpo">Kardex de Movimiento</a>
            <a class="collapse-item" href="../<?=$carpeta?>/rptStockMaterial.php" target="cuerpo">Stock de Materiales</a>
            <a class="collapse-item" href="../administracion/rptClientes.php" target="cuerpo">Reporte Clientes</a>
            <a class="collapse-item" href="../administracion/rptProveedores.php" target="cuerpo">Reporte Marcas</a>
          </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="../<?=$carpeta?>/procesoSalidaCosto.php" target="cuerpo">
          <i class="fas fa-fw fa-table"></i>
          <span>Calculo Costo/Salida</span></a>
    </li>

    <?php
  break; //fin 2
  case 3:
  $index_frame="../".$carpeta."/listPagos.php";
    ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages1" aria-expanded="true" aria-controls="collapsePages1">
          <i class="fas fa-fw fa-folder"></i>
          <span>Adm. Usuarios</span>
        </a>
        <div id="collapsePages1" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>

            <a class="collapse-item" href="../<?=$carpeta?>/navegadorCargos.php" target="cuerpo">Cargos</a>
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorGradoAcademico.php" target="cuerpo">Grados Academicos</a>
            <a class="collapse-item" href="../<?=$carpeta?>/listAreas.php" target="cuerpo">Areas</a>
            <a class="collapse-item" href="../<?=$carpeta?>/listUsuarios.php" target="cuerpo">Usuarios</a>
          </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages2" aria-expanded="true" aria-controls="collapsePages2">
          <i class="fas fa-fw fa-folder"></i>
          <span>Adm. Datos de Generales</span>
        </a>
        <div id="collapsePages2" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>

            <a class="collapse-item" href="../<?=$carpeta?>/navegadorGestiones.php" target="cuerpo">Gestiones</a>
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorCaracteristicas.php" target="cuerpo">Caracteristicas</a>
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorItems.php" target="cuerpo">Items</a>                 
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorTiposCotizacion.php" target="cuerpo">Tipos de Cotizaci&oacute;n</a> 
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorMaquinas.php" target="cuerpo">Maquinas</a>  
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorClientesCategorias.php" target="cuerpo">Categorias de Clientes</a>            
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorClientes.php" target="cuerpo">Clientes</a>  
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorBancos.php" target="cuerpo">Bancos</a> 
            <a class="collapse-item" href="../<?=$carpeta?>/listTipoCambio.php" target="cuerpo">Tipo Cambio</a> 
            <a class="collapse-item" href="../<?=$carpeta?>/listGastos.php" target="cuerpo">Gastos</a>
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorSucursales.php" target="cuerpo">Sucursales</a>
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorAlmacenes.php" target="cuerpo">Almacenes</a>
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorGrupos.php" target="cuerpo">Grupos</a>
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorMateriales.php" target="cuerpo">Materiales</a>
            <a class="collapse-item" href="../<?=$carpeta?>/listProveedores.php" target="cuerpo">Marcas</a>
            <a class="collapse-item" href="../<?=$carpeta?>/navegadorUnidadesMedida.php" target="cuerpo">Unidades de Medida</a>
            <a class="collapse-item" href="../<?=$carpeta?>/filtroCambioPreciosMateriales.php" target="cuerpo">Cambio Precio Venta Materiales</a>  
          </div>
        </div>
    </li>

     <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages3" aria-expanded="true" aria-controls="collapsePages3">
          <i class="fas fa-fw fa-folder"></i>
          <span>Almacenes</span>
        </a>
        <div id="collapsePages3" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>

              <a class="collapse-item" href="../<?=$carpeta?>/selectAlmacenIngreso.php" target="cuerpo">Ingresos</a> 
              <a class="collapse-item" href="../<?=$carpeta?>/selectAlmacenSalida.php" target="cuerpo">Salidas</a> 
          </div>
        </div>
    </li>

     <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages4" aria-expanded="true" aria-controls="collapsePages4">
          <i class="fas fa-fw fa-folder"></i>
          <span>Operaciones</span>
        </a>
        <div id="collapsePages4" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>
              <a class="collapse-item" href="../<?=$carpeta?>/listCotizaciones.php" target="cuerpo">Cotizaciones</a>                               
              <a class="collapse-item" href="../<?=$carpeta?>/listHojasRutas.php" target="cuerpo">Hojas de Ruta</a>                                   
              <a class="collapse-item" href="../<?=$carpeta?>/listOrdenTrabajo.php" target="cuerpo">Orden de Trabajo</a>
              <a class="collapse-item" href="../contable/listGastosGral.php" target="cuerpo">Gastos</a>
          </div>
        </div>
    </li>
  
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages5" aria-expanded="true" aria-controls="collapsePages5">
          <i class="fas fa-fw fa-folder"></i>
          <span>Cobranzas</span>
        </a>
        <div id="collapsePages5" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>
              <a class="collapse-item" href="../<?=$carpeta?>/listPagos.php" target="cuerpo">Listado de Pagos</a>
              <a class="collapse-item" href="../<?=$carpeta?>/newPago.php" target="cuerpo">Registro de Pago</a>
              <a class="collapse-item" href="../<?=$carpeta?>/newPago2.php" target="cuerpo">Registro de Pago Traspaso</a>
          </div>
        </div>
    </li>
              
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages6" aria-expanded="true" aria-controls="collapsePages6">
          <i class="fas fa-fw fa-folder"></i>
          <span>Pagos Marca</span>
        </a>
        <div id="collapsePages6" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>
              <a class="collapse-item" href="../<?=$carpeta?>/newPagoProveedor.php" target="cuerpo">Registro de Pago Marca</a>
              <a class="collapse-item" href="../<?=$carpeta?>/listPagoProveedor.php" target="cuerpo">Lista Pago Marca</a>
          </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages7" aria-expanded="true" aria-controls="collapsePages7">
          <i class="fas fa-fw fa-folder"></i>
          <span>Reportes</span>
        </a>
        <div id="collapsePages7" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>
              <a class="collapse-item" href="../<?=$carpeta?>/filtroRptCuentasCobrar.php" target="cuerpo">Cuentas por Cobrar</a>
              <a class="collapse-item" href="../<?=$carpeta?>/filtroRptCotizacionesItem.php" target="cuerpo">Reporte Cotizaciones por Item</a>
              <a class="collapse-item" href="../<?=$carpeta?>/rptClientes.php" target="cuerpo">Reporte Clientes</a>
              <a class="collapse-item" href="../<?=$carpeta?>/rptProveedores.php" target="cuerpo">Reporte Marcas</a>
          </div>
        </div>
    </li>
    <?php
    break; //fin 3
  case 4:
  $index_frame="../".$carpeta."/listComprobantes.php";
    ?>
    <li class="nav-item">
        <a class="nav-link" href="../cotizaciones/listClientesProveedores.php" target="cuerpo">
          <i class="fas fa-fw fa-table"></i>
          <span>Cuentas</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages1" aria-expanded="true" aria-controls="collapsePages1">
          <i class="fas fa-fw fa-folder"></i>
          <span>Plan de Cuentas</span>
        </a>
        <div id="collapsePages1" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>
              <a class="collapse-item" href="../<?=$carpeta?>/listCuentas.php" target="cuerpo">Cuentas</a>
              <a class="collapse-item" href="../<?=$carpeta?>/listClientesCuentas.php" target="cuerpo">Clientes</a>
              <a class="collapse-item" href="../<?=$carpeta?>/listProveedoresCuentas.php" target="cuerpo">Marcas</a>
          </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="../<?=$carpeta?>/listComprobantes.php" target="cuerpo">
          <i class="fas fa-fw fa-table"></i>
          <span>Comprobantes</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages2" aria-expanded="true" aria-controls="collapsePages2">
          <i class="fas fa-fw fa-folder"></i>
          <span>Operaciones</span>
        </a>
        <div id="collapsePages2" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>
              <a class="collapse-item" href="../administracion/selectAlmacenIngreso.php" target="cuerpo">Ingresos</a> 
              <a class="collapse-item" href="../administracion/selectAlmacenSalida.php" target="cuerpo">Salidas</a>     
              <a class="collapse-item" href="../<?=$carpeta?>/listCotizaciones.php" target="cuerpo">Cotizaciones</a>                                
              <a class="collapse-item" href="../<?=$carpeta?>/listHojasRutas.php" target="cuerpo">Hojas de Ruta</a>                               
              <a class="collapse-item" href="../<?=$carpeta?>/listOrdenTrabajo.php" target="cuerpo">Orden de Trabajo</a>   
              <a class="collapse-item" href="../<?=$carpeta?>/listNotasRemision.php" target="cuerpo">Notas de Remision</a>
              <a class="collapse-item" href="../<?=$carpeta?>/listGastosGral.php" target="cuerpo">Gastos</a>
          </div>
        </div>
    </li>
    
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages3" aria-expanded="true" aria-controls="collapsePages3">
          <i class="fas fa-fw fa-folder"></i>
          <span>Cobranzas</span>
        </a>
        <div id="collapsePages3" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>
              <a class="collapse-item" href="../administracion/listPagos.php" target="cuerpo">Listado de Pagos</a>
              <a class="collapse-item" href="../administracion/newPago.php" target="cuerpo">Registro de Pago</a>
              <a class="collapse-item" href="../administracion/newPago2.php" target="cuerpo">Registro de Pago - Traspaso</a>
          </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages4" aria-expanded="true" aria-controls="collapsePages4">
          <i class="fas fa-fw fa-folder"></i>
          <span>Pagos Marca</span>
        </a>
        <div id="collapsePages4" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>
              <a class="collapse-item" href="../administracion/listPagoProveedor.php" target="cuerpo">Lista Pago Marca</a>
              <a class="collapse-item" href="../administracion/newPagoProveedor.php" target="cuerpo">Registro de Pago Marca</a>
          </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages5" aria-expanded="true" aria-controls="collapsePages5">
          <i class="fas fa-fw fa-folder"></i>
          <span>Facturas</span>
        </a>
        <div id="collapsePages5" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>
                <a class="collapse-item" href="../<?=$carpeta?>/listFacturas.php" target="cuerpo">Listado de Facturas</a>
                <a class="collapse-item" href="../<?=$carpeta?>/newFactura.php" target="cuerpo">Registro de Factura</a>
                <a class="collapse-item" href="../<?=$carpeta?>/imprimirFacturas.php" target="cuerpo">Reporte Facturas</a>
                <a class="collapse-item" href="../<?=$carpeta?>/filtroReporteFacturas.php" target="cuerpo">Reporte Facturas 2</a> 
          </div>
        </div>
    </li>  
    
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages6" aria-expanded="true" aria-controls="collapsePages6">
          <i class="fas fa-fw fa-folder"></i>
          <span>Reportes</span>
        </a>
        <div id="collapsePages6" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>
                <a class="collapse-item" href="../<?=$carpeta?>/filtroLibroDiario.php" target="cuerpo">Libro Diario</a>  
                <a class="collapse-item" href="../<?=$carpeta?>/filtroEstadoCuenta.php" target="cuerpo">Estado de Cuenta</a>      
                <a class="collapse-item" href="../administracion/filtroRptCuentasCobrar.php" target="cuerpo">Cuentas por Cobrar</a>
                <a class="collapse-item" href="../administracion/filtroRptCotizacionesItem.php" target="cuerpo">Reporte Cotizaciones por Item</a>
                <a class="collapse-item" href="../administracion/rptClientes.php" target="cuerpo">Reporte Clientes</a>
                <a class="collapse-item" href="../administracion/rptProveedores.php" target="cuerpo">Reporte Marcas</a>
          </div>
        </div>
    </li> 
    <?php
  break; //fin 4
  case 5:
  $index_frame="../".$carpeta."/listCotizaciones.php";
    ?>
    <li class="nav-item">
        <a class="nav-link" href="../<?=$carpeta?>/listClientesProveedores.php" target="cuerpo">
          <i class="fas fa-fw fa-table"></i>
          <span>Clientes y Marcas</span></a>
    </li>
     
     <li class="nav-item">
        <a class="nav-link" href="../<?=$carpeta?>/listCotizaciones.php" target="cuerpo">
          <i class="fas fa-fw fa-table"></i>
          <span>Cotizaciones</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="../<?=$carpeta?>/listHojasRutas.php" target="cuerpo">
          <i class="fas fa-fw fa-table"></i>
          <span>Hojas de Ruta</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="../<?=$carpeta?>/listOrdenTrabajo.php" target="cuerpo">
          <i class="fas fa-fw fa-table"></i>
          <span>Orden de Trabajo</span></a>
    </li>
   <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages1" aria-expanded="true" aria-controls="collapsePages1">
          <i class="fas fa-fw fa-folder"></i>
          <span>Reportes</span>
        </a>
        <div id="collapsePages1" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white small py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu</h6>
                <a class="collapse-item" href="../<?=$carpeta?>/filtroRptCotizacionesItem.php" target="cuerpo">Reporte Cotizaciones por Item</a>
                <a class="collapse-item" href="../<?=$carpeta?>/rptClientes.php" target="cuerpo">Reporte Clientes</a>
                <a class="collapse-item" href="../<?=$carpeta?>/rptProveedores.php" target="cuerpo">Reporte Marcas</a>
          </div>
        </div>
    </li>    
    <?php
  break; //fin 5
  default:
    
  break;
}

//botones fijos

  $sql="select count(*) from usuarios_modulos where cod_usuario=".$_COOKIE['usuario_global'];
  $resp = mysql_query($sql);
  while($dat=mysql_fetch_array($resp)){ 
      $numModulos=$dat[0];          
  } 
  ?>
  <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        OPCIONES
      </div>

  <?php if($numModulos>1) {?>
      <li class="nav-item">
        <a class="nav-link" href="../modules.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Menu de Modulos</span></a>
      </li>  
  <?php } ?> 


     <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
          <i class="fas fa-fw fa-table"></i>
          <span>Salir de Sistema</span></a>
      </li>   

      


      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
