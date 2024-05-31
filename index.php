<?
@ini_set("url_rewriter.tags","");

require_once("includes/funcoes.php");
require_once("includes/conexao.php");
require_once("language.inc");

//echo "qwe".$url[0];

$mostrar_online= 1;

if ($pagina=="") $pagina= "home";

if ($pagina=="c") {
	header("location: http://norte.art.br/curador/index2.php?pagina=curadoria&segredo=". $url[1]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="pt-BR">

<head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
    <?
	if ($_SESSION["l"]=="") {
		$user_agent= $_SERVER['HTTP_USER_AGENT'];	
		$teste_pt= strpos($user_agent, "pt");
		
		if ($teste_pt===false) {
			$_SESSION["l"]= "en";
		}
		else {
			$_SESSION["l"]= "pt";
		}
	}
	
	$l= $_SESSION["l"];
	
	$title= "Norte";
	
    if (($pagina!="work") && ($pagina!="artist")) {
		if ($l=="pt") {
			$keywords= "move,artistas,ilustração,arte";
			$description= "A Norte é uma empresa focada em arte aplicada que viabiliza projetos envolvendo trabalhos de artistas e ilustradores. A proposta é ser uma interface entre dois universos distintos, mas em constante troca - arte e mercado.";
		}
		else {
			$keywords= "move,artist,projects,illustration";
			$description= "Norte is an artistic projects agency that mediates between two different universes, art and the market. Our goal is to simplify the communication process when a project calls for the work of an artist.";
		}
	}
	else {
		if ($pagina=="work") {
			$result= mysql_query("select *, projeto_". $l ." as projeto,
									resumo_". $l ." as resumo,
									texto_site_". $l ." as texto_site,
									tags_site_". $l ." as tags_site
									from projetos
									where url = '". $url[1] ."'
									and   status_projeto <> '0'
									order by id_projeto asc limit 1");
			$rs= mysql_fetch_object($result);
			
			$title.= " :: ". $rs->projeto;
			
			$keywords= $rs->tags_site;
			if ($rs->resumo!="") $description= $rs->resumo;
			else $description= substr(strip_tags($rs->texto_site), 0, 300) ."...";
		}
		else {
			$result= mysql_query("select *, pessoas.release_". $l ." as resumo,
									pessoas.tags_site_". $l ." as tags_site,
									pessoas.texto_site_". $l ." as texto_site
									from pessoas, pessoas_tipos
									where pessoas.id_pessoa = pessoas_tipos.id_pessoa
									and   pessoas_tipos.tipo_pessoa = 'r'
									and   pessoas.site = '1'
									and   pessoas.url = '". $url[1] ."'
									order by pessoas.apelido_fantasia asc") or die(mysql_error());
									
			$rs= mysql_fetch_object($result);
			
			$title.= " :: ". $rs->apelido_fantasia;
			
			$keywords= $rs->tags_site;
			//if ($rs->resumo!="") $description= $rs->resumo;
			//else
			$description= substr(strip_tags($rs->texto_site), 0, 300) ."...";
		}
		
	}
	
	?>
    
    <title><?= $title; ?></title>
    
    <meta name="keywords" content="<?=$keywords;?>" />
	<meta name="description" content="<?=$description;?>" />
	<meta name="viewport" content="initial-scale=1.0">
    
    <? /*<link href="http://fonts.googleapis.com/css?family=Droid+Serif:regular,italic" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,400italic' rel='stylesheet' type='text/css'>*/ ?>
    
    <link rel="shortcut icon" href="<?=$r;?>images/Norte_Favicon.png" />
    
    <link rel="alternate" type="application/rss+xml" title="Norte &raquo; <?= $_MENU_SELECTED[$l]; ?>" href="<?=$r;?>rss_projects/<?=$l;?>/" />
    <link rel="alternate" type="application/rss+xml" title="Norte &raquo; <?= $_MENU_ARTISTS[$l]; ?>" href="<?=$r;?>rss_artists/<?=$l;?>/" />
    
    <link rel="stylesheet" href="<?=$r;?>style.css?v=2" type="text/css" media="screen" />
    
    <link rel="stylesheet" href="<?=$r;?>style_responsive.css?v=2" type="text/css" />
    
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    <? /*
    <link media="screen and (min-width:721px) and (max-width: 950px)" href="<?=$r;?>css/750.css" rel="stylesheet" />
	
	<link media="screen and (min-width:451px) and (max-width: 720px), screen and (max-device-width: 480px) and (orientation: landscape)" rel="stylesheet" type="text/css" href="<?=$r;?>css/480.css" />
	
	<link media="screen and (min-width:1px) and (max-width: 450px), screen and (max-device-width: 320px)  and (orientation: portrait)" rel="stylesheet" type="text/css" href="<?=$r;?>css/320.css" />
	*/ ?>
	
	<script type="text/javascript" src="<?=$r;?>js/jquery.min.js"></script>
    <script type="text/javascript" src="<?=$r;?>js/jquery.lazyload.js"></script>
    <script type="text/javascript" src="<?=$r;?>js/functions.js"></script>
    
    <script type="text/javascript" charset="utf-8">
      /*$(function() {
          $("img").lazyload({
             placeholder : "<?=$r;?>img/grey.gif",
             effect      : "fadeIn"
          });
      });*/
	  
	  $.preload([
			"<?=$r;?>images/branco20-bg.png",
			"<?=$r;?>images/branco30-bg.png",
			"<?=$r;?>images/branco45-bg.png",
			"<?=$r;?>images/preto60-bg.png",
			"<?=$r;?>images/preto85-bg.png",
			"<?=$r;?>images/mais_h.png",
			"<?=$r;?>images/volta_h.png",
			"<?=$r;?>images/top2.png",
			"<?=$r;?>images/view_1_on.png",
			"<?=$r;?>images/view_2_on.png",
			"<?=$r;?>images/view_3_on.png",
			"<?=$r;?>images/arrow_down.png",
			"<?=$r;?>images/loading.gif",
			"<?=$r;?>images/loading_busca.gif",
			"<?=$r;?>images/ico-rss_2.png",
			"<?=$r;?>images/ico-facebook_2.png",
			"<?=$r;?>images/ico-behance_2.png",
			"<?=$r;?>images/ico-vimeo_2.png",
			"<?=$r;?>images/ico-flickr_2.png",
			"<?=$r;?>images/ico-twitter_2.png",
			"<?=$r;?>images/ico-pinterest_2.png",
			"<?=$r;?>images/ico-gplus_2.png",
			"<?=$r;?>images/ico-linkedin_2.png"
		]);
  </script>
  
  <!-- start Mixpanel --><script type="text/javascript">(function(f,b){if(!b.__SV){var a,e,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");
for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=f.createElement("script");a.type="text/javascript";a.async=!0;a.src="//cdn.mxpnl.com/libs/mixpanel-2.2.min.js";e=f.getElementsByTagName("script")[0];e.parentNode.insertBefore(a,e)}})(document,window.mixpanel||[]);
mixpanel.init("210a70db2a481d5466472b94063a7ed0");</script><!-- end Mixpanel -->
  
  <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-801754-33']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body class="<? if ($pagina=="home") echo "home"; elseif (($pagina=="artist") || ($pagina=="work")) echo "inner"; elseif (($pagina=="about") || ($pagina=="contact")) echo "inner-2"; ?>">
    <div class="out_bar">
	    <div class="out">
		    <a name="top"></a>
		    
		    <div id="header">
	    
	            <div id="logo">
	                <h1><a href="<?=$r;?>">M&ouml;ve</a></h1>
	                <? //echo $l; ?>
	            </div>
	            
	            <?
	            $ws=0;
	            if ($url[0]=="work") {
	                $result_work= mysql_query("select * from projetos
	                                            where url = '". $url[1] ."'
	                                            and   status_projeto <> '0'
	                                            ");
	                $rs_work= mysql_fetch_object($result_work);
	                
	                if ($rs_work->selecionado=="1") $ws=1;
	            }
	            ?>
	            
	            <div id="menu_controls">
	            	<a href="javascript:void(0);"><i class="fa fa-bars"></i></a>
	            </div>
	            
	            <div id="menu">
	                <ul>
	                    <li><a href="<?=$r;?>works/selected" <? if (($url[1]=="selected") || (($url[0]=="work") && ($ws))) echo "class=\"current\""; ?>><?= $_MENU_SELECTED[$l]; ?></a></li>
	                    <li><a href="<?=$r;?>works/archive" <? if (($url[1]=="archive") || (($url[0]=="work") && (!$ws))) echo "class=\"current\""; ?>><?= $_MENU_ARCHIVE[$l]; ?></a></li>
	                    <li><a href="<?=$r;?>artists" <? if (($url[0]=="artists") || ($url[0]=="artist")) echo "class=\"current\""; ?>><?= $_MENU_ARTISTS[$l]; ?></a></li>
	                    <li><a href="<?=$r;?>about" <? if ($url[0]=="about") echo "class=\"current\""; ?>><?= $_MENU_ABOUT[$l]; ?></a></li>
	                    <li><a href="<?=$r;?>contact" <? if ($url[0]=="contact") echo "class=\"current\""; ?>><?= $_MENU_CONTACT[$l]; ?></a></li>
	                </ul>
	            </div>
				
	            <div id="busca-area">
	            	<input type="hidden" name="r" id="r" value="<?=$r;?>" />
	            	<input type="text" name="busca" id="busca" />
	                
	                <div id="sugestoes"></div>
	            </div>
	        </div>
	        
	    </div>
    </div>
    
    <div class="out">

        <hr />
        
        <div id="page">
            <div id="content">
                <?
                if (file_exists("__". $pagina .".php")) include("__". $pagina .".php");
                else include("__erro.php");
                ?>
            </div>  
          
            <hr />
            
            <div id="footer" <? if ($url[2]==3) echo "class=\"sem-margem\""; ?>>
                <div id="footer-infos">
                    <?
                    for ($i=0; $i<3; $i++) if ($url[$i]!="") $redir .= $url[$i] ."/";
                    ?>
                                    
                    <ul class="lang">
                        <li><a <? if ($l=="en") echo "class=\"atual\""; ?> href="<?=$r;?>set/en/<?=$redir;?>">English</a></li>
                        <li>|</li>
                        <li><a <? if ($l=="pt") echo "class=\"atual\""; ?> href="<?=$r;?>set/pt/<?=$redir;?>">Português</a></li>
                    </ul>
                    
                    <br /><br />
                    <?
					$result= mysql_query("select pagina_". $l ." as pagina,
											destaque_". $l ." as destaque,
											conteudo_". $l ." as conteudo
											from paginas
											where id_pagina = '3'
											");
					$rs= mysql_fetch_object($result);
					
					echo formata_texto_saida($rs->conteudo);
					?>
                    <br />
                    
                    Site by <a target="_blank" href="http://intelio.com.br">intelio</a>.
                </div>
                
                <div id="footer-networks">
                    <ul>
                        <li><a class="ico-rss" href="<?=$r;?>rss_projects/<?=$l;?>">RSS</a></li>
                        
                        <?
						$result_rede= mysql_query("select *, pagina_". $l ." as pagina,
												destaque_". $l ." as destaque,
												conteudo_". $l ." as conteudo
												from paginas
												where id_pagina = '7'
												");
						$rs_rede= mysql_fetch_object($result_rede);
						?>
                        <li><a class="ico-facebook" href="<?= strip_tags($rs_rede->conteudo_pt); ?>" target="_blank">Facebook</a></li>
                        
                        <?
						$result_rede= mysql_query("select *, pagina_". $l ." as pagina,
												destaque_". $l ." as destaque,
												conteudo_". $l ." as conteudo
												from paginas
												where id_pagina = '8'
												");
						$rs_rede= mysql_fetch_object($result_rede);
						?>
                        <li><a class="ico-behance" href="<?= strip_tags($rs_rede->conteudo_pt); ?>" target="_blank">Behance</a></li>
    					
                        <?
						$result_rede= mysql_query("select *, pagina_". $l ." as pagina,
												destaque_". $l ." as destaque,
												conteudo_". $l ." as conteudo
												from paginas
												where id_pagina = '9'
												");
						$rs_rede= mysql_fetch_object($result_rede);
						?>
                        <li><a class="ico-vimeo" href="<?= strip_tags($rs_rede->conteudo_pt); ?>" target="_blank">Vimeo</a></li>
                        
                        <?
						$result_rede= mysql_query("select *, pagina_". $l ." as pagina,
												destaque_". $l ." as destaque,
												conteudo_". $l ." as conteudo
												from paginas
												where id_pagina = '10'
												");
						$rs_rede= mysql_fetch_object($result_rede);
						?>
                        <li><a class="ico-flickr" href="<?= strip_tags($rs_rede->conteudo_pt); ?>" target="_blank">Flickr</a></li>
                        
                        <?
                        /*
						$result_rede= mysql_query("select *, pagina_". $l ." as pagina,
												destaque_". $l ." as destaque,
												conteudo_". $l ." as conteudo
												from paginas
												where id_pagina = '12'
												");
						$rs_rede= mysql_fetch_object($result_rede);
						?>
                        <li><a class="ico-pinterest" href="<?= strip_tags($rs_rede->conteudo_pt); ?>" target="_blank">Pinterest</a></li>
                        <?
                        
						$result_rede= mysql_query("select *, pagina_". $l ." as pagina,
												destaque_". $l ." as destaque,
												conteudo_". $l ." as conteudo
												from paginas
												where id_pagina = '13'
												");
						$rs_rede= mysql_fetch_object($result_rede);
						?>
                        <li><a class="ico-gplus" href="<?= strip_tags($rs_rede->conteudo_pt); ?>" target="_blank" rel="publisher">G+</a></li>
                        
                        
                        <?
						$result_rede= mysql_query("select *, pagina_". $l ." as pagina,
												destaque_". $l ." as destaque,
												conteudo_". $l ." as conteudo
												from paginas
												where id_pagina = '11'
												");
						$rs_rede= mysql_fetch_object($result_rede);
						?>
                        <li><a class="ico-twitter" href="<?= strip_tags($rs_rede->conteudo_pt); ?>" target="_blank">Twitter</a></li>
                        <? */ ?>
                        
                        <?
						$result_rede= mysql_query("select *, pagina_". $l ." as pagina,
												destaque_". $l ." as destaque,
												conteudo_". $l ." as conteudo
												from paginas
												where id_pagina = '14'
												");
						$rs_rede= mysql_fetch_object($result_rede);
						?>
                        <li><a class="ico-linkedin" href="<?= strip_tags($rs_rede->conteudo_pt); ?>" target="_blank">Linkedin</a></li>
                    </ul>
                </div>
            </div>
            
            <div id="footer-padding">
            
            </div>
        </div>
    </div>
</body>
</html>