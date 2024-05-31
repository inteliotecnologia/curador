<?
require_once("includes/conexao.php");
require_once("includes/funcoes.php");

if (pode("v", $_SESSION["permissao"])) {
	
	$_NOTAS_DA_CURADORIA["pt"]= "NOTAS DA CURADORIA";
	$_NOTAS_DA_CURADORIA["en"]= "CURATORSHIP NOTES";
	
	$_PUBLICACOES["pt"]= "PUBLICAÇÕES";
	$_PUBLICACOES["en"]= "PUBLICATIONS";
	
	$_EXPOSICOES["pt"]= "EXPOSIÇÕES";
	$_EXPOSICOES["en"]= "EXHIBITIONS";
	
	$_INDIVIDUAL["pt"]= "INDIVIDUAL";
	$_INDIVIDUAL["en"]= "INDIVIDUAL";
	
	$_COLETIVA["pt"]= "COLETIVA";
	$_COLETIVA["en"]= "IN GROUP";
	
	$result= mysql_query("select * from  projetos, curadorias
							where curadorias.id_curadoria = '". $_GET["id_curadoria"] ."'
							and   curadorias.id_projeto = projetos.id_projeto
							and   curadorias.id_empresa = '". $_SESSION["id_empresa"] ."'
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	
	$l= $rs->lingua_preferencial;
	
	define('FPDF_FONTPATH','includes/fpdf/fonts/');
	require("includes/fpdf/fpdf.php");
	
	$larguras[0]= 18.75;
	$larguras[1]= 29.7;
	
	$pdf=new FPDF("L", "cm", $larguras);
	$pdf->SetMargins(0, 0, 0);
	$pdf->SetAutoPageBreak(false, 0);
	$pdf->SetFillColor(0, 0, 0);
	$pdf->AddFont('conduitc');
	$pdf->AddFont('conduitcbla');
	$pdf->AddFont('conduitcextbol');
	$pdf->AddFont('conduitcita');
	$pdf->AddFont('conduitextlig');
	
	$pdf->AddPage();
	$pdf->Rect(0, 0, 29.8, 18.85, 'F');
	$pdf->Image("images/logo_move_pdf.jpg", 11.098, 7.503, 7.503, 3.845, 0);
	$pdf->SetTextColor(255, 255, 255);
	
	//-----------------------
	
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
		
		
		$pdf->AddPage();
		$pdf->Rect(0, 0, 29.8, 18.85, 'F');
		//$pdf->Image("images/pdf_bg.jpg", 0, 0, 29.8, 3.517, 0);
		//$pdf->Rect(0, 3.517, 29.8, 1.719, 'F');
		
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
		
		$pdf->SetY(2);
		$pdf->SetFont('conduitc', '', 74);
		$pdf->Cell(27.7, 1.8, strtoupper($nome_artista_linha_1), 0, 1, 'R');
		$pdf->Cell(27.7, 1.8, strtoupper($nome_artista_linha_2), 0, 1, 'R');
		
		if (pode('2', $rs->mostrar)) {
			$pdf->SetFont('conduitc', '', 20);
			$pdf->Cell(27.7, 2.5, $rs_artistas->cidade_uf, 0, 1, 'R');
		}
		
		if (pode('1', $rs->mostrar)) {
			$pdf->SetX(14);
			$pdf->SetFont('conduitc', '', 15);
			$pdf->MultiCell(13.7, 0.6, $rs_artistas->release1, 0, 'R');
		}
		
		
		if (pode('34', $rs->mostrar)) {
			
			if (($rs_artistas->exposicoes_individuais!="") || ($rs_artistas->exposicoes_coletivas!="")) {
				
				$pdf->AddPage();
				$pdf->Rect(0, 0, 29.8, 18.85, 'F');
				
				$pdf->SetY(2);
				$pdf->SetFont('conduitc', '', 30);
				$pdf->Cell(27.7, 0.6, utf8_decode($_EXPOSICOES[$l]), 0, 1, 'R');
				
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
					
						$pdf->SetFont('conduitc', '', 15);
						$pdf->SetXY(2+($coluna*6.425),4.5);
						
						$pdf->Cell(6.425, 1.6, $titulo, 0, 1, 'R');
						
						$pdf->SetXY(2+($coluna*6.425),6.5);
						
						//$pdf->SetFillColor(rand(0,200), rand(0,200), rand(0,200));
						
						
						
						for ($i=0; $i<count($exposicoes); $i++) {
							$j= $i+1;
							$posicao_x= 2+($coluna*6.425);
							
							$pdf->SetX($posicao_x);
							$pdf->MultiCell(6.425, 0.6, trim($exposicoes[$i]) ."\n\n\r", 0, 'R', 1);
							
							if (($i>0) && ($j%5)==0) {
								$coluna++;
								$pdf->SetY(6.5);
							}
						}
						
						$coluna++;
					}//fim permissao_exposicoes
				}
				
			}
		}//fim pode
		
		if (pode('5', $rs->mostrar)) {
		
			if ($rs_artistas->publicacoes!="") {
				
				$pdf->AddPage();
				$pdf->SetFillColor(0, 0, 0);
				$pdf->Rect(0, 0, 29.8, 18.85, 'F');
				
				$pdf->SetY(2);
				$pdf->SetFont('conduitc', '', 30);
				$pdf->Cell(27.7, 0.6, utf8_decode($_PUBLICACOES[$l]), 0, 1, 'R');
				
				$coluna=0;
				
				//for ($e=0; $e<2; $e++) {
					$publicacoes= $rs_artistas->publicacoes;
					$publicacoes= explode("\n\r", $publicacoes);
					$publicacoes_count= count($publicacoes);
					
					if ($publicacoes_count<=15) $coluna=1;
					elseif ($publicacoes_count<=10) $coluna=2;
					elseif ($publicacoes_count<=5) $coluna=3;
					
					$pdf->SetY(6.5);
					$pdf->SetFont('conduitc', '', 15);
					//$pdf->SetFillColor(rand(0,200), rand(0,200), rand(0,200));
					
					for ($i=0; $i<$publicacoes_count; $i++) {
						$j= $i+1;
						$posicao_x= 2+($coluna*6.425);
						
						$pdf->SetX($posicao_x);
						$pdf->MultiCell(6.425, 0.6, trim($publicacoes[$i]) ."\n\n\r", 0, 'R', 1);
						
						if (($i>0) && ($j%5)==0) {
							$coluna++;
							$pdf->SetY(6.5);
						}
					}
					
				//}
				
			}
		}
		
		if ($rs_artistas->notas!="") {
			$pdf->AddPage();
			$pdf->SetFillColor(0, 0, 0);
			$pdf->Rect(0, 0, 29.8, 18.85, 'F');
			
			$pdf->SetY(2);
			$pdf->SetFont('conduitc', '', 30);
			$pdf->Cell(27.7, 0.6, utf8_decode($_NOTAS_DA_CURADORIA[$l]), 0, 1, 'R');
			
			$pdf->SetXY(14, 4.5);
		
			$pdf->SetFont('conduitc', '', 15);
			
			$pdf->MultiCell(13.7, 0.6, $rs_artistas->notas, 0, 'R');
			
		}
		
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
				
				//									  sem zerar aqui, esta posição já está marcada como incompatível, desiste de achar uma imagem compatível e inicia nova página com as disponíveis
				if (($j==0) || ($nesta_pagina==4) || ($vetor[$v][3]==1)) {
					$j=0;
					$nesta_pagina=0;
					
					$pdf->AddPage();
					
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
					
					$pdf->SetFillColor(0, 0, 0);
					$pdf->Image("uploads/a_". $rs_artistas->id_pessoa ."/". $vetor[$v][2], $x, $y, $largura, $altura, 0);
					
					//mostra o nome do artista
					if ($j==0) {
						
					}
					
					switch($vetor[$v][1]) {
						//paisagem grande
						case 1:
							$x= cpc(150);
							$y= cpc(100);
							
							$pdf->Rect(cpc(1485), cpc(1028), cpc(900), cpc(48), 'F');
							$pdf->SetXY(cpc(1502), cpc(1026));
							$pdf->SetFont('conduitc', '', '12');
							$pdf->SetTextColor(255, 255, 255);
							$pdf->Cell(0, 0.8, strtoupper($rs_artistas->apelido_fantasia), 0, 1);
							
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
								
								$pdf->Rect(cpc(1485), cpc(1028), cpc(900), cpc(48), 'F');
								$pdf->SetXY(cpc(1502), cpc(1026));
								$pdf->SetFont('conduitc', '', '12');
								$pdf->SetTextColor(255, 255, 255);
								$pdf->Cell(0, 0.8, strtoupper($rs_artistas->apelido_fantasia), 0, 1);
								
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
								
								$pdf->Rect(cpc(1485), cpc(1028), cpc(900), cpc(48), 'F');
								$pdf->SetXY(cpc(1502), cpc(1026));
								$pdf->SetFont('conduitc', '', '12');
								$pdf->SetTextColor(255, 255, 255);
								$pdf->Cell(0, 0.8, strtoupper($rs_artistas->apelido_fantasia), 0, 1);
								
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
		
		//if ($_GET["mostra"]==1) echo "<br /><br /> negados: <br />";
		
	}//fim while artistas
	
	//-----------------------
	
	$result_atualiza= mysql_query("update curadorias
									set data_curadoria_mod= '". date("Y-m-d") ."',
									hora_curadoria_mod= '". date("H:i:s") ."',
									id_usuario_mod= '". $_SESSION["id_usuario"] ."'
									where id_curadoria = '". $_GET["id_curadoria"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									") or die(mysql_error());
	
	$pdf->AddPage();
	$pdf->Rect(0, 0, 29.8, 18.85, 'F');
	$pdf->Image("images/logo_move_pdf.jpg", 11.098, 7.503, 7.503, 3.845, 0);
	
	$pdf->Output("uploads/curadorias/curadoria_". $rs->auth .".pdf", "F");
	$pdf->Output("curadoria_". $rs->auth .".pdf", "D");
	
	//if (file_exists("uploads/curadorias/curadoria_". $rs->auth .".pdf")) echo "Clique <a href='uploads/curadorias/curadoria_". $rs->auth .".pdf' target='_blank'>aqui</a> para baixar o PDF.";
	//else echo "Não foi possível gerar a curadoria, tente novamente!";
	
	//$pdf->Output("curadoria_". $rs->auth .".pdf", "I");
	
	
	
}
?>