<?
if (pode("v", $_SESSION["permissao"])) {
	
	if ($_GET["selecionado"]!="") $str= " and   selecionado = '". $_GET["selecionado"] ."' ";
	
	$result= mysql_query("select * from projetos
							where 1=1
							". $str ."
							and   status_projeto <> '0'
							order by data_projeto desc, id_projeto desc
							");
	
	if (!isset($_GET["selecionado"])) $num=9999;
	else $num= 50;
	
	$total = mysql_num_rows($result);
	$num_paginas = ceil($total/$num);
	if ($_GET["num_pagina"]=="") $num_pagina= 0;
	else $num_pagina= $_GET["num_pagina"];
	$inicio = $num_pagina*$num;
	
	$result= mysql_query("select * from projetos
							where 1=1
							". $str ."
							and   status_projeto <> '0'
							order by data_projeto desc, id_projeto desc
							limit $inicio, $num
							");
	
?>
<script type="text/javascript">
	$().ready(function() {
		$("#tabela").tablesorter({ 
			widgets: ['zebra'],
			headers: {6:{sorter: false}
        	}
    	}); 
		
		<? if ($_GET["selecionado"]=="1") { ?>
		$(function() {
            $("#projetos").sortable({ opacity: 0.8, cursor: 'move', update: function() {
                var order = $(this).sortable("serialize") + '&chamada=atualizaOrdemImagens'; 
                $.get("link.php", order, function(theResponse){
                    //$("#ordem_retorno").html(theResponse);
                }); 															 
            }								  
            });
        });
		<? } ?>
		
	});
	
</script>

<div id="tela_mensagens_acoes">
</div>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="tit tit_papeis">Lista de projetos</h2>

<ul class="recuo1">
	<li><a href="./?pagina=financeiro/projeto&amp;acao=i">Inserir novo</a></li>
</ul>

<table cellspacing="0" width="100%" id="tabela" class="tablesorter">
	<thead>
        <tr>
            <th width="5%">Cód.</th>
            <th width="21%" align="left">Projeto</th>
            <? //if ($_GET["selecionado"]==1) { ?>
            <th width="6%">Ordem</th>
            <? //} ?>
            <th width="11%" align="left">Data início</th>
            <th width="14%" align="left">Agência</th>
            <th width="16%" align="left">Cliente</th>
            <th width="15%">Status</th>
            <th width="12%">Ações</th>
        </tr>
    </thead>
    <tbody id="projetos">
		<?
        $i=0;
        while ($rs= mysql_fetch_object($result)) {
        ?>
        <tr id="linha_<?=$rs->id_projeto;?>" class="status_<?=$rs->status_projeto;?>">
            <td align="center"><?= $rs->id_projeto; ?></td>
            <td><a href="./?pagina=financeiro/projeto&amp;id_projeto=<?= $rs->id_projeto; ?>&amp;acao=e"><?= $rs->projeto_pt; ?></a></td>
            <? //if ($_GET["selecionado"]==1) { ?>
            <td><?= $rs->ordem; ?></td>
            <? //} ?>
            <td><span class="escondido"><?=$rs->data_projeto;?></span></span><?= desformata_data($rs->data_projeto); ?></td>
            <td><?= pega_pessoa($rs->id_agencia); ?></td>
            <td><?= pega_pessoa($rs->id_cliente); ?></td>
            <td align="center"><?= pega_status_projeto($rs->status_projeto); ?></td>
            <td align="center">
                <a href="./?pagina=financeiro/projeto&amp;acao=e&amp;id_projeto=<?= $rs->id_projeto; ?>">
                    <img border="0" src="images/ico_lapis.png" alt="Edita" /></a>
                <a href="javascript:apagaLinha('projetoExcluir', <?=$rs->id_projeto;?>);" onclick="return confirm('Tem certeza que deseja excluir?');">
                    <img border="0" src="images/ico_lixeira.png" alt="Status" /></a>
            </td>
        </tr>
        <? $i++; } ?>
    </tbody>
</table>

<?
if ($num_paginas > 1) {
	$link_pagina= "financeiro/projeto_listar";
?>
	<div class="paginacao">
	<? if ($num_pagina > 0) {
		$menos = $num_pagina - 1;
		echo "<a href=\"./?pagina=". $link_pagina ."&amp;num_pagina=". $menos. "\">&laquo; Anterior</a>";
	}

	for ($i=0; $i<$num_paginas; $i++) {
		$link = $i + 1;
		if ($num_pagina==$i)
			echo " <b>". $link ."</b>";
		else
			echo " <a href=\"./?pagina=". $link_pagina ."&amp;num_pagina=". $i. "\">". $link ."</a>";
	}

	if ($num_pagina < ($num_paginas - 1)) {
		$mais = $num_pagina + 1;
		echo " <a href=\"./?pagina=". $link_pagina ."&amp;num_pagina=". $mais ."\">Pr&oacute;xima &raquo;</a>";
	}
	?>
    </div>
<? } ?>

<? } ?>