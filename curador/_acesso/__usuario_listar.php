<?
die();
if (pode("a", $_SESSION["permissao"])) {
	
	if ($_GET["status_usuario"]!="") $status_pessoa= $_GET["status_usuario"];
	if ($_POST["status_usuario"]!="") $status_pessoa= $_POST["status_usuario"];
	if ($status_usuario!="") $str= "and   status_usuario = '". $status_usuario ."' ";
	
	$result= mysql_query("select * from usuarios
							where 1=1
							". $str ."
							and   status_usuario <> '2'
							order by nome asc
							");
	
	$num= 50;
	$total = mysql_num_rows($result);
	$num_paginas = ceil($total/$num);
	if ($_GET["num_pagina"]=="") $num_pagina= 0;
	else $num_pagina= $_GET["num_pagina"];
	$inicio = $num_pagina*$num;
	
	$result= mysql_query("select * from usuarios
							where 1=1
							". $str ."
							and   status_usuario <> '2'
							order by nome asc
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

<h2>Usuários</h2>

<ul class="recuo1">
	<li><a href="./?pagina=acesso/usuario&amp;acao=i">inserir</a></li>
</ul>

<table cellspacing="0" width="100%" id="tabela" class="tablesorter">
	<thead>
        <tr>
            <th width="7%">Cód.</th>
            <th width="23%" align="left">Empresa</th>
            <th width="33%" align="left">Nome</th>
            <th width="18%" align="left">Usuário</th>
            <th width="19%">Ações</th>
        </tr>
    </thead>
    <tbody>
		<?
        $i=0;
        while ($rs= mysql_fetch_object($result)) {
            
            if ($rs->status_usuario==1) $status= 0;
            else $status= 1;
        ?>
        <tr id="linha_<?=$rs->id_usuario;?>">
            <td align="center"><?= $rs->id_usuario; ?></td>
            <td><?= pega_empresa($rs->id_empresa); ?></td>
            <td>
            <?= $rs->nome; ?>
            </td>
            <td><?= $rs->usuario; ?></td>
            <td align="center">
                <a href="./?pagina=acesso/usuario&amp;acao=e&amp;id_usuario=<?= $rs->id_usuario; ?>">
                    <img border="0" src="images/ico_lapis.png" alt="Edita" /></a>
                <a href="javascript:void(0);" onclick="situacaoLinha('usuarioStatus', '<?= $rs->id_usuario; ?>', '<?= $status; ?>');">
                    <img border="0" id="situacao_link_<?=$rs->id_usuario;?>" src="images/ico_<?= $status; ?>.png" alt="Status" /></a>
                <a href="javascript:apagaLinha('usuarioExcluir', <?=$rs->id_usuario;?>);" onclick="return confirm('Tem certeza que deseja excluir?');">
                    <img border="0" src="images/ico_lixeira.png" alt="Status" /></a>
            </td>
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