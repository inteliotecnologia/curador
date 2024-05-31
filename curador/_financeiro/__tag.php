<?
require_once("includes/conexao.php");
if (pode("r", $_SESSION["permissao"])) {
	
	$acao= $_GET["acao"];
	if ($acao=='e') {
		$result= mysql_query("select * from tags
								where id_empresa = '". $_SESSION["id_empresa"] ."'
								and   id_tag = '". $_GET["id_tag"] ."'
								") or die(mysql_error());
		$rs= mysql_fetch_object($result);
	}
?>

<script type="text/javascript">
	$().ready(function() {
		$("#form").validate();
	});
</script>

<h2 class="tit tit_papeis">Tags</h2>

<form action="<?= AJAX_FORM; ?>formTag&amp;acao=<?= $acao; ?>" method="post" name="form" id="form">
    
    <? if ($acao=='e') { ?>
    <input name="id_tag" class="escondido" type="hidden" id="id_tag" value="<?= $rs->id_tag; ?>" />
    <? } ?>
    
    <fieldset>
        <legend><a href="javascript:void(0);" rel="dt">Dados da tag</a></legend>
        
        <div id="dt" class="fieldset_inner aberto">
	        <?
	        if ($acao=='i') {
				for ($i=0; $i<10; $i++) {
				?>
				<div class="parte33">
					<p>
					<label class="tamanho60" for="tag_pt_<?=$i;?>">Tag (PT):</label>
					<input class="required" name="tag_pt[]" id="tag_pt_<?=$i;?>" value="" />
					</p>
				</div>
				<div class="parte33">
					<p>
					<label class="tamanho60" for="tag_en_<?=$i;?>">* Tag (EN):</label>
					<input name="tag_en[]" id="tag_en_<?=$i;?>" value="" />
					</p>
			   </div>
			   <div class="parte33">  
					<p>
					<label class="tamanho60" for="tipo_tag_<?=$i;?>">* Tipo:</label>
					<select name="tipo_tag[]" id="tipo_tag_<?=$i;?>">
						<option value="1" class="cor_sim">Site + sistema</option>
						<option value="2" selected="selected">Somente sistema</option>
					</select>
					</p>
			   </div>
			   <br />
	        <?
	        	}
				?>
	            <script language="javascript">
					daFoco("tag_pt_0");
				</script>
	           <?
			}
			else { ?>
	        <div class="parte33">
	            <p>
	            <label class="tamanho60" for="tag_pt">Tag (PT):</label>
	            <input class="required" name="tag_pt" id="tag_pt" value="<?= $rs->tag_pt; ?>" />
	            </p>
	        </div>
	        <div class="parte33">
	            <p>
	            <label class="tamanho60" for="tag_en">* Tag (EN):</label>
	            <input name="tag_en" id="tag_en" value="<?= $rs->tag_en; ?>" />
	            </p>
	       </div>
	       <div class="parte33">  
	            <p>
	            <label class="tamanho60" for="tipo_tag">* Tipo:</label>
	            <select name="tipo_tag" id="tipo_tag">
	                <option value="1" <? if ($rs->tipo_tag=="1") echo "selected=\"selected\""; ?> class="cor_sim" >Site + sistema</option>
	                <option value="2" <? if ($rs->tipo_tag=="2") echo "selected=\"selected\""; ?>>Somente sistema</option>
	            </select>
	            </p>
	        </div>
	        <br />
	        <? } ?>
	        <br />
	    </div>
    </fieldset>
                
    <center>
        <button type="submit" id="enviar">Salvar &raquo;</button>
    </center>
</form>
<? } ?>