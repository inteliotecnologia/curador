<?
require_once("includes/conexao.unico.php");

define("AJAX_LINK", "link.php?");
define("AJAX_FORM", "form.php?");

//define("CAMINHO", "../uploads/");
define("CAMINHO2", "uploads/");
define("CAMINHO", "uploads/");

define("VERSAO", "Curador 0.6");
define("URL_SITE", "https://norte.art.br/");

setlocale(LC_ALL, "pt_BR", "ptb");
date_default_timezone_set('America/Sao_Paulo');

//if ($_GET["pagina"]=="")
//	header("location: index2.php?pagina=login");

//se a pagina atual nao for a de login

//echo $_GET["pagina"];

if ($_GET["pagina"]!="login") {
	$retorno= true;
	if ($_SESSION["id_usuario"]=="")
		$retorno= false;
	
	if (!$retorno)
		header("location: index2.php?pagina=login&redireciona");
}
?>