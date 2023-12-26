function mostrarBotones(e) {
  e.preventDefault();
  $(this).after(
    $(
      `<button type="submit" class="btn_admin admin_confirmar">Eliminar</button><button type="button" class="btn_admin admin_cancelar">Cancelar</button>`
    )
  );
  $(this).remove();
}
function cancelarEliminar(e) {
  e.preventDefault();
 $(".btn_admin").remove();
 $(".admin").append($(`<button class="btn_admin eliminar_contenido">Eliminar</button>`))
}
$(document).on("click", ".eliminar_contenido", mostrarBotones);
$(document).on("click", ".admin_cancelar", cancelarEliminar);
