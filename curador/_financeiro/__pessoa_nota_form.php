<?
require_once("includes/conexao.php");
if (pode("r", $_SESSION["permissao"])) {
	if ($_GET["id_pessoa_nota"]!="") $id_pessoa_nota= $_GET["id_pessoa_nota"];
	if ($_POST["id_pessoa_nota"]!="") $id_pessoa_nota= $_POST["id_pessoa_nota"];
	
	if ($_GET["id_pessoa"]!="") $id_pessoa= $_GET["id_pessoa"];
	if ($_POST["id_pessoa"]!="") $id_pessoa= $_POST["id_pessoa"];
	
	if ($_GET["id_contato"]!="") $id_contato= $_GET["id_contato"];
	if ($_POST["id_contato"]!="") $id_contato= $_POST["id_contato"];
	
	if ($_GET["tipo_pessoa"]!="") $tipo_pessoa= $_GET["tipo_pessoa"];
	if ($_POST["tipo_pessoa"]!="") $tipo_pessoa= $_POST["tipo_pessoa"];
	
	if ($acao=="") $acao= "i";

	if ($acao=="e") {
		if ($id_contato!="") $str = " and   tipo_nota = 'c' ";
		
		$result= mysql_query("select * from pessoas_notas
							 	where id_pessoa_nota= '". $id_pessoa_nota ."'
							 	". $str ."
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								") or die(mysql_error());
		$rs= mysql_fetch_object($result);
		
		$id_pessoa= $rs->id_pessoa;
	}
	
?>

<script type="text/javascript">
	$().ready(function() {
		$("#form").validate();
	});
</script>

<form action="<?= AJAX_FORM; ?>formPessoaNota&amp;acao=<?= $acao; ?>" method="post" name="form" id="form">
    
    <?
    if ($id_contato!="") {
		$id_pessoa_field= $id_contato;
    ?>
    <input name="tipo_nota" class="escondido" type="hidden" id="tipo_nota" value="c" />
    <? } else $id_pessoa_field= $id_pessoa; ?>
    
    <input name="id_pessoa" class="escondido" type="hidden" id="id_pessoa" value="<?= $id_pessoa_field; ?>" />
    
    <input name="tipo_pessoa" class="escondido" type="hidden" id="tipo_pessoa" value="<?= $tipo_pessoa; ?>" />
    
    <? if ($acao=="e") { ?>
    <input name="id_pessoa_nota" class="escondido" type="hidden" id="id_pessoa_nota" value="<?= $rs->id_pessoa_nota; ?>" />
    <? } ?>
    
    <div class="parte80 parte_nota">
        <p>
        <label for="obs">Observações:</label>
        <textarea class="altura80 required" name="nota" id="nota"><?=$rs->nota;?></textarea>
        </p>
	</div>
    <div class="parte20">
        
        <?
		if ($acao=="i") {
			$data_nota= date("d/m/Y");
			$hora_nota= date("H:i:s");
		}
		else {
			$data_nota= desformata_data($rs->data_nota);
			$hora_nota= $rs->hora_nota;
		}
		?>
        
        <p>
        <label for="data_nota">Data:</label>
        <input id="data_nota" name="data_nota" class="required tamanho15p espaco_dir" value="<?= $data_nota; ?>" title="Data" onkeyup="formataData(this);" maxlength="10" />
        </p>
        <br /><br />
        
        <p>
        <input type="radio" class="tamanho20 required" name="avaliacao" id="avaliacao_1" <? if ($rs->avaliacao=="1") echo "checked=\"checked\""; ?> value="1" /> <label class="alinhar_esquerda nao" for="avaliacao_1">Positiva</label><br />
        <input type="radio" class="tamanho20 required" name="avaliacao" id="avaliacao_2" <? if ($rs->avaliacao=="2") echo "checked=\"checked\""; ?> value="2" /> <label class="alinhar_esquerda nao" for="avaliacao_2">Neutra</label><br />
        <input type="radio" class="tamanho20 required" name="avaliacao" id="avaliacao_0" <? if ($rs->avaliacao=="0") echo "checked=\"checked\""; ?> value="0" /> <label class="alinhar_esquerda nao" for="avaliacao_0">Negativa</label><br />
        </p>
        
        <br />
        <button type="submit" id="enviar">Enviar &raquo;</button>
	</div>
    <br /><br />
    
    <center>
        
    </center>
    
</form>
<? } ?>