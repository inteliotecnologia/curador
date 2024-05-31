<?php /**/ ?><?
header("Content-type: text/xml; charset=iso-8859-1", true);
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$l= $url[1];

$result= mysql_query("select *, pessoas.texto_site_". $l ." as texto_site from pessoas, pessoas_tipos, enderecos
						where pessoas.id_pessoa = pessoas_tipos.id_pessoa
						and   pessoas_tipos.tipo_pessoa = 'r'
						and   pessoas.site = '1'
						and   pessoas.id_pessoa = enderecos.id_pessoa
						and   pessoas_tipos.status_pessoa <> '2'
						$str
						order by pessoas.id_pessoa desc
						") or die(mysql_error());

if ($l=="pt") {
	$description= "A Möve é uma empresa focada em arte aplicada que viabiliza projetos envolvendo trabalhos de artistas e ilustradores. A proposta é ser uma interface entre dois universos distintos, mas em constante troca - arte e mercado.";
}
else {
	$description= "Möve is an artistic projects agency that mediates between two different universes, art and the market. Our goal is to simplify the communication process when a project calls for the work of an artist.";
}

$novo= '<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>
			<rss version="0.92">
			<channel>
		
			<title>'. NOME .' &raquo; '. $_MENU_ARTISTS[$l] .'</title>
			<link>'. URL_SITE .'</link>
			
			';
			
			$novo .= '
			<description>'. $description .'</description>
			<language>'. $l .'</language>
			<copyright>©</copyright>';
			
			$novo .='
			<image>
				<title>'. NOME .'</title>
				<url>'. URL_SITE .'images/logo_seco.png</url>
				<link>'. URL_SITE .'</link>
				<width>140</width>
				<height>71</height>
			</image>
			
			
			';
while ($rs= mysql_fetch_object($result)) {
	
	$pd_dia= substr($rs->pub_date, 8, 2);
	$pd_mes= substr($rs->pub_date, 5, 2);
	$pd_ano= substr($rs->pub_date, 0, 4);
	$pd_hor= substr($rs->pub_date, 11, 2);
	$pd_min= substr($rs->pub_date, 14, 2);
	$pd_seg= substr($rs->pub_date, 17, 2);
	
	$descricao= '';
	
	if ($rs->resumo!="") {
        $descricao .= '<strong>'. formata_texto($rs->resumo) .'</strong><br /><br />';
    }
        
	if ($rs->texto_site!="")
		$descricao .= $rs->texto_site .'<br /><br />';
	
	/*
	$result_video= mysql_query("select * from videos
								where id_externo = '". $rs->id_projeto ."'
								and   tipo_video = 'p'
								and   site = '1'
								order by ordem asc
								");
	$linhas_video= mysql_num_rows($result_video);
	
	if ($linhas_video>0) {
		$k=1;
		while ($rs_video= mysql_fetch_object($result_video)) {
			$descricao .= faz_embed_video($rs_video->url, 940, 530) .'<br /><br />';
			$k++;
		}
	}
	*/
	
	$result_enviados= mysql_query("select * from imagens
									where id_externo = '". $rs->id_pessoa ."'
									and   tipo_imagem = 'a'
									and   site = '1'
									and   ( miniatura_destaque is NULL or miniatura_destaque = '0')
									order by ordem asc
									limit 1
									") or die(mysql_error());
	$linhas_enviados= mysql_num_rows($result_enviados);
	
	if ($linhas_enviados>0) {
		$i=1;
		while ($rs_enviados= mysql_fetch_object($result_enviados)) {
			if ($l=="pt") $legenda_site= $rs_enviados->legenda;
			else $legenda_site= $rs_enviados->legenda_en;
			if ($legenda_site=="") $classe_imagem.=" espacamento";
			
			$descricao .= '<img src="'. URL_SITE .'uploads/artista_'. $rs->id_pessoa .'/'. $rs_enviados->nome_arquivo_site .'" width="'. $rs_enviados->largura_site .'" height="'. $rs_enviados->altura_site .'" border="0" alt="" /><br />';
			
			if ($legenda_site!="")
				$descricao .= '<small>'. $legenda_site .'</small><br />';
				
			$descricao.= '<br />';
		
			$i++;
		}
	}
	
	$novo .= '
			<item>
				<title>
					<![CDATA[
						'. $rs->apelido_fantasia .'
					]]>
				</title>
				<link>'. URL_SITE .'artist/'. $rs->url .'/</link>
				
				<description>
					<![CDATA[
					'. $descricao .'
					]]>
				</description>
			</item>
			
			';
	
	$descricao= "";
}

//header("location: ./?pagina=foto_listar&id_galeria=". $_GET["id_galeria"]);

$novo .= '

				</channel>
			</rss>';


echo $novo;
?>