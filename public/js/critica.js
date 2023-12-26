$(".reply").hide();
function crearCritica(e) {
  var posicion = $(this);
  var texto = $(posicion).prev().val();
  if (texto) {
    $.ajax({
      type: "post",
      url: ruta_crear_critica,
      data: {
        codigo: contenido_codigo,
        texto: texto,
      },
      dataType: "json",
      success: function (response) {
        if (response.respuesta) {
          $(posicion).prev().val("");

          $(posicion).parent().after(`
            <div class="critica">
					<div class="comentario">
						<div class="comentario_datos">
            <img src="${userfoto}" alt="foto usuario" class="foto">
							<div class="enlace nombre_usuario">${username}</div>
							<div>${response.fecha}</div>
							<div>${contenido_titulo}</div>
						</div>
						<div class="texto">${texto.replace(/\n/g, "<br>")}</div>
						<div class="like">
							<div data-tipo="1" data-codigo="${response.codigo}">
									<label for="" class="darLike">Like</label>
								<div>
								</div>
							</div>
							<div>
								<label for="" class="comentario_reply">Reply</label>
								<div class="cantidad_reply">
								</div>
							</div>
								<div class="eliminar" data-tipo="1" data-codigo="{{ critica.getId() }}">Eliminar</div>
						</div>
					</div>
				</div>
        <div class="reply" style="display: none;">
            <div class="nuevo_comentario">
              <img src="${userfoto}" alt="foto usuario">
            <textarea name="" id="" cols="30" rows="10" placeholder="Escribe un comentario"></textarea>
            <div class="crearComentario btn_coment" data-codigo="${
              response.codigo
            }">comentar</div>
          </div>
        </div>
          `);
        } else {
          $(this)
            .prev()
            .css("border-color", "red")
            .delay(1500)
            .queue(function (next) {
              $(this).css("border-color", "black");
              next();
            });
        }
      },
    });
  } else {
    $(this)
      .prev()
      .css("border-color", "red")
      .delay(1500)
      .queue(function (next) {
        $(this).css("border-color", "initial");
        next();
      });
  }
}
function crearComentario(e) {
  var posicion = $(this);
  var texto = $(posicion).prev().val();
  if (texto) {
    $.ajax({
      type: "post",
      url: ruta_crear_comentario,
      data: {
        codigo: $(posicion).data("codigo"),
        texto: texto,
      },
      dataType: "json",
      success: function (response) {
        if (response.respuesta) {

          $(posicion).prev().val("");
          $(posicion).parent().after(`
          <div class="comentario">
							<div class="comentario_datos">
									<img src="${userfoto}" alt="foto usuario" class="foto">
								<div class="enlace nombre_usuario">${username}</div>
								<div>${response.fecha}</div>
							</div>
							<div class="texto">${texto.replace(/\n/g, "<br>")}</div>
							<div class="like">
								<div data-tipo='0' data-codigo="${response.codigo}">
										<label for="" class="darLike">Like</label>
									<div>
									</div>
								</div>
									<div class="eliminar" data-tipo="0" data-codigo="${
                    response.codigo
                  }">Eliminar</div>
							</div>
						</div>
          `);
          var cantidadActual;
          //chat gpt aqui escribe tu codigo
          var cantidadReply = $(posicion)
            .parent()
            .parent()
            .prev()
            .find(".cantidad_reply");
          cantidadActual = parseInt(cantidadReply.text()) | 0;
          cantidadActual = cantidadActual + 1;
          cantidadReply.html(cantidadActual);
        } else {
          $(this)
            .prev()
            .css("border-color", "red")
            .delay(1500)
            .queue(function (next) {
              $(this).css("border-color", "initial");
              next();
            });
        }
      },
    });
  } else {
    $(this)
      .prev()
      .css("border-color", "red")
      .delay(1500)
      .queue(function (next) {
        $(this).css("border-color", "initial");
        next();
      });
  }
}
function enlaceUsuario(e) {
  e.preventDefault();
  var url = ruta_usuario_perfil;
  url = url.replace("numero", $(this).html());
  window.location.href = url;
}
function darLike(e) {
  var like = $(this);
  $.ajax({
    type: "post",
    url: ruta_crear_like,
    data: {
      codigo: $(this).parent().data("codigo"),
      tipo: $(this).parent().data("tipo"),
    },
    dataType: "json",
    async: true,
    success: function (response) {
      if (response.respuesta) {
        var clase;
        var cantidad = parseInt($(like).next().text()) || 0;
        if (response.tipo == 1) {
          clase = "darLike miLike";
          cantidad = cantidad + 1;
        } else {
          clase = "darLike";
          cantidad = cantidad - 1;
          if (cantidad == 0) cantidad = "";
        }
        $(like).attr("class", clase);
        $(like).next().html(cantidad);
      }
    },
  });
}
function eliminar(e) {
  e.preventDefault();
  if ($(this).html() == "Eliminar") {
    $(this).html("Confirmar");
    var cancelar = $(`<div class="cancelar">Cancelar</div>`);
    $(this).after($(cancelar));
    $(cancelar).click(function () {
      $(this).prev().html("Eliminar");
      $(this).remove();
    });
  } else {
    var posicion = $(this);
    var tipo = $(posicion).data("tipo");
    $.ajax({
      type: "post",
      url: ruta_eliminar_comentario,
      data: {
        codigo: $(posicion).data("codigo"),
        tipo: tipo,
      },
      dataType: "json",
      success: function (response) {
        var cantidadActual;
        if (tipo) {
          $(posicion).parent().parent().parent().next().remove();
          $(posicion).parent().parent().parent().remove();
          cantidadActual = parseInt($(".cantidad_criticas").text());
          cantidadActual = cantidadActual - 1;
          $(".cantidad_criticas").html(cantidadActual);
        } else {
          //chat gpt aqui escribe tu codigo
          var cantidadReply = $(posicion)
            .parent()
            .parent()
            .parent()
            .prev()
            .find(".cantidad_reply");
          cantidadActual = parseInt(cantidadReply.text());
          cantidadActual = cantidadActual - 1;
          if (cantidadActual == 0) cantidadActual = "";
          cantidadReply.html(cantidadActual);
          $(posicion).parent().parent().remove();
        }
      },
    });
  }
}
$(document).on("click", ".crearCritica", crearCritica);
$(document).on("click", ".crearComentario", crearComentario);
$(document).on("click", ".enlace", enlaceUsuario);
$(document).on("click", ".darLike", darLike);
$(document).on("click", ".eliminar", eliminar);
$(document).on("click", ".comentario_reply", function () {
  var posicion = $(this).parent().parent().parent().parent().next();
  $(posicion).slideToggle("slow", function () {
    if ($(posicion).is(":visible")) {
      $(posicion).css("display", "flex");
    }
  });
});
