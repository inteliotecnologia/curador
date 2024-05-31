<?

function faz_embed_video($video, $largura, $altura) {
	
	if (strpos($video, "vimeo")) {
		$parte_video= explode("/", $video);
		$count_video= count($parte_video);
		
		$id_video= $parte_video[$count_video-1];
		
		$retorno= '<iframe src="http://player.vimeo.com/video/'. $id_video .'?portrait=0&amp;color=22B2BA" width="'. $largura .'" height="'. $altura .'" frameborder="0"></iframe>';
	} elseif (strpos($video, "youtube")) {
		$id_video= extrai_link_youtube($video);
		
		$retorno= pega_video_youtube($id_video, $largura, $altura);
	}
	
	return($retorno);
}

function extrai_link_youtube($link) {
	//http://www.youtube.com/watch?v=lsO6D1rwrKc&v1
	//http://www.youtube.com/watch?v=Dji8M2oBVTo&mode=related&search=
	
	$novo= explode("?v=", $link);
	if (strpos($novo[1], "&")) {
		$novo= explode("&", $novo[1]);
		$link_novo= $novo[0];
	}
	else
		$link_novo= $novo[1];
	
	return($link_novo);
}


function pega_video_youtube($codigo, $largura, $altura) {
	if ($codigo!="")
		$retorno= ' <object width="'. $largura .'" height="'. $altura .'">
						<param name="movie" value="http://www.youtube.com/v/'. $codigo .'"></param>
						<param name="wmode" value="transparent"></param>
						<embed src="http://www.youtube.com/v/'. $codigo .'" type="application/x-shockwave-flash" wmode="transparent" width="'. $largura .'" height="'. $altura .'"></embed>
					</object>';
	//else $retorno= "Sem vídeo.";
	
	return($retorno);
}

function pega_imagem($tipo_imagem, $id_externo) {
	$result= mysql_query("select * from imagens
							where tipo_imagem= '$tipo_imagem'
							and   id_externo = '$id_externo'
							and   site = '1'
							and   miniatura_destaque <> '1'
							and   miniatura_destaque <> '2'
							and   miniatura_destaque <> '3'
							and   miniatura_destaque <> '4'
							order by ordem asc limit 1
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	return($rs->nome_arquivo);	
}

function pega_imagem_site($tipo_imagem, $id_externo) {
	$result= mysql_query("select * from imagens
							where tipo_imagem= '$tipo_imagem'
							and   id_externo = '$id_externo'
							and   site = '1'
							and   miniatura_destaque <> '1'
							and   miniatura_destaque <> '2'
							and   miniatura_destaque <> '3'
							and   miniatura_destaque <> '4'
							order by ordem asc limit 1
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	return($rs->nome_arquivo_site);	
}

function pega_legenda_imagem_site($tipo_imagem, $id_externo) {
	$result= mysql_query("select * from imagens
							where tipo_imagem= '$tipo_imagem'
							and   id_externo = '$id_externo'
							and   site = '1'
							and   miniatura_destaque <> '1'
							and   miniatura_destaque <> '2'
							and   miniatura_destaque <> '3'
							and   miniatura_destaque <> '4'
							order by ordem asc limit 1
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	return($rs->legenda);	
}

function pega_legenda_en_imagem_site($tipo_imagem, $id_externo) {
	$result= mysql_query("select * from imagens
							where tipo_imagem= '$tipo_imagem'
							and   id_externo = '$id_externo'
							and   site = '1'
							and   miniatura_destaque <> '1'
							and   miniatura_destaque <> '2'
							and   miniatura_destaque <> '3'
							and   miniatura_destaque <> '4'
							order by ordem asc limit 1
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	return($rs->legenda_en);	
}

function pega_imagem_miniatura($tipo_imagem, $id_externo, $miniatura) {
	$result= mysql_query("select * from imagens
							where tipo_imagem= '$tipo_imagem'
							and   id_externo = '$id_externo'
							and   miniatura_destaque = '". $miniatura ."'
							order by ordem asc limit 1
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	return($rs->nome_arquivo);	
}

function pega_imagem_miniatura_site($tipo_imagem, $id_externo, $miniatura) {
	$result= mysql_query("select * from imagens
							where tipo_imagem= '$tipo_imagem'
							and   id_externo = '$id_externo'
							and   miniatura_destaque = '". $miniatura ."'
							order by ordem asc limit 1
							") or die(mysql_error());
	
	$num= mysql_num_rows($result);
	
	//pesquisar não marcadas (GIF)
	if ($num==0) {
		$result= mysql_query("select * from imagens
							where tipo_imagem= '$tipo_imagem'
							and   id_externo = '$id_externo'
							order by ordem asc limit 1
							") or die(mysql_error());
	}
	
	$rs= mysql_fetch_object($result);
	return($rs->nome_arquivo_site);	
}

function string_maior_que($string, $tamanho) {
	if (strlen($string)>$tamanho) $var= substr($string, 0, $tamanho) ."...";
	else $var= $string;
	
	return($var);
}

function gambiarra_acentos($palavra) {
	$original= array("cao", "mao");
	$gambiarra= array("&ccedil;&atilde;o", "m&atilde;o");
	
	return(str_replace($original, $gambiarra, $palavra));
}

function pega_lingua($user_agent, $lingua, $ip) {
	
	$novo_ip= explode(".", $ip);
	
	switch ($novo_ip[0]) {
		case "187":
		case "189":
		case "200":
		case "201":
			$retorno= "pt";
			break;
		default:
			
			if (strstr($user_agent, "pt")) $retorno= "pt";
			elseif (strstr($user_agent, "en")) $retorno= "en";
			
			if ($retorno=="") {
				if (strstr($lingua, "pt")) $retorno= "pt";
				elseif (strstr($lingua, "en")) $retorno= "en";
			}
			if ($retorno=="") $retorno= "en";
			
			break;
	}
	
	return($retorno);	
}

function pega_link_youtube($codigo) {
	if ($codigo!="") return("http://www.youtube.com/watch?v=". $codigo);
}

function inicia_transacao() {
	mysql_query("set autocommit=0;");
	mysql_query("start transaction;");
}

function finaliza_transacao($var) {
	if ($var==0) mysql_query("commit;");
	else mysql_query("rollback;");
}

function pode_um($area, $permissao) {
	if (strpos($permissao, $area)) $retorno= true;
	else $retorno= false;
	
	if ($permissao=="www") $retorno= true;
	
	return($retorno);
}

function pode($areas, $permissao) {
	$tamanho= strlen($areas);
	$retorno= false;
	
	for ($i=0; $i<$tamanho; $i++) {
		if (pode_um($areas[$i], $permissao)) {
			$retorno=true;
			break;
		}
	}

	return($retorno);
}

function retira_acentos($texto) {
  $array1 = array(   ",", "(", ")", "&", "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç"
                     , "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç" );
  $array2 = array(   "", "", "", "e", "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c"
                     , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C" );
  return str_replace( $array1, $array2, $texto );
} 

function faz_url($str) {
	return(retira_acentos(strtolower(str_replace(" ", "-", $str))));
}

function pega_pessoa($id_pessoa) {
	if ($id_pessoa==0) return("");
	else {
		$result= mysql_query("select * from pessoas, pessoas_tipos
												where pessoas.id_pessoa = '$id_pessoa'
												and   pessoas.id_pessoa = pessoas_tipos.id_pessoa
												") or die(mysql_error());
		$rs= mysql_fetch_object($result);
		
		return($rs->apelido_fantasia);
	}
}

function formata_texto_saida($texto) {
	
	$texto= str_replace("[titulo]", "<span class='rosa'>", $texto);
	$texto= str_replace("[/titulo]", "</span>", $texto);
	
	return((trim($texto)));
}

function pega_cidade($id_cidade) {
	$rs= mysql_fetch_object(mysql_query("select cidades.cidade, ufs.uf from cidades, ufs
											where cidades.id_uf = ufs.id_uf
											and   cidades.id_cidade = '$id_cidade'
											"));
	return($rs->cidade ."/". $rs->uf);
}

function formata_data($var) {
	$var= explode("/", $var, 3);
	
	if (strlen($var[2])==2)
		$var[2]= "20". $var[2];
		
	$var= $var[2] . $var[1] . $var[0];
	return($var);
}

function pega_num_comentarios($id_post) {
	$result= mysql_query("select * from comentarios
							where id_post = '". $id_post ."'
							and   situacao_comentario = '1'
							");
	return(mysql_num_rows($result));
}


function pega_pai_categoria($id_categoria) {
	$result= mysql_query("select pai
							from categorias
							where id_categoria = '$id_categoria'
							");
	$rs= mysql_fetch_object($result);
	return($rs->pai);
}

function pega_categoria($id_categoria, $l) {
	if ($id_categoria==0) return("-");
	else {
		$result= mysql_query("select categoria_". $l ." as categoria
								from categorias
								where id_categoria = '$id_categoria'
								");
		$rs= mysql_fetch_object($result);
		return($rs->categoria);
	}
}

function pega_post($id_post, $l) {
	$result= mysql_query("select titulo_". $l ." as titulo from posts
							where id_post = '$id_post'
							");
	$rs= mysql_fetch_object($result);
	return($rs->titulo);
}

function parametro($parametro) {
	$result= mysql_query("select valor from parametros
							where parametro = '$parametro'
							");
	$rs= mysql_fetch_object($result);
	return($rs->valor);
}

function msg_alerta($msg_alerta, $_PAR, $l) {
	
	if ($msg_alerta!="") {
		switch ($msg_alerta) {
			case "informe-dados-validos": $msg= $_PAR["site_msg_informe_". $l]; break;
			case "enviado": $msg= $_PAR["site_msg_mensagem_enviada_". $l]; break;
			case "nao-enviado": $msg= $_PAR["site_msg_mensagem_nao_enviada_". $l]; break;
			case "captcha-incorreto": $msg= $_PAR["site_msg_captcha_incorreto_". $l]; break;
			case "comentario-enviado": $msg= $_PAR["site_msg_comentario_enviado_". $l]; break;
			case "comentario-nao-enviado": $msg= $_PAR["site_msg_comentario_nao_enviado_". $l]; break;
		}
		if ($msg!="") {
			$retorno= "<div class=\"msg\" id=\"msg\">". $msg ."</div>";
		}
	}
	return($retorno);
}

function data_extenso() {
	switch(date('D')) {
		case 'Sun': $data_extenso="Domingo"; break;
		case 'Mon': $data_extenso="Segunda-feira"; break;
		case 'Tue': $data_extenso="Terça-feira"; break;
		case 'Wed': $data_extenso="Quarta-feira"; break;
		case 'Thu': $data_extenso="Quinta-feira"; break;
		case 'Fri': $data_extenso="Sexta-feira"; break;
		case 'Sat': $data_extenso="Sábado"; break;
	}
	$data_extenso .= ", ";
	$data_extenso .= date('d');
	$data_extenso .= " de ";
	
	switch(date('n')) {
		case 1: $data_extenso .= "Janeiro"; break;
		case 2: $data_extenso .= "Fevereiro"; break;
		case 3: $data_extenso .= "Março"; break;
		case 4: $data_extenso .= "Abril"; break;
		case 5: $data_extenso .= "Maio"; break;
		case 6: $data_extenso .= "Junho"; break;
		case 7: $data_extenso .= "Julho"; break;
		case 8: $data_extenso .= "Agosto"; break;
		case 9: $data_extenso .= "Setembro"; break;
		case 10: $data_extenso .= "Outubro"; break;
		case 11: $data_extenso .= "Novembro"; break;
		case 12: $data_extenso .= "Dezembro"; break;
	}
	$data_extenso .= " de ";
	$data_extenso .= date('Y');
	return($data_extenso);
}

function formata_texto($texto) {
   $texto = str_replace('\"', '"', $texto);
   $texto = str_replace("\'", "'", $texto);
  	
   //$texto = @ereg_replace("[a-zA-Z0-9_/-?&%]+@([-]*[.]?[a-zA-Z0-9_/-?&%])*", "<a href=\"mailto:\\0\">\\0</a>", $texto);
   //$texto = @ereg_replace("[a-zA-Z]+://([-]*[.]?[a-zA-Z0-9_/-?&%])*", "<a target=\"_blank\" href=\"\\0\">\\0</a>", $texto);
   //$texto = @ereg_replace("(^| )(www([-]*[.]?[a-zA-Z0-9_/-?&%])*)", "\\1<a target=\"_blank\" href=\"http://\\2\">\\2</a>", $texto);
   
   return(($texto));
}

function traduz_mes($id_mes, $l) {
	switch ($id_mes) {
		case 1: $mes["en"]= "JAN"; $mes["es"]= "ENE"; $mes["pt"]= "JAN"; break;
		case 2: $mes["en"]= "FEB"; $mes["es"]= "FEB"; $mes["pt"]= "FEV"; break;
		case 3: $mes["en"]= "MAR"; $mes["es"]= "MAR"; $mes["pt"]= "MAR"; break;
		case 4: $mes["en"]= "APR"; $mes["es"]= "ABR"; $mes["pt"]= "ABR"; break;
		case 5: $mes["en"]= "MAY"; $mes["es"]= "MAY"; $mes["pt"]= "MAI"; break;
		case 6: $mes["en"]= "JUN"; $mes["es"]= "JUN"; $mes["pt"]= "JUN"; break;
		case 7: $mes["en"]= "JUL"; $mes["es"]= "JUL"; $mes["pt"]= "JUL"; break;
		case 8: $mes["en"]= "AUG"; $mes["es"]= "AGO"; $mes["pt"]= "AGO"; break;
		case 9: $mes["en"]= "SEP"; $mes["es"]= "SEP"; $mes["pt"]= "SET"; break;
		case 10: $mes["en"]= "OCT"; $mes["es"]= "OCT"; $mes["pt"]= "OUT"; break;
		case 11: $mes["en"]= "NOV"; $mes["es"]= "NOV"; $mes["pt"]= "NOV"; break;
		case 12: $mes["en"]= "DEC"; $mes["es"]= "DIC"; $mes["pt"]= "DEZ"; break;
		break;
	}
	
	return($mes[$l]);
}

?>