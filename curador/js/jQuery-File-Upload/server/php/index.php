<?php

define("S3_KEY", "");
define("S3_SECRET", "");
//define("BUCKET", "http://nortecurador-site.s3-website-us-east-1.amazonaws.com/");

define("BUCKET_CURADOR", "curador/uploads/");
//define("BUCKET_SITE", "site/uploads/");

define("CAMINHO_CDN", BUCKET . BUCKET_CURADOR);

function gera_auth() {
	return(substr(strtoupper(md5(uniqid(rand(), true))), 0, 24));
}

/*
function retira_acentos($texto) {
  $array1 = array(   "#", " ", "&", "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç"
                     , "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç" );
  $array2 = array(   "_", "_", "e", "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c"
                     , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C" );
  return @str_replace( $array1, $array2, $texto );
}

*/
function pega_dimensao_imagem($l, $a) {
	if (($l>$a) && ($l>1000)) $dimensao_imagem=1;
	elseif (($l>$a) && ($l<=1000)) $dimensao_imagem=2;
	elseif ($a>$l) $dimensao_imagem=3;
	else $dimensao_imagem=2;
	
	return($dimensao_imagem);
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

function pega_descricao_dimensao_imagem($dimensao_imagem) {
	switch ($dimensao_imagem) {
		case 1: $d= "Paisagem grande"; 
		break;
		case 2: $d= "Paisagem pequena";
		break;
		case 3: $d= "Retrato";
		break;
	}
	return($d);
}

/*
*/

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

/*
 * jQuery File Upload Plugin PHP Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);

include("/var/www/custom/curador.norte.art.br/html/includes/conexao.unico.php");
include("/var/www/custom/curador.norte.art.br/html/includes/funcoes.php");

//Envia para S3
if (!class_exists('S3')) require_once '../../../../includes/amazon-s3-php-class/S3.php';

// Check for CURL
if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
	exit("\nERROR: CURL extension not loaded\n\n");

//if (function_exists("pega_cor")) echo "pega_cor_existe"; else echo "pega_cor_nao_existe!!!";

//if (file_exists("/var/www/custom/curador.norte.art.br/html/includes/funcoes.php")) echo "funcoes_existe"; else echo "funcoes_nao_existe!!!";

//$variavel= "Pomar";

require('UploadHandler.php');
$upload_handler = new UploadHandler();


