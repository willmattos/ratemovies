filtros["generos"].forEach((element) => {
  $(`.lista_generos [value = '${element}']`).attr("checked", true);
});
filtros["fecha"].forEach((element) => {
  $(`[name='fecha[]'][value='${element}']`).attr("checked", true);
});
filtros["tipo"].forEach((element) => {
  $(`[name='tipo[]'][value='${element}']`).attr("checked", true);
});
filtros["ordenar"].forEach((element) => {
  $(`[name='ordenar'][value='${element}']`).attr("checked", true);
});
$(".admin").on("click", function () {
  window.location.href = ruta_admin;
});
$(".categoria_box").on("click", ".card", function (e) {
  e.preventDefault();
  e.stopPropagation();
  var url = ruta_contenido;
  url = url.replace("numero", $(this).data("codigo"));
  url = url.replace("titulo", $(this).data("nombre"));
  window.location.href = url;
});
$("[name='ordenar']").on("change", function () {
  if ($(this).is(":checked")) {
    $("[name='ordenar']").not(this).prop("checked", false);
  }
});
