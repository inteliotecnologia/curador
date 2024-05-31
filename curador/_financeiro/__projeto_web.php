<?
require_once("includes/conexao.php");
if (pode("v", $_SESSION["permissao"])) {
	$acao= 'e';//$_GET["acao"];
	if ($acao=='e') {
		$result= mysql_query("select * from  projetos
								where id_projeto = '". $_GET["id_projeto"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								") or die(mysql_error());
		$rs= mysql_fetch_object($result);
	}
?>
<script language="javascript" type="text/javascript" src="js/tinytips/jquery.tinyTips.js"></script>

<script type="text/javascript">
	$().ready(function() {
		$("#form").validate();
	});
	
	tinyMCE.init({
		theme : "advanced",
		language : "en",
		mode : "exact",
		elements : "texto_site_pt,texto_site_en",
		plugins : "inlinepopups",
		convert_urls : false,
		theme_advanced_buttons1: "bold,italic,underline,separator,forecolor,backcolor,separator,removeformat,separator,link,unlink,separator,code",
		theme_advanced_buttons2: "",
		theme_advanced_buttons3: "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left"
		//theme_advanced_path_location : "bottom",
	});
</script>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="tit tit_maleta"><?= pega_projeto($_GET["id_projeto"]); ?></h2>

<?
if ($acao=='e') include("_financeiro/__projeto_abas.php");
?>

<form action="<?= AJAX_FORM; ?>formProjetoWeb&amp;acao=<?= $acao; ?>" method="post" name="form" id="form">
    
    <? if ($acao=='e') { ?>
    <input name="id_projeto" class="escondido" type="hidden" id="id_projeto" value="<?= $rs->id_projeto; ?>" />
    <? } ?>
    
    <fieldset>
        <legend><a href="javascript:void(0);" rel="dps">Dados para o site</a></legend>
        
        <div id="dps" class="fieldset_inner aberto">
	        <div class="parte25">
	        	<label for="site">Mostrar no site:</label>
	            <input type="checkbox" class="tamanho20" <? if ($rs->site=="1") echo "checked=\"checked\""; ?> name="site" value="1" id="site" />
	        </div>
	        <div class="parte25">
	        	<label for="destaque">Destaque na home:</label>
	            <input type="checkbox" class="tamanho20" <? if ($rs->destaque=="1") echo "checked=\"checked\""; ?> name="destaque" value="1" id="destaque" />
	        </div>
	        <div class="parte25">
	        	<label for="selo_cor">Selo cor (Novo):</label>
	        	<input name="selo_cor" id="selo_cor" value="<?= $rs->selo_cor; ?>" placeholder="#000000" />
	        	
	        	<? /*<label>Compartilhar:&nbsp;&nbsp;&nbsp;&nbsp;</label>
	        	<div id="post-networks">    
	                <ul>
	                    <li><a class="ico-facebook" href="http://www.facebook.com/sharer.php?u=<?= URL_SITE ?>work/<?= $rs->url; ?>&amp;t=<?= $rs->projeto_pt; ?>" target="_blank">Facebook</a></li>
	                    <li><a class="ico-twitter" href="http://twitter.com/?status=<?= URL_SITE ?>work/<?= $rs->url; ?> @movebusca" target="_blank">Twitter</a></li>
	                </ul>
	            </div>
	            <br />*/ ?>
	        </div>
	        <div class="parte25">
	        	<label for="selecionado">Selecionado:</label>
	            <input type="checkbox" class="tamanho20" <? if ($rs->selecionado=="1") echo "checked=\"checked\""; ?> name="selecionado" value="1" id="selecionado" />
	        </div>
	        <br class="limpa" />
	        
	        <hr />
	        
	        <div class="parte50">
	        	
	            <label for="projeto_pt">Nome em portugu&ecirc;s</label>
	            <input class="required" name="projeto_pt" value="<?= $rs->projeto_pt; ?>" id="projeto" />
	        	<br />
	            
		        <label for="resumo_pt">Resumo em portugu&ecirc;s</label>
	            <textarea class="altura80" name="resumo_pt" id="resumo_pt"><?= $rs->resumo_pt; ?></textarea>
	            <br />
	            
	            <label for="tags_site_pt">Tags em portugu&ecirc;s</label>
	        	<input name="tags_site_pt" id="tags_site_pt" value="<?= $rs->tags_site_pt; ?>" />
	            <br />
	            
	            <label for="texto_site_pt">Texto site em portugu&ecirc;s</label> <br />
	            <textarea class="mceEditor" name="texto_site_pt" id="texto_site_pt"><?= $rs->texto_site_pt; ?></textarea>
	            <br />
	            
	        </div>
	        <div class="parte50">
	        	
	        	<label for="projeto_en">Nome em ingl&ecirc;s</label>
	            <input name="projeto_en" value="<?= $rs->projeto_en; ?>" id="projeto" />
	            <br />
	        	
	        	<label for="resumo_en">Resumo em ingl&ecirc;s</label>
	            <textarea class="altura80" name="resumo_en" id="resumo_en"><?= $rs->resumo_en; ?></textarea>
	            <br />
	            
	        	<label for="tags_site_en">Tags em ingl&ecirc;s</label>
	            <input name="tags_site_en" id="tags_site_en" value="<?= $rs->tags_site_en; ?>" />
	            <br />
	                    
	        	<label for="texto_site_en">Texto site em ingl&ecirc;s</label> <br />
	        	<textarea class="mceEditor" name="texto_site_en" id="texto_site_en"><?= $rs->texto_site_en; ?></textarea>
	            <br />
	            
	        </div>
	        
	        <div class="parte50">
	        	<? if ($acao=="e") { ?>
		    	
		    	<label for="url">URL:</label>
	        	<input name="url" id="url" value="<?= $rs->url; ?>" />
	        	<br />
	        	
	            <? } ?>
	        </div>
	        <div class="parte50">
	        	<label for="texto_videos">V&iacute;deos:</label>
	            <textarea name="texto_videos" id="texto_videos"><?= $rs->texto_videos; ?></textarea>
	            <br />	
	        </div>
	        <br />
        </div>
        
    </fieldset>
    
    <center>
        <button type="submit" id="enviar">Enviar &raquo;</button>
    </center>
</form>
<? } ?>