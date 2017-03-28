function validaSugira(x){
 d = x;
 var rx = new RegExp("^[\\w-_\.]*[\\w-_\.]\@[\\w]\.+[\\w]+[\\w]$");
 var matches;
 
if (d.nome.value == ""){
jAlert("<b>Nome</b> deve ser preenchido");
d.nome.focus();
   return false;
}

if (d.email.value == ""){
jAlert("<b>E-mail</b> deve ser preenchido.");
d.email.focus();
   return false;
}

if ( ! rx.test(d.email.value) )
 {
jAlert("O <b>e-mail</b> informado é inválido.");
d.email.focus();
   return false;
}

if (d.estado.value == ""){
jAlert("Estado deve ser preenchido");
d.estado.focus();
   return false;
}

if (d.cidade.value == ""){
jAlert("<b>Cidade</b> deve ser preenchida");
d.cidade.focus();
   return false;
}

return true;

}
