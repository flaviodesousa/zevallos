function validaConvidar(x){

 d = x;
 var rx = new RegExp("^[\\w-_\.]*[\\w-_\.]\@[\\w]\.+[\\w]+[\\w]$");
 var matches;

 if (d.emailAmigo.value == ""){
jAlert("<b>E-mail</b> deve ser preenchido.");
d.emailAmigo.focus();
   return false;
}

if ( ! rx.test(d.emailAmigo.value) )
 {
jAlert("O <b>e-mail</b> informado é inválido.");
d.emailAmigo.focus();
   return false;
}

return true;

}
