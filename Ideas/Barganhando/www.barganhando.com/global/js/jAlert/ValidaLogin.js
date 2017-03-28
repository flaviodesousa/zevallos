function validaLogin(x){

 d = x;

if (d.email_usuario.value == ""){
jAlert("E-mail deve ser preenchido");
d.email_usuario.focus();
   return false;
}

if (d.senha_usuario.value == ""){
jAlert("Senha deve ser preenchida");
d.senha_usuario.focus();
   return false;
}

return true;

}