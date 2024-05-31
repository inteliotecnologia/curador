<?
require_once("includes/conexao.php");
require_once("includes/funcoes.php");
	
if (isset($_POST["acao"])) {
	
	$usuario= str_replace("'", "xxx", str_replace('"', 'xxx', $_POST["usuario"]));
	$senha= str_replace("'", "xxx", str_replace('"', 'xxx', $_POST["senha"]));
	
	$result= mysql_query("select * from usuarios
							where usuario= '$usuario'
							and   senha= '". md5($senha) ."'
							and   status_usuario = '1'
							") or die(mysql_error());
	
	if (mysql_num_rows($result)==0)
		header("location: ./index2.php?pagina=login&erro=s1");
	else {
		$rs= mysql_fetch_object($result);
		
		if ($rs->status_usuario==0)
			header("location: ./index2.php?pagina=login&erro=s2");
		else {
			session_start();
			
			$permissao= $rs->permissao;
			
			$_SESSION["id_empresa"]= $rs->id_empresa;
			$_SESSION["id_usuario"]= $rs->id_usuario;
			$_SESSION["tipo_usuario"]= $rs->tipo_usuario;
		
			$_SESSION["nome"]= $rs->nome;
			$_SESSION["email"]= $rs->email;
			$_SESSION["id_funcionario_sessao"]= $rs->id_funcionario;
			$redir= "./?l=1" ;
			
			$_SESSION["permissao"]= $permissao;
			
			//$_SESSION["id_acesso"]= grava_acesso($rs->id_usuario, $rs->id_empresa, 'e', $_SERVER["REMOTE_ADDR"], gethostbyaddr($_SERVER["REMOTE_ADDR"]));
			
			@setcookie ("usuario", $usuario, ((time()+3600)*24)*1000);
			
			//alerta_documentos($_SESSION["id_empresa"]);
			//alerta_aniversariantes($_SESSION["id_empresa"]);
			//alerta_aniversariantes_clientes($_SESSION["id_empresa"]);
			
			header("location: ". $redir);
			//verifica_backup();
		}
		//header("location: ./");
	}//fim else
}
else {
	//echo 1;
	session_start();
	
	//@session_unregister("id_usuario");
	//@session_unregister("tipo_usuario");
	//@session_unregister("nome");
	//@session_unregister("permissao");
	
	if (isset($_GET["redireciona"]))
		echo
		"
		<script language='javascript' type='text/javascript'>
			window.top.location.href='index2.php?pagina=login';
		</script>
		";
	
	$result_enviados= mysql_query("select * from imagens, pessoas
                                    where imagens.tipo_imagem = 'a'
                                    and   pessoas.id_pessoa = imagens.id_externo
                                    and   pessoas.id_categoria = '1'
                                    and   imagens.id_empresa = '1'
                                    and   imagens.largura > '1024'
                                    and   imagens.largura < '2501'
                                    and   imagens.dimensao_imagem = '1'
                                    and   ( imagens.miniatura_destaque is NULL or imagens.miniatura_destaque = '0')
                                    order by RAND()
                                    limit 1
                                    ") or die(mysql_error());
    $linhas_enviados= mysql_num_rows($result_enviados);
    
    $rs_enviados= mysql_fetch_object($result_enviados);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title><?= VERSAO; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <link href="estilo.css" rel="stylesheet" type="text/css" media="all" />
    
    <!--[if IE 6]>
		<link rel="stylesheet" type="text/css" media="screen" href="estilo_ie6.css" />
	<![endif]-->
    
    <script language="javascript" type="text/javascript" src="js/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/validacoes.js"></script>
    <script language="javascript" type="text/javascript" src="js/validate/jquery.validate.js"></script>
    
    <link href="js/uniform/css/uniform.aristo.css" rel="stylesheet" type="text/css" media="screen" charset="utf-8" />
    
    <script type="text/javascript">
		$().ready(function() {
			$("#formulario").validate();
			$('#usuario').focus();
		});
	</script>
    
    <script src="js/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
      $(function(){
        $("input:text, input:password, textarea, select").uniform();
      });
    </script>
	
	<!-- start Mixpanel --><script type="text/javascript">(function(f,b){if(!b.__SV){var a,e,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");
for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=f.createElement("script");a.type="text/javascript";a.async=!0;a.src="//cdn.mxpnl.com/libs/mixpanel-2.2.min.js";e=f.getElementsByTagName("script")[0];e.parentNode.insertBefore(a,e)}})(document,window.mixpanel||[]);
mixpanel.init("c054498c84a17d478cf86d458f3ffb4e");</script><!-- end Mixpanel -->
</head>

<body class="login" style="background: #efefef url(<?=CAMINHO_CDN;?>a_<?=$rs_enviados->id_externo;?>/<?=$rs_enviados->nome_arquivo;?>) no-repeat; background-size:120%;">

	<noscript>
	  <meta http-equiv="Refresh" content="1; url=index2.php?pagina=erro" />
	</noscript>
	
	
		<div id="centro">
        	<div class="centro_tit">
        		<h2>Fa&ccedil;a o login:</h2>
        	</div>
        	<div class="centro_logo">
        		<img src="<?= CAMINHO2; ?>empresa_1p.png" alt="Logo empresa" />
        	</div>
            
            <hr />
            
			<form action="index2.php?pagina=login" method="post" name="formulario" id="formulario">
				<input type="hidden" name="acao" id="acao" value="1" class="escondido" />
                
                <div class="parte33">
	                <label for="usuario">Usu&aacute;rio</label>
	                <input name="usuario" id="usuario" class="required" value="<?= $_COOKIE["usuario"]; ?>" />
				</div>
                
                <div class="parte33">
	                <label for="senha">Senha</label>
	                <input  type="password" name="senha" id="senha" class="required" />
                </div>
                <div class="parte33">
                	<button id="enviar" type="submit">Login</button>
                </div>
                <br class="limpa" />
                
                <br />
				
				<label>&nbsp;</label>
                <span class="vermelho">
				<? if ($_GET[erro]=='') { ?>
				<script>
					mixpanel.track("Acessou Login");
				</script>
				<?
				}
				
                if ($_GET["erro"]=="s1") {
                	echo "Usuário e/ou senha inválidos!";
                }
				if ($_GET["erro"]=="s2") {
					echo "Acesso desativado!";
				}
				?>
				</span>
			</form>
			
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
<? } ?>