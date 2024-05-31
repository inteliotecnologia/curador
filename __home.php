<?

$result_destaque_projetos= mysql_query("select *, projeto_". $l ." as projeto from projetos
										where status_projeto <> '0'
										and   site = '1'
										and   destaque = '1'
										");
$linhas_destaque_projetos= mysql_num_rows($result_destaque_projetos);

$result_destaque_artistas= mysql_query("select *, texto_site_". $l ." as texto_site from pessoas, pessoas_tipos
										where pessoas.id_pessoa = pessoas_tipos.id_pessoa
										and   pessoas_tipos.tipo_pessoa = 'r'
										and   pessoas_tipos.status_pessoa <> '2'
										and   pessoas.site = '1'
										and   pessoas.destaque = '1'
										");
$linhas_destaque_artistas= mysql_num_rows($result_destaque_artistas);

$total_destaques= $linhas_destaque_projetos+$linhas_destaque_artistas;

$percent_projetos= ($linhas_destaque_projetos*100)/$total_destaques;
$percent_artistas= ($linhas_destaque_artistas*100)/$total_destaques;

//echo $percent_projetos ." | ". $percent_artistas;
$rand= rand(1, 100);

if (($linhas_destaque_projetos>0) && ($percent_projetos<=$percent_artistas)) {
	if ($rand<=$percent_projetos) $mostra= "projetos";
	else $mostra= "artistas";
}
elseif ($linhas_destaque_artistas>0) {
	if ($rand<=$percent_artistas) $mostra= "artistas";
	else $mostra= "projetos";
}
else {
	$mostra= "projetos";
}

?>
    <div class="highlight home-block">      
        <?
		if ($mostra=="projetos") {
			$result_destaque= mysql_query("select *, projeto_". $l ." as projeto from projetos
											where status_projeto <> '0'
											and   site = '1'
											and   destaque = '1'
											order by RAND() asc limit 1
											");
			$linhas_destaque= mysql_num_rows($result_destaque);
		
			$rs_destaque= mysql_fetch_object($result_destaque);
			
			$imagem_miniatura= pega_imagem_miniatura_site('p', $rs_destaque->id_projeto, 3);
			
			if ($imagem_miniatura!="") $imagem_definitiva= $imagem_miniatura;
			else {
				$imagem= pega_imagem('p', $rs_destaque->id_projeto);
				$imagem_definitiva= $imagem;
			}
			
			if ($imagem_definitiva!="") {
					
					/*<a href="<?=$r;?>work/<?=$rs_destaque->url?>/"><img width="940" height="380" src="<?= $r; ?>includes/phpthumb/phpThumb.php?src=/p_<?= $rs_destaque->id_projeto; ?>/<?= $imagem_definitiva; ?>&amp;w=940&amp;h=380&amp;zc=1" alt="" /></a>*/
			?> 
			<a href="<?=$r;?>work/<?=$rs_destaque->url?>/"><img width="940" height="380" src="<?= BUCKET . BUCKET_SITE; ?>projeto_<?= $rs_destaque->id_projeto; ?>/<?= $imagem_definitiva; ?>" alt="" /></a>
			
			<div class="highlight-text" style="display:none;">
				<a href="<?=$r;?>work/<?=$rs_destaque->url?>/">
					
					<span class="highlight-text-linha">
						<span class="h4_tit"><?= pega_pessoa($rs_destaque->id_agencia); ?></span>
						<span class="h3_tit"> | <?= $rs_destaque->projeto; ?></span>
					</span>
					
					<span class="highlight-text-arrow">
						&gt;
					</span>
				</a>
				
			</div>    
		<? } } ?>
        
        <?
		if ($mostra=="artistas") {
			if ($l=="pt") $artista_texto= "Artista";
			else $artista_texto= "Artist";
			
			$result_destaque= mysql_query("select *, texto_site_". $l ." as texto_site from pessoas, pessoas_tipos
											where pessoas.id_pessoa = pessoas_tipos.id_pessoa
											and   pessoas_tipos.tipo_pessoa = 'r'
											and   pessoas_tipos.status_pessoa <> '2'
											and   pessoas.site = '1'
											and   pessoas.destaque = '1'
											order by RAND() asc limit 1
											");
			$linhas_destaque= mysql_num_rows($result_destaque);
			
			$rs_destaque= mysql_fetch_object($result_destaque);
			
			$imagem_miniatura= pega_imagem_miniatura_site('a', $rs_destaque->id_pessoa, 3);
			
			if ($imagem_miniatura!="") $imagem_definitiva= $imagem_miniatura;
			else {
				$imagem= pega_imagem('a', $rs_destaque->id_pessoa);
				$imagem_definitiva= $imagem;
			}
			
			if ($imagem_definitiva!="") {
				/*<a href="<?=$r;?>work/<?=$rs_destaque->url?>/"><img width="940" height="380" src="<?= $r; ?>includes/phpthumb/phpThumb.php?src=/p_<?= $rs_destaque->id_projeto; ?>/<?= $imagem_definitiva; ?>&amp;w=940&amp;h=380&amp;zc=1" alt="" /></a>*/
		?> 
        <a href="<?=$r;?>artist/<?=$rs_destaque->url?>/"><img width="940" height="380" src="<?= BUCKET . BUCKET_SITE; ?>artista_<?= $rs_destaque->id_pessoa; ?>/<?= $imagem_definitiva; ?>" alt="" /></a>
        
        <div class="highlight-text" style="display:none;">
            <a href="<?=$r;?>artist/<?=$rs_destaque->url?>/">
                
                <span class="highlight-text-linha">
                    <span class="h4_tit"><?= $artista_texto; ?></span>
                    <span class="h3_tit">| <?= $rs_destaque->apelido_fantasia; ?></span>
    			</span>
                
                <span class="highlight-text-arrow">
                    &gt;
                </span>
            </a>
            
        </div>
        <? } } ?>
    </div>
    
    <div class="list-thumb list-thumb-home home-block home-block-hide">
        
        
        <?
		$result= mysql_query("select *, projeto_". $l ." as projeto from projetos
								where site = '1'
								and   selecionado = '1'
								and   status_projeto <> '0'
								and   id_projeto <> '". $rs_destaque->id_projeto ."'
								order by RAND() asc limit 2 ") or die(mysql_error());
		$i=1;
		while ($rs= mysql_fetch_object($result)) {
			
			$imagem_miniatura= pega_imagem_miniatura_site('p', $rs->id_projeto, 2);
			
			if ($imagem_miniatura!="") $imagem_definitiva= $imagem_miniatura;
			else {
				$imagem= pega_imagem('p', $rs->id_projeto);
				$imagem_definitiva= $imagem;
			}
			
			if ($rs->cidade_uf!="") $cidade_uf= $rs->cidade_uf;
			else $cidade_uf= pega_cidade($rs->id_cidade);
			
			if (($i%3)==0) $classe= "nope";
		?>            
		<div class="list-thumb">
			<div class="post <?=$classe;?>">
				<a href="<?=$r;?>work/<?= $rs->url; ?>/" title="<?= $rs->apelido_fantasia; ?>">
					<? if ($imagem_definitiva!="") { ?>
                    <img width="300" height="160" src="<?= BUCKET . BUCKET_SITE; ?>projeto_<?= $rs->id_projeto; ?>/<?= $imagem_definitiva; ?>" alt=""/>
                    <? } ?>
					<span class="h3"><?= $rs->projeto; ?></span>
		
					<span class="h4"><?= pega_pessoa($rs->id_agencia); ?></span>
				</a>
			</div>
						
		</div>
		<? $i++; } ?>
                    
        <div class="nope">
        	<?
			$result= mysql_query("select pagina_". $l ." as pagina,
									destaque_". $l ." as destaque,
									conteudo_". $l ." as conteudo
									from paginas
									where id_pagina = '4'
									");
			$rs= mysql_fetch_object($result);
			?>
            
            <h2 class="sectiontitle"><a class="link-tit" href="<?=$r;?>works/selected/"><?= $rs->pagina; ?></a></h2>
            
			<?= $rs->conteudo; ?><br /><br />
            
            <a class="mais" href="<?=$r;?>works/selected/"><?= $_MORE[$l]; ?></a>
        </div>
        
        <br class="clear" />
    </div>
    
    <div class="list-thumb list-thumb-home home-block home-block-hide home-block-noline">
        
        <?
		$result= mysql_query("select * from pessoas, pessoas_tipos, enderecos
									where pessoas.id_pessoa = pessoas_tipos.id_pessoa
									and   pessoas_tipos.tipo_pessoa = 'r'
									and   pessoas.id_pessoa = enderecos.id_pessoa
									and   pessoas_tipos.status_pessoa <> '2'
									and   pessoas.site = '1'
									order by RAND() asc limit 2") or die(mysql_error());
		$i=1;
		while ($rs= mysql_fetch_object($result)) {
			
			$imagem_miniatura= pega_imagem_miniatura_site('a', $rs->id_pessoa, 2);
			
			if ($imagem_miniatura!="") $imagem_definitiva= $imagem_miniatura;
			else {
				$imagem= pega_imagem('a', $rs->id_pessoa);
				$imagem_definitiva= $imagem;
			}
			
			//if ($rs->cidade_uf!="")
			$cidade_uf= $rs->cidade_uf;
			//else $cidade_uf= pega_cidade($rs->id_cidade);
			
			if (($i%3)==0) $classe= "nope";
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
		<? $i++; } ?>
                    
        <div class="nope">
            
            <?
			$result= mysql_query("select pagina_". $l ." as pagina,
									destaque_". $l ." as destaque,
									conteudo_". $l ." as conteudo
									from paginas
									where id_pagina = '5'
									");
			$rs= mysql_fetch_object($result);
			?>
            
            <h2 class="sectiontitle"><a class="link-tit" href="<?=$r;?>artists/"><?= $rs->pagina; ?></a></h2>

            <?= $rs->conteudo; ?><br /><br />
            
            <a class="mais" href="<?=$r;?>artists/"><?= $_MORE[$l]; ?></a>
        </div>
        
        <br class="clear" />
    </div>
    
    <script>
		mixpanel.track("Acessou Home", {
			"Língua": "<?=$_SESSION["l"];?>"
		});
	</script>
    
