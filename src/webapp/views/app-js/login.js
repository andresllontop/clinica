$(document).ready(function() {
  $(".FormularioAjax").submit(function(e) {
    e.preventDefault();
    var form = $(this);
    var tipo = form.attr("data-form");
    var metodo = form.attr("method");
    var listJson = {};
    $.each($(this).serializeArray(), function() {
      if (listJson[this.name]) {
        if (!listJson[this.name].push) {
          listJson[this.name] = [listJson[this.name]];
        }
        listJson[this.name].push(this.value || "");
      } else {
        listJson[this.name] = this.value || "";
      }
    });
    listJson["accion"] = "data";
    // $("#cargarpagina").html(ajax_load);
    ProcesarAjax(metodo, JSON.stringify(listJson));
  });
  function ProcesarAjax(metodo, jsondata) {
    $.ajax({
      type: metodo,
      url: URL + "src/php/Ajax/loginAjax.php",
      data: jsondata,
      cache: false,
      contentType: false,
      processData: false,
      success: function(data) {
        // console.log(data);
        $("#RespuestaAjax").html(data);
        // swal({
        //   title: JSON.parse(data).Titulo,
        //   text: JSON.parse(data).Texto,
        //   type: JSON.parse(data).Tipo,
        //   confirmButtonText: "Aceptar"
        // });
        // $("#cargarpagina").html("");
        // $(".FormularioAjax")[0].reset();
      },
      error: function(e) {
        swal(
          "Ocurrió un error inesperado",
          "Por favor recargue la página",
          "error"
        );
      }
    });
    return false;
  }
});
