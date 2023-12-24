$("input[type='file']").on("change", function () {
  $(this)
    .parent()
    .next()
    .attr("src", URL.createObjectURL($(this)[0].files[0]));
  // if ($(this).attr("name") == "poster") {
  // } else {
  //   $(".portada").attr("src", URL.createObjectURL($(this)[0].files[0]));
  // }
  $(this).next($("<button class='file_delete'>Eliminar</button>"));
  // $(this)
  //   .prev()
  //   .attr("src", URL.createObjectURL($(this)[0].files[0]));
});
$(".file_delete").click(function () {
  $(this).prev().val("");
  $(this).remove();
});
$("#genero").on("keyup", function (event) {
  if (event.key === "," || event.key === " ") {
    var genero = $(this).val().replace(",", "");
    genero = genero.trim();
    $(this).val("");
    if (genero.length > 0) {
      genero = genero[0].toUpperCase() + genero.substring(1);
      $(".generos").append(
        $(`<div class='generos_contenido'>${genero} | X</div>`)
      );
      $(".genres").append(
        $(`<input type="checkbox" name="generos[]" value="${genero}" checked>`)
      );
    }
  }
});
$("#reparto").on("keyup", function (event) {
  if (event.key === ",") {
    var actor = $(this).val().replace(",", "");
    actor = actor.trim();
    $(this).val("");
    if (actor.length > 0) {
      actor = actor[0].toUpperCase() + actor.substring(1);
      $(".reparto").append($(`<div class='actor_reparto'>${actor} | X</div>`));
      $(".actress").append(
        $(`<input type="checkbox" name="reparto[]" value="${actor}" checked>`)
      );
    }
  }
});
function eliminarReparto(e) {
  $(`.actress input[value="${$(this).html()}"]`).remove();
  $(this).remove();
}
function eliminarGenero(e) {
  $(`.genres input[value="${$(this).html()}"]`).remove();
  $(this).remove();
}
$(document).on("click", ".actor_reparto", eliminarReparto);
$(document).on("click", ".generos_contenido", eliminarGenero);
