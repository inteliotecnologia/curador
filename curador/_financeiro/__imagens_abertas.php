<?
require_once("includes/conexao.php");
require_once("includes/funcoes.php");

if (pode_algum("vr", $_SESSION["permissao"])) {
	if ($acao=="") $acao= $_GET["acao"];
	
	if ($_GET["tipo_pessoa"]!="") $tipo_pessoa= $_GET["tipo_pessoa"];
	if ($_POST["tipo_pessoa"]!="") $tipo_pessoa= $_POST["tipo_pessoa"];
	
	if ($_GET["status_pessoa"]!="") $status_pessoa= $_GET["status_pessoa"];
	if ($_POST["status_pessoa"]!="") $status_pessoa= $_POST["status_pessoa"];
	if ($status_pessoa=="") $status_pessoa= 1;
	
	if ($_GET["id_pessoa"]!="") $id_pessoa= $_GET["id_pessoa"];
	if ($_POST["id_pessoa"]!="") $id_pessoa= $_POST["id_pessoa"];
	
	if ($_GET["id_projeto"]!="") $id_projeto= $_GET["id_projeto"];
	if ($_POST["id_projeto"]!="") $id_projeto= $_POST["id_projeto"];
	
	if ($_GET["tamanho_imagem"]!="") $tamanho_imagem= $_GET["tamanho_imagem"];
	if ($_POST["tamanho_imagem"]!="") $tamanho_imagem= $_POST["tamanho_imagem"];
	
	if ($tamanho_imagem=="") $tamanho_imagem=1;
	
	if ($id_pessoa!="") {
		$tipo_imagem= "a";
		$id_externo= $id_pessoa;
		$id_externo_nome= "id_pessoa";
	}
	elseif ($id_projeto!="") {
		$tipo_imagem= "p";
		$id_externo= $id_projeto;
		$id_externo_nome= "id_projeto";
	}
	
	if ($tipo_imagem=="a") {
		$result= mysql_query("select *, DATE_FORMAT(data, '%d/%m/%Y') as data2
								from  pessoas, pessoas_tipos
								where pessoas.id_pessoa = pessoas_tipos.id_pessoa
								and   pessoas.id_pessoa = '". $id_pessoa ."'
								and   pessoas_tipos.tipo_pessoa = 'r'
								and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								") or die(mysql_error());
		$rs= mysql_fetch_object($result);
		
		$tipo_pessoa= $rs->tipo_pessoa;
		$status_pessoa= $rs->status_pessoa;
		$id_externo= $rs->id_pessoa;
					
		$tit.= $rs->nome_rz;		
	}
	else {
		$result= mysql_query("select * from  projetos
								where id_projeto = '". $_GET["id_projeto"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								") or die(mysql_error());
		$rs= mysql_fetch_object($result);
		
		$id_externo= $rs->id_projeto;
					
		$tit.= $rs->projeto_pt;
	}
	?>
    
    <h2><?=$tit;?></h2>
    
    <?
	$result_enviados= mysql_query("select * from imagens
									where id_externo = '". $id_externo ."'
									and   tipo_imagem = '". $tipo_imagem ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									and   ( miniatura_destaque is NULL or miniatura_destaque = '0')
									order by ordem asc
									") or die(mysql_error());
	$linhas_enviados= mysql_num_rows($result_enviados);
	
	if ($linhas_enviados==0) {
	?>
	<div id="enviados_nenhum">Nenhuma imagem enviada.</div>
	<?
	}
	else {
		
		while ($rs_enviados= mysql_fetch_object($result_enviados)) {
	?>
		<img src="<?= CAMINHO_CDN . $rs_enviados->tipo_imagem ."_". $rs_enviados->id_externo ."/". $rs_enviados->nome_arquivo; ?>" border="0" alt="" />
		
        <br /><br />
        <hr />
        <br />
	<? } } ?>
<? } ?>