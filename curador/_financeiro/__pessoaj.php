<?
require_once("includes/conexao.php");
if (pode_algum("r", $_SESSION["permissao"])) {
?>

<input class="escondido" type="hidden" id="validacoes" value="tipo@vazio|nome_rz@vazio|id_cidade@vazio" />

<fieldset>
    <legend><a href="javascript:void(0);" rel="dpj">Dados da pessoa jurídica</a></legend>
    
    <div id="dpj" class="fieldset_inner aberto">
	    <div class="parte66">
	    	<label for="nome_rz">Razão Social</label>
	        <input name="nome_rz" id="nome_rz" value="<?= $rs->nome_rz; ?>" />
	        <br />
	    </div>
	    <div class="parte33">
	        <label for="cnpj">CNPJ</label>
	        <input title="CNPJ" name="cnpj" id="cnpj" <? /*onblur="verificaCnpj('<?=$acao;?>');"*/ ?> value="<?= $rs->cpf_cnpj; ?>" onkeypress="return formataCampo(form, this.name, '99.999.999/9999-99', event);" maxlength="18" />
	    </div>
	    
	    <div class="parte66">
	    	<label for="apelido_fantasia">Nome Fantasia</label>
	        <input class="required" title="Nome Fantasia" name="apelido_fantasia" value="<?= $rs->apelido_fantasia; ?>" id="apelido_fantasia" />
	    </div>
	    <div class="parte33">
	    	<label for="im">Inscr. municipal</label>
	        <input title="Inscrição municipal" name="im" id="im" value="<?= $rs->im; ?>" />
	    </div>
	    
	    <div class="parte66">
	    	&nbsp;
	    </div>
	    <div class="parte33">
	    	<label for="rg_ie">Inscr. estadual</label>
	        <input title="Inscrição Estadual" name="rg_ie" id="rg_ie" value="<?= $rs->rg_ie; ?>" />
	    </div>
	   	
	    <? /*<label>&nbsp;</label>
	    <div id="cnpj_testa" class="lado_campo">
	        <input title="CNPJ" name="passa_cnpj" id="passa_cnpj" type="hidden" class="escondido" value="" />
	        <span id="span_cnpj_testa" class="vermelho">Não testado!</span>
	    </div>
	    <br />
	    
	    <script language="javascript">
	        verificaCnpj('<?=$acao;?>');
	    </script>*/ ?>
	    
	    <br />
    </div>
</fieldset>

<? } ?>