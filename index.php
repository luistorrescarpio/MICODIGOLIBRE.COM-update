<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="lib/bootstrap-4.0.0_lite/css/bootstrap.min.css" type="text/css">
  <title>Paginacion Get</title>
</head>

<body>
  <nav class="navbar navbar-expand-md bg-warning navbar-light">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="fa d-inline fa-lg fa-list"></i> 
        <b>UPDATE DATABASE</b>
      </a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar2SupportedContent" aria-controls="navbar2SupportedContent"
        aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
    </div>
  </nav>
  <div class="section py-4">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-4">
          <h4>Seleccione para editar</h4>
          <ul class="list-group" id='booklist'>
            
          </ul>
        </div>
        <div class="col-md-8">
          
          <table align='center'  class="table">
            <tr>
              <td colspan="2" align="center">
                <h2 style="color:#EA942E;"><b>Actualizar Libro</b></h2>
              </td>
            </tr>
            <tr>
              <th>ID: </th>
              <td>
                <input type='text' id='id_libro' class="form-control" disabled maxlength='30' size='10'>
              </td>
            </tr>
            <tr>
              <th>Codigo: </th>
              <td>
                <input type='text' id='codigo' class="form-control" value="" maxlength='30' size='10'>
              </td>
            </tr>
            <tr>
              <th>Titulo: </th>
              <td>
                <input type='text' id='titulo' class="form-control" value="" maxlength='30'>
              </td>
            </tr>
            <tr>
              <th>Autor: </th>
              <td>
                <select id="autor" class="form-control">
                  <option value='gauss'>Gauss</option>
                  <option value='newton'>Newton</option>
                  <option value='mario'>Mario Vargas LL.</option>
                  <option value='aristoteles'>Aristoteles</option>
                </select>
              </td>
            </tr>
            <tr>
              <th>Editorial: </th>
              <td>
                <select id="editorial" class="form-control">
                  <option value='losescritores'>Los escritores</option>
                  <option value='academia'>Academia de Historia</option>
                  <option value='achebe'>Achebe Ediciones</option>
                  <option value='alba'>Alejo Editorial</option>
                </select>
              </td>
            </tr>
            <tr>
              <th>N° Ejemplares: </th>
              <td>
                <input type='number' id='ejemplares' value="" maxlength='30' size="6" class="form-control">
              </td>
            </tr>
            <tr>
              <th>Fecha Registro: </th>
              <td>
                <input type="date" id="fech_registro" value="" class="form-control">
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <button type="button" class="btn btn-warning" onclick="bookUpdate();">Actualizar</button>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                  <!-- Mensaje de confirmación se imprimira aqui -->
                <div class="alert" role="alert" id="message_rsta" style="display: none;"></div>

              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script src="js/jquery-3.2.1.js"></script>
  <script src="lib/bootstrap-4.0.0_lite/js/popper.min.js"></script>
  <script src="lib/bootstrap-4.0.0_lite/js/bootstrap.min.js"></script>
  <script>
  	$(document).ready(function() {
      viewBookList();
    });
    function viewBookList(id_libroSelected = false){
      // Limpiamos la lista antes de mostrar nuevo contenido
      $("#booklist").html("");
      // Solicitamos al servidor mostrarnos la lista de libros registrados
      $.post("query_sql.php",{
        action: "getBookList"
      },function(res){
        for( i in res ){
          $("#booklist").append(
            '<a href="#" onclick="selectedBook('+res[i].id_libro+')">'
              +'<li class="list-group-item" id="book_'+res[i].id_libro+'">'+res[i].titulo+'</li>'
            +'</a>'
            );
        }

        //  Cuando se presiona en el boton actualizar, esto permitira tener aun seleccionado el libro seleccionado aun despues de refrescar los cambios
        if(id_libroSelected)
          $("#book_"+id_libroSelected).addClass('active').addClass('text-warning');

      },"json");
    }
    function selectedBook(id){
      // Resaltar libro seleccionado
      $(".list-group-item").removeClass('active');
      $("#book_"+id).addClass('active');
      // Solicitamos al servidor mostrarnos los datos del libro seleccionado
      $.post("query_sql.php",{
        action: "getDataBook"
        ,id_libro: id
      },function(res){
        var info = res[0]; //[0]: Primer registro encontrado
        console.log(info);
        // Escribimos datos del libro seleccionado en el formulario automaticamente
        $("#id_libro").val(info.id_libro);
        $("#codigo").val(info.codigo);
        $("#titulo").val(info.titulo);
        $("#autor").val(info.autor);
        $("#editorial").val(info.editorial);
        $("#ejemplares").val(info.ejemplares);
        $("#fech_registro").val(info.fech_registro);
      },"json");
    }

    // ********************** UPDATE ****************************//
    function bookUpdate(){
      if($("#id_libro").val() == ""){
        alert("Debe seleccionar un Libro del lado izquierdo para actualizar");
        return;
      }
      // Libro seleccionado para actualizar
      var id_libroSelected = $("#id_libro").val()
      $.post("query_sql.php",{
        action: "update_book" //acción a ejecutar en QUERY_SQL
        ,id_libro: id_libroSelected //Important. Un identificador
        ,codigo: $("#codigo").val()
        ,titulo: $("#titulo").val()
        ,autor: $("#autor").val()
        ,editorial: $("#editorial").val()
        ,ejemplares: $("#ejemplares").val()
        ,fech_registro: $("#fech_registro").val()
      },function(id_res){
        console.log(id_res);
        
        if(id_res>0){ //Si es mayor a cero, hubo registro exitoso
          $("#message_rsta").attr("class","alert alert-warning"); //Color verde
          $("#message_rsta").html("Se actualizo libro con exito [ID: "+id_libroSelected+"]").show();
          viewBookList(id_libroSelected);
        }else{
          $("#message_rsta").attr("class","alert alert-danger"); //Color rojo
          $("#message_rsta").html("Error al Registrar").show();
        }
        setTimeout(function(){
          $("#message_rsta").hide();
        },3000);
      });
    }
    // ********************** FIN - UPDATE ****************************//
  </script>
</body>
</html>