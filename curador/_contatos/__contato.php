<?
require_once("includes/conexao.php");
if (pode("r", $_SESSION["permissao"])) {
	
	if ($_GET["status_funcionario"]!="") $status_funcionario= $_GET["status_funcionario"];
	if ($_POST["status_funcionario"]!="") $status_funcionario= $_POST["status_funcionario"];
	
	if ($_GET["tipo_contato"]!="") $tipo_contato= $_GET["tipo_contato"];
	if ($_POST["tipo_contato"]!="") $tipo_contato= $_POST["tipo_contato"];
	
	if ($_GET["origem"]!="") $origem= $_GET["origem"];
	if ($_POST["origem"]!="") $origem= $_POST["origem"];
	
	if ($acao=="") $acao= $_GET["acao"];
	
	if ($acao=='e') {
		$result= mysql_query("select * from contatos
								where id_empresa = '". $_SESSION["id_empresa"] ."'
								and   id_contato = '". $_GET["id_contato"] ."'
								") or die(mysql_error());
		$rs= mysql_fetch_object($result);
		
		$id_pessoa= $rs->id_pessoa;
		$tipo_contato= $rs->tipo_contato;
	}
?>

<script type="text/javascript">
	$().ready(function() {
		$("#form").validate();
	});
</script>

<? if ($origem!="2") { ?>
<h2 class="tit tit_agenda">Contatos</h2>

<?
include("_contatos/__contato_interno_abas.php");
?>

<? } ?>

<form action="<?= AJAX_FORM; ?>formContato&amp;acao=<?= $acao; ?>" method="post" name="form" id="form">
    
    <? if ($acao=='e') { ?>
    <input name="id_contato" class="escondido" type="hidden" id="id_contato" value="<?= $rs->id_contato; ?>" />
    <? } ?>
    
    <input name="origem" class="escondido" type="hidden" id="origem" value="<?= $origem; ?>" />
    
    <fieldset>
        <legend><a href="javascript:void(0);" rel="dc">Dados do contato</a></legend>
        
        <div id="dc" class="fieldset_inner aberto">
	        <div class="parte50">
	        	<label for="nome">Nome completo</label>
	        	<input class="required" name="nome" id="nome" value="<?= $rs->nome; ?>" />
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
	        
	        <div class="parte50">
	            <label for="cargo">Cargo:</label>
	            <input name="cargo" id="cargo" value="<?= $rs->cargo; ?>" />
	        </div>
	        
	        <div class="parte25">
	            <label for="tipo_contato">Tipo</label>
	            <br />
	            <select name="tipo_contato" id="tipo_contato" class="required" onchange="alteraTipoContatoPessoa(this.value);">
	                <option value="">-</option>
	                <option value="c" class="cor_sim" <? if ($tipo_contato=="c") echo "selected=\"selected\""; ?>>Clientes</option>
	                <option value="f" <? if ($tipo_contato=="f") echo "selected=\"selected\""; ?>>Fornecedores</option>
	                <option value="g" class="cor_sim" <? if ($tipo_contato=="g") echo "selected=\"selected\""; ?>>Agência</option>
	                <option value="r" <? if ($tipo_contato=="r") echo "selected=\"selected\""; ?>>Artistas</option>
	                <option value="o" class="cor_sim" <? if ($tipo_contato=="o") echo "selected=\"selected\""; ?>>Outros</option>
	            </select>
	        </div>
	        
	        <div class="parte25" id="div_id_pessoa">
	        	<? if ($id_pessoa!="") { ?>
	            <label for="id_pessoa"><?= pega_tipo_pessoa($tipo_contato); ?></label>
	            <select name="id_pessoa" id="id_pessoa">
	                <option selected="selected" value="">-</option>
	                <?
	                $result_pes= mysql_query("select * from pessoas, pessoas_tipos
	                                            where pessoas.id_pessoa = pessoas_tipos.id_pessoa
	                                            and   pessoas_tipos.tipo_pessoa = '". $tipo_contato ."'
	                                            order by pessoas.nome_rz asc");
	                $i=0;
	                while ($rs_pes= mysql_fetch_object($result_pes)) {
	                ?>
	                <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_pes->id_pessoa; ?>"<? if ($rs_pes->id_pessoa==$rs->id_pessoa) echo "selected=\"selected\""; ?>><?= $rs_pes->nome_rz; ?></option>
	                <? $i++; } ?>
	            </select>
	            <br />
	            <? } ?>
	        </div>
	        <br />
	        
	        <div class="parte50">
	            
	        </div>
	        <br />
        </div>
    </fieldset>
    
    <fieldset>
    	<legend><a href="javascript:void(0);" rel="cont">Contatos</a></legend>
        
        <div id="cont" class="fieldset_inner fechado">
	        <?
			$result_contato= mysql_query("select * from contatos
											where id_pessoa = '". $rs->id_pessoa ."'
											order by id_pessoa asc limit 1
											");
			$rs_contato= mysql_fetch_object($result_contato);
			?>
	        
	        <div class="parte33">
	                
	            <div id="telefones">
	                <?
	                if ($acao=='e') {
	                    $result_tel= mysql_query("select * from contatos_telefones
	                                                where id_empresa = '". $_SESSION["id_empresa"] ."'
	                                                and   id_contato = '". $rs_contato->id_contato ."'
	                                                order by tipo asc
	                                                ");
	                    $k=1;
	                    while ($rs_tel= mysql_fetch_object($result_tel)) {
	                    ?>
	                    <div id="div_telefone_<?=$k;?>">
	                        <code class="escondido"></code>
	                        
	                        <a class="telefone_remover" href="javascript:void(0);" onclick="removeDiv('telefones', 'div_telefone_<?=$k;?>');">remover</a>
	                        
	                        <label class="tamanho80" for="telefone_<?=$k;?>">Telefone <?=$k;?>:</label>
	                        <input class="tamanho25p" title="Telefone" name="telefone[]" id="telefone_<?=$k;?>" value="<?=$rs_tel->telefone;?>" />
	                        
	                        <select class="tamanho25p" name="tipo[]" id="tipo_<?=$k;?>">
	                            <option <? if ($rs_tel->tipo=="1") echo "selected=\"selected\""; ?> value="1">Casa</option>
	                            <option <? if ($rs_tel->tipo=="2") echo "selected=\"selected\""; ?> value="2" class="cor_sim">Trabalho</option>
	                            <option <? if ($rs_tel->tipo=="3") echo "selected=\"selected\""; ?> value="3">Celular</option>
	                            <option <? if ($rs_tel->tipo=="4") echo "selected=\"selected\""; ?> value="4" class="cor_sim">Fax</option>
	                            <option <? if ($rs_tel->tipo=="6") echo "selected=\"selected\""; ?> value="6">Estúdio</option>
	                            <option <? if ($rs_tel->tipo=="5") echo "selected=\"selected\""; ?> value="5" class="cor_sim">Outros</option>
	                        </select>
	                        
	                        <? /*
	                        <label class="tamanho80" for="obs_<?=$k;?>">OBS <?=$k;?>:</label>
	                        <input class="tamanho25p" title="Observação" name="obs[]" id="obs_<?=$k;?>" value="<?=$rs_tel->obs;?>" />
	                        */ ?>
	                        
	                    </div>
	                    <?
	                    $k++;
	                    }
	                }
	                ?>
	            </div>
	            
	            <br />
	            <a class="link_mais" href="javascript:void(0);" onclick="criaEspacoTelefone();">Novo telefone</a>
	        </div>
	        
	        <div class="parte33">
	        	
				<input class="escondido" type="hidden" name="id_contato" id="id_contato" value="<?=$rs_contato->id_contato;?>" />
	        	
	            <label for="email">E-mail</label>
	            <input title="E-mail" name="email" id="email" value="<?= $rs_contato->email; ?>" />
	            <br />
	            
	            <label for="email_alternativo">E-mail alternativo</label>
	            <input title="E-mail alternativo" name="email_alternativo" id="email_alternativo" value="<?= $rs_contato->email_alternativo; ?>" />
	            <br />
	            
	            <label for="ichat">iChat</label>
	            <input title="E-mail alternativo" name="ichat" id="ichat" value="<?= $rs_contato->ichat; ?>" />
	            <br />
	            
	        </div>
	        
	        <div class="parte33">
	        	
	            <label for="msn">MSN</label>
	            <input title="MSN" name="msn" id="msn" value="<?= $rs_contato->msn; ?>" />
	            <br />
	            
	            <label for="skype">Skype</label>
	            <input title="Skype" name="skype" id="skype" value="<?= $rs_contato->skype; ?>" />
	            <br />
	            
	            <label for="gtalk">Gtalk</label>
	            <input title="Gtalk" name="gtalk" id="gtalk" value="<?= $rs_contato->gtalk; ?>" />
	            <br />
	            
	            <? /*
	            <label for="obs_contato">Observações:</label>
	            <textarea class="altura50" name="obs_contato" id="obs_contato"><?= $rs_contato->obs; ?></textarea>
	            */ ?>
	        </div>
	        <br />
        </div>
    </fieldset>
    
    <fieldset>
    	<legend><a href="javascript:void(0);" rel="ew">Endereços na web</a></legend>
        
        <div id="ew" class="fieldset_inner fechado">
        
	        <div class="parte33">
	            <label for="site">Website</label>
	            <input name="site" id="site" value="<?= $rs_contato->site; ?>" />
	        </div>
	        <div class="parte33">
	            <label for="blog">Blog</label>
	            <input name="blog" id="blog" value="<?= $rs_contato->blog; ?>" />
	        </div>
	        <div class="parte33">
	            <label for="flickr">Flickr</label>
	            <input name="flickr" id="flickr" value="<?= $rs_contato->flickr; ?>" />
	        </div>
	        <div class="parte33">
	            <label for="twitter">Twitter</label>
	            <input name="twitter" id="twitter" value="<?= $rs_contato->twitter; ?>" />
	        </div>
	        <div class="parte33">    
	            <label for="facebook">Facebook</label>
	            <input name="facebook" id="facebook" value="<?= $rs_contato->facebook; ?>" />
	        </div>
	        <div class="parte33">
	            <label for="outro">Outro</label>
	            <input name="outro" id="outro" value="<?= $rs_contato->outro; ?>" />
	        </div>
	        
	        <br />
        </div>
        
    </fieldset>
     
    <center>
        <button type="submit" id="enviar">Salvar &raquo;</button>
    </center>
</form>
<? } ?>