function validaContato(x){
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

/*if (d.telefone.value == ""){
jAlert("<b>Telefone</b> deve ser preenchido");
d.telefone.focus();
   return false;
}*/

if (d.estado.value == ""){
jAlert("<b>Estado</b> deve ser preenchido");
d.estado.focus();
   return false;
}

if (d.cidade.value == ""){
jAlert("<b>Cidade</b> deve ser preenchida");
d.cidade.focus();
   return false;
}


if (d.msg.value == ""){
jAlert("<b>Mensagem</b> deve ser preenchida");
d.msg.focus();
   return false;
}


return true;

}
