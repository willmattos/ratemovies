function comprobarClave(){
    if($("#password").val() != $("#confirmar").val()){
        alert("Las contraseñas deben coincidir")
        return false
    }
    return true
}