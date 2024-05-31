<?
require_once("includes/conexao.php");
if (pode("v", $_SESSION["permissao"])) {
	$acao= $_GET["acao"];
	if ($acao=='e') {
		$result= mysql_query("select * from  projetos
								where id_projeto = '". $_GET["id_projeto"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								") or die(mysql_error());
		$rs= mysql_fetch_object($result);
		
		$tit= $rs->projeto_pt;
	}
	else $tit= "Novo projeto";
?>
<script language="javascript" type="text/javascript" src="js/tinytips/jquery.tinyTips.js"></script>

<script type="text/javascript">
	$().ready(function() {
		$("#form").validate();
	});
</script>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="tit tit_maleta"><?= $tit; ?></h2>

<?
if ($acao=='e') include("_financeiro/__projeto_abas.php");
?>

<form action="<?= AJAX_FORM; ?>formProjeto&amp;acao=<?= $acao; ?>" method="post" name="form" id="form">
    
    <? if ($acao=='i') { ?>
    
    <div class="form_alternativo">
        <div class="parte50">
            
            <label for="projeto_pt">Nome do projeto</label>
            <input class="required" name="projeto_pt" value="<?= $rs->projeto_pt; ?>" id="projeto_pt" />
        
            <label for="lingua_preferencial">Língua</label>
            <select name="lingua_preferencial" id="lingua_preferencial">
            	<option value="pt" <? if ($rs->lingua_preferencial=="pt") echo "selected=\"selected\""; ?>>Português</option>
                <option value="en" <? if ($rs->lingua_preferencial=="en") echo "selected=\"selected\""; ?> class="cor_sim">Inglês</option>
                <!--<option value="3" <? if ($rs->lingua_preferencial=="es") echo "selected=\"selected\""; ?>>ano(s)</option>-->
            </select>
            <br />
        
            <label class="div_label" for="id_agencia">Agência:</label> <br />
            <div id="id_agencia_atualiza" class="div_select flutuar_esquerda">
                <select name="id_agencia" id="id_agencia" onchange="alteraContatos('div_responsavel_agencia', 'id_contato_agencia', 'Contato (agência):', this.value); alteraContatos('div_diretor_arte', 'id_contato_agencia_diretor_arte', 'Diretor de arte:', this.value);">
                    <option selected="selected" value="">-</option>
                    <?
                    $result_age= mysql_query("select * from pessoas, pessoas_tipos
                                                where pessoas.id_pessoa = pessoas_tipos.id_pessoa
                                                and   pessoas_tipos.tipo_pessoa = 'g'
												and   pessoas_tipos.status_pessoa <> '2'
                                                order by pessoas.apelido_fantasia asc");
                    $i=0;
                    while ($rs_age = mysql_fetch_object($result_age)) {
                    ?>
                    <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_age->id_pessoa; ?>"<? if ($rs_age->id_pessoa==$rs->id_agencia) echo "selected=\"selected\""; ?>><?= $rs_age->apelido_fantasia; ?></option>
                    <? $i++; } ?>
                </select>
            </div>
                
            <a class="div_label" id="link_cadastra_agencia" href="./index3.php?pagina=financeiro/pessoa_min&amp;acao=i&amp;tipo_pessoa=g&amp;status_pessoa=1" toptions="type = iframe, effect = fade, width = 640, height = 480, overlayClose = 1, shaded = 1">cadastrar nova agência</a>
            <br />
            
            <script type="text/javascript">
			  TopUp.addPresets({
				"#link_cadastra_agencia": {
				  
				  onclose: "atualizaProjetoNovaPessoa('id_agencia_atualiza', 'atualizaProjetoNovaAgencia')"
				}
			  });
			</script>
        
            <label class="div_label" for="id_cliente">Cliente:</label> <br />
            
            <div id="id_cliente_atualiza" class="div_select flutuar_esquerda">
            <select name="id_cliente" id="id_cliente">
                <option selected="selected" value="">-</option>
                <?
                $result_cli= mysql_query("select * from pessoas, pessoas_tipos
                                            where pessoas.id_pessoa = pessoas_tipos.id_pessoa
                                            and   pessoas_tipos.tipo_pessoa = 'c'
											and   pessoas_tipos.status_pessoa <> '2'
                                            order by pessoas.apelido_fantasia asc");
                $i=0;
                while ($rs_cli = mysql_fetch_object($result_cli)) {
                ?>
                <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_cli->id_pessoa; ?>"<? if ($rs_cli->id_pessoa==$rs->id_cliente) echo "selected=\"selected\""; ?>><?= $rs_cli->apelido_fantasia; ?></option>
                <? $i++; } ?>
            </select>
            </div>
            
            <a id="link_cadastra_cliente" href="./index3.php?pagina=financeiro/pessoa_min&amp;acao=i&amp;tipo_pessoa=c&amp;status_pessoa=1" toptions="type = iframe, effect = fade, width = 640, height = 480, overlayClose = 1, shaded = 1">cadastrar novo cliente</a>
            
            <script type="text/javascript">
			  TopUp.addPresets({
				"#link_cadastra_cliente": {
				  onclose: "atualizaProjetoNovaPessoa('id_cliente_atualiza', 'atualizaProjetoNovoCliente')"
				}
			  });
			</script>
            
                <? /*
                <label for="id_usuario_contato">Contato:</label>
                <select name="id_usuario_contato" id="id_usuario_contato">
                    <option selected="selected" value="">-</option>
                    <?
                    $result_usu= mysql_query("select * from usuarios
                                                where id_empresa = '". $_SESSION["id_empresa"] ."'
                                                order by nome asc");
                    $i=0;
                    while ($rs_usu= mysql_fetch_object($result_usu)) {
                    ?>
                    <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_usu->id_usuario; ?>"<? if ($rs_usu->id_usuario==$rs->id_usuario_contato) echo "selected=\"selected\""; ?>><?= $rs_usu->nome; ?></option>
                    <? $i++; } ?>
                </select>
                
                <label for="id_usuario_producao">Produção:</label>
                <select name="id_usuario_producao" id="id_usuario_producao">
                    <option selected="selected" value="">-</option>
                    <?
                    $result_usu= mysql_query("select * from usuarios
                                                where id_empresa = '". $_SESSION["id_empresa"] ."'
                                                order by nome asc");
                    $i=0;
                    while ($rs_usu = mysql_fetch_object($result_usu)) {
                    ?>
                    <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_usu->id_usuario; ?>"<? if ($rs_usu->id_usuario==$rs->id_usuario_producao) echo "selected=\"selected\""; ?>><?= $rs_usu->nome; ?></option>
                    <? $i++; } ?>
                </select>
                */ ?>
            
        </div>
        <div class="parte50">
        	<button type="submit" class="gigante" id="enviar">Adicionar projeto</button>
        </div>
        
        <br />
    </div>
    
    <? } else { ?>
    
    <div class="artista_categoria">
        <select name="status_projeto" id="status_projeto">
			<? if ($acao=='i') { ?>
            <option selected="selected" value="">-</option>
            <? } ?>
            
            <?
            $i=1;
            $status_projeto= pega_status_projeto('l');
            
            while ($status_projeto[$i]!="") {
            ?>
            <option <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $i; ?>"<? if ($i==$rs->status_projeto) echo "selected=\"selected\""; ?>><?= $status_projeto[$i]; ?></option>
            <? $i++; } ?>
        </select>
 	</div>
    
    <input name="id_projeto" class="escondido" type="hidden" id="id_projeto" value="<?= $rs->id_projeto; ?>" />
    
    <fieldset>
        <legend><a href="javascript:void(0);" rel="dp">Dados do projeto</a></legend>
        
        <div id="dp" class="fieldset_inner aberto">
	        <div class="parte50">
	        	<label for="projeto_pt">Nome do projeto</label>
	            <input class="required" name="projeto_pt" value="<?= $rs->projeto_pt; ?>" id="projeto_pt" />
	        </div>
	       	<div class="parte25">
	       		<label for="data_projeto">Data de entrada</label>
	            <input id="data_projeto" name="data_projeto" class="tamanho15p espaco_dir" value="<?= desformata_data($rs->data_projeto); ?>" title="Data" onkeyup="formataData(this);" onfocus="displayCalendar(this, 'dd/mm/yyyy', this);" maxlength="10" />
	       	</div>
	        <div class="parte25">
	       		<label for="lingua_preferencial">Língua:</label>
	            <select name="lingua_preferencial" id="lingua_preferencial">
	            	<option value="pt" <? if ($rs->lingua_preferencial=="pt") echo "selected=\"selected\""; ?>>Português</option>
	                <option value="en" <? if ($rs->lingua_preferencial=="en") echo "selected=\"selected\""; ?> class="cor_sim">Inglês</option>
	                <option value="es" <? if ($rs->lingua_preferencial=="es") echo "selected=\"selected\""; ?>>Espanhol</option>
	            </select>
	       	</div>
	       	<br class="limpa" />
	       	
	       	<div class="parte50">
	       		<label for="midias">Mídias:</label>
	            <input type="text" name="midias" id="midias" value="<?= $rs->midias; ?>" />
	       	</div>
	       	<div class="parte25">
	       		<label for="uso">Uso:</label>
	        	<input id="uso" name="uso" class="tamanho15p espaco_dir" value="<?= $rs->uso; ?>" />
	            
	       	</div>
	       	<div class="parte25">
	       		<label>&nbsp;</label>
	       		<select name="uso_periodo" id="uso_periodo" class="tamanho15p">
	            	<option value="1" <? if ($rs->uso_periodo=="1") echo "selected=\"selected\""; ?>>dia(s)</option>
	                <option value="2" <? if ($rs->uso_periodo=="2") echo "selected=\"selected\""; ?> class="cor_sim">mes(es)</option>
	                <option value="3" <? if ($rs->uso_periodo=="3") echo "selected=\"selected\""; ?>>ano(s)</option>
	            </select>
	       	</div>
	       	<br />
	       	
	       	<div class="parte33">
	       		<label for="pracas_veiculacao">Praças/veiculação:</label>
	            <input type="text" name="pracas_veiculacao" value="<?= $rs->pracas_veiculacao; ?>" id="pracas_veiculacao" />
	       	</div>
	        <div class="parte33">
	       		<label for="formato_entrega">Formato de entrega:</label>
	            <input type="text" name="formato_entrega" id="formato_entrega" value="<?= $rs->formato_entrega; ?>" />
	       	</div>
	       	<div class="parte33">
	       		<label for="prazo_pagamento">Prazo de pagamento:</label>
	            <input name="prazo_pagamento" value="<?= $rs->prazo_pagamento; ?>" id="prazo_pagamento" />
	       	</div>
	       	<br />
	       	
	       	<div class="parte100">
	       		<label for="descricao">Descrição:</label>
	            <textarea name="descricao" id="descricao"><?= $rs->descricao; ?></textarea>
	       	</div>
	       	<br />
        </div>
    </fieldset>
    
    <fieldset>
    	<legend><a href="javascript:void(0);" rel="ds">Dados do solicitante</a></legend>
        
        <div id="ds" class="fieldset_inner fechado">
		    <div class="parte50">
	            <label for="id_agencia">Agência:</label> <br />
	            
	            <div id="id_agencia_atualiza" class="div_select flutuar_esquerda">
	                <select name="id_agencia" id="id_agencia" onchange="alteraContatos('div_responsavel_agencia', 'id_contato_agencia', 'Contato (agência):', this.value); alteraContatos('div_diretor_arte', 'id_contato_agencia_diretor_arte', 'Diretor de arte:', this.value);">
	                    <option selected="selected" value="">-</option>
	                    <?
	                    $result_age= mysql_query("select * from pessoas, pessoas_tipos
	                                                where pessoas.id_pessoa = pessoas_tipos.id_pessoa
	                                                and   pessoas_tipos.tipo_pessoa = 'g'
													and   pessoas_tipos.status_pessoa <> '2'
	                                                order by pessoas.apelido_fantasia asc");
	                    $i=0;
	                    while ($rs_age = mysql_fetch_object($result_age)) {
	                    ?>
	                    <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_age->id_pessoa; ?>"<? if ($rs_age->id_pessoa==$rs->id_agencia) echo "selected=\"selected\""; ?>><?= $rs_age->apelido_fantasia; ?></option>
	                    <? $i++; } ?>
	                </select>
	            </div>
	            
	            <a id="link_cadastra_agencia" href="./index3.php?pagina=financeiro/pessoa_min&amp;acao=i&amp;tipo_pessoa=g&amp;status_pessoa=1" toptions="type = iframe, effect = fade, width = 640, height = 480, overlayClose = 1, shaded = 1">cadastrar</a>
	            <br /><br />
	            
	            <script type="text/javascript">
				  TopUp.addPresets({
					"#link_cadastra_agencia": {
					  
					  onclose: "atualizaProjetoNovaPessoa('id_agencia_atualiza', 'atualizaProjetoNovaAgencia')"
					}
				  });
				</script>
	            
	            <? /*<div id="projeto_contatos">
					<?
	                if ($acao=='e') {
	                    $result_cont= mysql_query("select * from projetos_contatos
	                                                where id_empresa = '". $_SESSION["id_empresa"] ."'
	                                                and   id_projeto = '". $rs->id_projeto ."'
	                                                order by id_projeto_contato asc
	                                                ");
	                    $k=1;
	                    while ($rs_cont= mysql_fetch_object($result_cont)) {
	                    ?>
	                    <div id="div_projeto_contato_<?=$k;?>">
	                        <code class="escondido"></code>
	                        <label for="telefone_<?=$k;?>">Contato/função <?=$k;?>:</label>
	                        <input class="tamanho25p" title="Telefone" name="contato[]" id="contato_<?=$k;?>" value="<?=$rs_cont->contato;?>" />
	                        <input class="tamanho25p" title="Função" name="funcao[]" id="funcao_<?=$k;?>" value="<?=$rs_cont->funcao;?>" />
	                        
	                        <label>&nbsp;</label>
	                        <a href="javascript:void(0);" onclick="removeDiv('projeto_contatos', 'div_projeto_contato_<?=$k;?>');">remover</a><br />
	                    </div>
	                    <?
	                    $k++;
	                    }
	                }
	                ?>
	            </div>
	            
	            <br />
	            <label>&nbsp;</label>
	            <a href="javascript:void(0);" onclick="criaEspacoProjetoContato();">novo contato &raquo;</a>
	            <br /><br />
	            */ ?>
	        
	            <label for="id_cliente">Cliente:</label> <br />
	            
	            <div id="id_cliente_atualiza" class="div_select flutuar_esquerda">
	            <select name="id_cliente" id="id_cliente">
	                <option selected="selected" value="">-</option>
	                <?
	                $result_cli= mysql_query("select * from pessoas, pessoas_tipos
	                                            where pessoas.id_pessoa = pessoas_tipos.id_pessoa
	                                            and   pessoas_tipos.tipo_pessoa = 'c'
												and   pessoas_tipos.status_pessoa <> '2'
	                                            order by pessoas.apelido_fantasia asc");
	                $i=0;
	                while ($rs_cli = mysql_fetch_object($result_cli)) {
	                ?>
	                <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_cli->id_pessoa; ?>"<? if ($rs_cli->id_pessoa==$rs->id_cliente) echo "selected=\"selected\""; ?>><?= $rs_cli->apelido_fantasia; ?></option>
	                <? $i++; } ?>
	            </select>
	            </div>
	            
	            <a id="link_cadastra_cliente" href="./index3.php?pagina=financeiro/pessoa_min&amp;acao=i&amp;tipo_pessoa=c&amp;status_pessoa=1" toptions="type = iframe, effect = fade, width = 640, height = 480, overlayClose = 1, shaded = 1">cadastrar</a>
	            <br /><br />
	            
	            <script type="text/javascript">
				  TopUp.addPresets({
					"#link_cadastra_cliente": {
					  onclose: "atualizaProjetoNovaPessoa('id_cliente_atualiza', 'atualizaProjetoNovoCliente')"
					}
				  });
				</script>
	    
	        </div> 
	        <div class="parte50">
	            
	        	<? /*
	            <p>
	            	<label for="tecnicas_empregadas">Técnicas empregadas:</label>
	            	<textarea class="altura80" name="tecnicas_empregadas" id="tecnicas_empregadas"><?= $rs->tecnicas_empregadas; ?></textarea>
	            </p>
	            
	            
	            <p>
	            	<label for="artistas_indicados">Artistas indicados:</label>
	            	<textarea class="altura80" name="artistas_indicados" id="artistas_indicados"><?= $rs->artistas_indicados; ?></textarea>
	            </p>
	            
	            <p>
	   		    	<label for="tags">Tags:</label>
	            	<input name="tags" id="tags" value="<?= $rs->tags; ?>" />
	            </p>
	            <br /><br />
	            
	            <p>
	                
	            </p>
	            
	            
	            <fieldset>
	            	<legend>Responsáveis</legend>
	                
	                <p>
	                    <label for="id_usuario_contato">Contato:</label>
	                    <select name="id_usuario_contato" id="id_usuario_contato">
	                        <option selected="selected" value="">-</option>
	                        <?
	                        $result_usu= mysql_query("select * from usuarios
	                                                    where id_empresa = '". $_SESSION["id_empresa"] ."'
	                                                    order by nome asc");
	                        $i=0;
	                        while ($rs_usu = mysql_fetch_object($result_usu)) {
	                        ?>
	                        <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_usu->id_usuario; ?>"<? if ($rs_usu->id_usuario==$rs->id_usuario_contato) echo "selected=\"selected\""; ?>><?= $rs_usu->nome; ?></option>
	                        <? $i++; } ?>
	                    </select>
	                </p>
	                
	                <p>
	                    <label for="id_usuario_producao">Produção:</label>
	                    <select name="id_usuario_producao" id="id_usuario_producao">
	                        <option selected="selected" value="">-</option>
	                        <?
	                        $result_usu= mysql_query("select * from usuarios
	                                                    where id_empresa = '". $_SESSION["id_empresa"] ."'
	                                                    order by nome asc");
	                        $i=0;
	                        while ($rs_usu = mysql_fetch_object($result_usu)) {
	                        ?>
	                        <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_usu->id_usuario; ?>"<? if ($rs_usu->id_usuario==$rs->id_usuario_producao) echo "selected=\"selected\""; ?>><?= $rs_usu->nome; ?></option>
	                        <? $i++; } ?>
	                    </select>
	                </p>
	            </fieldset>
	               */ ?> 
	        </div>
	        <br />
        </div>
    </fieldset>
    
    <center>
        <button type="submit" id="enviar">Enviar &raquo;</button>
    </center>
    
    <? } ?>
    
    
</form>
<? } ?>