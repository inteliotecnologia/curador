<?
require_once("includes/conexao.php");
if (pode_algum("vr", $_SESSION["permissao"])) {
	if ($acao=="") $acao= $_GET["acao"];
	
	if ($_GET["tipo_contato"]!="") $tipo_contato= $_GET["tipo_contato"];
	if ($_POST["tipo_contato"]!="") $tipo_contato= $_POST["tipo_contato"];
	
	if ($_GET["tipo_pessoa"]!="") $tipo_pessoa= $_GET["tipo_pessoa"];
	if ($_POST["tipo_pessoa"]!="") $tipo_pessoa= $_POST["tipo_pessoa"];
	
	if ($_GET["status_pessoa"]!="") $status_pessoa= $_GET["status_pessoa"];
	if ($_POST["status_pessoa"]!="") $status_pessoa= $_POST["status_pessoa"];
	if ($status_pessoa=="") $status_pessoa= 1;
	
	if ($_GET["pessoa_id_pessoa"]!="") $pessoa_id_pessoa= $_GET["pessoa_id_pessoa"];
	if ($_POST["pessoa_id_pessoa"]!="") $pessoa_id_pessoa= $_POST["pessoa_id_pessoa"];
	
	if ($_GET["id_pessoa"]!="") $id_pessoa= $_GET["id_pessoa"];
	if ($_POST["id_pessoa"]!="") $id_pessoa= $_POST["id_pessoa"];
	
	if ($_GET["origem"]!="") $origem= $_GET["origem"];
	if ($_POST["origem"]!="") $origem= $_POST["origem"];
	
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
	
	if ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="c") )
		$tit.= "Cliente";
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="f") )
		$tit.= "Fornecedor";
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="r") )
		$tit.= "Artista";
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="t") )
		$tit.= "Empresa de artista";
	elseif ( (pode("r", $_SESSION["permissao"])) && ($tipo_pessoa=="g") )
		$tit.= "Ag&ecirc;ncia";
	elseif (pode("a", $_SESSION["permissao"])) {
		$tipo_pessoa= "a";
		$tit.= "Empresa com acesso ao sistema";
	}
	
	if ($status_pessoa==3) $tit .= " (em vista)";
	
	if (($pessoa_id_pessoa!="") && ($pessoa_id_pessoa!="0")) $tit .= " (". pega_pessoa($pessoa_id_pessoa) .")";
	
	$tit.= ": ". $rs->apelido_fantasia;
?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2><?= $tit; ?></h2>

<?
include("_financeiro/__pessoa_outro_abas.php");
?>

<ul class="recuo1">
	<li><a href="./?pagina=contatos/contato&amp;acao=i&amp;tipo_contato=<?=$tipo_contato;?>&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;id_pessoa=<?=$id_pessoa;?>&origem=2">inserir</a></li>
    <li><a href="./?pagina=contatos/contato_importar&amp;acao=i&amp;tipo_contato=<?=$tipo_contato;?>&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;id_pessoa=<?=$id_pessoa;?>&origem=2">importar vCard</a></li>
    <li><a href="./?pagina=financeiro/pessoa_contatos&amp;tipo_contato=<?=$tipo_contato;?>&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;id_pessoa=<?=$id_pessoa;?>&origem=2">listar</a></li>
</ul>
<br />

<link href="js/uploadify/default.css" rel="stylesheet" type="text/css" />
<link href="js/uploadify/uploadify.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/uploadify/swfobject.js"></script>
<script type="text/javascript" src="js/uploadify/jquery.uploadify.v2.1.0.js"></script>

<script type="text/javascript" src="js/drag-n-drop/jquery-ui-1.7.1.custom.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#uploadify").uploadify({
            'uploader'       : 'js/uploadify/uploadify.swf',
            'script'         : 'upload_vcard.php',
            'cancelImg'      : 'js/uploadify/cancel.png',
            'folder'         : 'uploads',
            'buttonText'     : 'PROCURAR',
            'queueID'        : 'fileQueue',
            'scriptData'     : {id_pessoa: "<?= $id_pessoa; ?>", tipo_pessoa: "<?= $tipo_pessoa; ?>", id_empresa: "<?= $_SESSION["id_empresa"]; ?>", id_usuario: "<?= $_SESSION["id_usuario"]; ?>"},
            'onComplete'     : function(event, queueID, fileObj, response, data) {
                /*var parte= response.split("@@@");
                
                $('#enviados_nenhum').html("");
                
                $('#enviados').append('<div class="parte25 miniatura33 bloco_imagem <?=$div_nome;?>" id="linha_'+ parte[0] +'"><label class="label_nada" for="imagem_check_'+parte[0]+'"><div style="position: relative; width: <?=$largura_imagem;?>px; height: <?=$altura_imagem;?>px;"><img src="includes/phpthumb/phpThumb.php?src=../../<?= CAMINHO . $tipo_imagem ."_". $id_externo; ?>/'+parte[1]+'&amp;w=<?=$largura_imagem;?>&amp;h=<?=$altura_imagem;?>&amp;zc=1" border="0" alt="" /></div></label><div class="imagem_dimensao">'+parte[3]+'</div><a class="imagem_lupa" toptions="effect = appear, layout = quicklook" href="includes/phpthumb/phpThumb.php?w=940&amp;h=940&amp;src=../../<?= CAMINHO . $tipo_imagem ."_". $id_externo; ?>/'+parte[1]+'">+</a><a class="imagem_del" href="javascript:apagaLinha(\'imagemExcluir\', '+ parte[0] +');" onclick="return confirm(\'Tem certeza que deseja excluir?\');">del</a><input class="imagem_check tamanho20" type="checkbox" name="imagem_check" id="imagem_check_'+parte[0]+'" value="'+parte[0]+'" /><input name="id_imagem[]" type="hidden" id="id_imagem_'+parte[0]+'" value="'+parte[0]+'" /><br /><br /><label class="label_peq" for="legenda_'+parte[0]+'">(PT):</label><input name="legenda[]" class="imagem_legenda" id="legenda_'+parte[0]+'" value="" /><br /><label class="label_peq" for="legenda_en_'+parte[0]+'">(EN):</label><input name="legenda_en[]" class="imagem_legenda" id="legenda_en_'+parte[0]+'" value="" /><div id="div_imagem_site_'+parte[0]+'"></div><br /></div>');
				
				$(function(){
					$("#linha_"+ parte[0] +" input:text").uniform();
				  });
				*/
				
				$('#enviados').append(response);
				
				//alert('Envio completo!');
			},
            'auto'           : true,
            'multi'          : true
        });
        
        $('#uploadify').uploadifySettings('scriptData', {'id_pessoa': <?= $id_pessoa; ?>});
        
    });
    
</script>


<fieldset>
    <legend>Arquivos</legend>
    
    <div class="parte40">
        
        <div id="fileQueue"></div>
        
        <input type="file" name="uploadify" id="uploadify" />
        
        <br /><br />
        <a href="javascript:jQuery('#uploadify').uploadifyClearQueue()">Cancelar uploads</a>
    </div>
    <div class="parte60" id="enviados">
        
        <div id="ordem_retorno">
        
        </div>
     </div>
     <br /><br /><br />
     
</fieldset>
<br /><br />

<? } ?>