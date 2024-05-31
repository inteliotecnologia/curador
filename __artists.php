<?
if ($url[2]=="") $url[1]="all";
if ($url[2]=="") $url[2]=1;
?>

<script>
	mixpanel.track("Acessou Artistas", {
		"Língua": "<?=$_SESSION["l"];?>",
		"Modo de visualização": "<?=$url[2];?>"
	});
</script>

<h2 class="pagetitle"><?= $_MENU_ARTISTS[$l]; ?></h2>

<div class="view_options">
	<div class="view_options_label">
    	<?= $_VIEW_OPTIONS[$l]; ?>
    </div>
    
    <ul>
    	<li class="view_options_1"><a <? if ($url[2]==1) echo "class=\"on\""; ?> href="<?=$r;?>artists/<?=$url[1];?>/1/<?=$url[3];?>">1</a></li>
        <li class="view_options_2"><a <? if ($url[2]==2) echo "class=\"on\""; ?> href="<?=$r;?>artists/<?=$url[1];?>/2/<?=$url[3];?>">2</a></li>
        <li class="view_options_3"><a <? if ($url[2]==3) echo "class=\"on\""; ?> href="<?=$r;?>artists/<?=$url[1];?>/3/<?=$url[3];?>">3</a></li>
    </ul>
</div>

<div class="view_tags">
	<a class="link_tags closed" title="closed" href="javascript:void(0);"><?= $_SELECT_BY_TAGS[$l]; ?></a>
    
    <div class="tags_list" style="display:none;">
    	<ul>
        	<?
			$tags= "";
			
			$result_tags= mysql_query("select *, pessoas.tags_site_". $l ." as tags from pessoas, pessoas_tipos
										where pessoas.id_pessoa = pessoas_tipos.id_pessoa
										and   pessoas_tipos.status_pessoa <> '2'
										and   pessoas_tipos.tipo_pessoa = 'r'
										and   pessoas.site = '1'
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
					<li><a href="<?=$r;?>artists/<?=$url[1];?>/<?= $url[2]; ?>/<?=retira_acentos(str_replace(" ", "_", $valor));?>/"><?=$valor;?></a></li>
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
	$str= "and   pessoas.tags_site_". $l ." like '%". str_replace("_", " ", $url[3]) ."%' ";
	$subtitulo= $url[3];
}

if ($subtitulo!="") {
?>
<h3 class="subtitle"><?= str_replace("_", " ", gambiarra_acentos($subtitulo)); ?></h3>
<?
}

$result= mysql_query("select *, pessoas.texto_site_". $l ." as texto_site from pessoas, pessoas_tipos, enderecos
						where pessoas.id_pessoa = pessoas_tipos.id_pessoa
						and   pessoas_tipos.tipo_pessoa = 'r'
						and   pessoas.site = '1'
						and   pessoas.id_pessoa = enderecos.id_pessoa
						and   pessoas_tipos.status_pessoa <> '2'
						$str
						order by RAND() asc
						limit 0, ". $total_por_pagina ."
						");
$linhas= mysql_num_rows($result);

if ($url[2]==3) {
?>

<div class="list-lines list-lines-th">
    <span class="name">
        <?= $_WORD_ARTIST[$l]; ?>
    </span>
    <span class="other">
        Local
    </span>
</div>

<?
}

$excluir= "";
$i=1;
while ($rs= mysql_fetch_object($result)) {
	$excluir .= $rs->id_pessoa ."-";
	
	$imagem_miniatura= pega_imagem_miniatura_site('a', $rs->id_pessoa, $url[2]);
			
	if ($imagem_miniatura!="") $imagem_definitiva= $imagem_miniatura;
	else {
		$imagem= pega_imagem('a', $rs->id_pessoa);
		$imagem_definitiva= $imagem;
	}
	
	if ($l=="pt") $cidade_uf= $rs->cidade_uf;
	else $cidade_uf= $rs->cidade_uf_en;
	//else $cidade_uf= pega_cidade($rs->id_cidade);
	
	if (($i%3)==0) $classe= "nope";
	else $classe= "";
	
	$str.= " and   pessoas.id_pessoa <> '". $rs->id_pessoa ."' ";
	
	$result_teste= mysql_query("select * from pessoas, pessoas_tipos, enderecos
								where pessoas.id_pessoa = pessoas_tipos.id_pessoa
								and   pessoas_tipos.tipo_pessoa = 'r'
								and   pessoas.site = '1'
								and   pessoas.id_pessoa = enderecos.id_pessoa
								and   pessoas_tipos.status_pessoa <> '2'
								$str
								order by RAND() asc
								limit ". $total_por_pagina ."
								");
	$linhas_teste= mysql_num_rows($result_teste);
	
	switch ($url[2]) {
		case 1:
?>            
<div class="list-thumb">
    <div class="post <?=$classe;?>">
        <a href="<?=$r;?>artist/<?= $rs->url; ?>/" title="<?= $rs->apelido_fantasia; ?>">
            <? if ($imagem_definitiva!="") { ?>
            <img width="300" height="160" src="<?= BUCKET . BUCKET_SITE; ?>artista_<?= $rs->id_pessoa; ?>/<?= $imagem_definitiva; ?>" alt=""/>
            <? } ?>
            
            <span class="h3"><?= $rs->apelido_fantasia; ?></span>

            <span class="h4"><?= $cidade_uf; ?></span>
        </a>
    </div>
                
</div>
<?
	break;
	
	case 2:
		$ultimo_id= "linha_". $rs->id_pessoa;
?>
<div id="<?= $ultimo_id; ?>" class="list-open <? if ($linhas==$i) echo " sem-bg" ?>">
    <a href="<?=$r;?>artist/<?= $rs->url; ?>/" title="<?= $rs->apelido_fantasia; ?>">
        <span class="list-open-thumb">    
			<? if ($imagem_definitiva!="") { ?>
            <img width="620" height="330" src="<?= BUCKET . BUCKET_SITE; ?>artista_<?= $rs->id_pessoa; ?>/<?= $imagem_definitiva; ?>" alt=""/>
            <? } ?>
        </span>
        <span class="list-open-infos">
            <span class="h3"><?= $rs->apelido_fantasia; ?></span>
    
            <span class="h4"><?= $cidade_uf; ?></span>
            
            <span class="p"><?= string_maior_que(strip_tags($rs->texto_site), 230); ?></span>
        </span>
    </a>
</div>
<?
	break;

	case 3:
?>
<div class="list-lines <? if (($linhas_teste==0) && ($linhas==$i)) echo " sem-bg" ?>">
	<a href="<?=$r;?>artist/<?= $rs->url; ?>/" title="<?= $rs->apelido_fantasia; ?>">
        <span class="name">
            <?= $rs->apelido_fantasia; ?>
        </span>
        <span class="other">
            <?= $cidade_uf; ?>
        </span>
    </a>
</div>
<?
	break;
}
?>
<? $i++; } ?>

<?
if ($linhas_teste>0) {
?>
<div id="leva_<?= $total_por_pagina; ?>">
	<div class="div_more">
        <a id="link_leva_<?= $total_por_pagina; ?>" class="link_more" href="javascript:void(0);" onclick="carregaDinamico(this, '<?=$r;?>', 'artists', '<?=$url[2];?>', '<?= $total_por_pagina; ?>', '<?= $total_por_pagina; ?>', '<?= $url[3]; ?>', '<?= $excluir; ?>', '', '<?= $ultimo_id; ?>');"><?= $_MORE[$l]; ?></a>
    </div>
</div>
<? } ?>