function validaRecebe(x){

 d = x;
 var rx = new RegExp("^[\\w-_\.]*[\\w-_\.]\@[\\w]\.+[\\w]+[\\w]$");
 var matches;

 if (d.email_usuario.value == ""){
jAlert("<b>E-mail</b> deve ser preenchido.");
d.email_usuario.focus();
   return false;
}

if ( ! rx.test(d.email_usuario.value) )
 {
jAlert("O <b>e-mail</b> informado é inválido.");
d.email_usuario.focus();
   return false;
}

 if (d.cod_cidade.value == ""){
jAlert("<b>Cidade</b> deve ser preenchida.");
d.cod_cidade.focus();
   return false;
}

return true;

}
