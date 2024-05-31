<? if ($_SESSION["id_usuario"]!="") { ?>
<h2 class="titulos">Dashboard</h2>

<?
/*$result_andamento= mysql_query("select * from projetos
								
								");
?>
<div class="dashboard_barra">
	oi
</div>
*/ ?>

<div class="form_alternativo">
	<div class="parte50 dash_botoes">
    	<ul>
        	<li><a href="./?pagina=financeiro/pessoa&acao=i&tipo_pessoa=r&status_pessoa=1">Novo artista</a></li>
            <li><a href="./?pagina=financeiro/projeto&acao=i">Novo projeto</a></li>
            <li><a href="./?pagina=contatos/contato&acao=i">Novo contato</a></li>
        </ul>
    </div>
    <div class="parte50">
    	<h3>Curadorias em andamento</h3>
        <br /><br />
        
        <p>Em breve.</p>
    </div>
    <br />
</div>

<h3 class="alternativo">Estatísticas</h2>
<br /><br />

<ul class="dash_stats">
	<?
	$result_artistas= mysql_query("select * from pessoas, pessoas_tipos
									where pessoas.id_pessoa = pessoas_tipos.id_pessoa
									and   pessoas_tipos.tipo_pessoa = 'r'
									and   pessoas_tipos.status_pessoa = '1'
									and   pessoas_tipos.status_pessoa <> '2'
									") or die(mysql_error());
	$linhas_artistas= mysql_num_rows($result_artistas);
	?>
    <li><span class="numzao"><?=$linhas_artistas;?></span><br />artistas cadastrados</li>
    
    <?
	$result_artistas= mysql_query("select * from pessoas, pessoas_tipos
									where pessoas.id_pessoa = pessoas_tipos.id_pessoa
									and   pessoas_tipos.tipo_pessoa = 'r'
									and   pessoas_tipos.status_pessoa = '1'
									and   pessoas_tipos.status_pessoa <> '2'
									and   pessoas.id_categoria = '1'
									") or die(mysql_error());
	$linhas_artistas= mysql_num_rows($result_artistas);
	?>
    <li><span class="numzao"><?=$linhas_artistas;?></span><br />artistas exclusivos</li>
    
    <?
	$result_artistas= mysql_query("select * from pessoas, pessoas_tipos
									where pessoas.id_pessoa = pessoas_tipos.id_pessoa
									and   pessoas_tipos.tipo_pessoa = 'r'
									and   pessoas_tipos.status_pessoa = '1'
									and   pessoas_tipos.status_pessoa <> '2'
									and   pessoas.id_categoria = '2'
									") or die(mysql_error());
	$linhas_artistas= mysql_num_rows($result_artistas);
	?>
    <li class="dash_sm"><span class="numzao"><?=$linhas_artistas;?></span><br />artistas não exclusivos</li>
    
    <?
	$result_projetos= mysql_query("select * from projetos
									where status_projeto <> '2'
									") or die(mysql_error());
	$linhas_projetos= mysql_num_rows($result_projetos);
	?>
    <li><span class="numzao"><?=$linhas_projetos;?></span><br />projetos cadastrados</li>
    
    <?
	$result_projetos_curadorias= mysql_query("select * from curadorias
												where status_curadoria <> '2'
												") or die(mysql_error());
	$linhas_projetos_curadorias= mysql_num_rows($result_projetos_curadorias);
	?>
    <li><span class="numzao"><?=$linhas_projetos_curadorias;?></span><br />curadorias iniciadas</li>
    
    <?
	$result_usuarios= mysql_query("select * from usuarios
									where status_usuario <> '2'
									") or die(mysql_error());
	$linhas_usuarios= mysql_num_rows($result_usuarios);
	?>
    <li class="dash_sm"><span class="numzao"><?=$linhas_usuarios;?></span><br />usuários cadastrados</li>
</ul>
<br />

<?
/*
require_once("funcoes_espelho.php");

$retorno= pega_dados_rh($_SESSION["id_empresa"], 0, 0, 103, "17/01/2010", "17/01/2010");
$novo= explode("@", $retorno);

echo(calcula_total_horas($novo[3]));
*/
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>