<?
require_once("includes/conexao.php");
if (pode_algum("r", $_SESSION["permissao"])) {
	if ($acao=="") $acao= $_GET["acao"];
	
	if ($_GET["origem"]!="") $origem= $_GET["origem"];
	if ($_POST["origem"]!="") $origem= $_POST["origem"];
	
	if ($_GET["acao_origem"]!="") $acao_origem= $_GET["acao_origem"];
	if ($_POST["acao_origem"]!="") $acao_origem= $_POST["acao_origem"];
	
	if ($_GET["tipo_pessoa"]!="") $tipo_pessoa= $_GET["tipo_pessoa"];
	if ($_POST["tipo_pessoa"]!="") $tipo_pessoa= $_POST["tipo_pessoa"];
	
	if ($_GET["status_pessoa"]!="") $status_pessoa= $_GET["status_pessoa"];
	if ($_POST["status_pessoa"]!="") $status_pessoa= $_POST["status_pessoa"];
	if ($status_pessoa=="") $status_pessoa= 1;
	
	if ($_GET["pessoa_id_pessoa"]!="") $pessoa_id_pessoa= $_GET["pessoa_id_pessoa"];
	if ($_POST["pessoa_id_pessoa"]!="") $pessoa_id_pessoa= $_POST["pessoa_id_pessoa"];
	
	if ($_GET["id_pessoa"]!="") $id_pessoa= $_GET["id_pessoa"];
	if ($_POST["id_pessoa"]!="") $id_pessoa= $_POST["id_pessoa"];
	
	if ($_GET["esquema"]!="") $esquema= $_GET["esquema"];
	if ($_POST["esquema"]!="") $esquema= $_POST["esquema"];
	
	$result= mysql_query("select *, DATE_FORMAT(data, '%d/%m/%Y') as data2
							from  pessoas, pessoas_tipos, enderecos
							where pessoas.id_pessoa = enderecos.id_pessoa
							and   pessoas.id_pessoa = pessoas_tipos.id_pessoa
							and   pessoas.id_pessoa = '". $id_pessoa ."'
							and   pessoas_tipos.tipo_pessoa = '". $tipo_pessoa ."'
							and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'
							limit 1
							") or die(mysql_error());
	
	$rs= mysql_fetch_object($result);
	
	$id_pessoa= $rs->id_pessoa;
	$tipo_pessoa= $rs->tipo_pessoa;
	$status_pessoa= $rs->status_pessoa;
	$pessoa_id_pessoa= $rs->pessoa_id_pessoa;
	
	if ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="c") ) {
		$tit.= "Cliente";
		$tit_classe="tit_agenda";
	}
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="f") ) {
		$tit.= "Fornecedor";
		$tit_classe="tit_agenda";
	}
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="r") ) {
		$tit.= "Artista";
		$tit_classe="tit_jarro";
	}
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="t") ) {
		$tit.= "Empresa de artista";
		$tit_classe="tit_jarro";
	}
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="g") ) {
		$tit.= "Ag&ecirc;ncia";
		$tit_classe="tit_agenda";
	}
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="u") ) {
		$tit.= "Usuário";
		$tit_classe="tit_chaves";
	}
	elseif (pode("a", $_SESSION["permissao"])) {
		$tipo_pessoa= "a";
		$tit.= "Empresa admin";
		$tit_classe="tit_papeis";
	}
	
	if ($status_pessoa==3) $tit .= " (em vista)";
	
	if (($pessoa_id_pessoa!="") && ($pessoa_id_pessoa!="0")) $tit .= " (". pega_pessoa($pessoa_id_pessoa) .")";
	
	$tit.= ": ". $rs->apelido_fantasia;
	
	$esquema_pessoa=1;
?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="tit <?=$tit_classe;?>"><?=$tit;?></h2>

<?
include("_financeiro/__pessoa_outro_abas.php");
?>

<?
$pagina_include="_contatos/__contato_listar.php";

switch ($origem) {
	case "2":
		if ($acao_origem=='i') {
			$pagina_include= "_contatos/__contato.php";
			$acao='i';
		}
		elseif ($acao_origem=='e') {
			$pagina_include= "_contatos/__contato.php";
			$acao='e';
		}
	break;
	
	default: $pagina_include= "_contatos/__contato_listar.php";
	break;
}

include($pagina_include);
?>

<? } ?>