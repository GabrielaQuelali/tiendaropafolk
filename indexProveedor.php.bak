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
	<!--link type="text/css" rel="stylesheet" href="menuLibs/css/demo.css" />
	<link type="text/css" rel="stylesheet" href="menuLibs/dist/jquery.mmenu.css" />
    <link type="text/css" rel="stylesheet" href="stilos.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="menuLibs/dist/jquery.mmenu.js"></script>
	<script type="text/javascript">
		$(function() {
			$('nav#menu').mmenu();
		});
		
</script--> 

		
</head>
<body>
<?php
include("datosUsuario.php");
?>
<div id="page">
	<div class="header">
		<a href="#menu"><span></span></a>
		TuAdmin - Test <?php echo $nombreTiendaRopa;?>
		<div style="position:absolute; width:95%; height:50px; text-align:right; top:0px; font-size: 11px; font-weight: bold; color: #fff;">
			[<?php echo $fechaSistemaSesion?>][<?php echo $horaSistemaSesion;?>]			
            <button onclick="location.href='salir.php'" style="position:relative;z-index:99999;right:0px;" class="boton-azul">Salir</button>
		</div>
		<div style="position:absolute; width:95%; height:50px; text-align:left; top:0px; font-size: 11px; font-weight: bold; color: #fff;">
			[<?php echo $nombreUsuarioSesion?>][<?php echo $nombreAlmacenSesion;?>]
		</div>
	</div>	
	<div class="content">
		<iframe src="inicio_almacenes.php" name="contenedorPrincipal" id="mainFrame"  style="top:50px;" border="1"></iframe>	
	</div>
	
	
		<nav id="menu">
		<div id="panel-menu">
		<ul>

		<li><a href="navegador_preingreso.php" target="contenedorPrincipal">Pre - Ingresos</a></li>
		<li><a href="navegador_ingresos.php" target="contenedorPrincipal">Ingresos</a></li>			
		<li><a href="rpt_op_inv_existencias.php" target="contenedorPrincipal">Existencias</a></li>
		<li><a href="rptOpVentasMarcasDet.php" target="contenedorPrincipal">Ventas x Marcas Detallado</a></li>
		<li><a href="grupos/listaGrupos.php" target="contenedorPrincipal">Codigos de SubGrupos</a></li>


</div>			
	</nav>
</div>
<script src="dist/mmenu.polyfills.js"></script>
<script src="dist/mmenu.js"></script>
<script src="dist/demo.js"></script>
	</body>
</html>