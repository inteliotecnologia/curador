<?
require_once("includes/conexao.php");
if (pode_algum("vr", $_SESSION["permissao"])) {
	if ($acao=="") $acao= $_GET["acao"];
	
	if ($_GET["tipo_pessoa"]!="") $tipo_pessoa= $_GET["tipo_pessoa"];
	if ($_POST["tipo_pessoa"]!="") $tipo_pessoa= $_POST["tipo_pessoa"];
	
	if ($_GET["status_pessoa"]!="") $status_pessoa= $_GET["status_pessoa"];
	if ($_POST["status_pessoa"]!="") $status_pessoa= $_POST["status_pessoa"];
	if ($status_pessoa=="") $status_pessoa= 1;
	
	if ($_GET["id_pessoa"]!="") $id_pessoa= $_GET["id_pessoa"];
	if ($_POST["id_pessoa"]!="") $id_pessoa= $_POST["id_pessoa"];
	
	if ($_GET["id_projeto"]!="") $id_projeto= $_GET["id_projeto"];
	if ($_POST["id_projeto"]!="") $id_projeto= $_POST["id_projeto"];
	
	if ($_GET["tamanho_imagem"]!="") $tamanho_imagem= $_GET["tamanho_imagem"];
	if ($_POST["tamanho_imagem"]!="") $tamanho_imagem= $_POST["tamanho_imagem"];
	
	if ($tamanho_imagem=="") $tamanho_imagem=1;
	
	if ($id_pessoa!="") {
		$tipo_imagem= "a";
		$id_externo= $id_pessoa;
		$id_externo_nome= "id_pessoa";
		$tit_classe="tit_jarro";
	}
	elseif ($id_projeto!="") {
		$tipo_imagem= "p";
		$id_externo= $id_projeto;
		$id_externo_nome= "id_projeto";
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
					
		$tit.= $rs->nome_rz;		
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
	
switch($tamanho_imagem) {
	case 1:
		$largura_imagem= 165;
		$altura_imagem= 150;
		$div_nome= "bloco_imagem1";
	break;
	case 2:
		$largura_imagem= 300;
		$altura_imagem= 280;
		$div_nome= "bloco_imagem2";
	break;
	case 3:
		$largura_imagem= 600;
		$altura_imagem= 450;
		$div_nome= "bloco_imagem3";
	break;
}
?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="tit <?=$tit_classe;?>"><?= $tit; ?></h2>

<?
if ($tipo_imagem=='a') include("_financeiro/__pessoa_artista_abas.php");
else include("_financeiro/__projeto_abas.php");
?>

<link href="js/uploadify/default.css" rel="stylesheet" type="text/css" />
<link href="js/uploadify/uploadify.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/uploadify/swfobject.js"></script>
<script type="text/javascript" src="js/uploadify/jquery.uploadify.v2.1.0.js"></script>

<script type="text/javascript" src="js/drag-n-drop/jquery-ui-1.7.1.custom.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#uploadify").uploadify({
            'uploader'       : 'js/uploadify/uploadify.swf',
            'script'         : 'upload.php',
            'cancelImg'      : 'js/uploadify/cancel.png',
            'folder'         : 'uploads',
            'buttonText'     : 'PROCURAR',
            'queueID'        : 'fileQueue',
            'scriptData'     : {id_externo: "<?= $id_externo; ?>", tipo_imagem: "<?= $tipo_imagem; ?>", id_empresa: "<?= $_SESSION["id_empresa"]; ?>", id_usuario: "<?= $_SESSION["id_usuario"]; ?>"},
            'onComplete'     : function(event, queueID, fileObj, response, data) {
                
                console.log(response);
                
                var parte= response.split("@@@");
                
                $('#enviados_nenhum').html("");
                
                $('#enviados').append('<div class="parte25 miniatura33 bloco_imagem <?=$div_nome;?>" id="linha_'+ parte[0] +'"><label class="label_nada" for="imagem_check_'+parte[0]+'"><div style="position: relative; width: <?=$largura_imagem;?>px; height: <?=$altura_imagem;?>px;"><img src="includes/timthumb/timthumb.php?src=<?= CAMINHO_CDN . $tipo_imagem ."_". $id_externo; ?>/'+parte[1]+'&amp;w=<?=$largura_imagem;?>&amp;h=<?=$altura_imagem;?>&amp;zc=1&amp;q=95" border="0" alt="" /></div></label><div class="imagem_dimensao">'+parte[3]+'</div><a class="imagem_lupa" toptions="effect = appear, layout = quicklook" href="includes/timthumb/timthumb.php?w=940&amp;h=940&amp;src=<?= CAMINHO_CDN . $tipo_imagem ."_". $id_externo; ?>/'+parte[1]+'&amp;q=95">+</a><a class="imagem_del" href="javascript:apagaLinha(\'imagemExcluir\', '+ parte[0] +');" onclick="return confirm(\'Tem certeza que deseja excluir?\');">del</a><input class="imagem_check tamanho20" type="checkbox" name="imagem_check" id="imagem_check_'+parte[0]+'" value="'+parte[0]+'" /><input name="id_imagem[]" type="hidden" id="id_imagem_'+parte[0]+'" value="'+parte[0]+'" /><div class="imagens_legenda"><label class="label_peq" for="legenda_'+parte[0]+'">(PT):</label><input name="legenda[]" class="imagem_legenda" id="legenda_'+parte[0]+'" value="" /><br /><label class="label_peq" for="legenda_en_'+parte[0]+'">(EN):</label><input name="legenda_en[]" class="imagem_legenda" id="legenda_en_'+parte[0]+'" value="" /></div><div id="div_imagem_site_'+parte[0]+'"></div><br /></div>');
				
				$(function(){
					$("#linha_"+ parte[0] +" input:text").uniform();
				  });
				
			},
            'auto'           : true,
            'multi'          : true
        });
        
        $('#uploadify').uploadifySettings('scriptData', {'id_externo': <?= $id_externo; ?>});
        
        $(function() {
            $("#enviados").sortable({ opacity: 0.8, cursor: 'move', update: function() {
                var order = $(this).sortable("serialize") + '&chamada=atualizaOrdemImagens'; 
                $.get("link.php", order, function(theResponse){
                    //$("#ordem_retorno").html(theResponse);
                }); 															 
            }								  
            });
        });
		
		$(function() {
            $("#videos").sortable({ opacity: 0.8, cursor: 'move', update: function() {
                var order = $(this).sortable("serialize") + '&chamada=atualizaOrdemVideos'; 
                $.get("link.php", order, function(theResponse){
                    //$("#ordem_retorno").html(theResponse);
                }); 															 
            }								  
            });
        });
    });
    
    TopUp.images_path = "js/topup/images/top_up/";
    
    TopUp.addPresets({
            "#enviados div a.imagem_lupa": {
              fixed: 0,
              group: "images",
              modal: 0,
              title: "Imagem"
            },
          });
</script>

<form method="post" action="form.php?formLegendaImagem">

    <? /*<fieldset class="discreto">
        <legend>Imagens</legend>
        */ ?>
        
        <? /*
        <div class="parte40">
            
            <div id="fileQueue"></div>
            
            <input type="file" name="uploadify" id="uploadify" />
            
            <br /><br />
            <a href="javascript:jQuery('#uploadify').uploadifyClearQueue()">Cancelar uploads</a>
        </div>
        <div class="parte60">
            <button type="button" class="botao_grande_box botao_grande_box_check" onclick="checarDeschecarTudo('1', 'enviados');">checar todas</button>
            <button type="button" class="botao_grande_box botao_grande_box_descheck" onclick="checarDeschecarTudo('0', 'enviados');">deschecar todas</button>
            <button type="button" class="botao_grande_box botao_grande_box_apaga" onclick="excluiImagensSelecionadas('enviados');">apagar</button> <br />
            <button type="button" class="botao_grande_box botao_grande_box_envia" onclick="marcaImagensParaSite('enviados', '1');">enviar para o site</button>
            <button type="button" class="botao_grande_box botao_grande_box_retira" onclick="marcaImagensParaSite('enviados', '0');">tirar do site</button>
            <button type="button" class="botao_grande_box botao_grande_box_envia_topo" onclick="enviaImagensParaTopo('enviados', '<?=$id_pessoa;?>', '<?=$id_projeto;?>');">enviar para o topo</button>
            <button type="button" class="botao_grande_box botao_grande_box_baixa" onclick="window.open('index2.php?pagina=financeiro/imagens_abertas&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;<?=$id_externo_nome;?>=<?=$id_externo;?>','plain','width=960,height=800,location=1,status=1,scrollbars=1');">fazer download</button>
            <br /><br /><br />
            
            <div id="ordem_retorno">
            </div>
         </div>
         <br />
         */ ?>
         
         <div class="">
                
            <input name="id_externo" id="id_externo" type="hidden" class="escondido" value="<?=$id_externo;?>" />
            <input name="tipo_imagem" id="tipo_imagem" type="hidden" class="escondido" value="<?=$tipo_imagem;?>" />
            
            <div class="div_abas screen" id="aba_imagens_tamanhos">
                <ul class="abas abas_aux">
                    <li id="aba_imagens_tamanho1" <? if ($tamanho_imagem=="1") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/imagens&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;<?=$id_externo_nome;?>=<?=$id_externo;?>&amp;tamanho_imagem=1">165x150</a></li>
                    <li id="aba_imagens_tamanho1" <? if ($tamanho_imagem=="2") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/imagens&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;<?=$id_externo_nome;?>=<?=$id_externo;?>&amp;tamanho_imagem=2">300x280</a></li>
                    <li id="aba_imagens_tamanho2" <? if ($tamanho_imagem=="3") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/imagens&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;<?=$id_externo_nome;?>=<?=$id_externo;?>&amp;tamanho_imagem=3">600x450</a></li>
                </ul>
            </div>
            
            <div id="enviados">
            	
                <?
                $result_enviados= mysql_query("select * from imagens
                                                where id_externo = '". $id_externo ."'
                                                and   tipo_imagem = '". $tipo_imagem ."'
                                                and   id_empresa = '". $_SESSION["id_empresa"] ."'
                                                and   ( miniatura_destaque is NULL or miniatura_destaque = '0')
                                                order by ordem asc
                                                ") or die(mysql_error());
                $linhas_enviados= mysql_num_rows($result_enviados);
                
                if ($linhas_enviados==0) {
                ?>
                <div id="enviados_nenhum">Nenhuma imagem enviada.</div>
                <?
                }
                else {
                    
                    $nova_ordem=0;
                    
                    while ($rs_enviados= mysql_fetch_object($result_enviados)) {
                        
                        $result_index= mysql_query("update imagens set ordem = '". $nova_ordem ."'
                                                    where id_imagem = '". $rs_enviados->id_imagem ."'
                                                    ");
                        $nova_ordem++;
                        
                        /*
                        $targetFile= CAMINHO . $rs_enviados->tipo_imagem ."_". $rs_enviados->id_externo ."/". $rs_enviados->nome_arquivo;
                        
                        $originalimage= imagecreatefromjpeg($targetFile);
                        $l_original= imagesx($originalimage);
                        $a_original= imagesy($originalimage);
                        
                        $dimensao_imagem= pega_dimensao_imagem($l_original, $a_original);
                        
                        $result_atualiza= mysql_query("update imagens
                                                        set largura = '". $l_original ."',
                                                        altura = '". $a_original ."',
                                                        dimensao_imagem = '". $dimensao_imagem ."'
                                                        where id_imagem = '". $rs_enviados->id_imagem ."'
                                                        ");
                        */
                ?>
                <div class="parte25 miniatura33 bloco_imagem <?=$div_nome;?>" id="linha_<?= $rs_enviados->id_imagem; ?>">
                    <label class="label_nada" for="imagem_check_<?= $rs_enviados->id_imagem; ?>">
                        <div style="position: relative; width: <?=$largura_imagem;?>px; height: <?=$altura_imagem;?>px;">
	                        <img src="includes/timthumb/timthumb.php?src=<?= CAMINHO_CDN . $rs_enviados->tipo_imagem ."_". $rs_enviados->id_externo ."/". $rs_enviados->nome_arquivo; ?>&amp;w=<?=$largura_imagem;?>&amp;h=<?=$altura_imagem;?>&amp;zc=1&amp;q=95" border="0" alt="" />
                        </div>
                    </label>
                    
                    <div class="imagem_dimensao">
                        <?= pega_descricao_dimensao_imagem($rs_enviados->dimensao_imagem); ?>
                    </div>
                    
                    <? /* <a class="imagem_lupa" toptions="effect = appear, layout = quicklook" href="includes/phpthumb/phpThumb.php?w=940&amp;h=940&amp;src=../../<?= CAMINHO . $rs_enviados->tipo_imagem ."_". $rs_enviados->id_externo ."/". $rs_enviados->nome_arquivo; ?>"> */ ?>
                    <a class="imagem_lupa" toptions="layout = quicklook" href="<?= CAMINHO_CDN . $rs_enviados->tipo_imagem ."_". $rs_enviados->id_externo ."/". $rs_enviados->nome_arquivo; ?>">
                        +
                    </a>
                    <a class="imagem_del" href="javascript:apagaLinha('imagemExcluir', <?=$rs_enviados->id_imagem;?>);" onclick="return confirm('Tem certeza que deseja excluir?');">
                        del
                    </a>
                    <a class="imagem_down" href="<?= CAMINHO_CDN . $rs_enviados->tipo_imagem ."_". $rs_enviados->id_externo ."/". $rs_enviados->nome_arquivo; ?>" target="_blank">
                        down
                    </a>
                    
                    <input class="imagem_check tamanho20" type="checkbox" name="imagem_check" id="imagem_check_<?= $rs_enviados->id_imagem; ?>" value="<?= $rs_enviados->id_imagem; ?>" />
                    <input name="id_imagem[]" type="hidden" id="id_imagem_<?= $rs_enviados->id_imagem; ?>" value="<?= $rs_enviados->id_imagem; ?>" />
                    
                    <div class="imagens_legenda">
	                    <label class="label_peq" for="legenda_<?= $rs_enviados->id_imagem; ?>">(PT):</label>
	                    <input name="legenda[]" class="imagem_legenda" id="legenda_<?= $rs_enviados->id_imagem; ?>" value="<?= $rs_enviados->legenda; ?>" />
	                    <br />
	                    
	                    <label class="label_peq" for="legenda_en_<?= $rs_enviados->id_imagem; ?>">(EN):</label>
	                    <input name="legenda_en[]" class="imagem_legenda" id="legenda_en_<?= $rs_enviados->id_imagem; ?>" value="<?= $rs_enviados->legenda_en; ?>" />
                    </div>
                    
                    <div id="div_imagem_site_<?= $rs_enviados->id_imagem;?>">
                        <? if ($rs_enviados->site=="1") { ?>
                        <a class="imagem_site" id="link_site_<?= $rs_enviados->id_imagem;?>" href="javascript:removeImagemSite('imagemSite', <?=$rs_enviados->id_imagem;?>);">
                            site
                        </a>
                        <? } ?>
                    </div>
                    <br />
                    
                </div>
                <? } } ?>
            </div>
            <br /><br />
            
        </div>
    	
    	<hr />
    	
    	<h2 class="tit tit_videos">V&iacute;deos</h2>
    	
        <div id="videos">
            <?
			$result_video= mysql_query("select * from videos
										where id_empresa = '". $_SESSION["id_empresa"] ."'
										and   id_externo = '". $id_externo ."'
                                        and   tipo_video = '". $tipo_imagem ."'
										order by ordem asc
										");
			$k=1;
			while ($rs_video= mysql_fetch_object($result_video)) {
			?>
            <div id="div_video_<?=$rs_video->id_video;?>" class="form_alternativo form_drag">
            	<code class="escondido"></code>
                <div class="parte50">
                    
                    <label class="tamanho40" for="url_<?=$rs_video->id_video;?>">URL:</label>
                    <input name="url[]" id="url_<?=$rs_video->id_video;?>" value="<?= $rs_video->url; ?>" />
                    <br />
                    
                    <div class="parte25">
	                    <label class="tamanho70" for="largura_<?=$rs_video->id_video;?>">Largura:</label>
	                    <input class="tamanho70" name="largura[]" id="largura_<?=$rs_video->id_video;?>" value="<?= $rs_video->largura; ?>" />
                    </div>
                    <div class="parte25">
                    	<label class="tamanho70" for="altura_<?=$rs_video->id_video;?>">Altura:</label>
                    	<input class="tamanho70" name="altura[]" id="altura_<?=$rs_video->id_video;?>" value="<?= $rs_video->altura; ?>" />
                    </div>
                    <br />
                    
                    <label class="tamanho40" for="site_<?=$rs_video->id_video;?>">Site:</label>
                    <input class="tamanho30" type="checkbox" name="site[]" value="1" id="site_<?=$rs_video->id_video;?>" <? if ($rs_video->site=="1") { ?> checked="checked" <? } ?> />
                    <br /><br />
                    
                    <a class="telefone_remover link_remover" href="javascript:void(0);" onclick="removeDiv('videos', 'div_video_<?=$rs_video->id_video;?>');">remover</a><br />
                </div>
                <div class="parte50 alinhar_centro">
                    <?= faz_embed_video($rs_video->url, 440, 300); ?>
                </div>
                
                <br />
            </div>
            <? $k++; } ?>
		</div>    
        
        <br />
        <a class="link_mais" href="javascript:void(0);" onclick="criaEspacoVideo('<?=$tipo_imagem;?>', '<?=$id_externo;?>');">Novo v&iacute;deo</a>
            
        <? /*</fieldset>*/ ?>
	<br /><br /><br />
    
    <button type="submit">Salvar altera&ccedil;&otilde;es &raquo;</button>
    
</form>

<? } ?>