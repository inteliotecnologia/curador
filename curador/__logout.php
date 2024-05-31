<?
session_start();

require_once("includes/conexao.php");
require_once("includes/funcoes.php");

//grava_acesso($_SESSION["id_usuario"], $_SESSION["id_empresa"], 's', $_SERVER["REMOTE_ADDR"], gethostbyaddr($_SERVER["REMOTE_ADDR"]));

$_SESSION["id_usuario"]="";
$_SESSION["id_empresa"]="";
$_SESSION["permissao"]="";
$_SESSION["tipo_empresa"]="";
$_SESSION["tipo_usuario"]="";

$_SESSION["nome_fantasia"]="";
$_SESSION["id_acesso"]="";

$_SESSION["id_funcionario_sessao"]="";
$_SESSION["id_departamento_sessao"]="";
$_SESSION["id_turno_sessao"]="";

$_SESSION["id_empresa_atendente"]="";
$_SESSION["id_empresa_atendente2"]="";

session_destroy();

header("location: index2.php?pagina=login");
?>