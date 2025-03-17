<?php 
  include("head.php");
  require_once "../functions.php";

  if(isset($_GET['cod_almacen_global'])){
    $cod_almacen_global=$_GET['cod_almacen_global'];
    setcookie("cod_almacen_global", $cod_almacen_global, time()+28800,"/","");  
  }

  
    if(isset($_GET['cod_modulo'])){     
        $cod_modulo=$_GET["cod_modulo"];
        $carpeta=obtenerNombreCarpetaModulo($cod_modulo);            
    ?><div id="wrapper"><?php
        include("menu.php");
     ?>
     <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
     <?php   
        include("nav.php");
                    
        include ("body.php");
     
     ?></div>
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Imprenta 2020</span>
          </div>
        </div>
      </footer>
     <?php
   ?></div><?php          
    }else{
            //pagina error 404       
    }       
      ?>  
<?php 
include("pie.php");
?>