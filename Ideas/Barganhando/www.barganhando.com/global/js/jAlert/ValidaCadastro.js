function validaCadastro(x){
 d = x;
 var rx = new RegExp("^[\\w-_\.]*[\\w-_\.]\@[\\w]\.+[\\w]+[\\w]$");
 var matches;

if (d.nome_cliente.value == ""){
jAlert("<b>Nome</b> deve ser preenchido");
d.nome_cliente.focus();
   return false;
}

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

if (d.senha_usuario.value == ""){
jAlert("<b>Senha</b> deve ser preenchida");
d.senha_usuario.focus();
   return false;
}

if (d.senha_usuario2.value == ""){
jAlert("<b>Confirmar Senha</b> deve ser preenchida");
d.senha_usuario2.focus();
   return false;
}

if (d.senha_usuario.value != d.senha_usuario2.value){
jAlert("<b>Senha e Confirmação de senha</b> devem ser iguais");
d.senha_usuario.focus();
   return false;
}

if (d.cod_cidade.value == ""){
jAlert("Cidade deve ser preenchida");
d.cod_cidade.focus();
   return false;
}

if (d.aceito.checked == false ) {
 jAlert('Você deve clicar na caixa <b>"Li e concordo com os termos de uso do site"</b> para continuar');
 return false;
 }

   var err = 0
   string = d.data_nascimento.value
   var valid = "0123456789/"
   var ok = "yes";
   var temp;
   for (var i=0; i< string.length; i++) {
     temp = "" + string.substring(i, i+1);
     if (valid.indexOf(temp) == "-1") err = 1;
   }
   if (string.length != 10) err=1
   b = string.substring(3, 5)		// month
   c = string.substring(2, 3)		// '/'
   d = string.substring(0, 2)		// day 
   e = string.substring(5, 6)		// '/'
   f = string.substring(6, 10)	// year
   if (b<1 || b>12) err = 1
   if (c != '/') err = 1
   if (d<1 || d>31) err = 1
   if (e != '/') err = 1
   if (f<1850 || f>2050) err = 1
   if (b==4 || b==6 || b==9 || b==11){
     if (d==31) err=1
   }
   if (b==2){
     var g=parseInt(f/4)
     if (isNaN(g)) {
         err=1 
     }
     if (d>29) err=1
     if (d==29 && ((f/4)!=parseInt(f/4))) err=1
   }
   if (err==1) {
   	jAlert("Data inválida");
    return false;
   }

return true;

}

