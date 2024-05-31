<?
require_once("includes/conexao.php");
if (pode_algum("r", $_SESSION["permissao"])) {
?>

<fieldset>
    <legend><a href="javascript:void(0);" rel="dpf">Dados de pessoa física</a></legend>
    
    <div id="dpf" class="fieldset_inner aberto">
	    <div class="parte50">    
	        <label for="nome_rz">Nome completo</label>
	        <input title="Nome" name="nome_rz" id="nome_rz" value="<?= $rs->nome_rz; ?>" />
	        <br />
	    </div>
	    <div class="parte25">
	        <label for="cpf">CPF</label>
	        <input title="CPF" name="cpf" id="cpf" value="<?= $rs->cpf_cnpj; ?>" onkeypress="return formataCampo(form, this.name, '999.999.999-99', event);" maxlength="14" />
	        <br />
	    </div>
	    <div class="parte25">
	    	<label for="rg_ie">RG</label>
	        <input title="RG" name="rg_ie" id="rg_ie" value="<?= $rs->rg_ie; ?>" />
	        <br />
	    </div>
	        
	    <div class="parte50">  
	        <label for="apelido_fantasia">Apelido</label>
	        <input title="Apelido" class="required" name="apelido_fantasia" value="<?= $rs->apelido_fantasia; ?>" id="apelido_fantasia" />
	        <br />
	    </div>
	    <div class="parte25">
	        <label for="sexo">Sexo</label> 
	        <br />
	        <select name="sexo" id="sexo" title="Sexo"> 
	            <option selected="selected" value="">- SELECIONE -</option> 
	            <option value="m" <? if ($rs->sexo=='m') echo "selected=\"selected\""; ?> class="cor_sim">Masculino</option>
	            <option value="f" <? if ($rs->sexo=='f') echo "selected=\"selected\""; ?>>Feminino</option>
	        </select>
	        <br />
	    </div>
	    <div class="parte25">
	    	<label for="data_nasc">Data de nascimento</label>
	        <input title="Data de nascimento" name="data_nasc" id="data_nasc" onkeyup="formataData(this);" value="<?= desformata_data($rs->data); ?>" maxlength="10" /> 
	        <br />
	    </div>
	        
	    <div class="parte33">
	        <label for="naturalidade">Nacionalidade</label>
	        <input name="naturalidade" id="naturalidade" value="<?= $rs->naturalidade; ?>" />
	    </div>
	    
	    <div class="parte33">    
	        <label for="cidade_natal">Cidade natal</label>
	        <input name="cidade_natal" id="cidade_natal" value="<?= $rs->cidade_natal; ?>" />
	    </div>
	    
	    <div class="parte33">    
	        <label for="estado">Estado/província</label>
	        <input name="estado" id="estado" value="<?= $rs->estado; ?>" />
	    </div>
	    <br />
    </div>
</fieldset>

<? } ?>