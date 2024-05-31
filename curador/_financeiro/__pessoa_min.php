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
		$result= mysql_query("select *, DATE_FORMAT(data, '%d/%m/%Y') as data2
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
		
		$tit= "Cadastro de ";
	} else $tit= "Cadastro de ";
	
	if ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="c") )
		$tit.= "cliente";
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="f") )
		$tit.= "fornecedor";
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="r") )
		$tit.= "artista";
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="t") )
		$tit.= "empresa de artista";
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="g") )
		$tit.= "agência";
	elseif (pode("a", $_SESSION["permissao"])) {
		$tipo_pessoa= "a";
		$tit.= "empresa com acesso ao sistema";
	}
	
	if ($status_pessoa==3) $tit .= " (em vista)";
?>

<script type="text/javascript">
	$().ready(function() {
		$("#form").validate();
	});
</script>

<h2><?= $tit; ?></h2>

<?
if ($_GET["msg"]!="") {

	/*if ($_GET["tipo_pessoa"]=="g") {
		$campo_nova_pessoa= "id_agencia_atualiza";
		$comando= "atualizaProjetoNovaAgencia";
	}
	elseif ($_GET["tipo_pessoa"]=="c") {
		$campo_nova_pessoa= "id_cliente_atualiza";
		$comando= "atualizaProjetoNovoCliente";
	}
	
	echo "
	<script language=\"javascript\">
		//TopUp.close();
		
		atualizaProjetoNovaPessoa('". $campo_nova_pessoa ."', '". $comando ."');
	</script>
	";
*/
?>

<br /><br />
<p>Cadastrado com sucesso. Prossiga fechando esta janela.</p>

<? } else { ?>
<form action="<?= AJAX_FORM; ?>formPessoa&amp;acao=<?= $acao; ?>" enctype="multipart/form-data" method="post" name="form" id="form" class="min">
    
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
    
    <input class="escondido" type="hidden" name="origem" value="pessoa_min" />
    
    <div class="escondido">
		<? if (($tipo_pessoa!='a') && ($tipo_pessoa!='r') && ($tipo_pessoa!='t')) { ?>
        <fieldset>
            <legend>Seleção de tipo</legend>
            
            <div>
                <p>
                <label for="tipo">* Tipo:</label>
                <? if ($acao=='i') { ?>
                <select name="tipo" id="tipo" onchange="alteraTipoPessoa(this.value, '<?= $acao; ?>');">
                    <option selected="selected" value="j">Jurídica</option>
                    <option value="f">Física</option>
                </select>
                <?
                }
                else {
                    echo pega_tipo($rs->tipo);
                ?>
                <input name="tipo" class="escondido" type="hidden" id="tipo" value="<?= $rs->tipo; ?>" />
                <? } ?>
                </p>
            </div>
        </fieldset>
        <? } else { ?>
        <input name="tipo" class="escondido" type="hidden" id="tipo" value="j" />
        <? } ?>
    </div>
    
    <fieldset>
    	<legend><a href="javascript:void(0);" rel="dp">Dados</a></legend>
    	
    	<div id="dp" class="fieldset_inner aberto">
    	
		    <div id="tipo_pessoa_atualiza">
		        <?
		        if ((($acao=='i') || ($rs->tipo=='j')) && ($tipo_pessoa!='r')) require_once("_financeiro/__pessoaj_min.php");
				else require_once("_financeiro/__pessoaf_min.php");
				?>
		    </div>
	    
	        <div>
	            <label for="cidade_uf">Localização:</label>
	            <input name="cidade_uf" id="cidade_uf" value="<?= $rs->cidade_uf; ?>" />
	        </div>
		    
	        
	        <div>
	            <label for="email">E-mail:</label>
	            <input title="E-mail" name="email" id="email" value="<?= $rs->email; ?>" />
	        </div>
	        
	        <br />
	        
    	</div>
     </fieldset>
            
    <center>
        <button type="submit" id="enviar">Enviar &raquo;</button>
    </center>
</form>

<script language="javascript">
	daFoco("apelido_fantasia");
</script>

<? } } ?>