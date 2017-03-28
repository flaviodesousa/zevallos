function limpar(campo){
	if (campo.value == campo.defaultValue){
		campo.value = "";
	}
}

function escrever(campo){
	if (campo.value == ""){
		campo.value = campo.defaultValue;
	}
}