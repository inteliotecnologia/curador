<?
require_once("includes/conexao.php");
if (pode("v", $_SESSION["permissao"])) {
	
	if ($_GET["id_projeto"]!="") $id_projeto= $_GET["id_projeto"];
	if ($_POST["id_projeto"]!="") $id_projeto= $_POST["id_projeto"];
	
	if ($_GET["id_curadoria"]!="") $id_curadoria= $_GET["id_curadoria"];
	if ($_POST["id_curadoria"]!="") $id_curadoria= $_POST["id_curadoria"];

	$result= mysql_query("select * from  curadorias
							where id_curadoria = '". $_GET["id_curadoria"] ."'
							and   id_empresa = '". $_SESSION["id_empresa"] ."'
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	
	$id_curadoria= $rs->id_curadoria;
?>

<h2 class="tit tit_maleta"><?= pega_projeto($id_projeto); ?></h2>

<ul class="recuo1">
	<li><a href="./?pagina=financeiro/curadoria_projeto_listar&amp;id_projeto=<?= $rs->id_projeto; ?>">&laquo; Listar curadorias</a></li>
</ul>

<? //include("_financeiro/__projeto_abas.php"); ?>

<? include("_financeiro/__curadoria_abas.php"); ?>

<div class="form_branco">
	
	<div class="parte20 pdfzao">
		
	</div>
	<div class="parte80">
		<?
		$result_artistas= mysql_query("select count(*) as total
										from curadorias_pessoas
										where curadorias_pessoas.id_projeto = '". $rs->id_projeto ."'
										and   curadorias_pessoas.id_curadoria = '". $rs->id_curadoria ."'
										") or die(mysql_error());
		$rs_artistas= mysql_fetch_object($result_artistas);
		
		$partes= ceil($rs_artistas->total/15);
		?>
        
        
        <?
		for ($i=0; $i<$partes; $i++) {
			$j=$i+1;
		?>
        <? /*<div class="parte15">
            <input type="radio" name="parte" id="parte_<?=$i;?>" value="<?=$i;?>" onclick="ajustaLinkPdf('<?=$id_curadoria;?>', '<?=$i;?>');" <? if ($i==0) echo "checked=\"checked\""; ?> />
            <label for="parte_<?=$i;?>"><?=$j;?></label>
        </div>*/ ?>
        
        <a class="botao_download_pdf" target="_blank" href="index2.php?pagina=financeiro/curadoria_pdf&amp;id_curadoria=<?= $id_curadoria; ?>&amp;parte=<?=$i;?>">Download - parte <?=$j;?></a>
        
        <? } ?>
	</div>
	<br class="limpa" />
	
	<hr />
	
	<div class="parte20 htmlzao">
		
	</div>
	<div class="parte80">
		<a class="botao_curadoria_html" target="_blank" href="../curadoria/<?=$rs->auth;?>">Visualizar em HTML</a>
	</div>
	<br class="limpa" />
	
	<hr />
	
	<a class="botao_inteiro" target="_blank" href="../c/<?= $rs->auth; ?>">Gerar link para envio</a>
	
	<!--
    <div class="parte50 alinhar_centro">
        <a id="gera_pdf" class="botao_grande_box botao_gerar" target="_blank" href="index2.php?pagina=financeiro/curadoria_pdf&amp;id_curadoria=<?= $id_curadoria; ?>&amp;parte=0">gerar pdf</a>
        
        <br />
        
    </div>
    <div class="parte50 alinhar_centro">
        <a id="gera_html" class="botao_grande_box botao_gerar" target="_blank" href="index2.php?pagina=financeiro/curadoria_html&amp;id_curadoria=<?= $id_curadoria; ?>">gerar html</a>
    </div>
	-->
	
</div>

<script>
	mixpanel.track("Finaliza Curadoria", {
		"ID Projeto": "<?php echo $_GET[id_projeto]?>",
		"ID Curadoria": "<?php echo $_GET[id_curadoria]?>"
	});
</script>

<? } ?>