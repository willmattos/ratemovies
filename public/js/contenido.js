$(".categoria_box,.criticas").on(
  "click",
  ".card,.nombre_contenido",
  function (e) {
    e.preventDefault();
    e.stopPropagation();
    var url = ruta_contenido;
    url = url.replace("numero", $(this).data("codigo"));
    url = url.replace("titulo", $(this).data("nombre"));
    window.location.href = url;
  }
);
$(".categoria_box,.datos_contenido").on(
  "click",
  ".corazon,.fav_conten",
  function (e) {
    e.stopPropagation();
    e.preventDefault();
    var posicion = $(this);
    $.ajax({
      type: "post",
      url: ruta_crear_favorito,
      data: {
        codigo: $(this).parent().data("codigo"),
      },
      dataType: "json",
      success: function (response) {
        var actual = parseInt($(".favoritos").html());
        if (response.respuesta) {
          if (response.tipo) {
            $(posicion).removeClass("corazon_dislike");
            $(posicion).addClass("corazon_fav");
            actual++;
          } else {
            $(posicion).removeClass("corazon_fav");
            $(posicion).addClass("corazon_dislike");
            actual--;
          }
          $(".favoritos").html(actual);
        }
      },
    });
  }
);
var posicion_estrella = $(".estrella_seleccionada").length;
$(".svg").mouseover(function (e) {
  e.preventDefault();
  $(".estrella").removeClass("estrella_seleccionada");
  var posicion = $(this).prev().val();
  for (let index = 0; index < posicion; index++) {
    $(".estrella").eq(index).addClass("estrella_seleccionada");
  }
});
$(".svg").mouseout(function (e) {
  e.preventDefault();
  $(".estrella").removeClass("estrella_seleccionada");
  for (let index = 0; index < posicion_estrella; index++) {
    $(".estrella").eq(index).addClass("estrella_seleccionada");
  }
});
$(".svg").click(function (e) {
  var puntuacion = $(this).prev().val();
  $.ajax({
    type: "post",
    url: ruta_puntuar_contenido,
    data: {
      codigo: $(".contenido_datos").data("codigo"),
      puntuacion: puntuacion,
    },
    dataType: "json",
    async: true,
    success: function (response) {
      if (response.respuesta) {
        posicion_estrella = response.cantidad;
        $(".estrella").removeClass("estrella_seleccionada");
        for (let index = 0; index < posicion_estrella; index++) {
          $(".estrella").eq(index).addClass("estrella_seleccionada");
        }
      }
    },
  });
});
