<?
//echo URL_SITE;

if ($ws==1) {
	$titulo_pagina= $_MENU_SELECTED[$l];
	$link_pagina= "selected";
}
else {
	$titulo_pagina= $_MENU_ARCHIVE[$l];
	$link_pagina= "archive";
}
?>

<div class="pagetitle-top">
	<a href="<?=$r;?>works/<?= $link_pagina; ?>/">&laquo; <?= $titulo_pagina; ?></a>
</div>

<?
$result= mysql_query("select *, projeto_". $l ." as projeto,
						resumo_". $l ." as resumo,
						texto_site_". $l ." as texto_site
						from projetos
						where url = '". $url[1] ."'
						and   site = '1'
						and   status_projeto <> '0'
						order by id_projeto asc limit 1");
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
	$imagem= pega_imagem_site('p', $rs->id_projeto);
	//$legenda= pega_legenda_imagem_site('p', $rs->id_projeto);
	
	if ($l=="pt") $legenda= pega_legenda_imagem_site('p', $rs->id_projeto);
	else $legenda= pega_legenda_en_imagem_site('p', $rs->id_projeto);
?>

<script>
	mixpanel.track("Acessou Projeto", {
		"Língua": "<?=$_SESSION["l"];?>",
		"ID Projeto": "<?= $rs->id_projeto; ?>",
		"Projeto": "<?= $rs->projeto; ?>",
		<? if (($rs->id_agencia!="") && ($rs->id_agencia!="0")) { ?>
		"Comissionado por": "<?= pega_pessoa($rs->id_agencia); ?>",
		<? } ?>
		<? if (($rs->id_pessoa!="") && ($rs->id_pessoa!="0")) { ?>
		"Cliente": "<?= pega_pessoa($rs->id_pessoa); ?>",
		<? } ?>
		
	});
</script>

<?
$url_site= 'http://move.art.br/';
?>
<h2 class="pagetitle posttitle"><?= $rs->projeto; ?></h2>

<div class="work-box work-box-main">
	
	<div class="share">
		<div class="share1">
			<iframe src="https://www.facebook.com/plugins/like.php?href=<?= $url_site ?>work/<?= $url[1]; ?>/&amp;send=false&amp;layout=button_count&amp;width=85&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font=arial&amp;height=21&amp;appId=84478289311" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:85px; height:21px;" allowTransparency="true"></iframe>
		</div>
		
		<div class="share1">
			<iframe scrolling="no" frameborder="0" allowtransparency="true" src="https://platform.twitter.com/widgets/tweet_button.html#_=1322258300865&amp;count=horizontal&amp;id=twitter_tweet_button_0&amp;lang=en&amp;original_referer=<?= $url_site ?>work/<?= $url[1]; ?>/&amp;text=<?= $rs->projeto; ?>&amp;url=<?= $url_site ?>work/<?= $url[1]; ?>/&amp;via=movebusca" class="twitter-share-button twitter-count-horizontal" style="width: 110px; height: 20px;"></iframe>
		</div>
		
	</div>
	<br style="clear:both;" />
	
	<? if ($imagem!="") { ?>
        <img src="<?= BUCKET . BUCKET_SITE; ?>projeto_<?= $rs->id_projeto; ?>/<?= $imagem; ?>" width="940" border="0" alt=""/>
        <? if ($legenda!="") { ?>
            <span class="legenda legenda_sem"><?= $legenda; ?></span>
        <? } ?>
    <? } ?>
</div>

<div class="work-box">
    <div class="list-open-thumb list-open-thumb-inner">
        <? if ($rs->resumo!="") { ?>
        <p class="work-box-highlight"><?= formata_texto($rs->resumo); ?></p>
        <? } ?>
        
        <? if ($rs->texto_site!="") { ?>
        <?= formata_texto($rs->texto_site); ?>
        <? } else echo "&nbsp;"; ?>
        
    </div>
    <div class="list-open-infos inside not">
        
        <?
        $sem= "sem";
		
		if (($rs->id_agencia!="") && ($rs->id_agencia!="0")) {
		?>
        <h3 class="lateral-box <?=$sem;?>"><?= $_WORD_AGENCY[$l]; ?></h3>
        <div><?= pega_pessoa($rs->id_agencia); ?></div>
        <? $sem=""; } ?>
        
        <? if (($rs->id_pessoa!="") && ($rs->id_pessoa!="0")) { ?>
        <h3 class="lateral-box <?=$sem;?>"><?= $_WORD_CLIENT[$l]; ?></h3>
        <div><?= pega_pessoa($rs->id_cliente); ?></div>
        <? $sem=""; } ?>
        
        <? /*
        <h3 class="lateral-box <?=$sem;?>"><?= $_WORD_SHARE[$l]; ?></h3>
    
        <div id="post-networks">
            <ul>
                <li><a class="ico-facebook" href="http://www.facebook.com/sharer.php?u=<?= URL_SITE ?>work/<?= $url[1]; ?>&amp;t=<?= $rs->projeto; ?>" target="_blank">Facebook</a></li>
                <li><a class="ico-twitter" href="http://twitter.com/?status=<?= URL_SITE ?>work/<?= $url[1]; ?> @movebusca" target="_blank">Twitter</a></li>
            </ul>
        </div>
        */ ?>
        
    </div>
    <br />
</div>

<?
$result_video= mysql_query("select * from videos
							where id_externo = '". $rs->id_projeto ."'
							and   tipo_video = 'p'
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

<?

$result_enviados= mysql_query("select * from imagens
								where id_externo = '". $rs->id_projeto ."'
								and   tipo_imagem = 'p'
								and   site = '1'
								and   ( miniatura_destaque is NULL or miniatura_destaque = '0')
								order by ordem asc
								limit 1, 9999
								") or die(mysql_error());
$linhas_enviados= mysql_num_rows($result_enviados);

if ($linhas_enviados>0) {
?>

<div class="post-images">
	<?
	$i=1;
    while ($rs_enviados= mysql_fetch_object($result_enviados)) {
		if ($i==$linhas_enviados) $classe_imagem= "last-one";
		else $classe_imagem= " ";
		
		if ($l=="pt") $legenda_site= $rs_enviados->legenda;
		else $legenda_site= $rs_enviados->legenda_en;
		
		if ($legenda_site=="") $classe_imagem.=" espacamento";
    ?>
        <img class="<?= $classe_imagem; ?>" src="<?= BUCKET . BUCKET_SITE; ?>projeto_<?= $rs->id_projeto; ?>/<?= $rs_enviados->nome_arquivo_site; ?>" width="<?= $rs_enviados->largura_site;?>" height="<?= $rs_enviados->altura_site;?>" border="0" alt="" />
        
        <? if ($legenda_site!="") { ?>
            <span class="legenda"><?= $legenda_site; ?></span>
        <? } ?>
    
    <? $i++; } ?>
</div>

<div class="go-top" style="display:none;">
    <a href="#top">top</a>
</div>

<? } ?>



<?

if ($ws==1) {
	$str_proximo.= " and   selecionado = '1'
					 and   ordem > '". $rs->ordem ."'
					";
	$str_ordem= " ordem asc ";
	$str_proximo_primeiro.= " and   selecionado = '1'
								";
}
else {
	$str_proximo.= " and   selecionado <> '1'
					 and   ordem > '". $rs->ordem ."' ";
	$str_ordem= " ordem asc ";
	$str_proximo_primeiro.= " and   selecionado <> '1'
								";
}

$result_proximo= mysql_query("select *, projeto_". $l ." as projeto from projetos
								where site = '1'
								$str_proximo
								and   status_projeto <> '2'
								and   id_projeto <> '". $rs->id_projeto ."'
								order by $str_ordem
								limit 1
								");
$linhas_proximo= mysql_num_rows($result_proximo);

if ($linhas_proximo==0) {
	$result_proximo= mysql_query("select *, projeto_". $l ." as projeto from projetos
								where site = '1'
								$str_proximo_primeiro
								and   status_projeto <> '2'
								and   id_projeto <> '". $rs->id_projeto ."'
								order by $str_ordem
								limit 1
								");
	$linhas_proximo= mysql_num_rows($result_proximo);
}

$rs_proximo= mysql_fetch_object($result_proximo);

if ($linhas_proximo>0) {
?>
<div class="div_more div-more-next">
    <a id="link_leva_0" class="link_more" href="<?=$r;?>work/<?= $rs_proximo->url; ?>"><?= $_WORD_NEXT_WORK[$l]; ?></a>
</div>
<? } } ?>