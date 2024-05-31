<?php

include("includes/conexao.unico.php");
include("includes/funcoes.php");

if (!empty($_FILES)) {
	$auth= substr(gera_auth(), 0, 5);
	
	//if ($_POST["tipo_imagem"]=="a") {
		
		//Envia para S3
		if (!class_exists('S3')) require_once 'includes/amazon-s3-php-class/S3.php';
		
		// Check for CURL
		if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
			exit("\nERROR: CURL extension not loaded\n\n");
		
		$s3 = new S3(S3_KEY, S3_SECRET);
		
		$bucket = "nortecurador-site";
		
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$targetPath = BUCKET_CURADOR . '/'. $_POST["tipo_imagem"] .'_'. $_POST["id_externo"] .'/';
		
		//echo $targetPath;
		
		//if (!is_dir($targetPath)) {
		//	mkdir(str_replace('//','/',$targetPath), 0755, true);
		//}
		
		$targetFilename= $auth ."_". retira_acentos($_FILES['Filedata']['name']);
		$targetFile =  str_replace('//','/',$targetPath) . $targetFilename;
		
		// $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
		// $fileTypes  = str_replace(';','|',$fileTypes);
		// $typesArray = split('\|',$fileTypes);
		// $fileParts  = pathinfo($_FILES['Filedata']['name']);
		
		// if (in_array($fileParts['extension'],$typesArray)) {
		
		/*echo $tempFile .' / ';
		echo $targetFilename .' / ';
		echo $targetFile .' / ';
		*/
		
		$envia_s3= $s3->putObjectFile($tempFile, $bucket, $targetFile, S3::ACL_PUBLIC_READ);
		
		if ($envia_s3) {
			//move_uploaded_file($tempFile, $targetFile);
			
			$originalimage= @imagecreatefromjpeg(BUCKET . $targetFile);
			$l_original= @imagesx($originalimage);
			$a_original= @imagesy($originalimage);
			
			$dimensao_imagem= @pega_dimensao_imagem($l_original, $a_original);
			
			$ordem= pega_ultima_ordem_imagens($_POST["tipo_imagem"], $_POST["id_externo"]);
			
			$result_insere= mysql_query("insert into imagens
											(tipo_imagem, id_externo, id_empresa, nome_arquivo, ordem, legenda, dimensao_imagem, largura, altura, id_usuario)
											values
											('". $_POST["tipo_imagem"] ."', '". $_POST["id_externo"] ."', '". $_POST["id_empresa"] ."', '". $targetFilename ."', '". $ordem ."', '',
											'". $dimensao_imagem ."', '". $l_original ."', '". $a_original ."', '". $_POST["id_usuario"] ."')
											");
			$id_imagem= mysql_insert_id();
			
			echo $id_imagem ."@@@". $targetFilename ."@@@". $dimensao_imagem ."@@@". pega_descricao_dimensao_imagem($dimensao_imagem);
		}
		else {
			echo '0';
		}
		//echo $targetPath;
	//}
}
?>