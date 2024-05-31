<?

function inverte_0_1($valor) {
	if ($valor==1) $valor=0;
	else $valor=1;
	
	return($valor);
}

function pega_miniatura($id, $oque) {
	switch ($id) {
		case 1:
			$miniatura= "Miniatura - 300x160";
			$largura= 300;
			$altura= 160;
		break;
		case 2:
			$miniatura= "Miniatura maior - 620x330";
			$largura= 620;
			$altura= 330;
		break;
		case 3:
			$miniatura= "Destaque na home - 940x380";
			$largura= 940;
			$altura= 380;
		break;
	}
	
	switch($oque) {
		case "n": $retorno= $miniatura; break;
		case "l": $retorno= $largura; break;
		case "a": $retorno= $altura; break;
		case "p": $retorno= ($largura/$altura); break;
	}
	return($retorno);
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

function pega_imagem($tipo_imagem, $id_externo) {
	$result= mysql_query("select * from imagens
							where tipo_imagem= '$tipo_imagem'
							and   id_externo = '$id_externo'
							and   site = '1'
							order by ordem asc limit 1
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	return($rs->nome_arquivo);	
}

function pega_dimensao_imagem($l, $a) {
	if (($l>$a) && ($l>1000)) $dimensao_imagem=1;
	elseif (($l>$a) && ($l<=1000)) $dimensao_imagem=2;
	elseif ($a>$l) $dimensao_imagem=3;
	else $dimensao_imagem=2;
	
	return($dimensao_imagem);
}

function pega_largura_padrao($modo, $dimensao_imagem) {
	switch($dimensao_imagem) {
		case 1: $largura= 1598; $altura= 1000;
		break;
		case 2: $largura= 777; $altura= 474;
		break;
		case 3: $largura= 773; $altura= 998;
		break;
	}
	
	if ($modo=="l") return(cpc($largura));
	elseif ($modo=="a") return(cpc($altura));
}

function cpc($px) {
	return(($px*29.7)/1900);
}


function pega_projeto($id_projeto) {
	$result= mysql_query("select projeto_pt from projetos
							where id_projeto = '$id_projeto'
							");
	$rs= mysql_fetch_object($result);
	
	return($rs->projeto_pt);
}

function pega_ultima_ordem_imagens($tipo_imagem, $id_externo) {
	$result= mysql_query("select ordem from imagens
							where id_externo = '$id_externo'
							and   tipo_imagem = '$tipo_imagem'
							order by ordem desc limit 1
							");
	$rs= mysql_fetch_object($result);
	
	$ordem= $rs->ordem+1;
	
	return($ordem);
}

function pega_ultima_ordem_curadoria($tipo_imagem, $id_externo) {
	$result= mysql_query("select ordem from imagens
							where id_externo = '$id_externo'
							and   tipo_imagem = '$tipo_imagem'
							order by ordem desc limit 1
							");
	$rs= mysql_fetch_object($result);
	
	$ordem= $rs->ordem+1;
	
	return($ordem);
}

function retira_acentos($texto) {
  $array1 = array(   "#", " ", "&", "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç"
                     , "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç" );
  $array2 = array(   "_", "_", "e", "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c"
                     , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C" );
  return @str_replace( $array1, $array2, $texto );
}

function faz_url($str) {
	return(retira_acentos(strtolower(str_replace(" ", "-", $str))));
}

function string_maior_que($string, $tamanho) {
	if (strlen($string)>$tamanho) $var= $string ."...";
	else $var= $string;
	
	return($var);
}

function pega_tipo_pessoa($i) {
	switch ($i) {
		case 'f': $tipo= "Fornecedor"; break;
		case 'c': $tipo= "Cliente"; break;
		case 'u': $tipo= "Funcionário"; break;
		case 'r': $tipo= "Artista"; break;
		case 'g': $tipo= "Agência"; break;
		case 'a': $tipo= "Empresa com sistema"; break;
		default: $tipo=""; break;
	}
	return($tipo);
}

function pega_tipo_pessoa_plural($i) {
	switch ($i) {
		case 'f': $tipo= "Fornecedores"; break;
		case 'c': $tipo= "Clientes"; break;
		case 'u': $tipo= "Funcionários"; break;
		case 'r': $tipo= "Artistas"; break;
		case 'g': $tipo= "Agências"; break;
		case 'a': $tipo= "Empresas com sistema"; break;
		default: $tipo=""; break;
	}
	return($tipo);
}

function pega_tipo($i) {
	switch ($i) {
		case 'f': $tipo= "Física"; break;
		case 'j': $tipo= "Jurídica"; break;
		default: $tipo=""; break;
	}
	return($tipo);
}

function pega_sexo($sexo) {
	if ($sexo=="m") return("Masculino");
	else return("Feminino");
}

function fnum($numero) {
	$quebra= explode(".", $numero);
	$tamanho= strlen($quebra[1]);
	
	return(number_format($numero, 2, ',', '.'));
}

function fnum2($numero) {
	$quebra= explode(".", $numero);
	$tamanho= strlen($quebra[1]);
	
	return(number_format($numero, $tamanho, ',', '.'));
}

function fnumi($numero) {
	return(number_format($numero, 0, ',', '.'));
}

function fnumf($numero) {
	if ($numero!="") {
		$decimal= substr($numero, -2, 2);
		if ((strpos($numero, ".")) && ($decimal!="00")) return(fnum($numero));
		else return(fnumi($numero));
	}
}

function fnumf_naozero($numero) {
	if (($numero!=0) && ($numero!="")) {
		$decimal= substr($numero, -2, 2);
		if ((strpos($numero, ".")) && ($decimal!="00")) return(fnum($numero));
		else return(fnumi($numero));
	}
}

function pega_numero_semana($ano, $mes, $dia) {
   return ceil(($dia + date("w", mktime(0, 0, 0, $mes, 1, $ano)))/7);   
} 


function eh_decimal($numero) {
	$decimal= substr($numero, -2, 2);
	if ($decimal!="00") return(true);
	else return(false);
}

function primeira_palavra($frase) {
	$retorno= explode(" ", $frase);
	return($retorno[0]);
}

function formata_saida($valor, $tamanho_saida) {
	//3, 5
	$tamanho= strlen($valor);
	
	for ($i=$tamanho; $i<$tamanho_saida; $i++)
		$saida .= '0';
		
	return($saida . $valor);
}

function calcula_idade($data_nasc) {
	$var= explode("/", $data_nasc, 3);
	
	if (strlen($var[2])==2)
		$var[2]= "20". $var[2];
	
	$dia=$var[0];
	$mes=$var[1];
	$ano=$var[2];

	if (($data_nasc!="") && ($data_nasc!="00/00/0000") && ($ano<=date("Y"))) {
		
		$idade= date("Y")-$ano;
		if ($mes>date("m"))
			$idade--;
		if (($mes==date("m")) && ($dia>date("d")) )
			$idade--;
		return($idade);	}
	//else
	///	return("<span class=\"vermelho\">Não disponível!</span>");
}

// ------------------------------- funcoes do ponto -------------------------------------------

function pega_num_ultima_pessoa($id_empresa, $tipo_pessoa) {
	$result= mysql_query("select * from pessoas_tipos
						 		where id_empresa = '$id_empresa'
								and   tipo_pessoa = '$tipo_pessoa'
								order by id_pessoa desc limit 1
								");
	$rs= mysql_fetch_object($result);
	return($rs->num_pessoa);
}

function enviar_mensagem($id_empresa, $de, $para, $titulo, $mensagem) {
	$result= mysql_query("insert into com_mensagens
						 	(id_empresa, de, para, titulo, mensagem, data_mensagem, hora_mensagem,
							 lida, situacao_de, situacao_para, auth)
							values
							('$id_empresa', '$de', '$para', '$titulo', '$mensagem', '". date("Ymd") ."', '". date("His") ."',
							 '0', '1', '1', '". gera_auth() ."')
							");
	if ($result) return(mysql_insert_id());
	else return(0);
}

function mensagem_nova($id_usuario) {
	$id_pessoa= pega_id_pessoa_do_usuario($id_usuario);
	
	$result= mysql_query("select id_mensagem from com_mensagens
								 	where situacao_para='1'
									and para= '". $id_pessoa ."'
									and   lida= '0' ") or die(mysql_error());
	
	if (mysql_num_rows($result)>0) return(true);
	else return(false);
}

function verifica_backup() {
	//$data= date("Y-m-d");
	//$result_pre= mysql_query("select * from backups where data_backup = '". $data ."' ");
	
	//if (mysql_num_rows($result_pre)==0)
		header("location: includes/backup/backup.php");
		
	//else echo "Backup já feito no dia de hoje!";
		
}

function soma_data($data, $dias, $meses, $anos) {
	if (strpos($data, "-")) {
		$dia_controle= explode('-', $data);
		$data= date("Y-m-d", mktime(0, 0, 0, $dia_controle[1]+$meses, $dia_controle[2]+($dias), $dia_controle[0]+$anos));
	}
	elseif (strpos($data, "/")) {
		$dia_controle= explode('/', $data);
		$data= date("d/m/Y", mktime(0, 0, 0, $dia_controle[1]+$meses, $dia_controle[0]+($dias), $dia_controle[2]+$anos));
	}
    
    return($data);
}

function soma_data_hora($data_hora, $dias, $meses, $anos, $horas, $minutos, $segundos) {
	
	//2009-10-10 10:11:12
	if (strpos($data_hora, "-")) {
		$ano= substr($data_hora, 0, 4);
		$mes= substr($data_hora, 5, 2);
		$dia= substr($data_hora, 8, 2);
		$hora= substr($data_hora, 11, 2);
		$minuto= substr($data_hora, 14, 2);
		$segundo= substr($data_hora, 17, 2);
		
		$data= date("Y-m-d H:i:s", mktime($hora+$horas, $minuto+$minutos, $segundo+$segundos, $mes+$meses, $dia+$dias, $ano+$anos));
	}
	//10/10/2009 10:11:12
	elseif (strpos($data_hora, "/")) {
		$ano= substr($data_hora, 6, 4);
		$mes= substr($data_hora, 3, 2);
		$dia= substr($data_hora, 0, 2);
		$hora= substr($data_hora, 11, 2);
		$minuto= substr($data_hora, 14, 2);
		$segundo= substr($data_hora, 17, 2);
		
		$data= date("Y-m-d H:i:s", mktime($hora+$horas, $minuto+$minutos, $segundo+$segundos, $mes+$meses, $dia+$dias, $ano+$anos));
	}
    
    return($data);
}

function pega_id_empresa_da_pessoa($id_pessoa) {
	$rs= mysql_fetch_object(mysql_query("select id_empresa from empresas
											where id_pessoa = '$id_pessoa' "));
	return($rs->id_empresa);
}

function pega_pessoa($id_pessoa) {
	if ($id_pessoa==0) return(pega_empresa($_SESSION["id_empresa"]));
	else {
		$rs= mysql_fetch_object(mysql_query("select * from pessoas, pessoas_tipos
												where pessoas.id_pessoa = '$id_pessoa'
												and   pessoas.id_pessoa = pessoas_tipos.id_pessoa
												"));
		if ($rs->tipo_pessoa=='a') return($rs->nome_rz);
		else return($rs->apelido_fantasia);
	}
}

function pega_nome_usuario($id_usuario) {
	$rs_pre= mysql_fetch_object(mysql_query("select nome from usuarios
												where id_usuario = '$id_usuario' "));
	
	return($rs_pre->nome);
}

function traduz_periodicidade($p) {
	
	switch ($p[1]) {
		case "d": $periodo= "dia"; break;
		case "m": $periodo= "mês"; break;
		case "a": $periodo= "ano"; break;
	}
	
	return($p[0] ."x/". $periodo);
}

function pega_tipo_contato($tipo) {
	$vetor= array();

	$vetor['f']= "Fornecedor";
	$vetor['c']= "Cliente";
	$vetor['a']= "Agência";
	$vetor['r']= "Artista";
	$vetor['o']= "Outro";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_tipo_contato_plural($tipo) {
	$vetor= array();

	$vetor['f']= "Fornecedores";
	$vetor['c']= "Clientes";
	$vetor['a']= "Agências";
	$vetor['r']= "Artistas";
	$vetor['o']= "Outros";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function valor_extenso($valor=0) {

	$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
	$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
	$u = array("", "um", "dois", "trÍs", "quatro", "cinco", "seis","sete", "oito", "nove");

	$z=0;

	$valor = number_format($valor, 2, ".", ".");
	$inteiro = explode(".", $valor);
	for($i=0;$i<count($inteiro);$i++)
		for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
			$inteiro[$i] = "0".$inteiro[$i];

	$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
	for ($i=0;$i<count($inteiro);$i++) {
		$valor = $inteiro[$i];
		$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
		$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
		$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
	
		$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
		$t = count($inteiro)-1-$i;
		$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
		if ($valor == "000")$z++; elseif ($z > 0) $z--;
		if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t]; 
		if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : "") . $r;
	}

	return($rt ? $rt : "zero");
}

function pega_tipo_telefone($tipo) {
	$vetor= array();

	$vetor[1]= "Residencial";
	$vetor[2]= "Comercial";
	$vetor[3]= "Celular";
	$vetor[4]= "Fax";
	$vetor[5]= "Outros";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_empresa($id_empresa) {
	$rs= mysql_fetch_object(mysql_query("select pessoas.nome_rz, pessoas.apelido_fantasia from pessoas, empresas
											where pessoas.id_pessoa = empresas.id_pessoa
											and   empresas.id_empresa = '$id_empresa'
											"));
	return($rs->apelido_fantasia);
}

function pega_cnpj($id_empresa) {
	$rs= mysql_fetch_object(mysql_query("select pessoas.cpf_cnpj from pessoas, empresas
											where pessoas.id_pessoa = empresas.id_pessoa
											and   empresas.id_empresa = '$id_empresa'
											"));
	return($rs->cpf_cnpj);
}

function formata_hora($var) {
	//transformando em segundos
	$var= explode(":", $var, 3);
	
	$total_horas= $var[0]*3600;
	$total_minutos= $var[1]*60;
	$total_segundos= $var[2];
	
	$var= $total_horas+$total_minutos+$total_segundos;
	
	return($var);
}

function pode_um($area, $permissao) {
	if (strpos($permissao, $area)) $retorno= true;
	else $retorno= false;
	
	if ( ($permissao=="www") || (pode_um("a", $permissao)) ) $retorno= true;
	
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

function pode_algum($areas, $permissao) {
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

function grava_acesso($id_usuario, $id_empresa, $tipo, $ip, $ip_reverso) {
	$result= mysql_query("insert into acessos (id_usuario, id_empresa, tipo, data_acesso, ip, ip_reverso)
								values ('". $id_usuario ."', '". $id_empresa ."', '". $tipo ."', '". date("YmdHis") ."', '$ip', '$ip_reverso' ) ");
	return(mysql_insert_id());
}

function pega_acao_log($i) {
	$vetor= array();
	
	$vetor[1]= "Insere/altera peso de cliente";
	
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function logs($id_acesso, $id_usuario, $id_empresa, $var, $id_referencia, $texto, $id_acao_log, $ip) {
	$result= mysql_query("insert into logs (id_acesso, id_usuario, id_empresa, var, id_referencia, texto, id_acao_log, data, ip)
							values
							('$id_acesso', '$id_usuario', '$id_empresa', '$var', '$id_referencia', '$texto', '$id_acao_log', '". date("YmdHis") ."', '$ip')
							") or die(mysql_error());
}


function traduz_mes($mes) {
	switch($mes) {
		case 1: $retorno= "Janeiro"; break;
		case 2: $retorno= "Fevereiro"; break;
		case 3: $retorno= "Março"; break;
		case 4: $retorno= "Abril"; break;
		case 5: $retorno= "Maio"; break;
		case 6: $retorno= "Junho"; break;
		case 7: $retorno= "Julho"; break;
		case 8: $retorno= "Agosto"; break;
		case 9: $retorno= "Setembro"; break;
		case 10: $retorno= "Outubro"; break;
		case 11: $retorno= "Novembro"; break;
		case 12: $retorno= "Dezembro"; break;
		default: $retorno= ""; break;
	}
	return($retorno);
}

function inverte($num) {
	if ($num==1) return(0);
	else return(1);
}

function excluido_ou_nao($var) {
	if ($var==0) $retorno_msg= "Excluído com sucesso!";
	else $retorno_msg= "Não foi possível excluir!";
	
	return("<script language=\"javascript\">alert('". $retorno_msg ."');</script>");
}

function sim_nao($situacao) {
	if (($situacao==0) || ($situacao==2)) return("<span class=\"vermelho\">NÃO</span>");
	else return("<span class=\"verde\">SIM</span>");
}

function ativo_inativo($situacao) {
	if ($situacao==1) return("<span class=\"verde\">ATIVO</span>");
	elseif ($situacao==-1) return("<span class=\"vermelho\">EM ESPERA</span>");
	else return("<span class=\"vermelho\">INATIVO</span>");
}

function sim_nao_pdf($situacao) {
	if ($situacao==1) return("SIM");
	else return("");
}

function pega_cidade($id_cidade) {
	$rs= mysql_fetch_object(mysql_query("select cidades.cidade, ufs.uf from cidades, ufs
											where cidades.id_uf = ufs.id_uf
											and   cidades.id_cidade = '$id_cidade'
											"));
	return($rs->cidade ."/". $rs->uf);
}

function pega_uf($id_uf) {
	$rs= mysql_fetch_object(mysql_query("select uf from ufs where id_uf = '$id_uf' "));
	return($rs->uf);
}

function pega_id_uf($id_cidade) {
	$rs= mysql_fetch_object(mysql_query("select id_uf from cidades
											where id_cidade = '$id_cidade'
											"));
	return($rs->id_uf);
}

function otimiza_foto($foto, $l) {
	$qualidade = 90;
/*
	$originalimage = imagecreatefromjpeg($foto);
	$l_original= imagesx($originalimage);
	$a_original= imagesy($originalimage);
	
	if ($l_original>$l) {
		$a= floor(($l*$a_original)/$l_original);
	}
	else {
		$l= $l_original;
		$a= $a_original;
	}
	//cria um quadrado preto com as dimensoes especificadas
	$thumbnail = imagecreatetruecolor($l, $a);
	//poe a imagem resultante no quadrado preto acima
	imagecopyresampled($thumbnail, $originalimage, 0, 0, 0, 0, $l+1, $a+1, 
	imagesx($originalimage), imagesy($originalimage)); 
	imagejpeg($thumbnail,$foto,$qualidade); 
	imagedestroy($thumbnail); 
	//echo "aki";
	*/
}

function inicia_transacao() {
	mysql_query("set autocommit=0;");
	mysql_query("start transaction;");
}

function finaliza_transacao($var) {
	if ($var==0) mysql_query("commit;");
	else mysql_query("rollback;");
}

function gera_auth() {
	return(substr(strtoupper(md5(uniqid(rand(), true))), 0, 24));
}

function tira_caracteres($char) {
	return(str_replace("'", "xxx", str_replace('"', 'xxx', str_replace('/', '', str_replace('.', '', str_replace('-', '', $char))))));
}

function formata_cpf($cpf) {
	$cpfn= substr($cpf, 0, 3) .".". substr($cpf, 3, 3) .".". substr($cpf, 6, 3) ."-". substr($cpf, 9, 2);
	return($cpfn);
}

function pega_horario($horario, $tipo) {
	
	switch($tipo) {
		case 'h': $retorno= substr($horario, 0, 2); break;
		case 'm': $retorno= substr($horario, 3, 2); break;
		case 's': $retorno= substr($horario, 5, 2); break;
	}
	
	return($retorno);
}

function formata_cnpj($cnpj) {
	//99.999.999/9999-99
	//99 999 999 9999 99
	$cnpj= substr($cnpj, 0, 2) .".". substr($cnpj, 2, 3) .".". substr($cnpj, 5, 3) ."/". substr($cnpj, 8, 4) ."-". substr($cnpj, 12, 2);
	return($cnpj);
}

function formata_data($var) {
	$var= explode("/", $var, 3);
	
	if (strlen($var[2])==2)
		$var[2]= "20". $var[2];
		
	$var= $var[2] . $var[1] . $var[0];
	return($var);
}

function formata_data_timestamp($var) {
	$var= explode(" ", $var, 2);
	
	return(desformata_data($var[0]) . " ". $var[1]);
	
}


function formata_data_hifen($var) {
	$var= explode("/", $var, 3);
	
	if (strlen($var[2])==2)
		$var[2]= "20". $var[2];
		
	$var= $var[2] .'-'. $var[1] .'-'. $var[0];
	return($var);
}


function faz_mk_data($var) {
	if (strpos($var, "-")) {
		$var= explode("-", $var, 3);
		$mk= mktime(14, 0, 0, $var[1], $var[2], $var[0]);
		return($mk);
	}
	else {
		$var= explode("/", $var, 3);
		$mk= mktime(14, 0, 0, $var[1], $var[0], $var[2]);
		return($mk);
	}
}

function faz_mk_hora($var) {
	$var= explode(":", $var, 3);
	$mk= mktime($var[0], $var[1], $var[2], 0, 0, 0);
	return($mk);
}

function faz_mk_hora_simples($var) {
	$var= explode(":", $var, 3);
	$mk= (($var[0]*3600)+($var[1]*60)+$var[2]);
	return($mk);
}

function faz_mk_data_completa($var) {
	
	if (strpos($var, "-")) {
		//2008-07-31 13:25:05 
		$data_completa= explode(" ", $var, 2);
		
		$data= explode("-", $data_completa[0], 3);
		$hora= explode(":", $data_completa[1], 3);
		
		$mk= mktime($hora[0], $hora[1], $hora[2], $data[1], $data[2], $data[0]);
	}
	elseif (strpos($var, "/")) {
		//31/07/2008 13:25:05 
		$data_completa= explode(" ", $var, 2);
		
		$data= explode("/", $data_completa[0], 3);
		$hora= explode(":", $data_completa[1], 3);
		
		$mk= mktime($hora[0], $hora[1], $hora[2], $data[1], $data[0], $data[2]);
	}
	
	return($mk);
}

function desformata_data($var) {
	if (($var!="") && ($var!="0000-00-00")) {
		//2006-10-12
		$var= explode("-", $var, 3);
		
		//10/10/2007
		$var= $var[2] .'/'. $var[1] .'/'. $var[0];
		return($var);
	}
	//else
	//	return("<span class='menor vermelho'>não informado</span>");
}

function pega_dia($var) {
	return(substr($var, 6, 2));
}

function pega_mes($var) {
	return(substr($var, 4, 2));
}

function pega_ano($var) {
	return(substr($var, 0, 4));
}

function aumenta_dia($var) {
	//22-10-2007
	$var= explode("-", $var, 3);
	
	$data_ano= date("Y", mktime(0, 0, 0, $var[1], $var[0]+1, $var[2]));
	$data_mes= date("m", mktime(0, 0, 0, $var[1], $var[0]+1, $var[2]));
	$data_dia= date("d", mktime(0, 0, 0, $var[1], $var[0]+1, $var[2]));
	
	$var[0]= $data_dia;
	$var[1]= $data_mes;
	$var[2]= $data_ano;
	
	//2006-10-12
	//$var= $var[2] . $var[1] . $var[0];
	return($var);
}

function soma_mes($var, $valor) {
	
	if (strpos($var, "-")) {
		//2008-07-31
		$data_completa= explode(" ", $var, 2);
		$data= explode("-", $data_completa[0], 3);
		
		$mk= mktime(0, 0, 0, $data[1]+($valor), $data[2], $data[0]);
	}
	elseif (strpos($var, "/")) {
		//31/07/2008
		$data_completa= explode(" ", $var, 2);
		$data= explode("/", $data_completa[0], 3);
		
		$mk= mktime(0, 0, 0, $data[1]+($valor), $data[0], $data[2]);
	}
	
	$var= date("Y-m-d", $mk);
	//2006-10-12
	//$var= $var[2] . $var[1] . $var[0];
	return($var);
}

function formata_valor($var) {
	$var= str_replace(',', '.', str_replace('.', '', $var));
	return($var);
}

function data_extenso() {
	/*switch(date('D')) {
		case 'Sun': $data_extenso="Domingo"; break;
		case 'Mon': $data_extenso="Segunda-feira"; break;
		case 'Tue': $data_extenso="Terça-feira"; break;
		case 'Wed': $data_extenso="Quarta-feira"; break;
		case 'Thu': $data_extenso="Quinta-feira"; break;
		case 'Fri': $data_extenso="Sexta-feira"; break;
		case 'Sat': $data_extenso="Sábado"; break;
	}
	$data_extenso .= ", ";
	*/
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

function data_extenso_param($data) {
	$data= explode('-', $data);
	
	/*switch(date('D')) {
		case 'Sun': $data_extenso="Domingo"; break;
		case 'Mon': $data_extenso="Segunda-feira"; break;
		case 'Tue': $data_extenso="Terça-feira"; break;
		case 'Wed': $data_extenso="Quarta-feira"; break;
		case 'Thu': $data_extenso="Quinta-feira"; break;
		case 'Fri': $data_extenso="Sexta-feira"; break;
		case 'Sat': $data_extenso="Sábado"; break;
	}
	$data_extenso .= ", ";
	*/
	$data_extenso .= $data[2];
	$data_extenso .= " de ";
	
	switch($data[1]) {
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
	$data_extenso .= $data[0];
	return($data_extenso);
}


function enviar_email($email, $titulo, $corpo) {
	$enviado= @mail($email, $titulo, $corpo, "From: Prospital.com <prospital@prospital.com> \nContent-type: text/html\n");
}

function pega_tipo_usuario($tipo) {
	switch($tipo) {
		case "a": $retorno= "Administrador"; break;
		case "e": $retorno= "Usuário"; break;
		default: $retorno= ""; break;
	}
	return($retorno);
}
?>