<?

$result= mysql_query("select * from imagens
						where nome_arquivo_site <> ''
						and   site = '1'
						");
while ($rs= mysql_fetch_object($result)) {
	
	if ($rs->tipo_imagem=="a") {
		$pasta= "artista_";
	}
	else {
		$pasta= "projeto_";
	}
	
	$url = "../uploads/". $pasta . $rs->id_externo ."/". $rs->nome_arquivo_site;
	
	$tamanhos= getimagesize($url);
	
	echo $url ."<br />". $tamanhos[0] ." x ". $tamanhos[1] ."<br><br>";
	
	$result_atualiza= mysql_query("update imagens
									set largura_site = '". $tamanhos[0] ."',
									altura_site = '". $tamanhos[1] ."'
									where id_imagem = '". $rs->id_imagem ."'
									");
	
}

?>
