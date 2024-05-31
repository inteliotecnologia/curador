<?
require_once("includes/conexao.php");
if (pode("v", $_SESSION["permissao"])) {
	
	if ($_GET["id_projeto"]!="") $id_projeto= $_GET["id_projeto"];
	if ($_POST["id_projeto"]!="") $id_projeto= $_POST["id_projeto"];
	
	if ($_GET["id_curadoria"]!="") $id_curadoria= $_GET["id_curadoria"];
	if ($_POST["id_curadoria"]!="") $id_curadoria= $_POST["id_curadoria"];
	
	$result= mysql_query("select * from  projetos, curadorias
							where curadorias.id_curadoria = '". $id_curadoria ."'
							and   curadorias.id_projeto = projetos.id_projeto
							and   curadorias.id_empresa = '". $_SESSION["id_empresa"] ."'
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	
	$result_enviados_pre= mysql_query("select * from curadoria_paginas
									where curadoria_paginas.tipo_pagina = '6'
									and   curadoria_paginas.id_projeto = '". $id_projeto ."'
									and   curadoria_paginas.id_curadoria = '". $id_curadoria ."'
									");
	
	$linhas_enviados_pre= mysql_num_rows($result_enviados_pre);
	
	$result_limpa1= mysql_query("delete from curadoria_paginas
								where id_curadoria = '". $id_curadoria ."'
								and   tipo_pagina <> '6'
								");
								
	
	
	$l= $rs->lingua_preferencial;
	
	//-----------------------
	/*
	1- capa da curadoria;
	2- folha de rosto do artista;
	3- exposições
	4- publicações
	5- notas de curadoria
	*/
	
	$num_pagina=1;
	
	if (pode('0', $rs->mostrar)) insere_pagina_curadoria($_SESSION["id_empresa"], $id_projeto, $id_curadoria, -1, 1, 0, $_SESSION["id_usuario"]);
	
	$result_artistas= mysql_query("select *,
									pessoas.exposicoes_individuais_". $l ." as exposicoes_individuais,
									pessoas.exposicoes_coletivas_". $l ." as exposicoes_coletivas,
									pessoas.publicacoes_". $l ." as publicacoes,
									pessoas.release_". $l ." as release1
									from curadorias_pessoas, pessoas, enderecos
									where pessoas.id_pessoa = curadorias_pessoas.id_pessoa
									and   pessoas.id_pessoa = enderecos.id_pessoa
									and   curadorias_pessoas.id_projeto = '". $rs->id_projeto ."'
									and   curadorias_pessoas.id_curadoria = '". $rs->id_curadoria ."'
									order by curadorias_pessoas.ordem asc
									") or die(mysql_error());
	while ($rs_artistas= mysql_fetch_object($result_artistas)) {
		
		if (pode('7', $rs->mostrar)) insere_pagina_curadoria($_SESSION["id_empresa"], $id_projeto, $id_curadoria, -1, 2, $rs_artistas->id_pessoa, $_SESSION["id_usuario"]);
		
		$nome_artista= explode(" ", $rs_artistas->apelido_fantasia);
		
		$nome_artista_count= count($nome_artista);
		
		switch($nome_artista_count) {
			case 1:
				$nome_artista_linha_1= "";
				$nome_artista_linha_2= $nome_artista[0];
				break;
			case 2:
				$nome_artista_linha_1= $nome_artista[0];
				$nome_artista_linha_2= $nome_artista[1];
				break;
			case 3:
				$nome_artista_linha_1= $nome_artista[0];
				$nome_artista_linha_2= $nome_artista[1] ." ". $nome_artista[2];
				break;
			break;
		}
		
		if (pode('34', $rs->mostrar)) {
			
			if (($rs_artistas->exposicoes_individuais!="") || ($rs_artistas->exposicoes_coletivas!="")) {
				
				insere_pagina_curadoria($_SESSION["id_empresa"], $id_projeto, $id_curadoria, -1, 3, $rs_artistas->id_pessoa, $_SESSION["id_usuario"]);
				
				$coluna=0;
				
				//testando antes
				$exposicoes_individuais= explode("\n\r", $rs_artistas->exposicoes_individuais);
				$exposicoes_individuais_count= count($exposicoes_individuais);
				
				$exposicoes_coletivas= explode("\n\r", $rs_artistas->exposicoes_coletivas);
				$exposicoes_coletivas_count= count($exposicoes_coletivas);
				
				$total_exposicoes= $exposicoes_individuais_count+$exposicoes_coletivas_count;
				
				if ($total_exposicoes<=15) $coluna=1;
				elseif ($total_exposicoes<=10) $coluna=2;
				elseif ($total_exposicoes<=5) $coluna=3;
				
				for ($e=0; $e<2; $e++) {
					if ($e==0) {
						$exposicoes= $rs_artistas->exposicoes_coletivas;
						$titulo= $_COLETIVA[$l];
						
						$permissao_exposicoes= "4";
					}
					else {
						$exposicoes= $rs_artistas->exposicoes_individuais;
						$titulo= $_INDIVIDUAL[$l];
						
						$permissao_exposicoes= "3";
					}
					
					$exposicoes= explode("\n\r", $exposicoes);
					
					if (pode($permissao_exposicoes, $rs->mostrar)) {
					
						
						for ($i=0; $i<count($exposicoes); $i++) {
							$j= $i+1;
							$posicao_x= 2+($coluna*6.425);
							
							
							
							if (($i>0) && ($j%5)==0) {
								$coluna++;
								
							}
						}
						
						$coluna++;
					}//fim permissao_exposicoes
				}
				
			}
		}//fim pode
		
		if (pode('5', $rs->mostrar)) {
		
			if ($rs_artistas->publicacoes!="") {
				
				insere_pagina_curadoria($_SESSION["id_empresa"], $id_projeto, $id_curadoria, -1, 4, $rs_artistas->id_pessoa, $_SESSION["id_usuario"]);
				
				$coluna=0;
				
				//for ($e=0; $e<2; $e++) {
					$publicacoes= $rs_artistas->publicacoes;
					$publicacoes= explode("\n\r", $publicacoes);
					$publicacoes_count= count($publicacoes);
					
					if ($publicacoes_count<=15) $coluna=1;
					elseif ($publicacoes_count<=10) $coluna=2;
					elseif ($publicacoes_count<=5) $coluna=3;
					
					
					for ($i=0; $i<$publicacoes_count; $i++) {
						$j= $i+1;
						$posicao_x= 2+($coluna*6.425);
						
						if (($i>0) && ($j%5)==0) {
							$coluna++;
						}
					}
					
				//}
				
			}
		}
		
		if ($rs_artistas->notas!="") {
			insere_pagina_curadoria($_SESSION["id_empresa"], $id_projeto, $id_curadoria, -1, 5, $rs_artistas->id_pessoa, $_SESSION["id_usuario"]);
		}
		
		if (($linhas_enviados_pre==0) || ($_GET["reorganizar"]=="1")) {
			
			
			$result_limpa1_pre= mysql_query("select * from curadoria_paginas_imagens, curadoria_paginas
												where curadoria_paginas.id_curadoria = '". $id_curadoria ."'
												and   curadoria_paginas_imagens.id_curadoria_pagina = curadoria_paginas.id_curadoria_pagina
												and   curadoria_paginas.id_artista = '". $rs_artistas->id_pessoa ."'
												and   curadoria_paginas.tipo_pagina= '6'
												");
			while ($rs_limpa1_pre= mysql_fetch_object($result_limpa1_pre)) {
				
				$result_limpa2= mysql_query("delete from curadoria_paginas_imagens
												where id_curadoria_pagina_imagem = '". $rs_limpa1_pre->id_curadoria_pagina_imagem ."'
												limit 1
												") or die(mysql_error());
				
			}
			
			$result_limpa1= mysql_query("delete from curadoria_paginas
											where id_curadoria = '". $id_curadoria ."'
											and   id_artista = '". $rs_artistas->id_pessoa ."'
											and   tipo_pagina = '6'
											");
			
			// ----- imagens
			
			$result_imagem= mysql_query("select * from curadorias_pessoas_imagens, imagens
											where curadorias_pessoas_imagens.id_imagem = imagens.id_imagem
											and   curadorias_pessoas_imagens.id_projeto = '". $rs->id_projeto ."'
											and   curadorias_pessoas_imagens.id_curadoria = '". $rs->id_curadoria ."'
											and   curadorias_pessoas_imagens.id_pessoa = '". $rs_artistas->id_pessoa ."'
											order by imagens.ordem_curadoria asc, imagens.ordem asc
											");
			$vetor= array();
			
			//[0][0]= usado
			//[0][1]= dimensao_imagem
			//[0][2]= nome_arquivo
			
			$i=0;
			while ($rs_imagem= mysql_fetch_object($result_imagem)) {
				$vetor[$i][0]=0;
				$vetor[$i][1]= $rs_imagem->dimensao_imagem;
				$vetor[$i][2]= $rs_imagem->nome_arquivo;
				$vetor[$i][3]=0;
				$vetor[$i][4]=$rs_imagem->id_imagem;
				
				$i++;
			}
			
			//print_r($vetor);
			
			$proximo=" 1,2,3 ";
			
			$j=0;
			$nesta_pagina=0;
			
			$inseridas=0;
			
			$v=0;
			
			while ($inseridas<$i) {
				
				if ($vetor[$v][0]!=1) {
					if ($_GET["mostra"]==1) echo "<strong>$v</strong>) <br />";
				
					if ($_GET["mostra"]==1) echo "*". $vetor[$v][1] ."* <br />";
					
					//sem zerar aqui, esta posição já está marcada como incompatível, desiste de achar uma imagem compatível e inicia nova página com as disponíveis
					if (($j==0) || ($nesta_pagina==4) || ($vetor[$v][3]==1)) {
						$j=0;
						$nesta_pagina=0;
						
						//$pdf->AddPage();
						$id_curadoria_pagina= insere_pagina_curadoria($_SESSION["id_empresa"], $id_projeto, $id_curadoria, $num_pagina, 6, $rs_artistas->id_pessoa, $_SESSION["id_usuario"]); $num_pagina++;
						
						if ($vetor[$v][3]==1) {
							$add= "(quebrando a sequencia)";
							$proximo= " 1,2,3 ";
						}
						else $add="";
						
						if ($_GET["mostra"]==1) echo "Add page... ". $add ."<br />";
						
						$x= cpc(150);
						$y= cpc(100);
					}
					
					if (strpos($proximo, $vetor[$v][1])) {
						
						//usado!
						$vetor[$v][0]=1;
						$inseridas++;
						
						//setar todas as posições incompatíveis com zero, para que sejam testadas novamente
						for ($k=0; $k<$i; $k++) {
							$vetor[$k][3]= 0;
						}
						
						$largura= pega_largura_padrao('l', $vetor[$v][1]);
						$altura= pega_largura_padrao('a', $vetor[$v][1]);
						
						/*$pdf->SetFillColor(rand(0,200), rand(0,200), rand(0,200));
						$pdf->Rect($x, $y, $largura, $altura, 'F');
						$pdf->SetXY($x+1,$y+1);
						$pdf->Cell(0, 0.8, $vetor[$v][1] ." . ". $j ." . ". $nesta_pagina, 0, 1);
						*/
						
						if ($_GET["mostra"]==1) echo "***INSERIDA*** ". $vetor[$v][1] ." . ". $j ." . ". $nesta_pagina ."<br /><br />";
						
						//$pdf->SetFillColor(0, 0, 0);
						//$pdf->Image("uploads/a_". $rs_artistas->id_pessoa ."/". $vetor[$v][2], $x, $y, $largura, $altura, 0);
						
						insere_imagem_pagina_curadoria($_SESSION["id_empresa"], $id_curadoria, $id_curadoria_pagina, $vetor[$v][4], $vetor[$v][2], $vetor[$v][1], $_SESSION["id_usuario"]);
						
						//mostra o nome do artista
						if ($j==0) {
							
						}
						
						switch($vetor[$v][1]) {
							//paisagem grande
							case 1:
								$x= cpc(150);
								$y= cpc(100);
								
								$nesta_pagina=4;
								$proximo=" 1,2,3 ";
								break;
							//paisagem pequena
							case 2:
								$nesta_pagina++;
								
								switch ($nesta_pagina) {
									case 1:
										$x= cpc(150);
										$y= cpc(625);
									break;
									case 2:
										$x= cpc(975);
										$y= cpc(100);
									break;
									case 3:
										$x= cpc(975);
										$y= cpc(625);
									break;
									case 4:
									
										$x= cpc(150);
										$y= cpc(100);
									break;
								}
								
								if ($nesta_pagina==2) $proximo=" 2,3 ";
								elseif ($nesta_pagina<4) $proximo= " 2 ";
								else $proximo= " 1,2,3 ";
								
								break;
							//retrato
							case 3:
								$nesta_pagina+=2;
								
								if ($nesta_pagina<4) {
									$proximo=" 2,3 ";
									
									$x= cpc(975);
									$y= cpc(100);
								}
								else {
									$proximo= " 1,2,3 ";
									
									$x= cpc(150);
									$y= cpc(100);
								}
								break;
						}
						
						if ($nesta_pagina==4) $j=0;
						else $j++;
						
						if ($_GET["mostra"]==1) echo "* proxima: ". $proximo ." * <br /><br />";
						
					}//fim if entrou
					//posição atual não é compatível com o formato requerido
					else {
						$vetor[$v][3]= 1;
					}
				}//fim vetor já inserido
				
				$v++;
				
				if ($v==$i) $v=0;
			}//fim while vetor
		}
		
		//if ($_GET["mostra"]==1) echo "<br /><br /> negados: <br />";
		
	}//fim while artistas
	
	if (pode('0', $rs->mostrar)) insere_pagina_curadoria($_SESSION["id_empresa"], $id_projeto, $id_curadoria, -1, 7, 0, $_SESSION["id_usuario"]);
	
	//-----------------------
	
	$result_atualiza= mysql_query("update curadorias
									set data_curadoria_mod= '". date("Y-m-d") ."',
									hora_curadoria_mod= '". date("H:i:s") ."',
									id_usuario_mod= '". $_SESSION["id_usuario"] ."'
									where id_curadoria = '". $_GET["id_curadoria"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									") or die(mysql_error());
	
	$zoom= $_GET["zoom"];
	
	if ($zoom=="") $zoom=1;
		
	switch($zoom) {
		case 1:
			$div_nome= "bloco_imagem4";
		break;
		case 2:
			$div_nome= "bloco_imagem5";
		break;
		case 3:
			$div_nome= "bloco_imagem6";
		break;
	}
	
?>

<script type="text/javascript" src="js/drag-n-drop/jquery-ui-1.7.1.custom.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        
        $(function() {
            $(".enviados").sortable({ opacity: 0.8, cursor: 'move', <? if ($zoom==3) { ?> axis: 'y', <? } ?> update: function() {
                var order = $(this).sortable("serialize") + '&chamada=atualizaOrdemPaginasCuradoria&id_projeto=<?= $id_projeto; ?>&id_curadoria=<?=$id_curadoria;?>'; 
                $.get("link.php", order, function(theResponse){
                    //$("#ordem_retorno").html(theResponse);
                }); 															 
            }								  
            });
        });
		
    });
</script>

<h2 class="tit tit_maleta"><?= pega_projeto($id_projeto); ?></h2>

<ul class="recuo1">
	<li><a href="./?pagina=financeiro/curadoria_projeto_listar&amp;id_projeto=<?= $rs->id_projeto; ?>">&laquo; Listar curadorias</a></li>
</ul>

<? //include("_financeiro/__projeto_abas.php"); ?>

<? include("_financeiro/__curadoria_abas.php"); ?>

<div class="parte50">
    &nbsp;
</div>
<div class="parte50">
    <br />
    <div class="parte25">
    	&nbsp;
    </div>
    <div class="parte25">
        <input <? if ($zoom=="1") echo " checked=\"checked\""; ?>type="radio" onclick="zoomPaginasCuradoria('<?=$id_projeto;?>', '<?=$id_curadoria;?>', '1');" name="zoom" id="zoom_1" value="1" />
        <label for="zoom_1" class="tamanho30">25%</label>
    </div>
    <div class="parte25">
        <input <? if ($zoom=="2") echo " checked=\"checked\""; ?>type="radio" onclick="zoomPaginasCuradoria('<?=$id_projeto;?>', '<?=$id_curadoria;?>', '2');" name="zoom" id="zoom_2" value="2" />
        <label for="zoom_2" class="tamanho30">50%</label>
    </div>
    <div class="parte25">
        <input <? if ($zoom=="3") echo " checked=\"checked\""; ?> type="radio" onclick="zoomPaginasCuradoria('<?=$id_projeto;?>', '<?=$id_curadoria;?>', '3');" name="zoom" id="zoom_100" value="3" />
        <label for="zoom_100" class="tamanho30">100%</label>
    </div>
</div>
<br /><br />

<?
$result_artistas= mysql_query("select * from curadorias_pessoas
								where id_curadoria = '". $id_curadoria ."'
								and   id_projeto = '". $id_projeto ."'
								order by ordem asc
								") or die(mysql_error());
								
while ($rs_artistas= mysql_fetch_object($result_artistas)) {
?>

<div class="form_alternativo">
	<fieldset class="discreto">
	    <legend><?= pega_pessoa($rs_artistas->id_pessoa); ?></legend>
    	
        <div class="enviados">
			<?
            $result_enviados= mysql_query("select * from curadoria_paginas
                                            where curadoria_paginas.tipo_pagina = '6'
                                            and   curadoria_paginas.id_projeto = '". $id_projeto ."'
                                            and   curadoria_paginas.id_curadoria = '". $id_curadoria ."'
											and   curadoria_paginas.id_artista = '". $rs_artistas->id_pessoa ."'
                                            order by curadoria_paginas.num_pagina asc
                                            ");
            
            $linhas_enviados= mysql_num_rows($result_enviados);
            
            if ($linhas_enviados==0) {
            ?>
            <div id="enviados_nenhum">Curadoria ainda n&atilde;o gerada.</div>
            <?
            }
            else {
                
                $nova_ordem=0;
                
                while ($rs_enviados= mysql_fetch_object($result_enviados)) {
                    
                    /*$result_index= mysql_query("update imagens set ordem = '". $nova_ordem ."'
                                                where id_imagem = '". $rs_enviados->id_imagem ."'
                                                "); */
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
            <div class="parte25 miniatura33 bloco_imagem <?=$div_nome;?>" id="linha_<?= $rs_enviados->id_curadoria_pagina; ?>">
                <div class="pagina_curadoria">
                	<?
					
					$result_enviados_imagens= mysql_query("select * from curadoria_paginas_imagens
															where id_curadoria_pagina= '". $rs_enviados->id_curadoria_pagina ."'
															order by id_curadoria_pagina_imagem asc
															");
					
					$i=1;
					
					while ($rs_enviados_imagens= mysql_fetch_object($result_enviados_imagens)) {
						
						if ($zoom==1) {
							switch ($rs_enviados_imagens->dimensao_imagem) {
								case "1":
									$largura=184;
									$altura=114;
								break;
								case "2":
									$largura=90;
									$altura=55;
								break;
								case "3":
									$largura=90;
									$altura=114;
								break;
							}
						}
						elseif ($zoom==2) {
							switch ($rs_enviados_imagens->dimensao_imagem) {
								case "1":
									$largura=368;
									$altura=228;
								break;
								case "2":
									$largura=180;
									$altura=110;
								break;
								case "3":
									$largura=180;
									$altura=228;
								break;
							}
						}
						elseif ($zoom==3) {
							switch ($rs_enviados_imagens->dimensao_imagem) {
								case "1":
									$largura=736;
									$altura=456;
								break;
								case "2":
									$largura=360;
									$altura=220;
								break;
								case "3":
									$largura=360;
									$altura=456;
								break;
							}
						}
						
						$classe_img= "img_". $rs_enviados_imagens->dimensao_imagem ."_". $i;
					?>
                    
                    <img class="<?=$classe_img;?> <?=$anterior;?>" src="includes/timthumb/timthumb.php?src=<?= CAMINHO_CDN ."a_". $rs_artistas->id_pessoa ."/". $rs_enviados_imagens->nome_arquivo; ?>&amp;w=<?=$largura;?>&amp;h=<?=$altura;?>&amp;zc=1&amp;q=90" width="<?=$largura;?>" height="<?=$altura;?>" border="0" alt="" />
                    
                    <? if ($i==1) $anterior= "anterior_". $rs_enviados_imagens->dimensao_imagem ."_". $i; $i++; } ?>
                </div>
                
                <? /*<img src="includes/phpthumb/phpThumb.php?src=../../<?= CAMINHO . $rs_enviados->tipo_imagem ."_". $rs_enviados->id_externo ."/". $rs_enviados->nome_arquivo; ?>&amp;w=215&amp;h=135&amp;zc=1" width="215" height="135" border="0" alt="" />
                
                <div class="imagem_dimensao imagem_dimensao2">
                    <?= pega_pessoa($rs_enviados->id_artista); ?>
                </div>*/ ?>
                
                <a class="imagem_del imagem_del2" href="javascript:apagaLinha('curadoriaPaginaExcluir', <?=$rs_enviados->id_curadoria_pagina;?>);" onclick="return confirm('Tem certeza que deseja excluir esta p&aacute;gina?');">
                    del
                </a>
                
                <input name="id_curadoria_pagina[]" type="hidden" id="id_curadoria_pagina_<?= $rs_enviados->id_curadoria_pagina; ?>" value="<?= $rs_enviados->id_curadoria_pagina; ?>" />
                
                <br />
                
            </div>
            <? } } ?>
        </div>
        
	</fieldset>
</div>

<? } ?>

<center>
    <button type="button" id="enviar" onclick="window.top.location.href='./?pagina=financeiro/curadoria_passo3&id_projeto=<?=$id_projeto;?>&id_curadoria=<?=$id_curadoria;?>';">Avan&ccedil;ar &raquo;</button>
</center>

<? } ?>