<div class="pagetitle-top">
	<a href="<?=$r;?>artists/">&laquo; <?= $_MENU_ARTISTS[$l]; ?></a>
</div>

<?
$result= mysql_query("select *, texto_site_". $l ." as texto_site from pessoas, pessoas_tipos, enderecos
							where pessoas.id_pessoa = pessoas_tipos.id_pessoa
							and   pessoas_tipos.tipo_pessoa = 'r'
							and   pessoas_tipos.status_pessoa = '1'
							and   pessoas.id_pessoa = enderecos.id_pessoa
							and   pessoas.url = '". $url[1] ."'
							and   pessoas.site = '1'
							order by pessoas.apelido_fantasia asc");
							
$linhas= mysql_num_rows($result);
if ($linhas==0) {
?>


<h2 class="pagetitle posttitle">Ops!</h2>

<p>Conteúdo não encontrado.</p>
<br /><br /><br />
<?
}
else {
	$rs= mysql_fetch_object($result);

	if ($l=="pt") $cidade_uf= $rs->cidade_uf;
	else $cidade_uf= $rs->cidade_uf_en;
?>

<script>
	mixpanel.track("Acessou Artista", {
		"Língua": "<?=$_SESSION["l"];?>",
		"ID Artista": "<?= $rs->id_pessoa; ?>",
		"Artista": "<?= $rs->apelido_fantasia; ?>",
		<? if ($cidade_uf!="") { ?>
		"Local": "<?= $cidade_uf; ?>",
		<? } ?>
		
	});
</script>

<?
$url_site= 'http://move.art.br/';
?>

<h2 class="pagetitle posttitle"><?= $rs->apelido_fantasia; ?></h2>

<div class="list-open-thumb">
    
    <div class="share">
		<div class="share1">
			<iframe src="https://www.facebook.com/plugins/like.php?href=<?= $url_site; ?>artist/<?= $url[1]; ?>/&amp;send=false&amp;layout=button_count&amp;width=85&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font=arial&amp;height=21&amp;appId=84478289311" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:85px; height:21px;" allowTransparency="true"></iframe>
		</div>
		
		<div class="share1">
			<iframe scrolling="no" frameborder="0" allowtransparency="true" src="https://platform.twitter.com/widgets/tweet_button.html#_=1322258300865&amp;count=horizontal&amp;id=twitter_tweet_button_0&amp;lang=en&amp;original_referer=<?= $url_site; ?>artist/<?= $url[1]; ?>/&amp;text=<?= $rs->apelido_fantasia; ?>&amp;url=<?= $url_site; ?>artist/<?= $url[1]; ?>/&amp;via=movebusca" class="twitter-share-button twitter-count-horizontal" style="width: 110px; height: 20px;"></iframe>
		</div>
		
	</div>
	<br style="clear:both;" />
    
    <? if ($rs->texto_site!="") { ?>
    <?= formata_texto($rs->texto_site); ?>
    <br />
    <? } ?>
    
    <?
	$result_video= mysql_query("select * from videos
								where id_externo = '". $rs->id_pessoa ."'
								and   tipo_video = 'a'
								and   site = '1'
								order by ordem asc
								");
	$linhas_video= mysql_num_rows($result_video);
	
	if ($linhas_video>0) {
	?>
	<div class="post-images post-videos">
		<?
		$k=1;
		while ($rs_video= mysql_fetch_object($result_video)) {
		?>
		
		<?= faz_embed_video($rs_video->url, $rs_video->largura, $rs_video->altura); ?>
		
		<? $k++; } ?>
	</div>
	<? } ?>
    
    <div class="post-images">
		<?
        $result_enviados= mysql_query("select * from imagens
                                        where id_externo = '". $rs->id_pessoa ."'
                                        and   tipo_imagem = 'a'
										and   site = '1'
										and   ( miniatura_destaque is NULL or miniatura_destaque = '0')
                                        order by ordem asc
                                        ") or die(mysql_error());
        $linhas_enviados= mysql_num_rows($result_enviados);
        
		$i=1;
        while ($rs_enviados= mysql_fetch_object($result_enviados)) {
			if ($i==$linhas_enviados) $classe_imagem= " last-one ";
			else $classe_imagem= " ";
			
			if ($l=="pt") $legenda_site= $rs_enviados->legenda;
			else $legenda_site= $rs_enviados->legenda_en;
			
			if ($legenda_site=="") $classe_imagem.=" espacamento";
        ?>
            <img class="<?= $classe_imagem; ?>" src="<?= BUCKET . BUCKET_SITE; ?>artista_<?= $rs->id_pessoa; ?>/<?= $rs_enviados->nome_arquivo_site; ?>" width="<?= $rs_enviados->largura_site;?>" height="<?= $rs_enviados->altura_site;?>" border="0" alt="" />
            
            <? if ($legenda_site!="") { ?>
                <br />
                <span class="legenda"><?= $legenda_site; ?></span>
            <? } ?>
        
        <? $i++; } ?>
    </div>
    
    <div class="go-top" style="display:none;">
    	<a class="branco" href="#top">top</a>
    </div>
    
</div>

<div class="list-open-infos inside">
	
    <? if ($cidade_uf!="") { ?>
    <h3 class="lateral-box sem_margem"><?= $_WORD_LOCAL[$l]; ?></h3>
    
    <div><?= $cidade_uf; ?></div>
    <? } else $classe_tags= "sem_margem"; ?>
    
    <h3 class="lateral-box <?= $classe_tags; ?>">Tags</h3>
    
    <ul class="lista-tags-lado">
		<?
        $tags= "";
        
        $result_tags= mysql_query("select *, pessoas.tags_site_". $l ." as tags from pessoas, pessoas_tipos
                                    where pessoas.id_pessoa = pessoas_tipos.id_pessoa
                                    and   pessoas_tipos.status_pessoa <> '2'
                                    and   pessoas_tipos.tipo_pessoa = 'r'
									and   pessoas.id_pessoa = '". $rs->id_pessoa ."'
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
					$j=$i+1;
					
					if ($j==$tamanho) $ponto= ".";
					else $ponto= ",&nbsp;";
				?>
					<? /*<li><?=$valor . $ponto;?>&nbsp;</li>*/ ?>
					<li><a href="<?=$r;?>artists/all/1/<?=retira_acentos(str_replace(" ", "_", $valor));?>/"><?=$valor;?></a><?= $ponto; ?></li>
				<?
					$i++;
				}
			}
		}
        ?>
    </ul>
    
    <? /*
    <h3 class="lateral-box"><?= $_WORD_SHARE[$l]; ?></h3>
    
    <div id="post-networks">
        <ul>
            <li><a class="ico-facebook" href="http://www.facebook.com/sharer.php?u=<?= URL_SITE ?>artist/<?= $url[1]; ?>&amp;t=<?= $rs->apelido_fantasia; ?>" target="_blank">Facebook</a></li>
            <li><a class="ico-twitter" href="http://twitter.com/?status=<?= URL_SITE ?>artist/<?= $url[1]; ?> @movebusca" target="_blank">Twitter</a></li>
        </ul>
    </div>
    */ ?>
    
</div>

<?
$result_proximo= mysql_query("select * from pessoas, pessoas_tipos, enderecos
								where pessoas.id_pessoa = pessoas_tipos.id_pessoa
								and   pessoas_tipos.tipo_pessoa = 'r'
								and   pessoas.site = '1'
								and   pessoas.id_pessoa = enderecos.id_pessoa
								and   pessoas_tipos.status_pessoa <> '2'
								and   pessoas.apelido_fantasia > '". $rs->apelido_fantasia ."'
								$str
								order by pessoas.apelido_fantasia asc
								limit 1
								");
$linhas_proximo= mysql_num_rows($result_proximo);

if ($linhas_proximo==0) {
	$result_proximo= mysql_query("select * from pessoas, pessoas_tipos, enderecos
								where pessoas.id_pessoa = pessoas_tipos.id_pessoa
								and   pessoas_tipos.tipo_pessoa = 'r'
								and   pessoas.site = '1'
								and   pessoas.id_pessoa = enderecos.id_pessoa
								and   pessoas_tipos.status_pessoa <> '2'
								$str
								order by pessoas.apelido_fantasia asc
								limit 1
								");
	$linhas_proximo= mysql_num_rows($result_proximo);
}

$rs_proximo= mysql_fetch_object($result_proximo);

if ($linhas_proximo>0) {
?>
<div class="div_more div-more-next">
    <a id="link_leva_0" class="link_more" href="<?=$r;?>artist/<?= $rs_proximo->url; ?>"><?= $_WORD_NEXT_ARTIST[$l]; ?></a>
</div>
<? } } ?>
