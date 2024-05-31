<?
die();
require_once("includes/conexao.php");
if (pode("a", $_SESSION["permissao"])) {
	$acao= $_GET["acao"];
	if ($acao=='e') {
		$result= mysql_query("select * from  usuarios
								where id_usuario = '". $_GET["id_usuario"] ."'
								") or die(mysql_error());
		$rs= mysql_fetch_object($result);
	}
?>
<script language="javascript" type="text/javascript" src="js/tinytips/jquery.tinyTips.js"></script>

<script type="text/javascript">
	$().ready(function() {
		$("a.tip").tinyTips('light', 'title');

		$("#form").validate({
			rules: {
				id_empresa: { required: true },
				nome: { required: true }<? if ($acao=='i') { ?>,
				usuario: { required: true },
				senha: {
					required: true,
					minlength: 4
				},
				senha2: {
					required: true,
					minlength: 4,
					equalTo: "#senha"
				}
				<? } ?>
			},
			messages: {
				id_empresa: {
					required: "Selecione a empresa"
				},
				nome: {
					required: "Informe o nome"
				}<? if ($acao=='i') { ?>,
				usuario: {
					required: "Informe nome de usuário"
				},
				senha: {
					required: "Informe uma senha",
					minlength: "No mínimo, 4 caracteres"
				},
				senha2: {
					required: "Informe uma senha",
					minlength: "No mínimo, 4 caracteres",
					equalTo: "Confirme a senha corretamente"
				}
				<? } ?>
			}
		});
	});
</script>

<h2>Usuário</h2>

<form action="<?= AJAX_FORM; ?>formUsuario&amp;acao=<?= $acao; ?>" method="post" name="form" id="form">
    
    <? if ($acao=='e') { ?>
    <input name="id_usuario" class="escondido" type="hidden" id="id_usuario" value="<?= $rs->id_usuario; ?>" />
    <? } ?>
    
    <fieldset>
        <legend>Dados do usuário</legend>
        
        <div class="parte50">
            <p>
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
            </p>
            
            <p>
                <label for="nome">* Nome:</label>
                <input class="required" name="nome" value="<?= $rs->nome; ?>" id="nome" />
            </p>
            
            <p>
                <label for="usuario">* Usuário:</label>
                <input class="required" name="usuario" value="<?= $rs->usuario; ?>" id="usuario" />
            </p>
            
            <p>
            	<label for="senha">* Senha:</label>
            	<input type="password" name="senha" id="senha" />
            </p>
            
            <p>
            	<label for="senha2">* Confirmação:</label>
            	<input type="password" name="senha2" id="senha2" />
            </p>
        </div>
        <div class="parte50">
            <fieldset>
            	<legend>Permissão</legend>
                
                <? /* arvhmiutpsldcqnogfeykjw */ ?>
                
                <?
				$permissao_a= "Acesso total ao sistema.";
				?>
                <input <? if (pode('a', $rs->permissao)) echo "checked=\"checked\""; ?> class="tamanho15" type="checkbox" name="campo_permissao[]" id="campo_permissao_a" value="a" />
                <label for="campo_permissao_a" class="alinhar_esquerda nao_negrito"><a href="javascript:void(0);" class="tip" title="<?=$permissao_a;?>">Administrador</a></label>
                <br />

				<?
				$permissao_r= "Cadastro de clientes, Cadastro de fornecedores, Cadastro de artistas, Contatos";
				?>
                <input <? if (pode('r', $rs->permissao)) echo "checked=\"checked\""; ?> class="tamanho15" type="checkbox" name="campo_permissao[]" id="campo_permissao_r" value="r" />
                <label for="campo_permissao_r" class="alinhar_esquerda nao_negrito"><a href="javascript:void(0);" class="tip" title="<?=$permissao_r;?>">Cadastros</a></label>
                
                <?
				$permissao_v= "Projetos, Curadoria.";
				?>
                <input <? if (pode('v', $rs->permissao)) echo "checked=\"checked\""; ?> class="tamanho15" type="checkbox" name="campo_permissao[]" id="campo_permissao_v" value="v" />
                <label for="campo_permissao_v" class="alinhar_esquerda nao_negrito"><a href="javascript:void(0);" class="tip" title="<?=$permissao_v;?>">Projetos/Curadoria</a></label>
                
                <?
				$permissao_m= "Orçamentos, Financeiro.";
				?>
                <input <? if (pode('m', $rs->permissao)) echo "checked=\"checked\""; ?> class="tamanho15" type="checkbox" name="campo_permissao[]" id="campo_permissao_m" value="m" />
                <label for="campo_permissao_m" class="alinhar_esquerda nao_negrito"><a href="javascript:void(0);" class="tip" title="<?=$permissao_m;?>">Orçamentos</a></label>
                
        </div>
    </fieldset>
                
    <center>
        <button type="submit" id="enviar">Enviar &raquo;</button>
    </center>
</form>
<? } ?>