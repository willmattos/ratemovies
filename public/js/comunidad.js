$(".criticas").on("click", ".card", function (e) {
    e.preventDefault();
    e.stopPropagation();
    var url = ruta_contenido;
    url = url.replace("numero", $(this).data("codigo"));
    url = url.replace("titulo", $(this).data("nombre"));
    window.location.href = url;
  });