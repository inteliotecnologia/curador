<?
if (pode("v", $_SESSION["permissao"])) {
	
	if ($_GET["id_projeto"]!="") $id_projeto= $_GET["id_projeto"];
	if ($_POST["id_projeto"]!="") $id_projeto= $_POST["id_projeto"];
	
	$result= mysql_query("select * from curadorias
							where id_projeto = '". $id_projeto ."'
							". $str ."
							and   status_curadoria <> '2'
							order by data_curadoria desc, id_curadoria desc
							");
	
	$num= 50;
	$total = mysql_num_rows($result);
	$num_paginas = ceil($total/$num);
	if ($_GET["num_pagina"]=="") $num_pagina= 0;
	else $num_pagina= $_GET["num_pagina"];
	$inicio = $num_pagina*$num;
	
	$result= mysql_query("select * from curadorias
							where id_projeto = '". $id_projeto ."'
							". $str ."
							and   status_curadoria <> '2'
							order by data_curadoria desc, id_curadoria desc
							limit $inicio, $num
							");
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

<h2 class="tit tit_maleta"><?= pega_projeto($id_projeto); ?></h2>

<?
include("_financeiro/__projeto_abas.php");
?>

<ul class="recuo1">
	<li><a href="./?pagina=financeiro/curadoria_passo1&amp;acao=i&amp;id_projeto=<?=$id_projeto;?>" onclick="return confirm('Tem certeza que deseja iniciar\numa nova curadoria para este projeto?');">inserir</a></li>
</ul>
<br />

<? if ($linhas==0) { ?>
<p>Nenhuma curadoria até o momento.</p>
<? } else { ?>

<table cellspacing="0" width="100%" id="tabela" class="tablesorter">
	<thead>
        <tr>
            <th width="7%">Cód.</th>
            <th width="15%" align="left">Nome</th>
            <th width="8%" align="left">Data</th>
            <th width="21%" align="left">&Uacute;ltima modifica&ccedil;&atilde;o</th>
            <th width="31%" align="left">Artistas</th>
            <th width="18%">Ações</th>
        </tr>
    </thead>
    <tbody>
		<?
        $i=0;
        while ($rs= mysql_fetch_object($result)) {
        ?>
        <tr id="linha_<?=$rs->id_curadoria;?>">
            <td align="center"><a href="./?pagina=financeiro/curadoria_passo1&amp;acao=e&amp;id_projeto=<?= $rs->id_projeto; ?>&amp;id_curadoria=<?=$rs->id_curadoria;?>"><?= $rs->id_curadoria; ?></a></td>
            <td><a href="./?pagina=financeiro/curadoria_passo1&amp;acao=e&amp;id_projeto=<?= $rs->id_projeto; ?>&amp;id_curadoria=<?=$rs->id_curadoria;?>"><?= $rs->titulo_curadoria; ?></a></td>
            <td><?= desformata_data($rs->data_curadoria); ?></td>
            <td><?= desformata_data($rs->data_curadoria_mod) ." ". $rs->hora_curadoria_mod; ?> por <?= primeira_palavra(pega_nome_usuario($rs->id_usuario_mod)); ?></td>
            
            <td>
            <?
            $result_artistas= mysql_query("select id_pessoa from curadorias_pessoas
											where id_curadoria = '". $rs->id_curadoria ."'
											order by ordem asc
											");
			$linhas_artistas= mysql_num_rows($result_artistas);
			
			$j=1;
			while ($rs_artistas= mysql_fetch_object($result_artistas)) {
            	if ($j==$linhas_artistas) $sinal=".";
				else $sinal= ",";
				
				echo pega_pessoa($rs_artistas->id_pessoa) . $sinal ." ";
				
				$j++;
			}
			?>
            </td>
            <td align="center">
                <a title="Duplicar" onclick="return confirm('Tem certeza que deseja duplicar esta curadoria?');" href="link.php?chamada=duplicaCuradoria&amp;id_curadoria=<?= $rs->id_curadoria; ?>&amp;id_projeto=<?= $rs->id_projeto; ?>">
                    <img border="0" src="images/ico_duplicar.png" alt="Duplica" /></a>
                
                <? if (file_exists("uploads/curadorias/curadoria_". $rs->auth .".pdf")) { ?>
                <a title="Download" href="uploads/curadorias/curadoria_<?= $rs->auth;?>.pdf" target="_blank">
                    <img border="0" src="images/ico_download.png" alt="Download" /></a>
                <? } ?>
                
                <a title="Editar" href="./?pagina=financeiro/curadoria_passo1&amp;acao=e&amp;id_projeto=<?= $rs->id_projeto; ?>&amp;id_curadoria=<?=$rs->id_curadoria;?>">
                    <img border="0" src="images/ico_lapis.png" alt="Edita" /></a>
                    
                <a title="Excluir" href="javascript:apagaLinha('curadoriaExcluir', <?=$rs->id_curadoria;?>);" onclick="return confirm('Tem certeza que deseja excluir?');">
                    <img border="0" src="images/ico_lixeira.png" alt="Status" /></a>
            </td>
        </tr>
        <? $i++; } ?>
    </tbody>
</table>

<?
if ($num_paginas > 1) {
	$link_pagina= "financeiro/curadoria_projeto_listar&amp;id_projeto=". $id_projeto;
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
<? } } ?>

<? } ?>