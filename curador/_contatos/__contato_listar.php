<?
if (pode("r", $_SESSION["permissao"])) {
	if ($_GET["letra"]!="") $letra= $_GET["letra"];
	if ($_POST["letra"]!="") $letra= $_POST["letra"];
	if ($letra!="") $str .= " and   nome like '". $letra ."%' ";
	
	if ($_GET["tipo_contato"]!="") $tipo_contato= $_GET["tipo_contato"];
	if ($_POST["tipo_contato"]!="") $tipo_contato= $_POST["tipo_contato"];
	
	if ($_GET["tipo_pessoa"]!="") $tipo_pessoa= $_GET["tipo_pessoa"];
	if ($_POST["tipo_pessoa"]!="") $tipo_pessoa= $_POST["tipo_pessoa"];
	
	if ($_GET["origem"]!="") $origem= $_GET["origem"];
	if ($_POST["origem"]!="") $origem= $_POST["origem"];
	
	if ($_GET["id_pessoa"]!="") $id_pessoa= $_GET["id_pessoa"];
	if ($_POST["id_pessoa"]!="") $id_pessoa= $_POST["id_pessoa"];
	
	if (($_POST["geral"]=="1") && ($_POST["nome"]!="")) $str .= " and   nome like '%". $_POST["nome"] ."%' ";
	if (($_POST["geral"]=="1") && ($_POST["email"]!="")) $str .= " and   email like '%". $_POST["email"] ."%' ";
	if (($_POST["geral"]=="1") && ($_POST["obs"]!="")) $str .= " and   obs like '%". $_POST["obs"] ."%' ";
	
	if ($tipo_contato!="") $str .= " and   tipo_contato = '". $tipo_contato ."' ";
	if ($id_pessoa!="") $str .= " and   id_pessoa = '". $id_pessoa ."' ";
	
	$result= mysql_query("select * from  contatos
								where id_empresa = '". $_SESSION["id_empresa"] ."'
								". $str ."
								and   status_contato <> '2'
								order by nome asc
								") or die(mysql_error());
								
	$linhas= mysql_num_rows($result);
?>
<script type="text/javascript">
	$().ready(function() {
		$("#tabela").tablesorter({ 
			widgets: ['zebra'],
			headers: {4:{sorter: false}
        	}
    	}); 
	});
</script>

<? /*if ($_POST["geral"]==1) { ?>
<h2>Busca de contato</h2>
<? }*/ ?>

<? if ($esquema_pessoa==1) { ?>
<ul class="recuo1">
	<li><a href="./?pagina=financeiro/pessoa_contatos&amp;acao=i&amp;tipo_contato=<?=$tipo_contato;?>&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;id_pessoa=<?=$id_pessoa;?>&amp;origem=2&amp;acao_origem=i">inserir</a></li>
    <li><a href="./?pagina=contatos/contato_importar&amp;acao=i&amp;tipo_contato=<?=$tipo_contato;?>&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;id_pessoa=<?=$id_pessoa;?>">importar vCard</a></li>
    <li><a href="./?pagina=financeiro/pessoa_contatos&amp;tipo_contato=<?=$tipo_contato;?>&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;id_pessoa=<?=$id_pessoa;?>">listar</a></li>
    <li><a href="index2.php?pagina=contatos/contato_exportar&amp;tipo_contato=<?=$tipo_contato;?>&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;id_pessoa=<?=$id_pessoa;?>">exportar vCards</a></li>
</ul>
<? } else { ?>
<ul class="recuo1">
	<li><a href="./?pagina=contatos/contato&amp;acao=i&amp;tipo_contato=<?=$tipo_contato;?>&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;id_pessoa=<?=$id_pessoa;?>">inserir</a></li>
    <? if ($id_pessoa!="") { ?>
    <li><a href="./?pagina=contatos/contato_importar&amp;acao=i&amp;tipo_contato=<?=$tipo_contato;?>&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;id_pessoa=<?=$id_pessoa;?>">importar vCard</a></li>
    <? } ?>
    <li><a href="./?pagina=contatos/contato_buscar&amp;tipo_contato=<?=$tipo_contato;?>&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;id_pessoa=<?=$id_pessoa;?>">buscar</a></li>
</ul>
<? } ?>
<br />

<? if ($linhas==0) { ?>
<p>Nenhum contato encontrado.</p>
<? } else { ?>

<form action="<?= AJAX_FORM; ?>formContatoExcluirCheck" method="post" name="form" id="form">
	
    <input name="tipo_contato" class="escondido" type="hidden" value="<?= $tipo_contato; ?>" />
    <input name="tipo_pessoa" class="escondido" type="hidden" value="<?= $tipo_pessoa; ?>" />
    <input name="id_pessoa" class="escondido" type="hidden" value="<?= $id_pessoa; ?>" />
    <input name="origem" class="escondido" type="hidden" value="<?= $origem; ?>" />
    
    <table cellspacing="0" width="100%" id="tabela" class="tablesorter">
        <thead>
            <tr>
              <th width="3%" align="left">&nbsp;</th>
              <th width="18%" align="left">Contato</th>
              <th width="12%" align="left">Entidade</th>
              <? if (($_POST["geral"]==1) || ($tipo_contato=="")) { ?>
              <th width="9%" align="left">Tipo</th>
              <? } ?>
              <th width="21%" align="left">E-mail/Website</th>
              <? for ($i=1; $i<2; $i++) { ?>
              <th width="12%" align="left">Tel. <?= $i; ?></th>
              <? } ?>
              <th width="14%">Ações</th>
          </tr>
        </thead>
        <tbody>
        <?
        $j=0;
        while ($rs= mysql_fetch_object($result)) {
            if (($j%2)==0) $classe= "odd";
            else $classe= "even";
            
            $result_tel= mysql_query("select * from contatos_telefones
                                        where id_empresa = '". $_SESSION["id_empresa"] ."'
                                        and   id_contato = '". $rs->id_contato ."'
                                        order by id asc
                                        ");
            unset($telefone);
            unset($classe_tel);
            $k=1;
            while ($rs_tel= mysql_fetch_object($result_tel)) {
                $telefone[$k] = $rs_tel->telefone ."";
                $obs[$k] = $rs_tel->obs ."";
                
                switch($rs_tel->tipo) {
                    case 1: $classe_tel[$k]= "preto"; break;
                    case 2: $classe_tel[$k]= "azul"; break;
                    case 3: $classe_tel[$k]= "verde"; break;
                    case 4: $classe_tel[$k]= "vermelho"; break;
                    case 5: $classe_tel[$k]= "cinza"; break;
                }
                
                $k++;
            }
        ?>
            <tr id="linha_<?= $rs->id_contato; ?>" class="<?= $classe; ?> corzinha">
                <td valign="top">
                    <input type="hidden" class="escondido" name="index[]" id="index_<?= $rs->id_contato; ?>" value="1" />
                    <input type="checkbox" name="id_contato[]" id="id_contato_<?= $rs->id_contato; ?>" value="<?= $rs->id_contato; ?>" />
                </td>
                <td valign="top">
                <?
                if ($origem=="2") $link_editar_contato= "./?pagina=financeiro/pessoa_contatos&amp;acao_origem=e&amp;id_contato=". $rs->id_contato ."&amp;tipo_pessoa=". $_GET["tipo_pessoa"] ."&amp;tipo_contato=". $rs->tipo_contato ."&amp;id_pessoa=". $rs->id_pessoa ."&amp;origem=". $origem ."";
                else $link_editar_contato= "./?pagina=contatos/contato&amp;acao=e&amp;id_contato=". $rs->id_contato ."&amp;id_origem=". $origem ."";
                ?>
                <a href="<?= $link_editar_contato; ?>">
                <?
                echo $rs->nome;
                ?>
                </a>
                </td>
                <td valign="top" align="left">
                <?
                if ($rs->id_pessoa!="") echo pega_pessoa($rs->id_pessoa); else echo "-";
                ?>
                </td>
                <? if (($_POST["geral"]==1) || ($tipo_contato=="")) { ?>
                <td valign="top" align="left"><?= pega_tipo_contato($rs->tipo_contato);?></td>
                <? } ?>
                <td valign="top">
                    <?=$rs->email;?>
                    
                    <? if ($rs->site!="") { ?>
                    <br />
                    <a href="<?=$rs->site;?>" target="_blank"><?=$rs->site;?></a>
                    <? } ?>
                </td>
                <? for ($i=1; $i<2; $i++) { ?>
                <td valign="top"><a <? if ($obs[$i]!="") { ?> onmouseover="Tip('<?= $obs[$i];?>');" <? } ?> href="javascript:void(0);" class="<?= $classe_tel[$i]; ?>"><?= $telefone[$i]; ?></a></td>
                <? } ?>
                <td valign="top" align="center">
                    
                    <a href="<?= $link_editar_contato; ?>">
                        <img border="0" src="images/ico_lapis.png" alt="Edita" /></a>
                    <a href="javascript:apagaLinha('contatoExcluir', <?=$rs->id_contato;?>);" onclick="return confirm('Tem certeza que deseja excluir?');">
                        <img border="0" src="images/ico_lixeira.png" alt="Status" />
                    </a>
                </td>
            </tr>
        <? $j++; } ?>
        </tbody>
    </table>
    
    <br />
    
    <button class="flutuar_esquerda" onclick="checarDeschecarTudo('1', 'tabela');" type="button">selecionar tudo</button>
    
    <button class="flutuar_direita" type="submit" id="enviar">apagar selecionados</button>
    
</form>

<? } } ?>