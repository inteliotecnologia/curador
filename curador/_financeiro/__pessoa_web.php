<?
require_once("includes/conexao.php");
if (pode_algum("r", $_SESSION["permissao"])) {
	if ($acao=="") $acao= $_GET["acao"];
	
	if ($_GET["tipo_pessoa"]!="") $tipo_pessoa= $_GET["tipo_pessoa"];
	if ($_POST["tipo_pessoa"]!="") $tipo_pessoa= $_POST["tipo_pessoa"];
	
	if ($_GET["status_pessoa"]!="") $status_pessoa= $_GET["status_pessoa"];
	if ($_POST["status_pessoa"]!="") $status_pessoa= $_POST["status_pessoa"];
	if ($status_pessoa=="") $status_pessoa= 1;
	
	if ($_GET["pessoa_id_pessoa"]!="") $pessoa_id_pessoa= $_GET["pessoa_id_pessoa"];
	if ($_POST["pessoa_id_pessoa"]!="") $pessoa_id_pessoa= $_POST["pessoa_id_pessoa"];
	
	if ($_GET["id_pessoa"]!="") $id_pessoa= $_GET["id_pessoa"];
	if ($_POST["id_pessoa"]!="") $id_pessoa= $_POST["id_pessoa"];
	
	if ($_GET["esquema"]!="") $esquema= $_GET["esquema"];
	if ($_POST["esquema"]!="") $esquema= $_POST["esquema"];
	
	if ($_GET["id_cliente"]!="") $id_pessoa= $_GET["id_cliente"];
	
	if ($acao=='e') {
		$result= mysql_query("select *, DATE_FORMAT(data, '%d/%m/%Y') as data2, pessoas.site as site2
								from  pessoas, pessoas_tipos, enderecos
								where pessoas.id_pessoa = enderecos.id_pessoa
								and   pessoas.id_pessoa = pessoas_tipos.id_pessoa
								and   pessoas.id_pessoa = '". $id_pessoa ."'
								and   pessoas_tipos.tipo_pessoa = '". $tipo_pessoa ."'
								and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								") or die(mysql_error());
		
		$rs= mysql_fetch_object($result);
		
		$id_pessoa= $rs->id_pessoa;
		$tipo_pessoa= $rs->tipo_pessoa;
		$status_pessoa= $rs->status_pessoa;
		$pessoa_id_pessoa= $rs->pessoa_id_pessoa;
		
		$tit.= $rs->nome_rz;		
	} else $tit= "Cadastro de ";
	
	if (($pessoa_id_pessoa!="") && ($pessoa_id_pessoa!="0")) $tit .= " (". pega_pessoa($pessoa_id_pessoa) .")";
?>

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

<h2 class="tit tit_jarro">Artista: <?= $tit; ?></h2>

<?
if ($tipo_pessoa=='r')
	include("_financeiro/__pessoa_artista_abas.php");
?>

<? if (($pessoa_id_pessoa!="") && ($pessoa_id_pessoa!="0")) { ?>
<a href="javascript:history.back(-1);">&laquo; voltar</a>
<br /><br />
<? } ?>

<form action="<?= AJAX_FORM; ?>formPessoaWeb&amp;acao=<?= $acao; ?>" enctype="multipart/form-data" method="post" name="form" id="form">
    
    <? if ($acao=='e') { ?>
    <input name="id_pessoa" class="escondido" type="hidden" id="id_pessoa" value="<?= $rs->id_pessoa; ?>" />
    <? } ?>
    <input name="tipo_pessoa" class="escondido" type="hidden" id="tipo_pessoa" value="<?= $tipo_pessoa; ?>" />    
    
	<? if ($status_pessoa!=3) { ?>
    <input name="status_pessoa" class="escondido" type="hidden" id="status_pessoa" value="<?= $status_pessoa; ?>" />
    <? } ?>
    
    <input class="escondido" type="hidden" name="esquema" value="<?=$esquema;?>" />
    
    <fieldset>
        <legend><a href="javascript:void(0);" rel="dps">Dados para o site</a></legend>
        
        <div id="dps" class="fieldset_inner aberto">
	        
	        <div class="parte50">
	            <p>
	                <label for="site">Mostrar no site:&nbsp;&nbsp;</label>
	                <input type="checkbox" class="tamanho20" <? if ($rs->site2=="1") echo "checked=\"checked\""; ?> name="site" value="1" id="site" />
	            </p>
	            
	            <p>
	                <label for="destaque">Destaque na home:&nbsp;&nbsp;</label>
	                <input type="checkbox" class="tamanho20" <? if ($rs->destaque=="1") echo "checked=\"checked\""; ?> name="destaque" value="1" id="destaque" />
	            </p>
	            <br />
	            
	            <label>Compartilhar: &nbsp;&nbsp;</label>
	            
	            <div id="post-networks">    
	                <ul>
	                    <li><a class="ico-facebook" href="http://www.facebook.com/sharer.php?u=<?= URL_SITE ?>artist/<?= $rs->url; ?>&amp;t=<?= $rs->apelido_fantasia; ?>" target="_blank">Facebook</a></li>
	                    <li><a class="ico-twitter" href="http://twitter.com/?status=<?= URL_SITE ?>artist/<?= $rs->url; ?> @movebusca" target="_blank">Twitter</a></li>
	                </ul>
	            </div>
	            <br /><br /><br />
	            
	            <label for="selo_cor">Selo cor (Novo):</label>
	        	<input name="selo_cor" id="selo_cor" value="<?= $rs->selo_cor; ?>" placeholder="#000000" />
	        	
	            <p>
	   		    	<label for="tags_site_pt">Tags (PT)</label>
	            	<input name="tags_site_pt" id="tags_site_pt" value="<?= $rs->tags_site_pt; ?>" <? /*onkeypress="return false;" onkeyup="return false;" */ ?> onfocus="meuFadeIn('lista_tags_1');" />
	            </p>
	            
	            <div class="lista_tags_flutuante" id="lista_tags_1">
	            	<div class="lista_tags_flutuante_seta"></div>
	                <a href="javascript:void(0);" onclick="meuFadeOut('lista_tags_1');">[x]</a>
	                
	            	<?= pega_tags_campo('tags_site_pt', 1, 'pt', $rs->tags_site_pt, 1); ?>
	            </div>
	            
			   	<label for="tags_site_en">Tags (ING)</label>
	        	<input name="tags_site_en" id="tags_site_en" value="<?= $rs->tags_site_en; ?>" <? /*onkeypress="return false;" onkeyup="return false;"  onfocus="meuFadeIn('lista_tags_2');" */ ?> />
	            
	            <? /*
	            <div class="lista_tags_flutuante" id="lista_tags_2">
	            	<div class="lista_tags_flutuante_seta"></div>
	                <a href="javascript:void(0);" onclick="meuFadeOut('lista_tags_2');">[x]</a>
	                
	            	<?= pega_tags_campo('tags_site_en', 1, 'en', $rs->tags_site_en); ?>
	            </div>
	            */ ?>
	            
	            <? if ($acao=="e") { ?>
	            <br />
		    	<label for="url">URL:</label>
	        	<input name="url" id="url" value="<?= $rs->url; ?>" />
	            <? } ?>
	        </div>
	        <div class="parte50">
	        	<label for="texto_site_pt">Site (PT)</label> <br />
	        	<textarea class="" name="texto_site_pt" id="texto_site_pt"><?= $rs->texto_site_pt; ?></textarea>
	        
	        	<label for="texto_site_en">Site (ING)</label> <br />
	        	<textarea class="" name="texto_site_en" id="texto_site_en"><?= $rs->texto_site_en; ?></textarea>
	        
	        </div>
	        <br />
        </div>
    </fieldset>
            
    <center>
        <button type="submit" id="enviar">Enviar &raquo;</button>
    </center>
</form>
<? } ?>