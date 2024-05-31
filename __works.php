<?
if ($url[1]=="selected") {
	$str= " and   selecionado = '1' ";
	$titulo= $_MENU_SELECTED[$l];
	
	$ordem= "ordem asc";
	$tipo_projeto= "selecionado";
	?>
	<script>
		mixpanel.track("Acessou Projetos", {
			"Língua": "<?=$_SESSION["l"];?>",
			"Área": "Selecionados",
			"Modo de visualização": "<?=$url[2];?>"
		});
	</script>
	<?
}
elseif ($url[1]=="archive") {
	$str= " and   selecionado <> '1' ";
	$titulo= $_MENU_ARCHIVE[$l];
	
	if ($url[1]=="") $url[1]="archive";
	
	$ordem= "ordem asc";
	$tipo_projeto= "";
	
	?>
	<script>
		mixpanel.track("Acessou Projetos", {
			"Língua": "<?=$_SESSION["l"];?>",
			"Área": "Arquivo",
			"Modo de visualização": "<?=$url[2];?>"
		});
	</script>
	<?
}
else {
	$str= "";
	$titulo= $_MENU_ARCHIVE[$l];
	$ordem= "ordem asc";
	$tipo_projeto= "";
	
	?>
	<script>
		mixpanel.track("Acessou Projetos", {
			"Língua": "<?=$_SESSION["l"];?>",
			"Área": "Todos",
			"Modo de visualização": "<?=$url[2];?>"
		});
	</script>
	<?
}

if ($url[2]=="") $url[2]=1;


?>

<h2 class="pagetitle"><?= $titulo; ?></h2>

<div class="view_options">
	<div class="view_options_label">
    	<?= $_VIEW_OPTIONS[$l]; ?>
    </div>
    
    <ul>
    	<li class="view_options_1"><a <? if ($url[2]==1) echo "class=\"on\""; ?> href="<?=$r;?>works/<?=$url[1];?>/1/<?=$url[3];?>">1</a></li>
        <li class="view_options_2"><a <? if ($url[2]==2) echo "class=\"on\""; ?> href="<?=$r;?>works/<?=$url[1];?>/2/<?=$url[3];?>">2</a></li>
        <li class="view_options_3"><a <? if ($url[2]==3) echo "class=\"on\""; ?> href="<?=$r;?>works/<?=$url[1];?>/3/<?=$url[3];?>">3</a></li>
    </ul>
</div>

<div class="view_tags">
	<a class="link_tags closed" title="closed" href="javascript:void(0);"><?= $_SELECT_BY_TAGS[$l]; ?></a>
    
    <div class="tags_list" style="display:none;">
    	<ul>
        	<?
			$tags= "";
			
			$result_tags= mysql_query("select *, tags_site_". $l ." as tags from projetos
										where status_projeto <> '0'
										and   site = '1'
										/* ". $str ." */
										") or die(mysql_error());
			$i= 0;
			while ($rs_tags= mysql_fetch_object($result_tags)) {
				if ($rs_tags->tags!="") {
					$tags_aqui= explode(",", $rs_tags->tags);
					
					$j=0;
					while (($tags_aqui[$j]!="") && ($tags_aqui[$j]!=" ")) {
						
						$tag[$i]= trim($tags_aqui[$j]);
						
						$i++;
						$j++;
					}
				}
			}
			
			if ($i>0) {
				$tag= @array_unique($tag);
				@sort($tag);
				
				$tamanho= (count($tag));
			
				if ($tamanho>0) {
					
				$i=0;
				foreach ($tag as $chave => $valor){
				?>
					<li><a href="<?=$r;?>works//<?= $url[2]; ?>/<?=retira_acentos(str_replace(" ", "_", $valor));?>/"><?=$valor;?></a></li>
				<?
					$i++;
				} }
			}
			?>
        </ul>
    </div>
</div>

<?
switch($url[2]) {
	case 1: $total_por_pagina= 15; break;
	case 2: $total_por_pagina= 8; break;
	case 3: $total_por_pagina= 20; break;
}

if ($url[3]!="") {
	$str.= "and   tags_site_". $l ." like '%". str_replace("_", " ", $url[3]) ."%' ";
	
	$subtitulo= $url[3];
}

if ($subtitulo!="") {
?>
<h3 class="subtitle"><?= str_replace("_", " ", gambiarra_acentos($subtitulo)); ?></h3>
<?
}

$result= mysql_query("select *, projeto_". $l ." as projeto, texto_site_". $l ." as texto_site from projetos
						where site = '1'
						". $str ."
						and   status_projeto <> '0'
						order by $ordem
						limit 0, ". $total_por_pagina ."
						");
						
$linhas= mysql_num_rows($result);

$result_teste= mysql_query("select * from projetos
							where site = '1'
							". $str ."
							and   status_projeto <> '0'
							order by $ordem
							limit ". $total_por_pagina .", ". $total_por_pagina ."
							");
$linhas_teste= mysql_num_rows($result_teste);

if ($url[2]==3) {
?>

<div class="list-lines list-lines-th">
    <span class="name">
        <?= $_WORD_PROJECT[$l]; ?>
    </span>
    <span class="other">
        <?= $_WORD_AGENCY[$l]; ?>
    </span>
</div>

<?
}

$i=1;
while ($rs= mysql_fetch_object($result)) {
	
	$imagem_miniatura= pega_imagem_miniatura_site('p', $rs->id_projeto, $url[2]);
		
	if ($imagem_miniatura!="") $imagem_definitiva= $imagem_miniatura;
	else {
		$imagem= pega_imagem('p', $rs->id_projeto);
		$imagem_definitiva= $imagem;
	}
	
	$j=$i-1;
	
	if (($i%3)==0) $classe= "nope";
	else $classe= "";
	
	switch ($url[2]) {
		case 1:
?>            
<div class="list-thumb">
    <div class="post <?=$classe;?>">
        <a href="<?=$r;?>work/<?= $rs->url; ?>/" title="<?= $rs->projeto; ?>">
            <? if ($imagem_definitiva!="") { ?>
            <img width="300" height="160" src="<?= BUCKET . BUCKET_SITE; ?>projeto_<?= $rs->id_projeto; ?>/<?= $imagem_definitiva; ?>" alt=""/>
            <? } ?>
            
            <span class="h3"><?= $rs->projeto; ?></span>

            <span class="h4"><?= pega_pessoa($rs->id_agencia); ?></span>
        </a>
    </div>         
</div>
<?
	break;
	
	case 2:
	
	$ultimo_id= "linha_". $rs->id_projeto;
?>
<div id="<?= $ultimo_id;?>" class="list-open <? if ($linhas==$i) echo " sem-bg" ?>">
	<a href="<?=$r;?>work/<?= $rs->url; ?>/" title="<?= $rs->projeto; ?>">
        <span class="list-open-thumb">
			<? if ($imagem_definitiva!="") { ?>
                <img width="620" height="330" src="<?= BUCKET . BUCKET_SITE; ?>projeto_<?= $rs->id_projeto; ?>/<?= $imagem_definitiva; ?>" alt=""/>
            <? } ?>
    	</span>
        <span class="list-open-infos">
            <span class="h3"><?= $rs->projeto; ?></span>
    
            <span class="h4"><?= pega_pessoa($rs->id_agencia); ?></span>
            
            <span class="p"><?= string_maior_que(strip_tags($rs->texto_site), 230); ?></span>
        </span>
    </a>
</div>
<?
	break;

	case 3:
?>
<div class="list-lines <? if (($linhas_teste==0) && ($linhas==$i)) echo " sem-bg" ?>">
    <a href="<?=$r;?>work/<?= $rs->url; ?>/" title="<?= $rs->projeto; ?>">
        <span class="name">
            <?= $rs->projeto; ?>
        </span>
        <span class="other">
            <?= pega_pessoa($rs->id_agencia); ?>
        </span>
    </a>
</div>
<?
	break;
}
?>
<? $i++; } ?>

<? if (($url[1]=="selected") && ($total_por_pagina>=15)) { ?>
<div class="div_more">
    <a class="link_more link_more_ucfirst" href="<?=$r;?>works/archive"><?= $_MENU_ARCHIVE[$l]; ?> &raquo;</a>
</div>
<?
}
elseif ($linhas_teste>0) {
?>

<div id="leva_<?= $total_por_pagina; ?>">
	<div class="div_more">
        <a id="link_leva_<?= $total_por_pagina; ?>" class="link_more" href="javascript:void(0);" onclick="carregaDinamico(this, '<?=$r;?>', 'works', '<?=$url[2];?>', '<?= $total_por_pagina; ?>', '<?= $total_por_pagina; ?>', '<?= $url[3]; ?>', '', '<?= $tipo_projeto; ?>', '<?=$ultimo_id;?>');"><?= $_MORE[$l]; ?></a>
    </div>
</div>

<? } ?>
