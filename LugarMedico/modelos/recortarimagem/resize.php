<?
require_once($_SERVER['DOCUMENT_ROOT'].'/libraries/thumb/phpthumb.class.php');

$dimensao=$_POST['dimensao']; //Dimensão da imagem
$url=$_POST['url']; // Caso seja passado uma URL
$fotoTmp=$_FILES['img']['tmp_name']; // Caminho temp do arquivo de upload
$foto=$_FILES['img']['name']; //Arquivo de upload

$img=$_POST['img'];

if (is_file($img)){
	$caminhoFotoTmp=$img;
	$infoArquivo=pathinfo($img);
	//$caminhoFotoTmp= $img;
	//copy($img,$caminhoFotoTmp);
	
}

if (is_file($caminhoFotoTmp)){

	### Dimensões da imagem
	switch ($dimensao){
		case 1:
			$h=80;
			$w=120;
			break;
		default:
			$h=80;
			$w=80;
	}
	###
	
	### Gera imagem e redireciona pra download
	$phpThumb = new phpThumb();
	$phpThumb->setSourceFilename($caminhoFotoTmp);
	$phpThumb->f='jpg';
	/*$phpThumb->h=$h;
	$phpThumb->w=$w;*/
	
	if ($_POST['x']){
		$phpThumb->sx=$_POST['x'];
		$phpThumb->sy=$_POST['y'];
		$phpThumb->sh=$_POST['h'];
		$phpThumb->sw=$_POST['w'];
	}
	
	$phpThumb->far='C';
	$phpThumb->err='index.php?msg='.urlencode($msgErro);
	$phpThumb->down=$infoArquivo['filename'].'.jpg'; // Seta arquivo para download (parametro é o nome da imagem)
	$phpThumb->GenerateThumbnail();
	if ( !$phpThumb->OutputThumbnail() ) die('Erro:' .print_r($phpThumb->debugmessages));
	###
}
//@unlink($caminhoFotoTmp); // Apaga a imagem da pasta temporária
?>