function cargarClasesFrame(){
    $(":text").addClass("form-control");
    $(":text").attr("autocomplete","off"); 
    $("textarea").addClass("form-control");
    $("select").addClass("select-css");

   //if(!$("table").hasClass("table")){
     $("table").addClass("table");
     $("table").addClass("table-sm");
     $("table").addClass("table-success");
     $("#resultados table").addClass("table-bordered");
     $("resultados table tfoot tr th").removeAttr("style");
   //}  
   $(":button").removeClass("boton");
   $(":button").addClass("btn");
   $(":button").addClass("btn-sm");
   $(":button").addClass("btn-success");
   $(":submit").removeClass("boton");
   $(":submit").addClass("btn");
   $(":submit").addClass("btn-sm");
   $(":submit").addClass("btn-success");
   
   if($(".sa-confirm-button-container button").hasClass("btn-success")){
       $(".sa-confirm-button-container button").removeClass("btn-sm");
       $(".sa-confirm-button-container button").removeClass("btn-success");
    } 

   $(":reset").removeClass("boton");
   $(":reset").addClass("btn");
   $(":reset").addClass("btn-sm");
   $(":reset").addClass("btn-success");
   
   $("a").attr("target","cuerpo");

   /*enlaces dentro de tabla*/
   $("table tr td a").each( function () {
      $(this).addClass("btn");
      $(this).addClass("btn-sm");

      switch($(this).html().toLowerCase().trim()){
         case "detalle":
           $(this).attr("title",$(this).html());
           $(this).html("<i class='fas fa-fw fa-list'></i>");
           $(this).addClass("btn-warning");
           $(this).addClass("text-white");
         break;
         case "editar":
           $(this).attr("title",$(this).html());
           $(this).html("<i class='fas fa-fw fa-edit'></i>");
           $(this).addClass("btn-primary");
           $(this).addClass("text-white");
         break;
         case "copiar":
           $(this).attr("title",$(this).html());
           $(this).html("<i class='fas fa-fw fa-copy'></i>");
           $(this).addClass("btn-warning");
           $(this).addClass("text-white");
         break;
         case "anular":
           $(this).attr("title",$(this).html());
           $(this).html("<i class='fas fa-fw fa-trash'></i>");
           $(this).addClass("btn-danger");
           $(this).addClass("text-white");
         break;
         case "&lt;--anterior":
           $(this).attr("title",$(this).html());
           $(this).html("<i class='fas fa-fw fa-arrow-left'></i> ANTERIOR");
           $(this).addClass("btn-info");
           $(this).addClass("text-white");
         break;
         case "siguiente--&gt;":
           $(this).attr("title",$(this).html());
           $(this).html("SIGUIENTE <i class='fas fa-fw fa-arrow-right'></i>");
           $(this).addClass("btn-info");
           $(this).addClass("text-white");
         break;
         default:
           /*$(this).attr("title",$(this).html());*/
           $(this).addClass("btn btn-link");
           $(this).css("text-decoration","none");
         break;
      }       
      
      if($(this).html().toLowerCase().trim().substr(0,1)=="["){
        $(this).addClass("btn btn-secondary text-white");
        $(this).css("text-decoration","none");
     }
  });

  

   $("table tr td a h3").each( function () {
      $(this).replaceWith("<b>"+$(this).html()+"</b>");
   });

   $('table .titulo_tabla td').each( function () {
   	$(this).replaceWith("<th>"+$(this).text()+"</th>");
  } );

   $('table tr td strong').each( function () {
   	$(this).replaceWith("<label class=''>"+$(this).html()+"</label>");
   });

   $("table tr").removeClass("titulo_tabla");	
	 $("#resultados table tr td").attr("style","font-size:9px !important;");


   //buscar titulos o etiquetas th
   $("table tr th").each( function () {
      switch($(this).html().toLowerCase().trim()){
         case "¡no existen registros!":
           $(this).html("<center><h5>No se encontró ningún dato</h5></center>");
           $(this).addClass("text-success");
         break; 
         default:
           
         break;
      }
  });

   //buscar titulos o etiquetas th
   if($("#div_resultado").length>0){
   $("table tr td a").each( function () {
    //var urlBuscar=$(this).attr("href")+"".toLowerCase();
    var urlOnclick=$(this).attr("href")+"";
      if(urlOnclick.search(/javascript:/g) !=-1){
        //alert(urlBuscar);
        urlOnclick=urlOnclick.replace('javascript:','');
        $(this).removeAttr("href");
        $(this).removeAttr("class");
        $(this).attr("style","font-family: verdana, arial, sans-serif;font-size: 11px;padding: 4px;color: #1EA3DC;text-decoration: none;");
        $(this).attr("onclick",urlOnclick);
      }
  }); 
}
   
  /* $("#cotizacion tr td").each( function () {
    var cantidad =  $(this).attr("colspan");
    //alert(cantidad);
    for (var i = 1; i < parseInt(cantidad); i++) {
       $(this).after("<td class='d-none'></td>");
    };
  });
   $("#cotizacion").DataTable();*/
}


function alert(texto){
  console.log("alert_text"+texto);
  swal(
  'Alerta',
  texto,
  'warning'
  );
}
var tablaReporte=null;
var tablaReporteClase=null;
function agregarTablaReporteFiltros(){
  // Setup - add a text input to each footer cell
        $('#tablaReporteFiltros tfoot th').each( function () {
            var title = $(this).text();
            $(this).html( '<input type="text" class="no-form-control" placeholder="'+title+'" />' );
        } );
     
        // DataTable
        tablaReporte = $('#tablaReporteFiltros').DataTable({
            initComplete: function () {
                // Apply the search
                this.api().columns().every( function () {
                    var that = this;
                    $( 'input', this.footer() ).on( 'keyup change clear', function () {
                        if ( that.search() !== this.value ) {
                            that
                                .search( this.value )
                                .draw();
                        }
                    });
                });
            },
            "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            },
            fixedHeader: {
                  header: true,
                  footer: true
            },
            "order": false,
            "pageLength": 100
        }); 
}
function agregarTablaReporteFiltrosClase(){
  // Setup - add a text input to each footer cell
        $('.tablaReporteFiltros tfoot th').each( function () {
            var title = $(this).text();
            $(this).html( '<input type="text" class="no-form-control" placeholder="'+title+'" />' );
        } );
     
        // DataTable
        tablaReporteClase = $('.tablaReporteFiltros').DataTable({
            initComplete: function () {
                // Apply the search
                this.api().columns().every( function () {
                    var that = this;
                    $( 'input', this.footer() ).on( 'keyup change clear', function () {
                        if ( that.search() !== this.value ) {
                            that
                                .search( this.value )
                                .draw();
                        }
                    });
                });
            },
            "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            },
            fixedHeader: {
                  header: true,
                  footer: true
            },
            "order": false,
            "pageLength": 100
        }); 
}


var tablaReporteSimple=null;
var tablaReporteSimpleClase=null;
function agregarTablaReporte(){
   $('#tablaReporte').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            },
            fixedHeader: {
              header: true,
              footer: true
            },
            "searching": false,
            "order": false,
            "pageLength": 50

    } );
}
function agregarTablaReporteClase(){
  $('.tablaReporte').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            },
            fixedHeader: {
              header: true,
              footer: true
            },
            "searching": false,
            "order": false,
            "pageLength": 50

    } );
}

$(document).ready(function() {
  /*if($("#resultados").length>0){
    $("#resultados").html('<div class="card-header py-3"><h6 class="m-0 font-weight-bold text-success">LISTADO</h6></div>'+$("#resultados").html());
   }*/
	cargarClasesFrame();
  setInterval('cargarClasesFrame()',1000);
	$("body").attr("style","visibility:visible !important;background:none;");

  $(".csp").each(function(){
    var cantidad =  $(this).attr("colspan");
    //alert(cantidad);
    for (var i = 1; i < parseInt(cantidad); i++) {
       $(this).after("<td class='d-none'></td>");
    };
   });
   
    agregarTablaReporte();
    agregarTablaReporteClase();

    agregarTablaReporteFiltros();
    agregarTablaReporteFiltrosClase();
        
});