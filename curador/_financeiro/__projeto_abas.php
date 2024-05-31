<?
require_once("includes/conexao.php");
if (pode_algum("v", $_SESSION["permissao"])) {
	if ($_POST["id_projeto"]!="") $id_projeto= $_POST["id_projeto"];
	if ($_GET["id_projeto"]!="") $id_projeto= $_GET["id_projeto"];
?>
<div class="div_abas screen" id="aba_projetos">
    <ul class="abas">
        <li id="aba_projetos_dados" <? if ($pagina=="financeiro/projeto") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/projeto&amp;acao=e&amp;id_projeto=<?=$id_projeto;?>">Dados</a></li>
        <li id="aba_projetos_curadoria" <? if (($pagina=="financeiro/curadoria_projeto_listar") || ($pagina=="financeiro/curadoria_passo1") || ($pagina=="financeiro/curadoria_passo2")) { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/curadoria_projeto_listar&amp;id_projeto=<?=$id_projeto;?>">Curadoria</a></li>
        
        <li id="aba_projetos_imagens" <? if ($pagina=="financeiro/imagens2") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/imagens2&amp;id_projeto=<?=$id_projeto;?>">Imagens: upload</a></li>
        
        <li id="aba_projetos_imagens" <? if ($pagina=="financeiro/imagens") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/imagens&amp;id_projeto=<?=$id_projeto;?>">Imagens: ordenação</a></li>
        
        <li id="aba_projetos_publicacao" <? if ($pagina=="financeiro/projeto_web") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/projeto_web&amp;id_projeto=<?=$id_projeto;?>">Publica&ccedil;&atilde;o</a></li>
        <li id="aba_projetos_publicacao_miniaturas" <? if ($pagina=="financeiro/imagens_miniaturas") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/imagens_miniaturas&amp;id_projeto=<?=$id_projeto;?>">Publica&ccedil;&atilde;o: miniaturas</a></li>
    </ul>
</div>

<? } ?>