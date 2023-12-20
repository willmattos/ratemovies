var modificarfoto;
var fotohtml;
$(".administrador").click(function(){
  window.location.href = ruta_administracion;
})
function verFavoritos() {
  var ocultos = $(".categoria_favoritos").children().filter(":hidden");
  if (ocultos.length > 0) {
    $(".categoria_favoritos")
      .children(":hidden")
      .first()
      .css("display", "flex");
  }
  if (ocultos.length == 1) {
    $(".mas_favoritos").css("display", "none");
  }
}
function verFollowings() {
  var ocultos = $(".categoria_followings").children().filter(":hidden");
  if (ocultos.length > 0) {
    $(".categoria_followings")
      .children(":hidden")
      .first()
      .css("display", "flex");
  }
  if (ocultos.length == 1) {
    $(".mas_followings").css("display", "none");
  }
}
function verFollowers() {
  var ocultos = $(".categoria_followers").children().filter(":hidden");
  if (ocultos.length > 0) {
    $(".categoria_followers")
      .children(":hidden")
      .first()
      .css("display", "flex");
  }
  if (ocultos.length == 1) {
    $(".mas_followers").css("display", "none");
  }
}
$(".follow").click(function () {
  var div = $(this);
  $.ajax({
    type: "post",
    url: ruta_crear_seguir,
    data: {
      codigo: $(this).attr("class").split(" ")[1],
    },
    dataType: "json",
    success: function (response) {
      var posicion;
      if (usuario_visita) {
        posicion = $(".followers");
      } else {
        posicion = $(".following");
      }
      var actual = parseInt($(posicion).html());
      if (response.respuesta) {
        if (response.tipo == 1) {
          $(div).html("Unfollow");
          actual++;
        } else {
          $(div).html("Follow");
          actual--;
        }
        $(posicion).html(actual);
      }
    },
  });
});
function modificarPerfil() {
  $(".usuario_perfil").html(
    `<input type='text' name='username' value='${username}' >`
  );
  modificarfoto = $(this).attr("src");
  $(this).attr("alt", "cancelar cambios");
  $(this).attr("src", cancelarfoto);
  $(this).addClass("cancelar_modificar");
  $(this).removeClass("editar_perfil");
  $(this).before(
    $(
      `<img class='aceptar_modificar' src='${aceptarfoto}' alt='aceptar cambios'>`
    )
  );
  $(".cabecera").prepend(
    $(
      `<div class='editar_foto'><img src='${$(".cabecera .foto").attr(
        "src"
      )}' alt='foto usuario' class='foto_fondo'><input type="file" name="nuevafoto" id="nuevafoto" class='nuevafoto'><img src='${img_modificar_foto}' alt='cambiarfoto' class='herramienta'></div>`
    )
  );
  $("input[type='file']").on("change", function () {
    $(this)
      .prev()
      .attr("src", URL.createObjectURL($(this)[0].files[0]));
  });
  fotohtml = $(".cabecera .foto").prop("outerHTML");
  $(".cabecera .foto").remove();
}
function aceptarModificar(e) {
  e.preventDefault();
  var posicion = $(this);
  var inputFile = $(".nuevafoto")[0].files[0]
    ? $(".nuevafoto")[0].files[0]
    : null;
  var formData = new FormData();
  var cambiofoto = false;
  if (inputFile) {
    cambiofoto = true;
    formData.append("file", inputFile);
  } else {
    formData = null;
  }

  if (cambiofoto) {
    $.ajax({
      type: "POST",
      url: ruta_actualizar_foto,
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      async: true,
      success: function (response) {},
    });
    $(".cabecera .foto").attr("src", URL.createObjectURL(inputFile));
  }
  var nombre = $(this).prev().children(0).val();
  $.ajax({
    type: "post",
    url: ruta_actualizar_perfil,
    data: {
      username: nombre,
    },
    dataType: "json",
    async: false,
    success: function (response) {
      if (!response.respuesta) {
        $(posicion)
          .prev()
          .children(0)
          .css("border-color", "red")
          .delay(1500)
          .queue(function (next) {
            $(this).css("border-color", "initial");
            next();
          });
      } else {
        $(".usuario_perfil").html(nombre);
        $(posicion).next().attr("alt", "modificar perfil");
        $(posicion).next().attr("src", modificarfoto);
        $(posicion).next().addClass("editar_perfil");
        $(posicion).next().removeClass("cancelar_modificar");
        $(posicion).remove();
        $(".editar_foto img").eq(0).removeClass("foto_fondo");
        $(".editar_foto img").eq(0).addClass("foto");
        $(".cabecera").prepend($(".editar_foto img").eq(0));
        $(".editar_foto").remove();
      }
    },
  });
}
function cancelarModificar(e) {
  e.preventDefault();
  $(this).attr("alt", "modificar perfil");
  $(this).attr("src", modificarfoto);
  $(this).addClass("editar_perfil");
  $(this).removeClass("cancelar_modificar");
  $(this).prev().remove();
  $(".editar_foto").remove();
  $(".cabecera").prepend(fotohtml);
  $(".usuario_perfil").html(username)
}
verFavoritos();
verFavoritos();
verFollowings();
verFollowings();
verFollowers();
verFollowers();

$(document).on("click", ".mas_followings", verFollowings);
$(document).on("click", ".mas_followers", verFollowers);
$(document).on("click", ".mas_favoritos", verFavoritos);
$(document).on("click", ".editar_perfil", modificarPerfil);
$(document).on("click", ".cancelar_modificar", cancelarModificar);
$(document).on("click", ".aceptar_modificar", aceptarModificar);
