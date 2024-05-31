<?
switch($url[0]) {
	case "about": $id_pagina=1;
	?>
	<script>
		mixpanel.track("Acessou Sobre", {
			"Língua": "<?=$_SESSION["l"];?>"
		});
		</script>
	<?
	break;
	case "contact": $id_pagina=2;
	?>
	<script>
		mixpanel.track("Acessou Contato", {
			"Língua": "<?=$_SESSION["l"];?>"
		});
		</script>
	<?
	break;
}

$result= mysql_query("select pagina_". $l ." as pagina,
						destaque_". $l ." as destaque,
						conteudo_". $l ." as conteudo
						from paginas
						where id_pagina = '". $id_pagina ."'
						");
$rs= mysql_fetch_object($result);
?>

<h2 class="pagetitle"><?= $rs->pagina; ?></h2>

<div class="entry">
	<? if ($rs->destaque!="") { ?>
    <blockquote><p><?= $rs->destaque; ?></p></blockquote>
    <br />
    <? } ?>
    
    <? if ($rs->conteudo!="") { ?>
    <?
	$conteudo= explode("____", $rs->conteudo);
	$conteudo_count= count($conteudo);
	
	switch ($conteudo_count) {
		case 2: $classe= "parte50"; break;
		case 3: $classe= "parte33"; break;
	}
	
	for ($i=0; $i<$conteudo_count; $i++) {
		echo "<div class='". $classe ."'>";
		echo formata_texto_saida($conteudo[$i]);
		echo "</div>";
	}
	
    //echo nl2br($rs->conteudo);
    ?>
    
    <? } ?>
</div>