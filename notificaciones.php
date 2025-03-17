<style type="text/css">
.alert-dark {
    color: #e6992b;
    background-color: #1d2a76;
    border-color: #c6c8ca;
}
.alert-dark .material-icons{
  color: #e6992b;
}


</style>
<?php
if(!function_exists('notificaciones_ingresos')){
  function notificaciones_ingresos(){
    return "Correcto";
  }

$global_almacen=$_COOKIE["global_almacen"];
$narchivo=explode("/",$_SERVER["REQUEST_URI"]);
$archivoname=$narchivo[count($narchivo)-1];
$soloname=explode(".",$archivoname)[0];

if($soloname=="indexVentas"){

$sqlConf="SELECT valor_configuracion FROM configuraciones where id_configuracion=28";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$notificar=0;
while($datConf=mysqli_fetch_array($respConf)){
     $notificar=$datConf[0];   
}

if($notificar==1){
       // INICIO NOTIFICACION
  $sqlNoti="SELECT count(*)as cantidad,s.cod_almacen
  FROM salida_almacenes s, tipos_salida ts, almacenes a 
  where s.cod_tiposalida=ts.cod_tiposalida and s.almacen_destino='$global_almacen' and s.estado_salida=1 and a.cod_almacen=s.cod_almacen and (s.salida_anulada=0 or s.salida_anulada is null) GROUP BY s.cod_almacen";
  $respNoti=mysqli_query($enlaceCon,$sqlNoti);
  $ingresoPendiente=0;
  $ingresoPendienteAlmacen=0;
  while($datNoti=mysqli_fetch_array($respNoti)){
     $ingresoPendiente+=$datNoti[0];
     if($datNoti[1]==1000){
      $ingresoPendienteAlmacen+=$datNoti[0];
     }
     $plural="S";       
  }
  if($ingresoPendiente==1){
      $plural="";
  } 
  $tituloAlmacenCentral="";
  if($ingresoPendienteAlmacen>0){
    $tituloAlmacenCentral.="<small>DESDE ALMACEN CENTRAL ($ingresoPendienteAlmacen)&nbsp;&nbsp;&nbsp;&nbsp;<a href=\'navegador_ingresotransitoalmacen2.php\' target=\'_blank\'>Ver Aquí</a></small>";
  } 
  $ingresosOtros=$ingresoPendiente-$ingresoPendienteAlmacen;
  if($ingresosOtros>0){
    $tituloAlmacenCentral.="<br><small>OTRAS SUCURSALES O ALM VENCIDOS ($ingresosOtros)&nbsp;&nbsp;&nbsp;&nbsp;<a href=\'navegador_ingresotransito.php\' target=\'_blank\'>Ver Aquí</a></small>";
  } 
}


if($ingresoPendiente>0){ //&&!($soloname=="navegador_ingresotransito"||$soloname=="registrar_ingresotransito")
  ?>
<script>
function notificacion_navegador(titulo,texto,imagen) {
if (Notification) {
    if (Notification.permission !== "granted") {
    Notification.requestPermission()
    }    
    var title = titulo
    var extra = {
       icon: imagen,
       body: texto
    }
    var noti = new Notification( title, extra)
    noti.onclick = function(event) {
        event.preventDefault();
        window.open('navegador_ingresotransito.php', '_blank');
    }
    noti.onclose = {
    // Al cerrar
    }
    setTimeout( function() { noti.close() }, 5000)
   }
}

  $(document).ready(function() {
    notificacionMD('dark','bottom','right',0,'add_alert','<div style="position:fixed;left:-20;top:50;"><img src="<?=$dirNoti?>imagenes/notificacion_icono.gif" width="100px" height="100px"></div> TIENE<?=$plural?> <?=$ingresoPendiente?> INGRESO<?=$plural?> PENDIENTE<?=$plural?>','<?=$tituloAlmacenCentral?> <br> <br>Farmacias Bolivia - <?=date("Y")?>','<?="Actualizado en: ".date("d/m H:i")?>');
    // notificacion_navegador('FARMACIAS BOLIVIA','<?=$ingresoPendiente?> INGRESO<?=$plural?> PENDIENTE<?=$plural?> ','<?=$dirNoti?>imagenes/logoMinka.png');
  /*<img src="<?=$dirNoti?>imagenes/logoMinka.png" width="40px" height="40px">*/
  // Swal.fire({
  //     title: '<?=$ingresoPendiente?> INGRESO<?=$plural?> PENDIENTE<?=$plural?>',
  //     html:'Debe ingresar los <b>documentos pendientes</b>, ' +
  //   '<a href="navegador_ingresotransito.php">Aqui</a> ' +
  //   '<b>Ingresos en Transito</b>',
  //     imageUrl: '<?=$dirNoti?>imagenes/pendiente.png',
  //     imageWidth: 200,
  //     imageHeight: 200,
  //     imageAlt: 'Custom image',
  //     width: 600,
  //     padding: '3em',
  //     background: '#fff url(/<?=$dirNoti?>imagenes/trees.png)',
  //     backdrop: ' rgba(0,0,123,0.8) center top no-repeat'
  //  });
    });</script>
  <?php
 }//FIN IF SOLO PAGINAS
//FIN NOTIFICACION
  }//FIN DE SOLO PAGINAS INDEXVENTAS
}