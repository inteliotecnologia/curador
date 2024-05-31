<?
require_once("includes/conexao.php");
require_once("includes/funcoes.php");

if (!isset($_GET["pagina"])) $pagina= "dashboard";
else $pagina= $_GET["pagina"];

$empresa_nome= pega_empresa($_SESSION["id_empresa"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?= VERSAO; ?> ::: <?= $empresa_nome; ?></title>
    
    <link rel="stylesheet" type="text/css" media="screen" href="estilo.css" />
    <link rel="stylesheet" type="text/css" media="print" href="estilo_print.css" />
    <link rel="shortcut icon" href="images/ico.gif" />

    <!--[if IE 6]>
		<link rel="stylesheet" type="text/css" media="screen" href="estilo_ie6.css" />
	<![endif]-->
    
    <? if ( ($pagina=="financeiro/imagens") || ($pagina=="financeiro/curadoria_passo1") ) { ?>
    <script language="javascript" type="text/javascript" src="js/jquery.min.js"></script>
    <? } else { ?>
    <script src="//code.jquery.com/jquery-1.7.1.min.js"></script>
    <? } ?>
    
    <script language="javascript" type="text/javascript" src="js/validacoes.js"></script>
    <script language="javascript" type="text/javascript" src="js/ajax.js"></script>
    <script language="javascript" type="text/javascript" src="js/validate/jquery.validate.js"></script>
    
    <link rel="stylesheet" type="text/css" media="screen" href="js/calendar/calendar.css" />
	<script language="javascript" type="text/javascript" src="js/calendar/calendar.js?random=20060118"></script>
    
    <script language="javascript" type="text/javascript" src="js/superfish/js/hoverIntent.js"></script>
    <script language="javascript" type="text/javascript" src="js/superfish/js/superfish.js"></script>
    <script language="javascript" type="text/javascript" src="js/tablesorter/jquery.tablesorter.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script> 
    
    <script type="text/javascript" src="js/topup/javascripts/top_up.js"></script>
    
    <script src="js/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
      $(function(){
        $("input:text, input:radio, input:checkbox,<? if ( ($pagina!="financeiro/imagens") && ($pagina!="financeiro/imagens2") ) { ?>input:file, <? } ?> input:password, textarea, select").uniform();
      });
    </script>
    
    <link href="js/uniform/css/uniform.aristo.css" rel="stylesheet" type="text/css" media="screen" charset="utf-8" />
    <link href="js/superfish/css/superfish.css" rel="stylesheet" type="text/css" media="screen" charset="utf-8" />
    
    <? /*<link href="js/datepicker/theme.css" rel="stylesheet" type="text/css" media="screen" charset="utf-8" />*/ ?>
    
    <script type="text/javascript">
		$().ready(function() {
			$("ul.sf-menu").superfish();
		});
	</script>
	
	<!-- start Mixpanel --><script type="text/javascript">(function(f,b){if(!b.__SV){var a,e,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");
for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=f.createElement("script");a.type="text/javascript";a.async=!0;a.src="//cdn.mxpnl.com/libs/mixpanel-2.2.min.js";e=f.getElementsByTagName("script")[0];e.parentNode.insertBefore(a,e)}})(document,window.mixpanel||[]);
mixpanel.init("c054498c84a17d478cf86d458f3ffb4e");</script><!-- end Mixpanel -->
    
    <script type="text/javascript">
		mixpanel.people.set({
			"$name": "<?= ($_SESSION["nome"]);?>",
			"$email": "<?= $_SESSION["email"];?>",
			"Nome completo": "<?= ($_SESSION["nome"]);?>",
			"Perfil": "<?= pega_tipo_usuario($_SESSION["tipo_usuario"]);?>",
			"ID Empresa": "<?=$_SESSION["id_empresa"];?>",
			"ID Usuario": "<?=$_SESSION["id_usuario"];?>"
		});
		
		mixpanel.register({
	        "Nome completo": "<?= ($_SESSION["nome"]);?>",
	        "ID Empresa": "<?=$_SESSION["id_empresa"];?>",
	        "ID Usuario": "<?=$_SESSION["id_usuario"];?>",
	        "Perfil": "<?= pega_tipo_usuario($_SESSION["tipo_usuario"]);?>",
	    });
	    
		mixpanel.identify('<?=$_SESSION["id_usuario"];?>');
		
		<? if ($_GET["l"]=='1') { ?>
		mixpanel.track("Fez Login");
		<? } ?>
	</script>
	
	<?
	if ($_SESSION[mixpanel]!='') {
	?>		
	<script type="text/javascript">
		
		<?
		echo $_SESSION['mixpanel'];
		?>
		
	</script>
	<?
	}
	
	$_SESSION['mixpanel']='';	
	?>
</head>

<body class="sistema <?= str_replace("/", "-", $pagina); ?>">
	
	<noscript>
		<meta http-equiv="Refresh" content="1; url=index2.php?pagina=erro" />
	</noscript>

	<div id="corpo">
    	<div id="ajax_rotina" class="escondido"></div>
		<div id="topo">
        	<div id="logo">
            	<?= VERSAO; ?>
			</div>
            
            <? if ($_SESSION["id_empresa"]!="") { ?>
            <div id="logo_empresa">
            	<img src="<?= CAMINHO_CDN; ?>empresa_<?= $_SESSION["id_empresa"] ;?>.png" alt="Logo empresa" />
			</div>
            <? } ?>
            
            <? /* if ($_SESSION["tipo_usuario"]=='a') { ?>
            <div id="emula_empresa" class="telinha1 screen">
                <a href="javascript:void(0);" onclick="fechaDiv('emula_empresa');" class="fechar">x</a>
                
                <h2>Trocar empresa</h2>
                <br />
                
                <form action="<?= AJAX_FORM; ?>formEmpresaEmular" id="formEmpresaEmular" name="formEmpresaEmular" method="post">
                    
                    <label for="id_empresa_emula">Empresa:</label>
                    <select name="id_empresa_emula" id="id_empresa_emula" title="Empresa">
                        <option selected="selected" value="">- NENHUMA -</option>
                        <?
                        $result_emp= mysql_query("select * from pessoas, pessoas_tipos, empresas
													where pessoas.id_pessoa = empresas.id_pessoa
													and   pessoas.id_pessoa = pessoas_tipos.id_pessoa
													and   pessoas_tipos.tipo_pessoa = 'a'
													order by pessoas.codigo asc,
													pessoas.nome_rz asc");
                        $i=0;
                        while ($rs_emp = mysql_fetch_object($result_emp)) {
                        ?>
                        <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_emp->id_empresa; ?>"<? if ($rs_emp->id_empresa==$_SESSION["id_empresa"]) echo "selected=\"selected\""; ?>><?= $rs_emp->nome_rz; ?></option>
                        <? $i++; } ?>
                    </select>
                    <br />
                    
                    <br />
                    
                    <label>&nbsp;</label>
                    <button type="submit">Emular</button>
                </form>
            </div>
            <? } */ ?>
			<div id="infos">

			</div>		
		</div>
        <div id="infos_mesmo">
            <?= $_SESSION["nome"]; ?><? if ($_SESSION["id_empresa"]!="") echo " / ". $empresa_nome; ?> <br />
            
            <a href="./?pagina=dashboard">Dashboard</a>
            |
            <a href="index2.php?pagina=logout">Sair</a>
        </div>
		<div id="menu">
			<? include("__menu.php"); ?>
		</div>
		<div id="conteudo">
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
    <div id="rodape">
        <div id="rodape_inner">
            <address class="sistema">
                <?= VERSAO; ?> <br /><br /><br />
                
                <?= $_SESSION["nome"]; ?><? if ($_SESSION["id_empresa"]!="") echo " / ". $empresa_nome; ?> <br /><br />
            
	            <a href="./?pagina=dashboard">Dashboard</a> | <a href="index2.php?pagina=logout">Sair</a>
            </address>
            
            <address class="relatorio">
                Relatório gerado por:&nbsp;&nbsp;<strong><?= $_SESSION["nome"]; ?></strong></em>
            </address>
        </div>
        
        <div class="escondido">
        	<img src="images/loading_cinza.gif" alt="" />
            <img src="images/loading.gif" alt="" />
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
