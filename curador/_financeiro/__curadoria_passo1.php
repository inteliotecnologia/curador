<?
require_once("includes/conexao.php");
if (pode("v", $_SESSION["permissao"])) {
	
	if ($_GET["id_projeto"]!="") $id_projeto= $_GET["id_projeto"];
	if ($_POST["id_projeto"]!="") $id_projeto= $_POST["id_projeto"];
	
	if ($_GET["id_curadoria"]!="") $id_curadoria= $_GET["id_curadoria"];
	if ($_POST["id_curadoria"]!="") $id_curadoria= $_POST["id_curadoria"];
	
	$acao= $_GET["acao"];
	
	if ($acao=='i') {
		$result_insere= mysql_query("insert into curadorias
										(id_projeto, id_empresa, data_curadoria, data_curadoria_mod, hora_curadoria_mod, id_usuario_mod, auth, status_curadoria, id_usuario)
										values
										('". $id_projeto ."', '". $_SESSION["id_empresa"] ."', '". date("Y-m-d") ."', '". date("Y-m-d") ."', '". date("H:i:s") ."',
										'". $_SESSION["id_usuario"] ."', '". gera_auth() ."', '1', '". $_SESSION["id_usuario"] ."')
										");
		$id_curadoria= mysql_insert_id();
		
		$acao= 'e';
	}
	
	
	if ($acao=='e') {
		$result= mysql_query("select * from  curadorias
								where id_curadoria = '". $id_curadoria ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								") or die(mysql_error());
		$rs= mysql_fetch_object($result);
		
		$id_curadoria= $rs->id_curadoria;
	}
?>
<script type="text/javascript" src="js/drag-n-drop/jquery-ui-1.7.1.custom.min.js"></script>

<script type="text/javascript">
	$().ready(function() {
		$("#form").validate();
		
		$(function() {
            $("#artistas_selecionados").sortable({ opacity: 0.8, cursor: 'move', axis: 'y', update: function() {
                var order = $(this).sortable("serialize") + '&chamada=atualizaOrdemArtistas&id_projeto=<?=$id_projeto;?>&id_curadoria=<?=$id_curadoria;?>'; 
                $.get("link.php", order, function(theResponse){
                    //$("#ordem_retorno").html(theResponse);
                }); 															 
            }								  
            });
        });
	});
	
	TopUp.images_path = "js/topup/images/top_up/";
	
	TopUp.addPresets({
			"a.imagem_lupa": {
			  fixed: 0,
			  group: "images",
			  modal: 0,
			  title: "Imagem"
			},
		  });
</script>

<h2 class="tit tit_maleta"><?= pega_projeto($id_projeto); ?></h2>

<ul class="recuo1">
	<li><a href="./?pagina=financeiro/curadoria_projeto_listar&amp;id_projeto=<?= $rs->id_projeto; ?>">&laquo; Listar curadorias</a></li>
</ul>

<? //include("_financeiro/__projeto_abas.php"); ?>

<? include("_financeiro/__curadoria_abas.php"); ?>

<form action="<?= AJAX_FORM; ?>formCuradoriaPasso1&amp;acao=<?= $acao; ?>" method="post" name="form" id="form">
    
    <input name="id_projeto" class="escondido" type="hidden" id="id_projeto" value="<?= $id_projeto; ?>" />
    
    <? if ($acao=='e') { ?>
    <input name="id_curadoria" class="escondido" type="hidden" id="id_curadoria" value="<?= $id_curadoria; ?>" />
    <? } ?>
    
    <div class="form_branco">
            
        <div class="parte50">
            <label for="titulo_curadoria">Título</label>
            <input id="titulo_curadoria" name="titulo_curadoria" class="tamanho50p espaco_dir" value="<?= $rs->titulo_curadoria; ?>" />
        </div>
        <div class="parte25">
            <label for="data_curadoria">Data</label>
            <input id="data_curadoria" name="data_curadoria" class="tamanho25p espaco_dir" value="<?= desformata_data($rs->data_curadoria); ?>" title="Data" onkeyup="formataData(this);" maxlength="10" />
            
        </div>
        <div class="parte25">
        	<label>Última alteração</label> <br />
        	<?= desformata_data($rs->data_curadoria_mod) ." ". $rs->hora_curadoria_mod; ?><br /> por <?= primeira_palavra(pega_nome_usuario($rs->id_usuario_mod)); ?>
        <!--
            <label for="num_curadoria">Identificador:</label>
            <input id="num_curadoria" name="num_curadoria" class="tamanho15p espaco_dir" value="<?= $rs->num_curadoria; ?>" />
            -->
        </div>
        <br />
        
        <hr />
        
        <h4>Escolha as informações que devem constar na curadoria:</h4>
        
        
        <div class="parte25">
            <label class="tamanho_auto" for="mostrar_0">Capa/contracapa</label>
            <input <? if (pode('0', $rs->mostrar)) echo "checked=\"checked\""; ?> class="tamanho30" type="checkbox" name="mostrar[]" id="mostrar_0" value="0" />
        </div>
        <div class="parte25">
            <label class="tamanho_auto" for="mostrar_7">Folha de rosto do artista</label>
            <input <? if (pode('7', $rs->mostrar)) echo "checked=\"checked\""; ?> class="tamanho30" type="checkbox" name="mostrar[]" id="mostrar_7" value="7" />
        </div>
        <div class="parte25">
            <label class="tamanho_auto" for="mostrar_1">Release</label>
            <input <? if (pode('1', $rs->mostrar)) echo "checked=\"checked\""; ?> class="tamanho30" type="checkbox" name="mostrar[]" id="mostrar_1" value="1" />
        </div>
        <div class="parte25">
            <label class="tamanho_auto" for="mostrar_2">Local</label>
            <input <? if (pode('2', $rs->mostrar)) echo "checked=\"checked\""; ?> class="tamanho30" type="checkbox" name="mostrar[]" id="mostrar_2" value="2" />
        </div>
        <div class="parte25">
            <label class="tamanho_auto" for="mostrar_3">Exposições individuais</label>
            <input <? if (pode('3', $rs->mostrar)) echo "checked=\"checked\""; ?> class="tamanho30" type="checkbox" name="mostrar[]" id="mostrar_3" value="3" />
        </div>
        <div class="parte25">
            <label class="tamanho_auto" for="mostrar_4">Exposições coletivas</label>
            <input <? if (pode('4', $rs->mostrar)) echo "checked=\"checked\""; ?> class="tamanho30" type="checkbox" name="mostrar[]" id="mostrar_4" value="4" />
        </div>
        <div class="parte25">
            <label class="tamanho_auto" for="mostrar_5">Publicações</label>
            <input <? if (pode('5', $rs->mostrar)) echo "checked=\"checked\""; ?> class="tamanho30" type="checkbox" name="mostrar[]" id="mostrar_5" value="5" />
        </div>
        <div class="parte25">
            <label class="tamanho_auto" for="mostrar_6">Currículo complementar</label>
            <input <? if (pode('6', $rs->mostrar)) echo "checked=\"checked\""; ?> class="tamanho30" type="checkbox" name="mostrar[]" id="mostrar_6" value="6" />
        </div>
        <br />
        
		<hr />
        
        <div class="parte33" id="curadoria_busca">
            
            <h4>Busca:</h4>
            
            <input class="tamanho200" name="busca" id="busca" onkeydown="if (event.keyCode==13) { buscaArtistaCuradoria('<?=$id_projeto;?>', '<?=$id_curadoria;?>'); return false; }" />
            
            <button class="escondido" type="button" onclick="buscaArtistaCuradoria('<?=$id_projeto;?>', '<?=$id_curadoria;?>');">buscar</button>
            <br />
            
            <p class="menor">Digite as primeiras letras e encontre artistas por 
			Cidade, País ou pelo Nome, Sobrenome ou Apelido. Digite <strong>Enter</strong> para buscar.</p>
            <br />
            
            <div id="artistas_lista" class="busca_listagem">
            
            </div>
            
        </div>
        <div class="parte66" id="curadoria_tags">
            
            <h4>Filtrar a busca por Tags:</h4>
                
            <ul class="lista_tags_curadoria">
                <?
                $tags= "";
                
                $result_tags= mysql_query("select * from pessoas, pessoas_tipos
                                            where pessoas.id_pessoa = pessoas_tipos.id_pessoa
                                            and   pessoas_tipos.status_pessoa <> '2'
                                            and   pessoas_tipos.tipo_pessoa = 'r'
                                            ") or die(mysql_error());
                $i= 0;
                while ($rs_tags= mysql_fetch_object($result_tags)) {
                    $tags_aqui= explode(",", $rs_tags->tags);
                    
                    $j=0;
                    while ($tags_aqui[$j]!="") {
                        
                        $tag[$i]= trim($tags_aqui[$j]);
                        
                        $i++;
                        $j++;
                    }
                }
                
                $tag= array_unique($tag);
                
				sort($tag, SORT_STRING);
				
                $i=0;
                foreach ($tag as $chave => $valor){
					if ($valor!="") {
                ?>
                <li>
                    <label class="tamanho_auto" for="tag_<?= $i; ?>"><?= $valor; ?></label>
                    <input class="tamanho30" type="checkbox" name="tag[]" onclick="buscaArtistaCuradoria('<?=$id_projeto;?>', '<?=$id_curadoria;?>'); " id="tag_<?= $i; ?>" value="<?= retira_acentos($valor); ?>" />
                </li>
                <?
					}
                	$i++;
                }
            ?>
            </ul>
            <br />
        </div>
	    <br />
	    
	    <hr />
	    
        <div id="artistas_selecionados">
        	<h4>Artistas selecionados:</h4>
        	
        	<?
			$result_artistas= mysql_query("select * from curadorias_pessoas
											where id_projeto = '". $id_projeto ."'
											and   id_curadoria = '". $id_curadoria ."'
											order by ordem asc
											");
			while ($rs_artistas= mysql_fetch_object($result_artistas)) {
			?>
            <div id="artista_<?= $rs_artistas->id_pessoa;?>" title="fechado">
            	<div class="artista_linha">
                    <div class="parte80">
                        <a href="javascript:void(0);" onclick="abreFechaArtistasObrasCuradoria('<?=$rs_artistas->id_pessoa;?>', '<?= $rs_artistas->id_projeto; ?>', '<?= $rs_artistas->id_curadoria; ?>');"><?= pega_pessoa($rs_artistas->id_pessoa); ?></a>
                    </div>
                    <div class="parte20 alinhar_direita">
                        <a href="javascript:void(0);" onclick="excluiArtistaCuradoria('<?= $rs_artistas->id_pessoa; ?>', '<?= $rs_artistas->id_projeto; ?>', '<?= $rs_artistas->id_curadoria; ?>');"><img src="images/ico_lixeira.png" alt="" /></a>
                    </div>
                    <br />
                </div>
                
                <div id="artista_obras_<?= $rs_artistas->id_pessoa; ?>" class="artista_obras" style="display:none;">
                </div>
            </div>
            <? } ?>
        </div>
        
        <br />
        
    	<hr />
    
    	<h4>Reorganizar?</h4>
        
        <label class="tamanho_auto" for="reorganizar">Reorganizar a estrutura:</label>
        <input class="tamanho30" type="checkbox" checked="checked" name="reorganizar" id="reorganizar" value="1" />
        
    </div>
    
    <center>
        <button type="submit" id="enviar">Avançar &raquo;</button>
    </center>
</form>
<? } ?>