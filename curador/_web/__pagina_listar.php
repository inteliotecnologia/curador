<?
if (pode("v", $_SESSION["permissao"])) {
	
	$result= mysql_query("select * from paginas
							where 1=1
							". $str ."
							and   status_pagina <> '2'
							order by id_pagina asc
							");
	
	$num= 50;
	$total = mysql_num_rows($result);
	$num_paginas = ceil($total/$num);
	if ($_GET["num_pagina"]=="") $num_pagina= 0;
	else $num_pagina= $_GET["num_pagina"];
	$inicio = $num_pagina*$num;
	
	$result= mysql_query("select * from paginas
							where 1=1
							". $str ."
							and   status_pagina <> '2'
							order by id_pagina asc
							limit $inicio, $num
							");
	
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

<h2 class="tit tit_papeis">Páginas</h2>

<? /*
<ul class="recuo1">
	<li><a href="./?pagina=financeiro/projeto&amp;acao=i">inserir</a></li>
</ul>
*/ ?>

<table cellspacing="0" width="100%" id="tabela" class="tablesorter">
	<thead>
        <tr>
            <th width="10%">Cód.</th>
            <th width="67%" align="left">Página</th>
            <th width="23%">Última edição</th>
        </tr>
    </thead>
    <tbody id="projetos">
		<?
        $i=0;
        while ($rs= mysql_fetch_object($result)) {
        ?>
        <tr id="linha_<?=$rs->id_pagina;?>">
            <td align="center"><?= $rs->id_pagina; ?></a></td>
            <td><a href="./?pagina=web/pagina&amp;id_pagina=<?= $rs->id_pagina; ?>&amp;acao=e"><?= $rs->pagina_pt; ?></a></td>
            <td align="center">
			<?
            $ultima_edicao= explode(" ", $rs->ultima_edicao);
			echo desformata_data($ultima_edicao[0]) ." ". $ultima_edicao[1];
			?>
            </td>
            <? /*
            <td align="center">
                <a href="./?pagina=financeiro/projeto&amp;acao=e&amp;id_projeto=<?= $rs->id_projeto; ?>">
                    <img border="0" src="images/ico_lapis.png" alt="Edita" /></a>
                <a href="javascript:apagaLinha('projetoExcluir', <?=$rs->id_projeto;?>);" onclick="return confirm('Tem certeza que deseja excluir?');">
                    <img border="0" src="images/ico_lixeira.png" alt="Status" /></a>
            </td>*/ ?>
        </tr>
        <? $i++; } ?>
    </tbody>
</table>

<?
if ($num_paginas > 1) {
	$link_pagina= "acesso/usuario_listar";
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