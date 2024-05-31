
<? if ($_SESSION["id_usuario"]!="") { ?>

    <ul class="sf-menu sf-js-enabled sf-shadow">
        <? if (pode("a", $_SESSION["permissao"])) { ?>
        <li><a class="linkzao" href="javascript:void(0);">Administrativo</a>
            <ul>
                <li><a href="./?pagina=financeiro/pessoa_listar&amp;tipo_pessoa=a">Empresas</a></li>
                <li><a href="./?pagina=financeiro/pessoa_listar&amp;tipo_pessoa=u">Usuários</a></li>
                <li><a onclick="return confirm('Tem certeza que deseja fazer um backup agora?');" target="_blank" href="index2.php?pagina=acesso/backup">Backup</a></li>
            </ul>
        </li>
        <? } ?>
        
        <? if (pode_algum("v", $_SESSION["permissao"])) { ?>
        <li><a class="linkzao" href="javascript:void(0);">Projetos</a>
            <ul>
                <li><a href="./?pagina=financeiro/projeto&amp;acao=i">Inserir</a></li>
                <li>
                    <a href="./?pagina=financeiro/projeto_listar">Listar</a>
                    <ul>
                        <li><a href="./?pagina=financeiro/projeto_listar&amp;selecionado=1">Selecionados</a></li>
                        <li><a href="./?pagina=financeiro/projeto_listar&amp;selecionado=0">Arquivo</a></li>
                        <li><a href="./?pagina=financeiro/projeto_listar">Todos</a></li>
                    </ul>
                </li>
                <li>
                	<a href="javascript:void(0);">Ordenar</a>
                    <ul>
                        <li><a href="./?pagina=financeiro/projeto_ordenar&amp;selecionado=1">Selecionados</a></li>
                        <li><a href="./?pagina=financeiro/projeto_ordenar&amp;selecionado=0">Arquivo</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <? } ?>
        
        <? if (pode_algum("r", $_SESSION["permissao"])) { ?>
        <li><a class="linkzao" href="javascript:void(0);">Artistas</a>
            <ul>
                <li><a href="./?pagina=financeiro/pessoa&amp;tipo_pessoa=r&amp;acao=i">Novo artista</a></li>
                <li><a href="./?pagina=financeiro/pessoa_listar&amp;tipo_pessoa=r">Listar todos</a></li>
            </ul>
        </li>
        <? } ?>
        
        <? if (pode_algum("r", $_SESSION["permissao"])) { ?>
        <li><a class="linkzao" href="javascript:void(0);">Cadastros</a>
            <ul>
                <li><a href="./?pagina=financeiro/pessoa_listar&amp;tipo_pessoa=c">Clientes</a></li>
                <li><a href="./?pagina=financeiro/pessoa_listar&amp;tipo_pessoa=f">Fornecedores</a></li>
                <li><a href="./?pagina=financeiro/pessoa_listar&amp;tipo_pessoa=g">Agências</a></li>
                <li><a href="./?pagina=financeiro/tag_listar">Tags</a></li>
            </ul>
        </li>
        <? } ?>
        
        
        
		<? if (pode("vrm", $_SESSION["permissao"])) { ?>
        <li id="menu5" class="menu_vertical"><a class="linkzao" href="./?pagina=contatos/contato_esquema">Contatos</a>
            <ul id="nav" class="menu">
                <li><a href="./?pagina=contatos/contato&amp;acao=i">Inserir</a></li>
                <li><a href="./?pagina=contatos/contato_buscar">Buscar</a></li>
                <li class="submenu">
                    <a href="./?pagina=contatos/contato_esquema">Listar</a>
                    <ul>
                        <li class="sem_submenu"><a href="./?pagina=contatos/contato_esquema&amp;tipo_contato=f">Fornecedores</a></li>
                        <li class="sem_submenu"><a href="./?pagina=contatos/contato_esquema&amp;tipo_contato=c">Clientes</a></li>
                        <li class="sem_submenu"><a href="./?pagina=contatos/contato_esquema&amp;tipo_contato=g">Agências</a></li>
                        <li class="sem_submenu"><a href="./?pagina=contatos/contato_esquema&amp;tipo_contato=r">Artistas</a></li>	
                        <li class="sem_submenu"><a href="./?pagina=contatos/contato_esquema&amp;tipo_contato=o">Outros</a></li>	
                        <li class="sem_submenu"><a href="./?pagina=contatos/contato_esquema">Todos</a></li>	
                    </ul>
                </li>
            </ul>
        </li>
        <? } ?>
        
        <? if (pode_algum("v", $_SESSION["permissao"])) { ?>
        <li><a class="linkzao" href="./?pagina=web/pagina_listar">Páginas</a></li>
        <? } ?>
    </ul>

<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>