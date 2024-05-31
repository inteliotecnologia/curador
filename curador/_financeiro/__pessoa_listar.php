<?
if (pode_algum("r", $_SESSION["permissao"])) {
	
	if ($_GET["tipo_pessoa"]!="") $tipo_pessoa= $_GET["tipo_pessoa"];
	if ($_POST["tipo_pessoa"]!="") $tipo_pessoa= $_POST["tipo_pessoa"];
	
	if ( ($tipo_pessoa=='u') && (!pode("a", $_SESSION["permissao"]) ) ) die("Sem acesso à esta área.");
	
	if ($_GET["status_pessoa"]!="") $status_pessoa= $_GET["status_pessoa"];
	if ($_POST["status_pessoa"]!="") $status_pessoa= $_POST["status_pessoa"];
	
	if ($_GET["id_contrato"]!="") $id_contrato= $_GET["id_contrato"];
	if ($_POST["id_contrato"]!="") $id_contrato= $_POST["id_contrato"];
	
	if ($_GET["esquema"]!="") $esquema= $_GET["esquema"];
	if ($_POST["esquema"]!="") $esquema= $_POST["esquema"];
	
	if ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="c") ) {
		$tit= "Clientes";
		$str= "and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'";
		
		$tit_classe="tit_papeis";
	}
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="f") ) {
		$tit= "Fornecedores";
		$str= "and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'";
		
		$tit_classe="tit_agenda";
	}
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="r") ) {
		$tit= "Artistas";
		$str= "and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'";
		
		$tit_classe="tit_jarro";
	}
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="g") ) {
		$tit= "Agências";
		$str= "and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'";
		
		$tit_classe="tit_agenda";
	}
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="u") ) {
		$tit.= "Usuários";
		
		$str= "and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'";
		
		$tit_classe="tit_chaves";
	}
	elseif (pode("a", $_SESSION["permissao"])) {
		$tit= "Empresas admin";
		
		$tit_classe="tit_papeis";
	}
	else $tit= "Empresas";
	
	if ($status_pessoa=="") $status_pessoa= 1;
	
	if ($status_pessoa==1) $tit_situacao= "ativos";
	elseif ($status_pessoa==3) $tit_situacao= "em vista";
	else $tit_situacao= "inativos";
	
	//só vindo da busca, edicao e insercao nao entram aqui
	if (isset($_POST["geral"])) {
		if ($_POST["tipo"]!="") $str .= " and  pessoas.tipo = '". $_POST["tipo"] ."' ";
		if ($_POST["cpf_cnpj"]!="") $str .= " and  pessoas.cpf_cnpj like '". $_POST["cpf_cnpj"] ."%' ";
		if ($_POST["nome_rz"]!="") $str .= " and  pessoas.nome_rz like '%". $_POST["nome_rz"] ."%' ";
		if ($_POST["id_empresa_atendente"]!="") $str .= " and  pessoas.id_empresa_atendente = '". $_POST["id_empresa_atendente"] ."' ";
	}
	
	if ($id_contrato!="") $str .= " and  pessoas.id_contrato = '". $id_contrato ."' ";
	
	$result= mysql_query("select * from pessoas, pessoas_tipos
							where pessoas.id_pessoa = pessoas_tipos.id_pessoa
							and   pessoas_tipos.tipo_pessoa = '$tipo_pessoa'
							". $str ."
							and   pessoas_tipos.status_pessoa = '". $status_pessoa ."'
							and   pessoas_tipos.status_pessoa <> '2'
							order by pessoas_tipos.num_pessoa asc,
							pessoas.nome_rz asc
							") or die(mysql_error());
	$linhas= mysql_num_rows($result);
	
	if ( ($tipo_pessoa=="r") || ($tipo_pessoa=="u") ) {
		$coluna_nome= "Nome";
		$coluna_fantasia= "Apelido";
	}
	else {
		$coluna_nome="Razão social";
		$coluna_fantasia="Nome fantasia";
	}
	
?>
<script type="text/javascript">
	$().ready(function() {
		$("#tabela").tablesorter({ 
			widgets: ['zebra'],
			headers: {5:{sorter: false}
        } 

    	}); 
	});
</script>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="tit <?= $tit_classe; ?>"><?= $tit; ?> <!--(<?= $tit_situacao;?>)--></h2>

<ul class="recuo1">
	<li><a href="./?pagina=financeiro/pessoa&amp;acao=i&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;status_pessoa=<?= $status_pessoa; ?>">Novo</a></li>
    <li><a href="./?pagina=financeiro/pessoa_listar&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;status_pessoa=1&amp;id_contrato=<?=$id_contrato;?>">Ativos</a></li>
    <li><a href="./?pagina=financeiro/pessoa_listar&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;status_pessoa=0&amp;id_contrato=<?=$id_contrato;?>">Inativos</a></li>
</ul>

<? if ($linhas==0) { ?>
<br />
<p>Nenhum registro encontrado.</p>
<? } else { ?>
<table cellspacing="0" width="100%" id="tabela" class="tablesorter">
	<thead>
        <tr>
            <th width="8%">Cód.</th>
            <th width="35%" align="left"><?=$coluna_nome;?></th>
            <th width="25%" align="left"><?=$coluna_fantasia;?></th>
            <? if ($tipo_pessoa=='r') { ?>
            <th align="left" width="12%">Categoria</th>
            <? } ?>
            <th width="16%">Ações</th>
        </tr>
    </thead>
    <tbody>
		<?
        $i=0;
        while ($rs= mysql_fetch_object($result)) {
            if ($rs->status_pessoa==1) $status= 0;
            else $status= 1;
			
			//$result3= mysql_query("update pessoas set id_categoria = '1' where id_pessoa = '". $rs->id_pessoa ."' ");
        ?>
        <tr id="linha_<?=$rs->id_pessoa;?>">
            <td align="center" valign="top"><? if ($rs->tipo_pessoa=="a") echo pega_id_empresa_da_pessoa($rs->id_pessoa); else echo $rs->id_pessoa; ?></td>
            <td valign="top"><a href="./?pagina=financeiro/pessoa&amp;acao=e&amp;id_pessoa=<?= $rs->id_pessoa; ?>&amp;tipo_pessoa=<?= $rs->tipo_pessoa; ?>"><?= $rs->nome_rz; ?></a></td>
          <td valign="top"><?= $rs->apelido_fantasia; ?></td>
          <? if ($tipo_pessoa=='r') { ?>
            <td><?= pega_categoria($rs->id_categoria); ?></td>
            <? } ?>
          <td align="center" valign="top">
              <a href="./?pagina=financeiro/pessoa&amp;acao=e&amp;id_pessoa=<?= $rs->id_pessoa; ?>&amp;tipo_pessoa=<?= $rs->tipo_pessoa; ?>">
                  <img border="0" src="images/ico_lapis.png" alt="Edita" /></a>
              <? if ($status_cliente!=3) { ?>
              <a href="javascript:situacaoLinha('pessoaStatus', '<?= $rs->id_pessoa; ?>', '<?= $status; ?>', '<?= $tipo_pessoa; ?>');" onclick="return confirm('Tem certeza que deseja alterar o status?');">
                  <img border="0" id="situacao_link_<?=$rs->id_pessoa;?>" src="images/ico_<?= $status; ?>.png" alt="Status" /></a>
              <? } ?>
              <a href="javascript:apagaLinha('pessoaExcluir', '<?= $rs->id_pessoa; ?>', '<?= $tipo_pessoa; ?>');" onclick="return confirm('Tem certeza que deseja excluir?');">
                  <img border="0" src="images/ico_lixeira.png" alt="Status" /></a>
            </td>
        </tr>
        <? $i++; } ?>
    </tbody>
</table>

<? } } ?>