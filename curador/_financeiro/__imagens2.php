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

<script type="text/javascript" src="js/drag-n-drop/jquery-ui-1.7.1.custom.min.js"></script>

<? /*
<link href="js/uploadify/default.css" rel="stylesheet" type="text/css" />
<link href="js/uploadify/uploadify.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/uploadify/swfobject.js"></script>
<script type="text/javascript" src="js/uploadify/jquery.uploadify.v2.1.0.js"></script>


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

*/
?>

<script type="text/javascript">
	
	/*
	$(document).ready(function() {
		
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
	*/ 
	
	/*
	TopUp.images_path = "js/topup/images/top_up/";
    
    TopUp.addPresets({
            "#enviados div a.imagem_lupa": {
              fixed: 0,
              group: "images",
              modal: 0,
              title: "Imagem"
            },
          });
	*/
	
</script>

<? /*
<link
  rel="stylesheet"
  href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
  integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
  crossorigin="anonymous"
/>
*/ ?>

<!-- blueimp Gallery styles -->
<link
  rel="stylesheet"
  href="https://blueimp.github.io/Gallery/css/blueimp-gallery.min.css"
/>
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="js/jQuery-File-Upload/css/jquery.fileupload.css" />
<link rel="stylesheet" href="js/jQuery-File-Upload/css/jquery.fileupload-ui.css" />

<script>
	
	$(document).ready(function() {
		console.log("Chamando isso aqui");
		
		$(function () {
		    $('#fileupload').fileupload({
			    url: 'js/jQuery-File-Upload/server/php/?tipo_imagem=<?=$tipo_imagem;?>&id_externo=<?=$id_externo;?>',
			    //url: 'js/jQuery-File-Upload/server/php/',
			    autoUpload: true,
		        dataType: 'json',
		        disableImageLoad: true,
		        done: function (e, data) {
		            console.log("done here");
		            
		            //window.location.reload();
		            
		            
		            $.each(data.result.arquivos, function (index, file) {
		                //$('<p></p>').text(file.name).appendTo(document.body);
		                console.log("retornando "+file.name);
		            });
		            
		        }
		        /*
		        chunkdone: function (e, data) {
			        
			        window.location.reload();
			        
		        }*/
		    })
		    //.on('fileuploadadd', function (e, data) { console.log('fileuploadadd'); /* ... */})
		    .on('fileuploadsubmit', function (e, data) { console.log('fileuploadsubmit'); /* ... */})
		    .on('fileuploadsend', function (e, data) { console.log('fileuploadsend'); /* ... */})
		    .on('fileuploaddone', function (e, data) {
				
				console.log('fileuploaddone'); /* ... */
				
				setTimeout(function(){
					
					console.log("html agora eh: "+$(".progress-extended").html());
					
					if ( $(".progress-extended").html().trim()=="&nbsp;" )  {
						console.log('progresso tá vazio jah'); /* ... */
						
						window.location.reload();
						
					}
					else {
						console.log('progresso contém valor'); /* ... */
					}
					
				}, 1000);
				
			})
		    //.on('fileuploadfail', function (e, data) { console.log('fileuploadfail'); /* ... */})
		    //.on('fileuploadalways', function (e, data) { console.log('fileuploadalways'); /* ... */})
		    //.on('fileuploadprogress', function (e, data) { console.log('fileuploadprogress'); /* ... */})
		    //.on('fileuploadprogressall', function (e, data) { console.log('fileuploadprogressall'); /* ... */})
		    //.on('fileuploadstart', function (e) { console.log('fileuploadstart'); /* ... */})
		    //.on('fileuploadstop', function (e) { console.log('fileuploadstop'); /* ... */})
		    //.on('fileuploadchange', function (e, data) { console.log('fileuploadchange'); /* ... */})
		    //.on('fileuploadpaste', function (e, data) { console.log('fileuploadpaste'); /* ... */})
		    //.on('fileuploaddrop', function (e, data) { console.log('fileuploaddrop'); /* ... */})
		    //.on('fileuploaddragover', function (e) { console.log('fileuploaddragover'); /* ... */})
		    //.on('fileuploadchunkbeforesend', function (e, data) { console.log('fileuploadchunkbeforesend'); /* ... */})
		    //.on('fileuploadchunksend', function (e, data) { console.log('fileuploadchunksend'); /* ... */})
		    .on('fileuploadchunkdone', function (e, data) {
			    console.log('fileuploadchunkdone'); /* ... */
			    
			    window.location.reload();
			})
		    //.on('fileuploadchunkfail', function (e, data) { console.log('fileuploadchunkfail'); /* ... */})
		    //.on('fileuploadchunkalways', function (e, data) { console.log('fileuploadchunkalways'); /* ... */});
		});
	});
	
</script>

<form method="post" action="form.php?formLegendaImagem" id="fileupload" enctype="multipart/form-data" >

    <? /*<fieldset class="discreto">
        <legend>Imagens</legend>
        */ ?>
        
        <div class="parte60">
	        
	        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
	        <div class="row fileupload-buttonbar">
	          <div class="col-lg-7">
	            <!-- The fileinput-button span is used to style the file input field as button -->
	            <span class="btn btn-success fileinput-button">
	              
	              <button type="button">Anexar</button>
	              
	              <input type="file" name="arquivos[]" multiple="multiple" />
	            </span>
	            <br/><br/>
	            
	            <? /*
	            <button type="submit" class="btn btn-primary start">
	              <span>Iniciar upload</span>
	            </button>
	            
	            <button type="reset" class="btn btn-warning cancel">
	              <span>Cancelar uploads</span>
	            </button>
	            
	            <button type="button" class="btn btn-danger delete">
	              <span>Deletar selecionados</span>
	            </button>
	            */ ?>
	            
	            <!-- The global file processing state -->
	            <span class="fileupload-process"></span>
	          </div>
	          <!-- The global progress state -->
	          <div class="col-lg-5 fileupload-progress fade">
	            <!-- The global progress bar -->
	            <div
	              class="progress progress-striped active"
	              role="progressbar"
	              aria-valuemin="0"
	              aria-valuemax="100"
	            >
	              <div
	                class="progress-bar progress-bar-success"
	                style="width: 0%;"
	              ></div>
	            </div>
	            <!-- The extended global progress state -->
	            <div class="progress-extended">&nbsp;</div>
	          </div>
	        </div>
	        <!-- The table listing the files available for upload/download -->
	        <table role="presentation" class="table table-striped">
	          <tbody class="files"></tbody>
	        </table>
        
            <br /><br />
        </div>
        <div class="parte40">
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
         
         <div class="">
                
            <input name="id_externo" id="id_externo" type="hidden" class="escondido" value="<?=$id_externo;?>" />
            <input name="tipo_imagem" id="tipo_imagem" type="hidden" class="escondido" value="<?=$tipo_imagem;?>" />
            
            <div class="div_abas screen" id="aba_imagens_tamanhos">
                <ul class="abas abas_aux">
                    <li id="aba_imagens_tamanho1" <? if ($tamanho_imagem=="1") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/imagens2&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;<?=$id_externo_nome;?>=<?=$id_externo;?>&amp;tamanho_imagem=1">165x150</a></li>
                    <li id="aba_imagens_tamanho1" <? if ($tamanho_imagem=="2") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/imagens2&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;<?=$id_externo_nome;?>=<?=$id_externo;?>&amp;tamanho_imagem=2">300x280</a></li>
                    <li id="aba_imagens_tamanho2" <? if ($tamanho_imagem=="3") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/imagens2&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;<?=$id_externo_nome;?>=<?=$id_externo;?>&amp;tamanho_imagem=3">600x450</a></li>
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
                    
                    <? /*<a class="imagem_lupa" toptions="layout = quicklook" href="<?= CAMINHO_CDN . $rs_enviados->tipo_imagem ."_". $rs_enviados->id_externo ."/". $rs_enviados->nome_arquivo; ?>">
                        +
                    </a>
                    */ ?>
                    
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

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
      <tr class="template-upload fade{%=o.options.loadImageFileTypes.test(file.type)?' image':''%}">
          <td>
              <span class="preview"></span>
          </td>
          <td>
              <p class="name">{%=file.name%}</p>
              <strong class="error text-danger"></strong>
          </td>
          <td>
              <p class="size">Processing...</p>
              <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
          </td>
          <td>
              {% if (!o.options.autoUpload && o.options.edit && o.options.loadImageFileTypes.test(file.type)) { %}
                <button class="btn btn-success edit" data-index="{%=i%}" disabled>
                    <i class="glyphicon glyphicon-edit"></i>
                    <span>Edit</span>
                </button>
              {% } %}
              {% if (!i && !o.options.autoUpload) { %}
                  <button class="btn btn-primary start" disabled>
                      <i class="glyphicon glyphicon-upload"></i>
                      <span>Start</span>
                  </button>
              {% } %}
              
          </td>
      </tr>
  {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
      <tr class="template-download fade{%=file.thumbnailUrl?' image':''%}">
          <td>
              <span class="preview">
                  {% if (file.thumbnailUrl) { %}
                      <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                  {% } %}
              </span>
          </td>
          <td>
              <p class="name">
                  {% if (file.url) { %}
                      <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                  {% } else { %}
                      <span>{%=file.name%}</span>
                  {% } %}
              </p>
              {% if (file.error) { %}
                  <div><span class="label label-danger">Error</span> {%=file.error%}</div>
              {% } %}
          </td>
          <td>
              <span class="size">{%=o.formatFileSize(file.size)%}</span>
          </td>
          <td>
              {% if (file.deleteUrl) { %}
                  <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                      <i class="glyphicon glyphicon-trash"></i>
                      <span>Delete</span>
                  </button>
                  <input type="checkbox" name="delete" value="1" class="toggle">
              {% } else { %}
                  <button class="btn btn-warning cancel">
                      <i class="glyphicon glyphicon-ban-circle"></i>
                      <span>Cancel</span>
                  </button>
              {% } %}
          </td>
      </tr>
  {% } %}
</script>
<!--[if gte IE 9]><!-->
    

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="js/jQuery-File-Upload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- blueimp Gallery script -->
<script src="https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/jQuery-File-Upload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/jQuery-File-Upload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="js/jQuery-File-Upload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="js/jQuery-File-Upload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="js/jQuery-File-Upload/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="js/jQuery-File-Upload/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="js/jQuery-File-Upload/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="js/jQuery-File-Upload/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="js/jQuery-File-Upload/js/demo.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
  <script src="js/cors/jquery.xdr-transport.js"></script>
<![endif]-->


<? } ?>