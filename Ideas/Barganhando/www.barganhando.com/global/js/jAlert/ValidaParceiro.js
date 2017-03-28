function validaParceiro(x){
 d = x;
 var rx = new RegExp("^[\\w-_\.]*[\\w-_\.]\@[\\w]\.+[\\w]+[\\w]$");
 var matches;
 
if (d.nome_empresa.value == ""){
jAlert("<b>Nome da empresa</b> deve ser preenchido");
d.nome_empresa.focus();
   return false;
}

if (d.estado.value == ""){
jAlert("Estado de atuação deve ser preenchido");
d.estado.focus();
   return false;
}

if (d.cidade.value == ""){
jAlert("Cidade de atuação deve ser preenchida");
d.cidade.focus();
   return false;
}

if (d.nome_contato.value == ""){
jAlert("<b>Nome do contato</b> deve ser preenchido");
d.nome_contato.focus();
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

return true;

}
