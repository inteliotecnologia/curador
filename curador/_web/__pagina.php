<?
require_once("includes/conexao.php");
if (pode("v", $_SESSION["permissao"])) {
	$acao= $_GET["acao"];
	if ($acao=='e') {
		$result= mysql_query("select * from  paginas
								where id_pagina = '". $_GET["id_pagina"] ."'
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
		elements : "destaque_pt,destaque_en,conteudo_pt,conteudo_en",
		plugins : "inlinepopups,image",
		convert_urls : false,
		theme_advanced_buttons1: "bold,italic,underline,separator,forecolor,backcolor,separator,removeformat,separator,link,unlink,separator,code,image",
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

<h2 class="tit tit_papeis">Página</h2>

<form action="<?= AJAX_FORM; ?>formPagina&amp;acao=<?= $acao; ?>" method="post" name="form" id="form">
    
    <? if ($acao=='e') { ?>
    <input name="id_pagina" class="escondido" type="hidden" id="id_pagina" value="<?= $rs->id_pagina; ?>" />
    <? } ?>
    
    <fieldset>
        <legend><a href="javascript:void(0);" rel="dp">Dados do pagina</a></legend>
        
        <div id="dp" class="fieldset_inner aberto">
	        
	        <div class="parte50">
	            <p>
	                <label for="pagina_pt">Página (PT)</label>
	                <input class="required" name="pagina_pt" value="<?= $rs->pagina_pt; ?>" id="pagina_pt" />
	            </p>
	            
	            <p>
	            	<label for="destaque_pt">Destaque (PT)</label>
	            	<textarea class="altura80" name="destaque_pt" id="destaque_pt"><?= $rs->destaque_pt; ?></textarea>
	            </p>
	            
	            <p>
	            	<label for="conteudo_pt">Conteúdo (PT)</label> <br />
	            	<textarea class="altura250" name="conteudo_pt" id="conteudo_pt"><?= $rs->conteudo_pt; ?></textarea>
	            </p>
	            
	            
	        </div>
	        <div class="parte50">
	            
	            <p>
	            	<label for="pagina_en">Página (ING)</label>
	                <input class="" name="pagina_en" value="<?= $rs->pagina_en; ?>" id="pagina_en" />
	            </p>
	            
	            <p>
	            	<label for="destaque_en">Destaque (ING)</label>
	            	<textarea class="altura80" name="destaque_en" id="destaque_en"><?= $rs->destaque_en; ?></textarea>
	            </p>
	            
	            <p>
	   		    	<label for="conteudo_en">Conteúdo (ING)</label> <br />
	            	<textarea class="altura250" name="conteudo_en" id="conteudo_en"><?= $rs->conteudo_en; ?></textarea>
	            </p>
	            <br /><br />
	            
	        </div>
	        <br />
        </div>
    </fieldset>
    
    <center>
        <button type="submit" id="enviar">Enviar &raquo;</button>
    </center>
</form>
<? } ?>