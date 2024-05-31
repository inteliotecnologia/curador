<?
require_once("includes/conexao.php");
if (pode("v", $_SESSION["permissao"])) {
	$acao= 'e';//$_GET["acao"];
	
	if ($_GET["miniatura"]!="") $miniatura= $_GET["miniatura"];
	else $miniatura=1;
	
	if ($_GET["id_pessoa"]!="") $id_pessoa= $_GET["id_pessoa"];
	if ($_POST["id_pessoa"]!="") $id_pessoa= $_POST["id_pessoa"];
	
	if ($_GET["id_projeto"]!="") $id_projeto= $_GET["id_projeto"];
	if ($_POST["id_projeto"]!="") $id_projeto= $_POST["id_projeto"];
	
	if ($id_pessoa!="") {
		$tipo_imagem= "a";
		$id_chamada= "id_pessoa";
		$id_externo= $id_pessoa;
		$tit_classe="tit_jarro";
	}
	elseif ($id_projeto!="") {
		$tipo_imagem= "p";
		$id_chamada= "id_projeto";
		$id_externo= $id_projeto;
		$tit_classe="tit_maleta";
	}
	
	if ($tipo_imagem=="a") {
		$result= mysql_query("select *, DATE_FORMAT(data, '%d/%m/%Y') as data2
								from  pessoas, pessoas_tipos
								where pessoas.id_pessoa = pessoas_tipos.id_pessoa
								and   pessoas.id_pessoa = '". $id_pessoa ."'
								and   pessoas_tipos.tipo_pessoa = 'r'
								and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								") or die(mysql_error());
		$rs= mysql_fetch_object($result);
		
		$tipo_pessoa= $rs->tipo_pessoa;
		$status_pessoa= $rs->status_pessoa;
		$id_externo= $rs->id_pessoa;
					
		$tit.= pega_tipo_pessoa($tipo_pessoa) .": ". $rs->nome_rz;		
	}
	else {
		$result= mysql_query("select * from  projetos
								where id_projeto = '". $_GET["id_projeto"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								") or die(mysql_error());
		$rs= mysql_fetch_object($result);
		
		$id_externo= $rs->id_projeto;
					
		$tit.= $rs->projeto_pt;
	}
	
?>
<script src="js/jcrop/js/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="js/jcrop/css/jquery.Jcrop.css" type="text/css" />
<? /*<link rel="stylesheet" href="demo_files/demos.css" type="text/css" />*/ ?>

<script type="text/javascript">
	$(document).ready(function() {
		
		$('#cropbox').Jcrop({
			aspectRatio: <?= number_format(pega_miniatura($miniatura, 'p'), 3, '.', ','); ?>,
			onSelect: updateCoords
		});
		
	});
	
	function updateCoords(c)
	{
		$('#x').val(c.x);
		$('#y').val(c.y);
		$('#w').val(c.w);
		$('#h').val(c.h);
	};

	function checkCoords()
	{
		if (parseInt($('#w').val())) return true;
		alert('Selecione uma região para fazer o corte!');
		return false;
	};
</script>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="tit <?=$tit_classe;?>"><?= $tit; ?></h2>

<?
if ($tipo_imagem=='a') include("_financeiro/__pessoa_artista_abas.php");
else include("_financeiro/__projeto_abas.php");
?>    
    
    <fieldset>
        <legend>Cortar imagem no formato:</legend>
        
        <br><br>
        
        <ul class="recuo1">
            <li><a class="<? if ($miniatura==1) echo "atual"; ?>" href="./?pagina=financeiro/imagens_miniaturas&amp;<?= $id_chamada; ?>=<?= $id_externo; ?>&amp;miniatura=1"><?= pega_miniatura(1, 'n'); ?></a></li>
            <li><a class="<? if ($miniatura==2) echo "atual"; ?>" href="./?pagina=financeiro/imagens_miniaturas&amp;<?= $id_chamada; ?>=<?= $id_externo; ?>&amp;miniatura=2"><?= pega_miniatura(2, 'n'); ?></a></li>
            
			<? //if ($tipo_imagem=="p") { ?>
            <li><a class="<? if ($miniatura==3) echo "atual"; ?>" href="./?pagina=financeiro/imagens_miniaturas&amp;<?= $id_chamada; ?>=<?= $id_externo; ?>&amp;miniatura=3"><?= pega_miniatura(3, 'n'); ?></a></li>
            <? //} ?>
            
            <li><a class="<? if ($miniatura==4) echo "atual"; ?>" href="./?pagina=financeiro/imagens_miniaturas&amp;<?= $id_chamada; ?>=<?= $id_externo; ?>&amp;miniatura=4"><?= pega_miniatura(4, 'n'); ?></a></li>
            
            <li><a class="<? if ($miniatura==5) echo "atual"; ?>" href="./?pagina=financeiro/imagens_miniaturas&amp;<?= $id_chamada; ?>=<?= $id_externo; ?>&amp;miniatura=5"><?= pega_miniatura(5, 'n'); ?></a></li>
            
            <li><a class="<? if ($miniatura==6) echo "atual"; ?>" href="./?pagina=financeiro/imagens_miniaturas&amp;<?= $id_chamada; ?>=<?= $id_externo; ?>&amp;miniatura=6"><?= pega_miniatura(6, 'n'); ?></a></li>
            
        </ul>
        <br /><br />
        
        
        	
        	
            <form action="<?= AJAX_FORM; ?>formImagemCrop" method="post" onSubmit="return checkCoords();">
                <input type="hidden" id="miniatura" name="miniatura" value="<?= $miniatura; ?>" />
                <input type="hidden" id="id_externo" name="id_externo" value="<?= $id_externo; ?>" />
                <input type="hidden" id="tipo_imagem" name="tipo_imagem" value="<?= $tipo_imagem; ?>" />
                
                <input type="hidden" id="x" name="x" />
                <input type="hidden" id="y" name="y" />
                <input type="hidden" id="w" name="w" />
                <input type="hidden" id="h" name="h" />
            	
                <? /*<div class="parte50">
                	<button type="submit">Salvar corte</button>
                </div>*/ ?>
                <div class="parte50">
                    <br />
                    <div class="parte25">
                        <input <? if ($zoom=="25") echo " checked=\"checked\""; ?>type="radio" onclick="zoomImagem('0.25');" name="zoom" id="zoom_25" value="0.25" />
                        <label for="zoom_25" class="tamanho30">25%</label>
                    </div>
                    <div class="parte25">
                        <input <? if ($zoom=="50") echo " checked=\"checked\""; ?>type="radio" onclick="zoomImagem('0.5');" name="zoom" id="zoom_50" value="0.5" />
                        <label for="zoom_50" class="tamanho30">50%</label>
                    </div>
                    <div class="parte25">
                        <input <? if ($zoom=="75") echo " checked=\"checked\""; ?> type="radio" onclick="zoomImagem('0.75');" name="zoom" id="zoom_75" value="0.75" />
                        <label for="zoom_75" class="tamanho30">75%</label>
                    </div>
                    <div class="parte25">
                        <input <? if (($zoom=="") || ($zoom=="100")) echo " checked=\"checked\""; ?> type="radio" onclick="zoomImagem('1');" name="zoom" id="zoom_100" value="1" />
                        <label for="zoom_100" class="tamanho30">100%</label>
                    </div>
                </div>
                
                <br /><br />
                
                <?
                $imagem= pega_imagem($tipo_imagem, $id_externo);
				$al= pega_largura_altura_original_imagem($tipo_imagem, $id_externo);
				
				$al= explode("|", $al);
                ?>
                
                <? if ($imagem!="") { ?>
                <img src="images/asc.gif" id="jcrop_tamanho" class="escondido" width="<?=$al[0];?>" height="<?=$al[1];?>" />
                <div id="cropbox_area">
                    <img id="cropbox" src="<?= CAMINHO_CDN . $tipo_imagem ."_". $id_externo ."/". $imagem; ?>" border="0" width="<?=$al[0];?>" height="<?=$al[1];?>" alt="" />
                </div>
                <? } else echo "Nenhuma imagem selecionada para publica&ccedil;&atilde;o.<br />"; ?>
                
                <br /><br />
				
				
				<div class="parte50">
					<label>Padrão:</label>
					<select name="padrao">
						<option value="png">PNG</option>
						<option value="jpg">JPG</option>
					</select>
				</div>
				<div class="parte50">
					<label>Qualidade (apenas para padrão JPG):</label>
					<input type="text" name="qualidade" value="100" style="width:100px;" /><br/>
				</div>
				<br/>
				
				
                <button type="submit">Salvar corte</button>
            </form>
            
			<hr/>
        
        
        	<label>Imagens cortadas:</label>
        	
            <?
            for ($i=1; $i<7; $i++) {
				//if (($i!=3) || (($i==3) && ($tipo_imagem=="p"))) {
			?>	
            <div class="imagem_cortada" id="imagem_cortada_<?=$i;?>">
                <div class="parte40">
                    <?
                    $imagem_miniatura= "";
                    
                    $imagem_miniatura= pega_imagem_miniatura($tipo_imagem, $id_externo, $i);
					//$imagem_miniatura_site= pega_imagem_miniatura_site($tipo_imagem, $id_externo, $i);
                    
                    $tamanho= pega_imagem_miniatura_tamanho($tipo_imagem, $id_externo, $i);
                    
                    if ($imagem_miniatura!="") {
						
						if ($i==4) $largura_miniatura=32;
						else $largura_miniatura=300;
						
						//echo "qwe:". filesize(CAMINHO_CDN . $tipo_imagem ."_". $id_externo ."/". $imagem_miniatura);
                    ?>
                    <img id="cropbox" src="<?= CAMINHO_CDN . $tipo_imagem ."_". $id_externo ."/". $imagem_miniatura; ?>" width="<?=$largura_miniatura;?>" border="0" alt="" />
                    <? } ?>
                </div>
                <div class="parte60">
                    <h3><?= pega_miniatura($i, 'n'); ?></h3>
                    <br />
                    
                    <?
	                if ($tamanho!="") echo format_bytes($tamanho);  
                    ?>
                    <br/><br />
                    
                    <a class="botao_recuo1" href="./?pagina=financeiro/imagens_miniaturas&amp;<?= $id_chamada; ?>=<?= $id_externo; ?>&amp;miniatura=<?=$i;?>">cortar neste formato &raquo;</a>
                </div>
            </div>
            <? } //} ?>
        
    
<? } ?>