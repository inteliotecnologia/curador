<?
if (pode("v", $_SESSION["permissao"])) {
	
	if ($_GET["tipo_tag"]!="") $str= " and   tipo_tag = '". $_GET["tipo_tag"] ."' ";
	
	$result= mysql_query("select * from tags
							where id_empresa = '". $_SESSION["id_empresa"] ."'
							". $str ."
							order by tag_pt asc
							");
	
	/*$num= 50;
	$total = mysql_num_rows($result);
	$num_paginas = ceil($total/$num);
	if ($_GET["num_pagina"]=="") $num_pagina= 0;
	else $num_pagina= $_GET["num_pagina"];
	$inicio = $num_pagina*$num;
	
	$result= mysql_query("select * from tags
							where id_empresa = '". $_SESSION["id_empresa"] ."'
							". $str ."
							order by tag_pt asc
							limit $inicio, $num
							");*/
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

<div id="tela_mensagens_acoes">
</div>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="tit tit_papeis">Tags</h2>

<ul class="recuo1">
	<li><a href="./?pagina=financeiro/tag&amp;acao=i">Nova tag</a></li>
    <li><a href="./?pagina=financeiro/tag_listar&amp;tipo_tag=1">Site + sistema</a></li>
    <li><a href="./?pagina=financeiro/tag_listar&amp;tipo_tag=2">Somente site</a></li>
</ul>

<? if ($linhas==0) { ?>
<p>Nada encontrado.</p>
<? } else { ?>

<table cellspacing="0" width="100%" id="tabela" class="tablesorter">
	<thead>
        <tr>
            <th width="10%">Cód.</th>
            <th width="28%" align="left">Tag (PT)</th>
            <th width="24%" align="left">Tag (EN)</th>
            <th width="20%" align="left">Tipo</th>
            <th width="18%">Ações</th>
        </tr>
    </thead>
    <tbody>
		<?
        $i=0;
        while ($rs= mysql_fetch_object($result)) {
        ?>
        <tr id="linha_<?=$rs->id_tag;?>">
            <td align="center"><?= $rs->id_tag; ?></td>
            <td><?= $rs->tag_pt; ?></td>
            <td><?= $rs->tag_en; ?></td>
            <td>
            <?
            if ($rs->tipo_tag=="1") echo "site + sistema";
			else echo "somente sistema";
			?>
            </td>
            <td align="center">
                <a href="./?pagina=financeiro/tag&amp;acao=e&amp;id_tag=<?= $rs->id_tag; ?>">
                    <img border="0" src="images/ico_lapis.png" alt="Edita" /></a>
                <a href="javascript:apagaLinha('tagExcluir', <?=$rs->id_tag;?>);" onclick="return confirm('Tem certeza que deseja excluir?');">
                    <img border="0" src="images/ico_lixeira.png" alt="Status" /></a>
            </td>
        </tr>
        <? $i++; } ?>
    </tbody>
</table>

<? } ?>

<?
if ($num_paginas > 1) {
	$link_pagina= "financeiro/tag_listar";
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