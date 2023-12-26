if($('.splide').length){
  var splide = new Splide(".splide", {
    type: "loop",
    autoplay: true,
    interval: 2500,
    drag   : 'free',
    snap   : true,
    tabindex: "0",
    // pagination: false,
    // arrows: false
  });
  splide.mount();
}
$('.selec_todo').click(function(){
  var posicion = $(this).parent().parent().parent();
  $(posicion).find('.categoria_box').css('display', 'none');
  $(posicion).find('.todo_select').css('display','flex');
  /* $('.seccion_novedades .categoria_box').css('display','none');
  $(".todo_select").css('display','flex'); */
})
$('.selec_pelicula').click(function(){
  var posicion = $(this).parent().parent().parent();
  $(posicion).find('.categoria_box').css('display', 'none');
  $(posicion).find('.pelicula_select').css('display','flex');
  /* $(".pelicula_select").css('display','flex'); */
})
$('.selec_serie').click(function(){
  var posicion = $(this).parent().parent().parent();
  $(posicion).find('.categoria_box').css('display', 'none');
  $(posicion).find('.serie_select').css('display','flex');
  /* $('.seccion_novedades .categoria_box').css('display','none');
  $(".serie_select").css('display','flex'); */
})

$(".categoria_box").on("click", ".card", function (e) {
  e.preventDefault();
  e.stopPropagation();
  var url = ruta_contenido;
  url = url.replace("numero", $(this).data("codigo"));
  url = url.replace("titulo", $(this).data("nombre"));
  window.location.href = url;
});
$(".splide__slide").on("click", ".card-heading,.portada", function (e) {
  e.preventDefault();
  e.stopPropagation();
  var url = ruta_contenido;
  url = url.replace("numero", $(this).parent().data("codigo"));
  url = url.replace("titulo", $(this).parent().data("nombre"));
  window.location.href = url;
});
