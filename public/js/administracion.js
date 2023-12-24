function filtrar(nombre) {
  $.ajax({
    type: "post",
    url: ruta_filtrar_usuarios,
    data: {
      nombre: nombre,
    },
    dataType: "json",
    async: true,
    success: function (response) {
      $(".usuarios_box").empty();
      response.forEach((element) => {
        var div = $(`<div class="row usuarios" data-codigo="${element.codigo}">
                  <img src="/Usuario/u${element.codigo}/${element.foto}" alt="foto usuario" class="foto">
                  <div class="enlace nombre_usuario">${element.usuario}</div>
              </div>`);
        var botones = $(
          `<div class="botones" data-codigo="${element.codigo}"></div>`
        );
        if (element.bloquear) {
          $(botones).append(
            `<div class="desbloquear bloq btn">Desbloquear</div>`
          );
        } else {
          $(botones).append(`<div class="bloquear bloq btn">Bloquear</div>`);
        }
        if (element.rol) {
          var texto;
          switch (element.rol) {
            case 1:
              texto = `<div class="quitar_admin adm btn">Quitar ADMIN</div>`;
              break;
            default:
              texto = `<div class="quitar_admin adm btn">Quitar  SUPER ADMIN</div>`;
              break;
          }
          $(botones).append(texto);
        } else {
          $(botones).append(`<div class="admin adm btn">Hacer Admin</div>`);
        }
        $(div).append($(botones));
        $(".usuarios_box").append($(div));
        if (!element.foto.length) {
          $(`[data-codigo="${element.codigo}"] img`).attr("src", userfoto);
        }
      });
    },
  });
}
filtrar("");
$(".filtrar").keyup(function (e) {
  var nombre = $(this).val();
  filtrar(nombre);
});

function bloquearUsuario(e) {
  e.preventDefault();
  var posicion = $(this);
  $.ajax({
    type: "post",
    url: ruta_bloquear_usuarios,
    data: {
      codigo: $(this).parent().parent().data("codigo"),
    },
    dataType: "json",
    async: true,
    success: function (response) {
      if (response.respuesta) {
        if (response.tipo) {
          $(posicion).removeClass("bloquear");
          $(posicion).addClass("desbloquear");
          $(posicion).html("Desbloquear");
        } else {
          $(posicion).removeClass("desbloquear");
          $(posicion).addClass("bloquear");
          $(posicion).html("Bloquear");
        }
      }
    },
  });
}
function quitarAdmin(e) {
  e.preventDefault();
  var posicion = $(this);
  $.ajax({
    type: "post",
    url: ruta_quitar_admin_usuarios,
    data: {
      codigo: $(posicion).parent().parent().data("codigo"),
    },
    dataType: "json",
    async: true,
    success: function (response) {
      if (response.respuesta) {
        $(posicion).html("Hacer Admin");
        $(posicion).removeClass("quitar_admin");
        $(posicion).addClass("admin");
      }
    },
  });
}
function hacerAdmin(e) {
  e.preventDefault();
  $(this).after(
    `<div class='btn dar_rol rol_admin' data-tipo='0'>ADMIN</div><div class='btn dar_rol rol_superadmin' data-tipo='1'>SUPER ADMIN</div>`
  );
  $(this).remove();
}
function darRol(e) {
  e.preventDefault();
  var posicion = $(this);
  $.ajax({
    type: "post",
    url: ruta_admin_usuarios,
    data: {
      codigo: $(posicion).parent().parent().data("codigo"),
      tipo: $(posicion).data("tipo"),
    },
    dataType: "json",
    async: true,
    success: function (response) {
      if (response.respuesta) {
        var texto;
        switch (response.tipo) {
          case 1:
            texto = `<div class="quitar_admin adm btn">Quitar ADMIN</div>`;
            break;
          default:
            texto = `<div class="quitar_admin adm btn">Quitar SUPER ADMIN</div>`;
            break;
        }
        $(posicion).after(texto);
        $(".dar_rol").remove();
      }
    },
  });
}
$(document).on("click", ".bloq", bloquearUsuario);
$(document).on("click", ".quitar_admin", quitarAdmin);
$(document).on("click", ".admin", hacerAdmin);
$(document).on("click", ".dar_rol", darRol);
