<?
require_once("includes/conexao.php");
require_once("includes/funcoes.php");

ini_set("memory_limit", "512M");

if (pode("v", $_SESSION["permissao"])) {
	
	$_NOTAS_DA_CURADORIA["pt"]= "NOTAS DA CURADORIA";
	$_NOTAS_DA_CURADORIA["en"]= "CURATORSHIP NOTES";
	
	$_PUBLICACOES["pt"]= "PUBLICAÇÕES";
	$_PUBLICACOES["en"]= "PUBLICATIONS";
	
	$_EXPOSICOES["pt"]= "EXPOSIÇÕES";
	$_EXPOSICOES["en"]= "EXHIBITIONS";
	
	$_INDIVIDUAL["pt"]= "INDIVIDUAL";
	$_INDIVIDUAL["en"]= "SOLO";
	
	$_COLETIVA["pt"]= "COLETIVA";
	$_COLETIVA["en"]= "GROUP SHOWS";
	
	$tamanho_curadoria=0;
	
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
	//$pdf->AddFont('conduitc');
	//$pdf->AddFont('conduitcbla');
	//$pdf->AddFont('conduitcextbol');
	//$pdf->AddFont('conduitcita');
	//$pdf->AddFont('conduitextlig');
	//$pdf->AddFont('HelveticaBoldCondensedPlain', '', 'HelveticaBoldCondensedPlain.php');
	$pdf->AddFont('RobotoCondensedBold', '', 'RobotoCondensed-Bold.php');
	
	$pdf->SetAuthor('intelio');
	$pdf->SetCreator(VERSAO);
	$pdf->SetSubject('Curadoria #'. $rs->id_curadoria);
	//$pdf->SetSubject('Curadoria #'. $rs->id_curadoria);
	
	$result_teste= mysql_query("select * from curadoria_paginas
								where id_curadoria = '". $_GET["id_curadoria"] ."'
								and   tipo_pagina = '1'
								");
	$linhas_teste= mysql_num_rows($result_teste);
	
	if ( (($_GET["parte"]=="") || ($_GET["parte"]=="0")) && ($linhas_teste>0) && (pode('0', $rs->mostrar))) {
		$pdf->AddPage();
		$pdf->Rect(0, 0, 29.8, 18.85, 'F');
		$pdf->Image("images/logo_norte_pdf.jpg", 10.800, 5.400, 8.000, 7.620, 0);
	}
	
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
	
	$num= 15;
	
	$total = mysql_num_rows($result_artistas);
	$num_paginas = ceil($total/$num);
	
	if ($_GET["parte"]=="") $parte= 0;
	else $parte= $_GET["parte"];
	
	$parte_curadoria= $parte+1;
	
	$inicio= $parte*$num;
	
	$teste_folha_rosto= ($parte+1)*$num;
	
	if ($teste_folha_rosto>=$total) $mostra_ultima=1;
	else $mostra_ultima=0;
	
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
									
									limit $inicio, $num
									");
	
	

	$a=0;
	while ($rs_artistas= mysql_fetch_object($result_artistas)) {
		
		$tamanho_artista[]=0;
		
		$result_teste= mysql_query("select * from curadoria_paginas
									where id_curadoria = '". $_GET["id_curadoria"] ."'
									and   tipo_pagina = '2'
									and   id_artista = '". $rs_artistas->id_pessoa ."'
									");
		$linhas_teste= mysql_num_rows($result_teste);
		
		if ($linhas_teste>0) {
			
			if (pode('7', $rs->mostrar)) {
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
				//$pdf->SetFont('conduitc', '', 70);
				$pdf->SetFont('RobotoCondensedBold', '', 70);
				
				$pdf->Cell(27.7, 2.05, strtoupper(iconv('utf-8', 'cp1252', $nome_artista_linha_1)), 0, 1, 'R');
				$pdf->Cell(27.7, 2.05, strtoupper(iconv('utf-8', 'cp1252', $nome_artista_linha_2)), 0, 1, 'R');
				
				if (pode('2', $rs->mostrar)) {
					$pdf->SetY(5.3);
					//$pdf->SetFont('conduitcextbol', '', 19);
					$pdf->SetFont('RobotoCondensedBold', '', 19);
					$pdf->Cell(27.7, 2.5, strtoupper(iconv('utf-8', 'cp1252', $rs_artistas->cidade_uf)), 0, 1, 'R');
				}
				
				if (pode('1', $rs->mostrar)) {
					$pdf->SetXY(14, 7.5);
					//$pdf->SetFont('conduitc', '', 15);
					$pdf->SetFont('RobotoCondensedBold', '', 15);
					$pdf->MultiCell(13.7, 0.6, $rs_artistas->release1, 0, 'R');
				}
			}
		}//fim folha de rosto
		
		$result_teste= mysql_query("select * from curadoria_paginas
									where id_curadoria = '". $_GET["id_curadoria"] ."'
									and   tipo_pagina = '3'
									and   id_artista = '". $rs_artistas->id_pessoa ."'
									");
		$linhas_teste= mysql_num_rows($result_teste);
		
		if ($linhas_teste>0) {
		
			if (pode('34', $rs->mostrar)) {
				
				if (($rs_artistas->exposicoes_individuais!="") || ($rs_artistas->exposicoes_coletivas!="")) {
					
					$pdf->AddPage();
					$pdf->Rect(0, 0, 29.8, 18.85, 'F');
					
					$pdf->SetY(2);
					//$pdf->SetFont('conduitc', '', 30);
					$pdf->SetFont('RobotoCondensedBold', '', 30);
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
							
							$coluna--;
						}
						
						$exposicoes= explode("\n\r", $exposicoes);
						
						if (pode($permissao_exposicoes, $rs->mostrar)) {
						
							//$pdf->SetFont('conduitc', '', 15);
							$pdf->SetFont('RobotoCondensedBold', '', 15);
							$pdf->SetXY(2+($coluna*6.425), 3.5);
							
							$pdf->Cell(6.425, 1.6, $titulo, 0, 1, 'R');
							
							$pdf->SetXY(2+($coluna*6.425), 5.5);
							
							//$pdf->SetFillColor(rand(0,200), rand(0,200), rand(0,200));
							
							for ($i=0; $i<count($exposicoes); $i++) {
								
								if ($coluna==4) {
									
									$pdf->AddPage();
									$pdf->SetFillColor(0, 0, 0);
									$pdf->Rect(0, 0, 29.8, 18.85, 'F');
									
									$pdf->SetY(2);
									//$pdf->SetFont('conduitc', '', 30);
									$pdf->SetFont('RobotoCondensedBold', '', 30);
									$pdf->Cell(27.7, 0.6, utf8_decode($_EXPOSICOES[$l]), 0, 1, 'R');
									
									//$pdf->SetFont('conduitc', '', 15);
									$pdf->SetFont('RobotoCondensedBold', '', 15);
									$pdf->SetXY(2+($coluna*6.425), 3.5);
							
									$pdf->Cell(6.425, 1.6, $titulo, 0, 1, 'R');
									
									$pdf->SetXY(2+($coluna*6.425), 5.5);
									
									//$pdf->SetFillColor(rand(0,200), rand(0,200), rand(0,200));
									
									$coluna=0;
								}
								
								$j= $i+1;
								$posicao_x= 2+($coluna*6.425);
								
								$pdf->SetX($posicao_x);
								$pdf->MultiCell(6.425, 0.6, trim($exposicoes[$i]) ."\n\n\r", 0, 'R', 1);
								
								if (($i>0) && ($j%4)==0) {
									$coluna++;
									$pdf->SetY(5.5);
								}
							}
							
							$coluna++;
						}//fim permissao_exposicoes
					}
					
				}
			}//fim pode
		}
		
		$result_teste= mysql_query("select * from curadoria_paginas
									where id_curadoria = '". $_GET["id_curadoria"] ."'
									and   tipo_pagina = '4'
									and   id_artista = '". $rs_artistas->id_pessoa ."'
									");
		$linhas_teste= mysql_num_rows($result_teste);
		
		if ($linhas_teste>0) {
			
			if (pode('5', $rs->mostrar)) {
			
				if ($rs_artistas->publicacoes!="") {
					
					$pdf->AddPage();
					$pdf->SetFillColor(0, 0, 0);
					$pdf->Rect(0, 0, 29.8, 18.85, 'F');
					
					$pdf->SetY(2);
					//$pdf->SetFont('conduitc', '', 30);
					$pdf->SetFont('RobotoCondensedBold', '', 30);
					$pdf->Cell(27.7, 0.6, utf8_decode($_PUBLICACOES[$l]), 0, 1, 'R');
					
					$coluna=0;
					
					//for ($e=0; $e<2; $e++) {
						$publicacoes= $rs_artistas->publicacoes;
						$publicacoes= explode("\r", $publicacoes);
						$publicacoes_count= count($publicacoes);
						
						if ($publicacoes_count<=15) $coluna=1;
						elseif ($publicacoes_count<=10) $coluna=2;
						elseif ($publicacoes_count<=5) $coluna=3;
						
						$pdf->SetY(5.5);
						//$pdf->SetFont('conduitc', '', 15);
						$pdf->SetFont('RobotoCondensedBold', '', 15);
						//$pdf->SetFillColor(rand(0,200), rand(0,200), rand(0,200));
						
						for ($i=0; $i<$publicacoes_count; $i++) {
							
							if ($coluna==2) {
									
								$pdf->AddPage();
								$pdf->SetFillColor(0, 0, 0);
								$pdf->Rect(0, 0, 29.8, 18.85, 'F');
								
								$pdf->SetY(2);
								//$pdf->SetFont('conduitc', '', 30);
								$pdf->SetFont('RobotoCondensedBold', '', 30);
								$pdf->Cell(27.7, 0.6, utf8_decode($_PUBLICACOES[$l]), 0, 1, 'R');
								
								//$pdf->SetFillColor(rand(0,200), rand(0,200), rand(0,200));
								
								$pdf->SetY(5.5);
								//$pdf->SetFont('conduitc', '', 15);
								$pdf->SetFont('RobotoCondensedBold', '', 15);
								
								$coluna=0;
							}
							
							$j= $i+1;
							$posicao_x= 2+($coluna*13.048);
							
							$pdf->SetX($posicao_x);
							$pdf->MultiCell(13.048, 0.6, trim($publicacoes[$i]) ."\n\r", 0, 'R', 1);
							
							if (($i>0) && ($j%15)==0) {
								$coluna++;
								$pdf->SetY(5.5);
							}
						}
						
					//}
					
				}
			}
		}
		
		$result_teste= mysql_query("select * from curadoria_paginas
									where id_curadoria = '". $_GET["id_curadoria"] ."'
									and   tipo_pagina = '5'
									and   id_artista = '". $rs_artistas->id_pessoa ."'
									");
		$linhas_teste= mysql_num_rows($result_teste);
		
		if ($linhas_teste>0) {
				
			if ($rs_artistas->notas!="") {
				$pdf->AddPage();
				$pdf->SetFillColor(0, 0, 0);
				$pdf->Rect(0, 0, 29.8, 18.85, 'F');
				
				$pdf->SetY(2);
				//$pdf->SetFont('conduitc', '', 30);
				$pdf->SetFont('RobotoCondensedBold', '', 30);
				$pdf->Cell(27.7, 0.6, utf8_decode($_NOTAS_DA_CURADORIA[$l]), 0, 1, 'R');
				
				$pdf->SetXY(14, 4.5);
			
				//$pdf->SetFont('conduitc', '', 15);
				$pdf->SetFont('RobotoCondensedBold', '', 15);
				
				$pdf->MultiCell(13.7, 0.6, utf8_decode($rs_artistas->notas), 0, 'R');
				
			}
		}
		
		// ----- imagens
		
		/*
		$result_imagem= mysql_query("select * from curadorias_pessoas_imagens, imagens
										where curadorias_pessoas_imagens.id_imagem = imagens.id_imagem
										and   curadorias_pessoas_imagens.id_projeto = '". $rs->id_projeto ."'
										and   curadorias_pessoas_imagens.id_curadoria = '". $rs->id_curadoria ."'
										and   curadorias_pessoas_imagens.id_pessoa = '". $rs_artistas->id_pessoa ."'
										order by imagens.ordem_curadoria asc, imagens.ordem asc
										");
		*/

		$result_imagem= mysql_query("select * from curadoria_paginas, curadoria_paginas_imagens
										where curadoria_paginas.id_curadoria_pagina = curadoria_paginas_imagens.id_curadoria_pagina
										and   curadoria_paginas.tipo_pagina = '6'
										and   curadoria_paginas.id_projeto = '". $rs->id_projeto ."'
										and   curadoria_paginas.id_curadoria = '". $rs->id_curadoria ."'
										and   curadoria_paginas.id_artista = '". $rs_artistas->id_pessoa ."'
										order by curadoria_paginas.num_pagina asc, curadoria_paginas_imagens.id_curadoria_pagina_imagem asc
										");
		
		$vetor= array();
		
		//[0][0]= usado
		//[0][1]= dimensao_imagem
		//[0][2]= nome_arquivo
		
		$num_pagina2[0]=-1;
		
		$i=1;
		$j=1;
		while ($rs_imagem= mysql_fetch_object($result_imagem)) {
			/*$vetor[$i][0]=0;
			$vetor[$i][1]= $rs_imagem->dimensao_imagem;
			$vetor[$i][2]= $rs_imagem->nome_arquivo;
			$vetor[$i][3]=0;
			*/
			
			//echo $i ." ". $j ." ". $rs_imagem->num_pagina ." ". $rs_imagem->dimensao_imagem ." ". $rs_imagem->nome_arquivo ."<br />";
			
			
			
			$num_pagina2[$j]= $rs_imagem->num_pagina;
			
			$k=$j-1;
			
			//echo $j ." ". $num_pagina2[$j] ." ". $num_pagina2[$k] ."<br />";
			
			if ($num_pagina2[$j]!=$num_pagina2[$k]) {
				$pdf->AddPage();
				$i=1;
				
				//if ($j==18) $pdf->AddPage(); //echo "MWMWMWMWMWMWMW";
				
				//echo $rs_imagem->dimensao_imagem ."<br /><br />";
			}
			
			$imagem_fixada[$i]= $rs_imagem->dimensao_imagem;
			
			
			
			switch ($rs_imagem->dimensao_imagem) {
				case "1":
					$x= cpc(150);
					$y= cpc(100);
					
					
				break;
				case "2":
					
					if ($imagem_fixada[1]=="3") {
						switch ($i) {
							case "2":
							$x= cpc(975);
							$y= cpc(100);
							break;
							case "3":
							$x= cpc(975);
							$y= cpc(625);
							break;
						}
					}
					else {
						switch ($i) {
							case "1":
							$x= cpc(150);
							$y= cpc(100);
							break;
							case "2":
							$x= cpc(150);
							$y= cpc(625);
							break;
							case "3":
							$x= cpc(975);
							$y= cpc(100);
							break;
							case "4":
							$x= cpc(975);
							$y= cpc(625);
							break;
						}
					}
					
				break;
				case "3":
					if (($imagem_fixada[1]=="2") && ($imagem_fixada[2]=="2")) {
						switch ($i) {
							case "3":
							$x= cpc(975);
							$y= cpc(100);
							break;
						}
					}
					else {
						switch ($i) {
							case "1":
							$x= cpc(150);
							$y= cpc(100);
							break;
							case "2":
							$x= cpc(975);
							$y= cpc(100);
							break;
						}
					}
				break;
				
			}
			
			$largura= pega_largura_padrao('l', $rs_imagem->dimensao_imagem);
			$altura= pega_largura_padrao('a', $rs_imagem->dimensao_imagem);
			
			$arquivo_imagem= CAMINHO_CDN. "a_". $rs_artistas->id_pessoa ."/". $rs_imagem->nome_arquivo;
			
			$arquivo_imagem_tamanho= 0;//filesize($arquivo_imagem);
			
			$tamanho_curadoria+=$arquivo_imagem_tamanho;
			$tamanho_artista[$a]+=$arquivo_imagem_tamanho;
			
			//if (file_exists($arquivo_imagem)) {
			//	if (isset($_GET['diagnostico'])) {
			//		echo "<strong>". $arquivo_imagem ."</strong> -> ". format_bytes($arquivo_imagem_tamanho) ."<br />";
			//	}
			//	else {
					$pdf->Image($arquivo_imagem, $x, $y, $largura, $altura, 0);
			//	}
			//}
			
			if (pode('7', $rs->mostrar)) {
				$pdf->Rect(cpc(1485), cpc(1028), cpc(900), cpc(48), 'F');
				$pdf->SetXY(cpc(1502), cpc(1026));
				
				//$pdf->SetFont('conduitc', '', '8');
				$pdf->SetFont('RobotoCondensedBold', '', 8);
				
				$pdf->SetTextColor(255, 255, 255);
				$pdf->Cell(0, 0.8, strtoupper(iconv('utf-8', 'cp1252', $rs_artistas->apelido_fantasia)), 0, 1);
			}
			
			$i++;
			$j++;
		}
		
		if (isset($_GET['diagnostico'])) {
			echo "<br /><strong>Total artista:</strong> ". format_bytes($tamanho_artista[$a]) ."<br /><br />";
		}
		
		/*
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
		
		*/
		
		//if ($_GET["mostra"]==1) echo "<br /><br /> negados: <br />";
		
		$a++;
		
	}//fim while artistas
	
	if (isset($_GET['diagnostico'])) {
		echo "<br /><strong>Total curadoria:</strong> ". format_bytes($tamanho_curadoria) ."<br /><br />";
	}
	
	//-----------------------
	
	/*$result_atualiza= mysql_query("update curadorias
									set data_curadoria_mod= '". date("Y-m-d") ."',
									hora_curadoria_mod= '". date("H:i:s") ."',
									id_usuario_mod= '". $_SESSION["id_usuario"] ."'
									where id_curadoria = '". $_GET["id_curadoria"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									") or die(mysql_error());
	*/
	
	if (($mostra_ultima) && (pode('0', $rs->mostrar)) ) {
		$pdf->AddPage();
		$pdf->Rect(0, 0, 29.8, 18.85, 'F');
		//$pdf->Image("images/logo_move_pdf.jpg", 11.098, 7.503, 7.503, 3.845, 0);
		$pdf->Image("images/logo_norte_pdf.jpg", 10.800, 5.400, 8.000, 7.620, 0);
	}
	
	$pdf->Output("uploads/curadorias/curadoria_". $rs->auth ."_". $parte_curadoria .".pdf", "F");
	
	//$pdf->Output("curadoria_". $rs->auth .".pdf", "D");
	
	if (file_exists("uploads/curadorias/curadoria_". $rs->auth ."_". $parte_curadoria .".pdf")) {
		
		//Envia para S3
		if (!class_exists('S3')) require_once 'includes/amazon-s3-php-class/S3.php';
		
		// Check for CURL
		if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
			exit("\nERROR: CURL extension not loaded\n\n");
		
		$s3 = new S3(S3_KEY, S3_SECRET);
		
		$bucket = "nortecurador-site";
		
		$envia_s3= $s3->putObjectFile("uploads/curadorias/curadoria_". $rs->auth ."_". $parte_curadoria .".pdf", $bucket, BUCKET_CURADOR . "curadorias/curadoria_". $rs->auth ."_". $parte_curadoria .".pdf", S3::ACL_PUBLIC_READ);
		
		@unlink("uploads/curadorias/curadoria_". $rs->auth ."_". $parte_curadoria .".pdf");
		
		//header('Content-disposition: attachment; filename=uploads/curadorias/curadoria_'. $rs->auth .'_'. $parte_curadoria .'.pdf');
		//header('Content-type: application/pdf');
		//readfile('uploads/curadorias/curadoria_'. $rs->auth .'_'. $parte_curadoria .'.pdf');
	
		echo "Clique <a href='". CAMINHO_CDN . "curadorias/curadoria_". $rs->auth ."_". $parte_curadoria .".pdf' target='_blank'>aqui</a> para baixar o PDF.";
	}
	else echo "Não foi possível gerar a curadoria, tente novamente!";
	
	//$pdf->Output("curadoria_". $rs->auth .".pdf", "I");
	
	
	
}
?>