<?
require_once("includes/conexao.php");
if (pode_algum("r", $_SESSION["permissao"])) {
	if ($acao=="") $acao= $_GET["acao"];
	
	if ($_GET["tipo_pessoa"]!="") $tipo_pessoa= $_GET["tipo_pessoa"];
	if ($_POST["tipo_pessoa"]!="") $tipo_pessoa= $_POST["tipo_pessoa"];
	
	if ( ($tipo_pessoa=='u') && (!pode("a", $_SESSION["permissao"])) ) die("Sem acesso à esta área.");
	
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
		$result= mysql_query("select *, DATE_FORMAT(data, '%d/%m/%Y') as data2
								from  pessoas, pessoas_tipos, enderecos
								where pessoas.id_pessoa = enderecos.id_pessoa
								and   pessoas.id_pessoa = pessoas_tipos.id_pessoa
								and   pessoas.id_pessoa = '". $id_pessoa ."'
								and   pessoas_tipos.tipo_pessoa = '". $tipo_pessoa ."'
								and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								") or die(mysql_error());
		
		$linhas= mysql_num_rows($result);
		
		if ($linhas==0) die("Registro não encontrado.");
		
		$rs= mysql_fetch_object($result);
		
		$id_pessoa= $rs->id_pessoa;
		$tipo_pessoa= $rs->tipo_pessoa;
		$status_pessoa= $rs->status_pessoa;
		$pessoa_id_pessoa= $rs->pessoa_id_pessoa;
		
		$tit= "";
	} else $tit= "Cadastro de ";
	
	$email_secundario="secundário";
	
	if ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="c") ) {
		$tit.= "Cliente";
		$tit_classe="tit_papeis";
	}
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="f") ) {
		$tit.= "Fornecedor";
		$tit_classe="tit_agenda";
	}
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="r") ) {
		$tit.= "Artista";
		$tit_classe="tit_jarro";
		
		$email_secundario="Möve";
	}
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="t") ) {
		$tit.= "Empresa de artista";
		$tit_classe="tit_jarro";
	}
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="g") ) {
		$tit.= "Agência";
		$tit_classe="tit_agenda";
	}
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="u") ) {
		$tit.= "Usuário";
		$tit_classe="tit_chaves";
	}
	elseif (pode("a", $_SESSION["permissao"])) {
		$tipo_pessoa= "a";
		$tit.= "Empresa admin";
		
		$tit_classe="tit_papeis";
	}
	
	if ($status_pessoa==3) $tit .= " (em vista)";
	
	if (($pessoa_id_pessoa!="") && ($pessoa_id_pessoa!="0")) $tit .= " (". pega_pessoa($pessoa_id_pessoa) .")";
	
	if ($acao=='e') $tit.= ": ". $rs->apelido_fantasia;
?>

<script type="text/javascript">
	$().ready(function() {
		$("#form").validate(<? if (($tipo_pessoa=='u') && ($acao=='i')) { ?>{
			rules: {
				senha: {
					required: true,
					minlength: 4
				},
				senha2: {
					required: true,
					minlength: 4,
					equalTo: "#senha"
				}
			},
			messages: {
				senha: {
					required: "Informe uma senha",
					minlength: "No mínimo, 4 caracteres"
				},
				senha2: {
					required: "Informe uma senha",
					minlength: "No mínimo, 4 caracteres",
					equalTo: "Confirme a senha corretamente"
				}
			}
		} <? } ?>);
	});
</script>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="tit <?=$tit_classe;?>"><?= $tit; ?></h2>

<?
//não mostra as abas quando estiver cadastando dados PJ de PF
if ( ($acao=='e') && ($_GET[pessoa_id_pessoa]=="") && ($tipo_pessoa!='u') ) {
	if (($acao=='e') && ($tipo_pessoa=='r')) include("_financeiro/__pessoa_artista_abas.php");
	else include("_financeiro/__pessoa_outro_abas.php");
}
?>

<? if (($pessoa_id_pessoa!="") && ($pessoa_id_pessoa!="0")) { ?>
<a href="javascript:history.back(-1);">&laquo; voltar</a>
<br />
<? } ?>

<form action="<?= AJAX_FORM; ?>formPessoa&amp;acao=<?= $acao; ?>" enctype="multipart/form-data" method="post" name="form" id="form">
    
    <? if (($pessoa_id_pessoa!="") && ($pessoa_id_pessoa!="0")) { ?>
    <input name="pessoa_id_pessoa" class="escondido" type="hidden" id="pessoa_id_pessoa" value="<?= $pessoa_id_pessoa; ?>" />
    <? } ?>
    <? if ($acao=='e') { ?>
    <input name="id_pessoa" class="escondido" type="hidden" id="id_pessoa" value="<?= $rs->id_pessoa; ?>" />
    <? } ?>
    <input name="tipo_pessoa" class="escondido" type="hidden" id="tipo_pessoa" value="<?= $tipo_pessoa; ?>" />    
    
	<? if ($status_pessoa!=3) { ?>
    <input name="status_pessoa" class="escondido" type="hidden" id="status_pessoa" value="<?= $status_pessoa; ?>" />
    <? } ?>
    
    <input class="escondido" type="hidden" name="esquema" value="<?=$esquema;?>" />
    
    <? if ($tipo_pessoa=="r") { ?>
    <div class="artista_categoria">
        <select name="id_categoria" id="id_categoria">
			<? if ($acao=='i') { ?>
            <option selected="selected" value="">-</option>
            <? } ?>
            
            <?
            $result_cat= mysql_query("select * from categorias
                                        where tipo_categoria = 'r'
                                        order by categoria asc");
            $i=0;
            while ($rs_cat= mysql_fetch_object($result_cat)) {
            ?>
            <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_cat->id_categoria; ?>"<? if ($rs_cat->id_categoria==$rs->id_categoria) echo "selected=\"selected\""; ?>><?= $rs_cat->categoria; ?></option>
            <? $i++; } ?>
        </select>
 	</div>
    <? } ?>
    
    <? if (($acao=='i') && ($tipo_pessoa!='a') && ($tipo_pessoa!='r') && ($tipo_pessoa!='t') && ($tipo_pessoa!='u')) { ?>
    	
    	<div class="artista_categoria artista_categoria2">
        <? if ($acao=='i') { ?>
	        <select name="tipo" id="tipo" onchange="alteraTipoPessoa(this.value, '<?= $acao; ?>');">
	            <option selected="selected" value="j">Pessoa jurídica</option>
	            <option value="f">Pessoa física</option>
	        </select>
        </div>
        <?
        }
		else {
			echo '<div class="div_label">'. pega_tipo($rs->tipo) .'</div>';
		?>
        <input name="tipo" class="escondido" type="hidden" id="tipo" value="<?= $rs->tipo; ?>" />
		<? } ?>
        
        <? /*
        <div class="parte50">
        	<? if ($acao=='e') { ?>
            <p>
            <label for="num_pessoa">Matrícula:</label>
            <input name="num_pessoa" id="num_pessoa" class="tamanho15p" value="<?= $rs->num_pessoa; ?>" />
            </p>
            <? } ?>
            
            <? if ($status_pessoa==3) { ?>
            <p>
            <label for="status_pessoa">Situação:</label>
            <select name="status_pessoa" id="status_pessoa">
                <option value="1">Ativo</option>
                <option value="3" selected="selected">Em vista</option>
            </select>
            </p>
            <? } ?>
        </div> */ ?>
        
	<? } elseif ($tipo_pessoa=='u') { ?>
    <input name="tipo" class="escondido" type="hidden" id="tipo" value="f" />
    <? } else { ?>
    <input name="tipo" class="escondido" type="hidden" id="tipo" value="j" />
    <? } ?>
    
    <div id="tipo_pessoa_atualiza">
        <?
        if ((($acao=='i') || ($rs->tipo=='j')) && ($tipo_pessoa!='r') && ($tipo_pessoa!='u')) require_once("_financeiro/__pessoaj.php");
		else require_once("_financeiro/__pessoaf.php");
		?>
    </div>
    
    <fieldset>
        <legend><a href="javascript:void(0);" rel="ef">Endereço físico</a></legend>
        
        <div id="ef" class="fieldset_inner fechado">
	        <?
			/*
			$result_cidade_uf= mysql_query("select * from enderecos, cidades, ufs
											where cidades.id_uf = ufs.id_uf
											and   enderecos.id_cidade = cidades.id_cidade
											");
			$rs_cidade_uf= mysql_fetch_object($result_cidade_uf);
			?>
	            <p>
	                <label for="id_pais">* País:</label>
	                <select name="id_pais" id="id_pais" class="required" onchange="ajustaPais(this.value);">
	                    <?
	                    $result_pais= mysql_query("select * from paises
													order by pais ");
	                    $i=0;
	                    while ($rs_pais = mysql_fetch_object($result_pais)) {
	                    ?>
	                    <option <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_pais->id_pais; ?>"<? if ( (($acao=='i') && ($rs_pais->id_pais==32)) || (($acao=='e') && ($rs_pais->id_pais==$rs->id_pais)) ) echo "selected=\"selected\""; ?>><?= $rs_pais->pais; ?></option>
	                    <? $i++; } ?>
	                </select>
	            </p>
	            
	            <div id="internacional" <? if (($acao=='i') || (($acao=='e') && ($rs->id_pais==32)) ) { ?>class="nao_mostra" <? } ?>>
	                <p>
	                <label for="cidade_uf">Cidade/UF:</label>
	                <input name="cidade_uf" id="cidade_uf" value="<?= $rs->cidade_uf; ?>" />
	                </p>
	            </div>
	            
	            <div id="brasil" <? if ( ($acao=='e') && ($rs->id_pais!=32) ) { ?>class="nao_mostra" <? } ?>>
	                <p>
	                    <label for="id_uf">* UF:</label>
	                    <select name="id_uf" id="id_uf" onchange="alteraCidade('id_cidade_atualiza', 'id_uf', 'id_cidade');">
	                        <option selected="selected" value="">- UF -</option>
	                        <?
	                        $result_uf= mysql_query("select * from ufs order by uf ");
	                        $i=0;
	                        while ($rs_uf = mysql_fetch_object($result_uf)) {
	                        ?>
	                        <option <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_uf->id_uf; ?>"<? if ($rs_uf->id_uf==$rs->id_uf) echo "selected=\"selected\""; ?>><?= $rs_uf->uf; ?></option>
	                        <? $i++; } ?>
	                    </select>
	                </p>
	                
	                <p>
	                    <label for="id_cidade">* Cidade:</label>
	                    <div id="id_cidade_atualiza">
	                    <select id="id_cidade" name="id_cidade">
	                        <option value="" selected="selected">- CIDADE -</option>
	                        <?
	                        $result_cid= mysql_query("select * from cidades where id_uf = '". $rs->id_uf ."' order by id_cidade");
	                        $i=0;
	                        while($rs_cid= mysql_fetch_object($result_cid)) {
	                        ?>
	                        <option <? if ($i%2==0) echo "class=\"cor_sim\""; ?>  value="<?= $rs_cid->id_cidade; ?>"<? if ($rs_cid->id_cidade==$rs->id_cidade) echo "selected=\"selected\""; ?>><?= $rs_cid->cidade; ?></option>
	                        <? $i++; } ?>
	                    </select>
	                    </div>
	                </p>
	            </div>
	            */ ?>
	        
	        <div class="parte50">
	            <label for="rua">Endereço</label>
	            <input title="Endereço" name="rua" id="rua" value="<?= $rs->rua; ?>" />
	        </div>
	        <div class="parte25">
	            <label for="numero">Número</label>
	            <input name="numero" id="numero" class="tamanho15p" value="<?= $rs->numero; ?>" />
	        </div>
	        <div class="parte25">
	        	<label for="complemento">Complemento</label>
	            <input name="complemento" id="complemento" value="<?= $rs->complemento; ?>" />
	        </div>
	        
	        <div class="parte50">
	            <label for="bairro">Bairro:</label>
	            <input title="Bairro" name="bairro" id="bairro" value="<?= $rs->bairro; ?>" />
	        </div>
	        
	        <div class="parte50">
	            <label for="cep">CEP:</label>
	            <input title="CEP" name="cep" id="cep" value="<?= $rs->cep; ?>" />
	        </div>
	        
	        <div class="parte33">
	            <label for="cidade_uf">Cidade/Estado em português</label>
	            <input name="cidade_uf" id="cidade_uf" value="<?= $rs->cidade_uf; ?>" />
	            <br />
	        </div>
	        <div class="parte33">
	        	<label for="cidade_uf_en">Cidade/Estado em inglês</label>
	            <input name="cidade_uf_en" id="cidade_uf_en" value="<?= $rs->cidade_uf_en; ?>" />
	        </div>
	        <div class="parte33">
	            <label for="pais">País</label>
	            <input name="pais" id="pais" value="<?= $rs->pais; ?>" />
	            <br />
	        </div>
	        
	        <br />
        </div>
    </fieldset>
    
    <? if ($_GET["pessoa_id_pessoa"]=="") { ?>
    
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
	            
	            <label for="email_alternativo">E-mail <?=$email_secundario;?></label>
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
    <? } ?>
    
    
    <? if ( ($tipo_pessoa=="r") || ($tipo_pessoa=="g") || ($tipo_pessoa=="c") ) { ?>
    <fieldset>
        <legend><a href="javascript:void(0);" rel="db">Dados bancários</a></legend>
        
        <div id="db" class="fieldset_inner fechado">
	        <?
			$result_banco= mysql_query("select * from pessoas_dados_bancarios
											where id_pessoa = '". $id_pessoa ."'
											");
			$rs_banco= mysql_fetch_object($result_banco);
			?>
	        
	        <div class="parte33">
	   		    <label for="nome_banco">Nome do banco</label>
	            <input name="nome_banco" id="nome_banco" value="<?= $rs_banco->nome_banco; ?>" />
	        </div>
	        <div class="parte33">
	   		    <label for="agencia">Agência</label>
	            <input name="agencia" id="agencia" value="<?= $rs_banco->agencia; ?>" />
	        </div>
	        <div class="parte33">
	   		    <label for="conta">Conta</label>
	            <input name="conta" id="conta" value="<?= $rs_banco->conta; ?>" />
	        </div>
	        
	        <div class="parte33">
	   		    <label for="nome_titular">Nome do titular da conta</label>
	            <input name="nome_titular" id="nome_titular" value="<?= $rs_banco->nome_titular; ?>" />
	        </div>
	        <div class="parte33">
	   		    <label for="cpf_titular">CPF</label>
	            <input name="cpf_titular" id="cpf_titular" value="<?= $rs_banco->cpf_titular; ?>" />
	        </div>
	        <div class="parte33">
	   		    <label for="digito">Dígito</label>
	            <input name="digito" id="digito" value="<?= $rs_banco->digito; ?>" />
	        </div>
	        
	        <div class="parte50">
	            <p>
	   		    <label for="domicilio">Domicílio</label>
	            <input name="domicilio" id="domicilio" value="<?= $rs_banco->domicilio; ?>" />
	            </p>
	            
	            <p>
	   		    <label for="endereco_banco">Endereço do banco</label>
	            <input name="endereco_banco" id="endereco_banco" value="<?= $rs_banco->endereco_banco; ?>" />
	            </p>
	        </div>
	        <div class="parte50">
	            <p>
	   		    <label for="detalhes_internacionais">Detalhes internacionais</label>
	            <textarea name="detalhes_internacionais" id="detalhes_internacionais"><?= $rs_banco->detalhes_internacionais; ?></textarea>
	            </p>
	        </div>
	        <br />
        </div>
    </fieldset>
    <? } ?>
    
    <? if ($tipo_pessoa=="r") { ?>
    <fieldset>
    	<legend><a href="javascript:void(0);" rel="tgs">Tags</a></legend>
        
        <div id="tgs" class="fieldset_inner fechado">
	        
	        <div class="parte50">
	            
	            <label for="tags">Tags:</label>
	            <input name="tags" id="tags" value="<?= $rs->tags; ?>" <? /*onkeypress="return false;" onkeyup="return false;" */ ?> onfocus="meuFadeIn('lista_tags_1');" />
	            
	            <div class="lista_tags_flutuante" id="lista_tags_1">
	                <div class="lista_tags_flutuante_seta"></div>
	                <a href="javascript:void(0);" onclick="meuFadeOut('lista_tags_1');">[x]</a>
	                
	                <?= pega_tags_campo('tags', 2, 'pt', $rs->tags, '0'); ?>
	            </div>
	        </div>
	        <br /><br />
        </div>
        
    </fieldset>
    
    <? if ($acao=="e") { ?>
    <fieldset>
        <legend><a href="javascript:void(0);" rel="dpj2">Dados de pessoa jurídica</a></legend>
        
        <div id="dpj2" class="fieldset_inner fechado">
	        <br />
	        
			<?
	        if ($acao=="e") {
	        $result_empresa_teste= mysql_query("select * from pessoas
	                                            where pessoa_id_pessoa = '". $id_pessoa ."'
	                                            ");
	        $linhas_empresa_teste= mysql_num_rows($result_empresa_teste);
	        
	        if ($linhas_empresa_teste==0) {
	        ?>
	        <a class="link_mais" href="./?pagina=financeiro/pessoa&amp;acao=i&amp;tipo_pessoa=t&amp;pessoa_id_pessoa=<?= $id_pessoa; ?>">inserir informações de empresa</a>
	        <?
	        }
	        else {
	            $rs_empresa_teste= mysql_fetch_object($result_empresa_teste);
	        ?>
	        <a class="link_mais" href="./?pagina=financeiro/pessoa&amp;acao=e&amp;tipo_pessoa=t&amp;id_pessoa=<?= $rs_empresa_teste->id_pessoa; ?>">editar informações de empresa</a>
	        <? } } ?>
	        <br /><br />
        </div>
        
    </fieldset>
    <? } ?>
    
    <fieldset>
        <legend><a href="javascript:void(0);" rel="cur">Currículo</a></legend>
        
        <div id="cur" class="fieldset_inner fechado">
	        <div class="parte50">
	            <p>
	   		    <label for="release_pt">Release para curadoria (PT)</label>
	            <textarea name="release_pt" id="release_pt"><?= $rs->release_pt; ?></textarea>
	            </p>
	            
	            <p>
	   		    <label for="exposicoes_individuais_pt">Exposições individuais (PT)</label>
	            <textarea name="exposicoes_individuais_pt" id="exposicoes_individuais_pt"><?= $rs->exposicoes_individuais_pt; ?></textarea>
	            </p>
	            
	            <p>
	   		    <label for="exposicoes_coletivas_pt">Exposições coletivas (PT)</label>
	            <textarea name="exposicoes_coletivas_pt" id="exposicoes_coletivas_pt"><?= $rs->exposicoes_coletivas_pt; ?></textarea>
	            </p>
	            
	            <p>
	   		    <label for="publicacoes_pt">Publicações (PT)</label>
	            <textarea name="publicacoes_pt" id="publicacoes_pt"><?= $rs->publicacoes_pt; ?></textarea>
	            </p>
	            
	            <p>
	            <label for="curriculo_pt">Currículo complementar / Prêmios (PT)</label>
	            <textarea name="curriculo_pt" id="curriculo_pt"><?= $rs->curriculo_pt; ?></textarea>
	            </p>
	        </div>
	        
	        <div class="parte50">
	            <p>
	            <label for="release_en">Release para curadoria (ING)</label>
	            <textarea name="release_en" id="release_en"><?= $rs->release_en; ?></textarea>
	            </p>
	            
	            <p>
	   		    <label for="exposicoes_individuais_en">Exposições individuais (ING)</label>
	            <textarea name="exposicoes_individuais_en" id="exposicoes_individuais_en"><?= $rs->exposicoes_individuais_en; ?></textarea>
	            </p>
	            
	            <p>
	   		    <label for="exposicoes_coletivas_en">Exposições coletivas (ING)</label>
	            <textarea name="exposicoes_coletivas_en" id="exposicoes_coletivas_en"><?= $rs->exposicoes_coletivas_en; ?></textarea>
	            </p>
	            
	            <p>
	   		    <label for="publicacoes_en">Publicações (ING)</label>
	            <textarea name="publicacoes_en" id="publicacoes_en"><?= $rs->publicacoes_en; ?></textarea>
	            </p>
	            
	            <p>
	            <label for="curriculo_en">Currículo complementar / Prêmios (ING)</label>
	            <textarea name="curriculo_en" id="curriculo_en"><?= $rs->curriculo_en; ?></textarea>
	            </p>
	            
	        </div>
	        <br />
        </div>
    </fieldset>
    <? } ?>
	
	<?
	if ($tipo_pessoa=="u") {
		$result_usuario= mysql_query("select * from  usuarios
										where id_pessoa = '". $rs->id_pessoa ."'
										and   id_empresa = '". $_SESSION["id_empresa"] ."'
										") or die(mysql_error());
		$rs_usuario= mysql_fetch_object($result_usuario);
	?>
	<fieldset>
        <legend><a href="javascript:void(0);" rel="du">Dados do usuário</a></legend>
        
        <div id="du" class="fieldset_inner fechado">
	        <? if ($acao=='e') { ?>
	        <input name="id_usuario" class="escondido" type="hidden" id="id_usuario" value="<?= $rs_usuario->id_usuario; ?>" />
	        <? } ?>
	        
	        <div class="parte33">
	            <? /*<p>
	                <label for="id_empresa">* Empresa:</label>
	                <?
	                if ($acao=='e') {
	                    echo pega_empresa($rs->id_empresa);
	                ?>
	                <input type="hidden" class="escondido required" name="id_empresa" id="id_empresa" value="<?= $rs->id_empresa; ?>">
	                <? } else { ?>
	                <select name="id_empresa" id="id_empresa" class="required">
	                    <option selected="selected" value="">- EMPRESA -</option>
	                    <?
	                    $result_emp= mysql_query("select * from pessoas, pessoas_tipos, empresas
	                                                where pessoas.id_pessoa = empresas.id_pessoa
	                                                and   pessoas.id_pessoa = pessoas_tipos.id_pessoa
	                                                and   pessoas_tipos.tipo_pessoa = 'a'
	                                                order by pessoas.nome_rz asc");
	                    $i=0;
	                    while ($rs_emp = mysql_fetch_object($result_emp)) {
	                    ?>
	                    <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_emp->id_empresa; ?>"<? if ($rs_emp->id_empresa==$rs->id_empresa) echo "selected=\"selected\""; ?>><?= $rs_emp->nome_rz; ?></option>
	                    <? $i++; } ?>
	                </select>
	                <? } ?>
	            </p>*/ ?>
	            
	            <label for="usuario">Nome de usuário</label>
	            <input class="required" name="usuario" value="<?= $rs_usuario->usuario; ?>" id="usuario" />
	            
	            <label for="senha">Senha</label>
	            <input type="password" name="senha" id="senha" />
	            
	            <label for="senha2">Confirmação</label>
	            <input type="password" name="senha2" id="senha2" />
	        </div>
	        <div class="parte33">
	        	
	        	<label for="foto">Foto:</label>
	        	<br />
				<input type="file" name="foto" id="foto" />
	            <br />
	            
	            <? if ($rs->foto!="") { ?>
	            <div id="foto_area">
	            	<img src="includes/timthumb/timthumb.php?src=<?= $rs->foto; ?>&amp;w=100&amp;h=100&amp;zc=1&amp;q=95" border="0" alt="" />
	            	
	            	<br /><br />
		            <a id="foto_usuario_excluir" class="telefone_remover link_remover" href="javascript:apagaArquivo('<?=$rs->id_pessoa;?>', '<?= $rs->foto; ?>');">Apagar foto</a>
	            </div>
	            
	            <? } ?>
	        </div>
	        <div class="parte33">
	            
	            <label>Permissões</label>
	            <br /><br />
	            
	            <? /* arvhmiutpsldcqnogfeykjw */ ?>
	            
	            <?
	            $permissao_a= "Acesso total ao sistema.";
				?>
	            <input <? if (pode_um('a', $rs_usuario->permissao)==true) echo "checked=\"checked\""; ?> class="tamanho15" type="checkbox" name="campo_permissao[]" id="campo_permissao_a" value="a" />
	            <div class="rotulo_usuario">Administrador</div>
				<br />
				
				<?
				$permissao_r= "Cadastro de clientes, Cadastro de fornecedores, Cadastro de artistas, Contatos";
				?>
	            <input <? if (pode_um('r', $rs_usuario->permissao)==true) echo "checked=\"checked\""; ?> class="tamanho15" type="checkbox" name="campo_permissao[]" id="campo_permissao_r" value="r" />
	            <div class="rotulo_usuario">Cadastros</div>
	            <br />
	            
	            <?
				$permissao_v= "Projetos, Curadoria.";
				?>
	            <input <? if (pode_um('v', $rs_usuario->permissao)==true) echo "checked=\"checked\""; ?> class="tamanho15" type="checkbox" name="campo_permissao[]" id="campo_permissao_v" value="v" />
	            <div class="rotulo_usuario">Projetos/Curadoria</div>
	            <br />
	            
	            <?
				$permissao_m= "Orçamentos, Financeiro.";
				?>
	            <input <? if (pode_um('m', $rs_usuario->permissao)==true) echo "checked=\"checked\""; ?> class="tamanho15" type="checkbox" name="campo_permissao[]" id="campo_permissao_m" value="m" />
	            <div class="rotulo_usuario">Financeiro</div>
	            <br />
	            
	        </div>
	        <br />
        </div>
    </fieldset>
	<? } ?>
	
    <? /*if ($tipo_pessoa=="a") { ?>
    <fieldset>
	    <legend>Logo</legend>
        
        <div class="parte50 screen">
            <label for="foto">Arquivo:</label>
            <input type="file" name="foto" id="foto" />
            <br />
        </div>
        <div class="parte50" id="empresa_logo">
        	<?
			$id_empresa_aqui= pega_id_empresa_da_pessoa($rs->id_pessoa);
			if (file_exists(CAMINHO . "empresa_". $id_empresa_aqui .".jpg")) {
			?>
            <center>
	            <img src="<?= CAMINHO; ?>empresa_<?= $id_empresa_aqui; ?>.jpg" alt="<?= $rs->nome;?>" />
                <br />
                <a href="javascript:ajaxLink('empresa_logo', 'arquivoExcluir&amp;arquivo=empresa_<?= $id_empresa_aqui; ?>.jpg');" onclick="return confirm('Tem certeza que deseja excluir o logo?');">excluir</a>
            </center>
            <? } ?>
            <br /><br />
        </div>
    </fieldset>
    <? } */ ?>
            
    <center>
        <button type="submit" id="enviar">Salvar &raquo;</button>
    </center>
</form>
<? } ?>