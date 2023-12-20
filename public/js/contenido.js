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
        console.log(response);
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
