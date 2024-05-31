<?
require_once("includes/conexao.php");
require_once("includes/funcoes.php");

if (!isset($_GET["pagina"])) $pagina= "principal";
else $pagina= $_GET["pagina"];

$empresa_nome= pega_empresa($_SESSION["id_empresa"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title><?= VERSAO; ?> ::: <?= $empresa_nome; ?></title>
    
    <link rel="stylesheet" type="text/css" media="screen" href="estilo.css" />
    <link rel="stylesheet" type="text/css" media="print" href="estilo_print.css" />
    <link rel="shortcut icon" href="images/xicone.png" />

    <!--[if IE 6]>
		<link rel="stylesheet" type="text/css" media="screen" href="estilo_ie6.css" />
	<![endif]-->
    
    <script language="javascript" type="text/javascript" src="js/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/validacoes.js"></script>
    <script language="javascript" type="text/javascript" src="js/ajax.js"></script>
    <script language="javascript" type="text/javascript" src="js/validate/jquery.validate.js"></script>
    <script language="javascript" type="text/javascript" src="js/superfish/js/hoverIntent.js"></script>
    <script language="javascript" type="text/javascript" src="js/superfish/js/superfish.js"></script>
    <script language="javascript" type="text/javascript" src="js/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="js/topup/javascripts/top_up-min.js"></script> 
    
    <script src="js/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
      $(function(){
        $("input:text, input:radio, <? if ($pagina!="financeiro/imagens2") { ?>input:checkbox,<? } ?> input:password, textarea, select").uniform();
      });
    </script>
    
    <link href="js/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" media="screen" charset="utf-8" />
    <link href="js/superfish/css/superfish.css" rel="stylesheet" type="text/css" media="screen" charset="utf-8" />
    
    <script type="text/javascript">
		$().ready(function() {
			$("ul.sf-menu").superfish();
		});
	</script>
    
</head>

<body class="sistema_min">
	
	<noscript>
		<meta http-equiv="Refresh" content="1; url=index2.php?pagina=erro" />
	</noscript>

	<div id="corpo_min">
    	<div id="ajax_rotina" class="escondido"></div>
		
		<div id="conteudo_min">
			<?
			$paginar= $pagina;
			if (strpos($paginar, "/")) {
				$parte_pagina= explode("/", $paginar);
				
				if (file_exists("_". $parte_pagina[0] ."/". "__". $parte_pagina[1] .".php"))
					include("_". $parte_pagina[0] ."/". "__". $parte_pagina[1] .".php");
				else
					echo "<h2>Erro</h2><p>Página não encontrada!</p>";
			}
			else {
				if (file_exists("__". $paginar .".php"))
					include("__". $paginar .".php");
				else
					echo "<h2>Erro</h2><p>Página não encontrada!</p>";
			}
			?>
		
		</div>
		
	</div>
<?
if (isset($_GET["ctrl"])) {
	switch ($_GET["ctrl"]) {
		case 1: $msg= "ATENÇÃO!!!\\n\\nSe você estava realizando alguma operação, certifique-se que ela\\nfoi realizada ou não e tome as devidas providências!";
				break;
		default: $msg= "Curioso ;P";
				break;
	}
?>
<script language="javascript" type="text/javascript">alert('<?= $msg; ?>');</script>
<? } /* ?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-801754-23");
pageTracker._initData();
pageTracker._trackPageview();
</script>
*/ ?>
</body>
</html>

<? $fecha= mysql_close($conexao); ?>
