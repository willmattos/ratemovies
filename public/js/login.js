
$(".credenciales").eq(1).hide();
$(".credenciales div img").on("click", () => {
  if ($("#clave").attr("type") == "password") {
    $("#clave").attr("type", "text");
  } else {
    $("#clave").attr("type", "password");
  }
});
$("#recuperar").on("click", () => {
  if ($("h1").html() == "Recupera tu cuenta") {
    $("section form").attr("action",login)
    $("h1").html("Bienvenido");
    $("p").html("Hey ingresa tus credenciales para entrar en tu cuenta");
    $("#recuperar").html("¿Olvidaste tu contraseña?");
    $("button").html("Sign in");
  } else {
    $("section form").attr("action",recuperar)
    $("h1").html("Recupera tu cuenta");
    $("p").html("Tranquilo, tu cuenta está a salvo");
    $("#recuperar").html("¿Ya te acordaste?");
    $("button").html("Enviar Correo");
  }
  $(".credenciales").eq(2).slideToggle();
  $("#crear").slideToggle();
});
$("#crear").on("click", () => {
  $("#recuperar").slideToggle();
  $(".credenciales")
    .eq(1)
    .slideToggle("slow", function () {
      if ($(".credenciales").eq(1).is(":visible")) {
        $(".credenciales").eq(1).css("display", "flex");
      }
    });
  if ($("h1").html() == "Crear cuenta") {
    $("section form").attr("action",login)
    $("h1").html("Bienvenido");
    $("p").html("Hey ingresa tus credenciales para entrar en tu cuenta");
    $(".credenciales").eq(0).children(0).html("Correo/Usuario:");
    $(".credenciales").eq(0).children(1).attr("type", "text");
    $("#crear").html("¿No tienes cuenta? <strong>Registrate ahora</strong>");
    $("button").html("Sign in");
  } else {
    $("section form").attr("action",crear)
    $("h1").html("Crear cuenta");
    $("p").html("Hoy es tu día de suerta, hoy serás parte de nuestra familia");
    $(".credenciales").eq(0).children(0).html("Correo:");
    $(".credenciales").eq(0).children(1).attr("type", "email");
    $("#crear").html("¿Ya tienes una cuenta?");
    $("button").html("Crear cuenta");
  }
});
