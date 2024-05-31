<?php

include("includes/conexao.unico.php");
include("includes/funcoes.php");

if (!empty($_FILES)) {
	$auth= substr(gera_auth(), 0, 5);
	
	//if ($_POST["tipo_imagem"]=="a") {
	
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/vcards/';
		
		if (!is_dir($targetPath)) {
			mkdir(str_replace('//','/',$targetPath), 0755, true);
		}
		
		$targetFilename= $auth ."_". retira_acentos($_FILES['Filedata']['name']);
		$targetFile =  str_replace('//','/',$targetPath) . $targetFilename;
		
		// $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
		// $fileTypes  = str_replace(';','|',$fileTypes);
		// $typesArray = split('\|',$fileTypes);
		// $fileParts  = pathinfo($_FILES['Filedata']['name']);
		
		// if (in_array($fileParts['extension'],$typesArray)) {
		
		move_uploaded_file($tempFile, $targetFile);
		
		//echo "enviado ". $tempFile ." a ". $targetFile;
		
		$id_empresa_sessao= $_SESSION["id_empresa"];
		$id_usuario_sessao= $_SESSION["id_usuario"];
		
		require("includes/vcard/vbook.php");
		
		/*
		
		$result_insere= mysql_query("insert into imagens
										(tipo_imagem, id_externo, id_empresa, nome_arquivo, ordem, legenda, dimensao_imagem, largura, altura, id_usuario)
										values
										('". $_POST["tipo_imagem"] ."', '". $_POST["id_externo"] ."', '". $_POST["id_empresa"] ."', '". $targetFilename ."', '". $ordem ."', '',
										'". $dimensao_imagem ."', '". $l_original ."', '". $a_original ."', '". $_POST["id_usuario"] ."')
										");
		$id_imagem= mysql_insert_id();
		
		echo $id_imagem ."@@@". $targetFilename ."@@@". $dimensao_imagem ."@@@". pega_descricao_dimensao_imagem($dimensao_imagem);*/
	//}
}
?>