<?
require_once("includes/funcoes.php");

if (!$conexao)
	require_once("includes/conexao.unico.php");

require_once("language.inc");



header("Content-type: text/html; charset=utf-8", true);
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");



/*echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"
			\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
			<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
			<head>
			<title></title>
			</head>
			<body>";
*/
// ############################################### TODOS ###############################################

//r, modo, inicio, total_por_pagina, tags

if ($_POST["chamada"]=="buscaSugerida") {
	
	// Is there a posted query string?
	if(isset($_POST['busca'])) {
		$busca= addslashes($_POST['busca']);
		$r= $_POST["r"];
		
		// Is the string length greater than 0?
		if(strlen($busca)>0) {
			
			echo "<ul id=\"resultado\">";
			
			$result_artista= mysql_query("select *, texto_site_". $l ." as texto_site from pessoas, pessoas_tipos
											where pessoas.id_pessoa = pessoas_tipos.id_pessoa
											and   pessoas_tipos.tipo_pessoa = 'r'
											and   pessoas_tipos.status_pessoa <> '2'
											and   pessoas.site = '1'
											and   (
													( pessoas.apelido_fantasia like '%". $busca ."%' )
													or
													( pessoas.tags_site_". $l ." like '%". $busca ."%' )
													or
													( pessoas.texto_site_". $l ." like '%". $busca ."%' )
												  )
											order by pessoas.apelido_fantasia asc limit 5 ") or die(mysql_error());
			
			$linhas_artista= mysql_num_rows($result_artista);
			
			if ($linhas_artista>0) {
				
				echo '<li class="category">'. $_MENU_ARTISTS[$l] .'</li>';
				
				$i=1;
				while ($rs_artista= mysql_fetch_object($result_artista)) {
					
					$imagem_miniatura= pega_imagem_miniatura_site('a', $rs_artista->id_pessoa, 4);
						
					if ($imagem_miniatura!="") $imagem_definitiva= $imagem_miniatura;
					else {
						$imagem_miniatura= pega_imagem_miniatura_site('a', $rs_artista->id_pessoa, 1);
					}
					
					if ($linhas_artista==$i) $classe= " ultima";
					else $classe= "";
					
					echo '<li class="item '. $classe .'"><a href="'. $r .'artist/'. $rs_artista->url .'">';
					echo '<img width="32" height="26" src="'. BUCKET . BUCKET_SITE .'artista_'. $rs_artista->id_pessoa .'/'. $imagem_definitiva .'" alt="" />';
					echo '<span class="resultado-titulo">'. string_maior_que($rs_artista->apelido_fantasia, 30) .'</span></a></li>';
					//echo '<span>'. string_maior_que($rs_artista->texto_site, 90) .'</span>';
					
					$i++;
				}
				
			}
			
			$result_projeto= mysql_query("select *, projeto_". $l ." as projeto, texto_site_". $l ." as texto_site from projetos
											where projetos.site = '1'
											and   projetos.status_projeto <> '0'
											and   (
													(projetos.id_cliente in
														(
														select pessoas.id_pessoa as id_cliente from pessoas, pessoas_tipos
														where pessoas.id_pessoa = pessoas_tipos.id_pessoa
														and   pessoas_tipos.tipo_pessoa = 'c'
														and   pessoas.apelido_fantasia like '%". $busca ."%'
														)
												  	 )
												   	 or
												   	(projetos.id_agencia in
														(
														select pessoas.id_pessoa as id_cliente from pessoas, pessoas_tipos
														where pessoas.id_pessoa = pessoas_tipos.id_pessoa
														and   pessoas_tipos.tipo_pessoa = 'g'
														and   pessoas.apelido_fantasia like '%". $busca ."%'
														)
												  	 )
													 or
													 ( projetos.projeto_". $l ." like '%". $busca ."%' )
													 or
													 ( projetos.tags_site_". $l ." like '%". $busca ."%' )
													 or
													 ( projetos.texto_site_". $l ." like '%". $busca ."%' )
												   )
											order by projetos.projeto_". $l ." asc limit 5 ") or die(mysql_error());
			
			$linhas_projeto= mysql_num_rows($result_projeto);
			
			if ($linhas_projeto>0) {
				
				echo '<li class="category">'. $_MENU_SELECTED[$l] .'</li>';
				
				$i=1;
				while ($rs_projeto= mysql_fetch_object($result_projeto)) {
					
					$imagem_miniatura= pega_imagem_miniatura_site('p', $rs_projeto->id_projeto, 4);
						
					if ($imagem_miniatura!="") $imagem_definitiva= $imagem_miniatura;
					else {
						$imagem_miniatura= pega_imagem_miniatura_site('p', $rs_projeto->id_projeto, 1);
					}
					//else {
					//	$imagem= pega_imagem('p', $rs->id_projeto);
					//	$imagem_definitiva= $imagem;
					//}
					
					if ($linhas_projeto==$i) $classe= " ultima";
					else $classe= "";
					
					echo '<li class="item '. $classe .'"><a href="'. $r .'work/'. $rs_projeto->url .'">';
					echo '<img width="32" height="26" src="'. BUCKET . BUCKET_SITE .'projeto_'. $rs_projeto->id_projeto .'/'. $imagem_definitiva .'" alt="" />';
					echo '<span class="resultado-titulo">'. string_maior_que($rs_projeto->projeto, 30) .'</span></a></li>';
					//echo '<span>'. string_maior_que($rs_projeto->texto_site, 90) .'</span>';
					
					$i++;
				}
				
			}
			
			if (($linhas_artista==0) && ($linhas_projeto==0)) echo '<li class="separator">Nada encontrado.</li>';
			
			echo "</ul>";
			
		} else {
			// Dont do anything.
		} // There is a queryString.
	} else {
		echo 'There should be no direct access to this script!';
	}
	
}

if ($_GET["chamada"]=="carregaDinamicoWorks") {
	
	$inicio= $_GET["inicio"];
	$r= $_GET["r"];
	$total_por_pagina= $_GET["total_por_pagina"];
	
	$proximo= $inicio+$total_por_pagina;
	
	if ($_GET["tags"]!="") $str.= "and   tags like '%". $url[3] ."%' ";
	
	$tipo_projeto= $_GET["tipo_projeto"];
	
	if ($tipo_projeto=="selecionado") {
		$ordem= "ordem asc";
		$str.= "and   selecionado = '1'";
	}
	else {
		$ordem= "ordem asc";
		$str.= "and   selecionado <> '1'";
	}
	
	$result= mysql_query("select *, projeto_". $l ." as projeto, texto_site_". $l ." as texto_site from projetos
							where site = '1'
							". $str ."
							and   status_projeto <> '0'
							order by $ordem
							limit ". $inicio .", ". $total_por_pagina ."
							");
	$linhas= mysql_num_rows($result);
	
	$inicio_limite_teste= $inicio+$total_por_pagina;
	
	$result_teste= mysql_query("select *, projeto_". $l ." as projeto, texto_site_". $l ." as texto_site from projetos
								where site = '1'
								". $str ."
								and   status_projeto <> '0'
								order by $ordem
								limit ". $inicio_limite_teste .", ". $total_por_pagina ."
								");
	$linhas_teste= mysql_num_rows($result_teste);
	
	$i=1;
	while ($rs= mysql_fetch_object($result)) {
		
		$imagem_miniatura= pega_imagem_miniatura_site('p', $rs->id_projeto, $_GET["modo"]);
		
		if ($imagem_miniatura!="") $imagem_definitiva= $imagem_miniatura;
		else {
			$imagem= pega_imagem('p', $rs->id_projeto);
			$imagem_definitiva= $imagem;
		}
		
		if (($i%3)==0) $classe= "nope";
		else $classe= "";
		
		switch ($_GET["modo"]) {
			
			case 1:
	?>            
	<div class="list-thumb">
		<div class="post <?=$classe;?>">
			<a href="<?=$r;?>work/<?= $rs->url; ?>/" title="<?= $rs->projeto; ?>">
				<? if ($imagem_definitiva!="") { ?>
                <img width="300" height="160" src="<?= BUCKET . BUCKET_SITE; ?>projeto_<?= $rs->id_projeto; ?>/<?= $imagem_definitiva; ?>" alt=""/>
                <? } ?>
                
				<span class="h3"><?= $rs->projeto; ?></span>
	
				<span class="h4"><?= pega_pessoa($rs->id_agencia); ?></span>
			</a>
		</div>         
	</div>
	<?
		break;
		
		case 2:
			$ultimo_id= "linha_". $rs->id_projeto;
	?>
	<div id="<?= $ultimo_id; ?>" class="list-open <? if (($linhas_teste==0) && ($linhas==$i)) echo " sem-bg" ?>">
    	<a href="<?=$r;?>work/<?= $rs->url; ?>/" title="<?= $rs->projeto; ?>">
            <span class="list-open-thumb">	
				<? if ($imagem_definitiva!="") { ?>
                <img width="620" height="330" src="<?= BUCKET . BUCKET_SITE; ?>projeto_<?= $rs->id_projeto; ?>/<?= $imagem_definitiva; ?>" alt=""/>
                <? } ?>
            </span>
            <span class="list-open-infos">
                <span class="h3"><?= $rs->projeto; ?></span>
        
                <span class="h4"><?= pega_pessoa($rs->id_agencia); ?></span>
                
                <span class="p"><?= string_maior_que(strip_tags($rs->texto_site), 230); ?></span>
            </span>
        </a>
	</div>
	<?
		break;
	
		case 3:
	?>
	<div class="list-lines <? if (($linhas_teste==0) && ($linhas==$i)) echo " sem-bg" ?>">
		<a href="<?=$r;?>work/<?= $rs->url; ?>/" title="<?= $rs->projeto; ?>">
            <span class="name">
                <?= $rs->projeto; ?>
            </span>
            <span class="other">
                <?= pega_pessoa($rs->id_agencia); ?>
            </span>
        </a>
	</div>
	<?
		break;
	}
	?>
	<? $i++; } ?>
    
    <?
	if ($linhas_teste>0) {
	?>
    
    <div id="leva_<?= $proximo; ?>">
        <div class="div_more">
            <a id="link_leva_<?= $proximo; ?>" class="link_more" href="javascript:void(0);" onclick="carregaDinamico(this, '<?=$_GET["r"];?>', 'works', '<?=$_GET["modo"]; ?>', '<?= $proximo; ?>', '<?= $total_por_pagina; ?>', '<?= $_GET["tags"]; ?>', '', '<?= $tipo_projeto; ?>', '<?= $ultimo_id; ?>');"><?= $_MORE[$l]; ?></a>
        </div>
    </div>
    <? } elseif ($_GET["modo"]==2) { ?>
    <div class="div_more">
        <a class="link_more link_more_ucfirst" href="<?=$r;?>works/archive"><?= $_MENU_ARCHIVE[$l]; ?> &raquo;</a>
    </div>
    <? } ?>
    
    <?
}

//r, modo, inicio, total_por_pagina, tags

if ($_GET["chamada"]=="carregaDinamicoArtists") {
	
	$inicio= $_GET["inicio"];
	$r= $_GET["r"];
	$total_por_pagina= $_GET["total_por_pagina"];
	
	$proximo= $inicio+$total_por_pagina;
	
	if ($_GET["tags"]!="") $str.= "and   tags like '%". $url[3] ."%' ";
	
	$excluir_parte= explode('-', $_GET["excluir"]);
	
	for ($p=0; $p<count($excluir_parte)-1; $p++) {
		$str.= " and   pessoas.id_pessoa <> '". $excluir_parte[$p] ."' ";
	}
	
	$result= mysql_query("select *, pessoas.texto_site_". $l ." as texto_site from pessoas, pessoas_tipos, enderecos
							where pessoas.id_pessoa = pessoas_tipos.id_pessoa
							and   pessoas_tipos.tipo_pessoa = 'r'
							and   pessoas.site = '1'
							and   pessoas.id_pessoa = enderecos.id_pessoa
							and   pessoas_tipos.status_pessoa <> '2'
							$str
							order by RAND() asc
							limit ". $total_por_pagina ."
							") or die(mysql_error());
	$linhas= mysql_num_rows($result);
	
	$inicio_limite_teste= $inicio+$total_por_pagina;
	
	
	$excluir= $_GET["excluir"];
	$i=1;
	while ($rs= mysql_fetch_object($result)) {
		$excluir .= $rs->id_pessoa ."-";
		
		$imagem_miniatura= pega_imagem_miniatura_site('a', $rs->id_pessoa, $_GET["modo"]);
			
		if ($imagem_miniatura!="") $imagem_definitiva= $imagem_miniatura;
		else {
			$imagem= pega_imagem('a', $rs->id_pessoa);
			$imagem_definitiva= $imagem;
		}
		
		$cidade_uf= $rs->cidade_uf;
		
		if (($i%3)==0) $classe= "nope";
		else $classe= "";
		
		$str.= " and   pessoas.id_pessoa <> '". $rs->id_pessoa ."' ";
		
		$result_teste= mysql_query("select * from pessoas, pessoas_tipos, enderecos
									where pessoas.id_pessoa = pessoas_tipos.id_pessoa
									and   pessoas_tipos.tipo_pessoa = 'r'
									and   pessoas.site = '1'
									and   pessoas.id_pessoa = enderecos.id_pessoa
									and   pessoas_tipos.status_pessoa <> '2'
									$str
									order by RAND() asc
									limit ". $total_por_pagina ."
									") or die(mysql_error());
		$linhas_teste= mysql_num_rows($result_teste);
		
		switch ($_GET["modo"]) {
			
			case 1:
		?>            
		<div class="list-thumb">
			<div class="post <?=$classe;?>">
				<a href="<?=$r;?>artist/<?= $rs->url; ?>/" title="<?= $rs->apelido_fantasia; ?>">
					<? if ($imagem_definitiva!="") { ?>
                    <img width="300" height="160" src="<?= BUCKET . BUCKET_SITE; ?>artista_<?= $rs->id_pessoa; ?>/<?= $imagem_definitiva; ?>" alt=""/>
                    <? } ?>
                    
					<h3><?= $rs->apelido_fantasia; ?></h3>
		
					<h4><?= $cidade_uf; ?></h4>
				</a>
			</div>
						
		</div>
		<?
			break;
			
			case 2:
				$ultimo_id= "linha_". $rs->id_pessoa;
		?>
		<div id="<?= $ultimo_id; ?>" class="list-open <? if ($linhas==$i) echo " sem-bg" ?>">
			
            <a href="<?=$r;?>artist/<?= $rs->url; ?>/" title="<?= $rs->apelido_fantasia; ?>">
                <span class="list-open-thumb">    
                    <? if ($imagem_definitiva!="") { ?>
                    <img width="620" height="330" src="<?= BUCKET . BUCKET_SITE; ?>artista_<?= $rs->id_pessoa; ?>/<?= $imagem_definitiva; ?>" alt=""/>
                    <? } ?>
                </span>
                <span class="list-open-infos">
                    <span class="h3"><?= $rs->apelido_fantasia; ?></span>
            
                    <span class="h4"><?= $cidade_uf; ?></span>
                    
                    <span class="p"><?= string_maior_que(strip_tags($rs->texto_site), 230); ?></span>
                </span>
            </a>
		</div>
		<?
			break;
		
			case 3:
		?>
		<div class="list-lines <? if (($linhas_teste==0) && ($linhas==$i)) echo " sem-bg" ?>">
			<a href="<?=$r;?>artist/<?= $rs->url; ?>/" title="<?= $rs->apelido_fantasia; ?>">
                <span class="name">
                    <?= $rs->apelido_fantasia; ?>
                </span>
                <span class="other">
                    <?= $cidade_uf; ?>
                </span>
            </a>
		</div>
		<?
			break;
		}
		?>
	<? $i++; } ?>
    
    <?
	$result_teste= mysql_query("select * from pessoas, pessoas_tipos, enderecos
								where pessoas.id_pessoa = pessoas_tipos.id_pessoa
								and   pessoas_tipos.tipo_pessoa = 'r'
								and   pessoas.site = '1'
								and   pessoas.id_pessoa = enderecos.id_pessoa
								and   pessoas_tipos.status_pessoa <> '2'
								$str
								and   pessoas.id_pessoa <> '". $rs->id_pessoa ."'
								order by RAND() asc
								limit ". $total_por_pagina ."
								") or die(mysql_error());
	$linhas_teste= mysql_num_rows($result_teste);
	
	if ($linhas_teste>0) {
	?>
    
    <div id="leva_<?= $proximo; ?>">
        <div class="div_more">
            <a id="link_leva_<?= $proximo; ?>" class="link_more" href="javascript:void(0);" onclick="carregaDinamico(this, '<?=$_GET["r"];?>', 'artists', '<?=$_GET["modo"]; ?>', '<?= $proximo; ?>', '<?= $total_por_pagina; ?>', '<?= $_GET["tags"]; ?>', '<?= $excluir; ?>', '', '<?= $ultimo_id; ?>');"><?= $_MORE[$l]; ?></a>
        </div>
    </div>
    <? } ?>
    
    <?
}


/* ---------------------------------------------------------------------------------------------------- */

//echo "</body></html>";

/* <div id="temp">
	<strong>id_usuario:</strong> <?= $_SESSION["id_usuario"]; ?> <br />
	<strong>tipo_usuario:</strong> <?= $_SESSION["tipo_usuario"]; ?> <br />
	<strong>id_empresa:</strong> <?= $_SESSION["id_empresa"]; ?> <br />
	<strong>nome:</strong> <?= $_SESSION["nome"]; ?> <br />
	<strong>permissao:</strong> <?= $_SESSION["permissao"]; ?> <br />
	<strong>trocando:</strong> <?= $_SESSION["trocando"]; ?>
</div>
*/
            
?>