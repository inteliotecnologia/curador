<?
require_once("includes/conexao.php");
require_once("includes/funcoes.php");
	
	$result_enviados= mysql_query("select * from imagens, pessoas
                                    where imagens.tipo_imagem = 'a'
                                    and   pessoas.id_pessoa = imagens.id_externo
                                    and   imagens.id_empresa = '1'
                                    and   pessoas.id_categoria = '1'
                                    and   imagens.largura > '1024'
                                    and   imagens.largura < '2501'
                                    and   imagens.dimensao_imagem = '1'
                                    and   ( imagens.miniatura_destaque is NULL or imagens.miniatura_destaque = '0')
                                    order by RAND()
                                    limit 1
                                    ") or die(mysql_error());
    $linhas_enviados= mysql_num_rows($result_enviados);
    $rs_enviados= mysql_fetch_object($result_enviados);
    
    $result= mysql_query("select * from  curadorias, projetos
							where curadorias.auth = '". $_GET["segredo"] ."'
							and   curadorias.id_projeto = projetos.id_projeto
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	
	$j=1;
		
	for ($i=1; $i<20; $i++) {
		$url[$i]= CAMINHO ."curadorias/curadoria_". $rs->auth ."_". $i .".pdf";
		
		if (file_exists($url[$i])) $j=$i;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title><?= VERSAO; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    
    <link href="estilo.css" rel="stylesheet" type="text/css" media="all" />
    <link rel="shortcut icon" href="images/ico.gif" />
    
    <!--[if IE 6]>
		<link rel="stylesheet" type="text/css" media="screen" href="estilo_ie6.css" />
	<![endif]-->
    
    <script language="javascript" type="text/javascript" src="js/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/validacoes.js"></script>
    <script language="javascript" type="text/javascript" src="js/validate/jquery.validate.js"></script>
    
    <link href="js/uniform/css/uniform.aristo.css" rel="stylesheet" type="text/css" media="screen" charset="utf-8" />
    
    <script src="js/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
      $(function(){
        $("input:text, input:password, textarea, select").uniform();
      });
    </script>

</head>

<body class="login" style="background: #efefef url(<?=CAMINHO;?>/a_<?=$rs_enviados->id_externo;?>/<?=$rs_enviados->nome_arquivo;?>) no-repeat; background-size:120%;">

	<noscript>
	  <meta http-equiv="Refresh" content="1; url=index2.php?pagina=erro" />
	</noscript>
	
	
		<div id="centro" class="curadoria">
        	<div class="centro_tit">
        		<h2>Seja bem-vindo!</h2>
        	</div>
        	<div class="centro_logo">
        		<img src="<?= CAMINHO; ?>empresa_1p.png" alt="Logo empresa" />
        	</div>
            
            <hr />
            
            <? for ($i=1; $i<=$j; $i++) { ?>
            <div class="parte66">
	            <img width="55" class="flutuar_esquerda" src="images/icone_pdf.png" alt="" />
	            
	            <h4 class="tit_curadoria"><?= $rs->projeto_pt; ?></h4>
	            <h5>Parte <?=$i;?> - <?=format_bytes(filesize($url[$i]));?></h5>
            </div>
            <div class="parte33">
            	<a class="botao_recuo1 flutuar_direita" href="<?=$url[$i];?>" target="_blank">Download</a>
            </div>
            
            <br class="limpa" />
			<hr />
			<? } ?>
			
			<p>Aqui está o link para esse arquivo, caso deseje compartilhar:</p>
			<br />
			
			<div class="parte75">
				<input type="text" value="http://move.art.br/c/<?=$rs->auth;?>" />
			</div>
			
			<div class="arte_por">
				Arte por: <a href="http://move.art.br/artist/<?=$rs_enviados->url;?>" target="_blank"><?=pega_pessoa($rs_enviados->id_externo);?></a>
			</div>
			
		</div>
		
		
	
	
	<? /*<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
	</script>
	<script type="text/javascript">
	_uacct = "UA-801754-7";
	urchinTracker();
	</script> */ ?>
</body>
</html>