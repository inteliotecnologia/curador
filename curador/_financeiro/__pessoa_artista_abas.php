<?
require_once("includes/conexao.php");
if (pode_algum("r", $_SESSION["permissao"])) {
	
?>

<div class="div_abas screen" id="aba_artistas">
    <ul class="abas">
        <li id="aba_artistas_dados" <? if ($pagina=="financeiro/pessoa") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/pessoa&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;acao=e&amp;id_pessoa=<?=$id_pessoa;?>">Dados</a></li>
        <li id="aba_artistas_obras" <? if ($pagina=="financeiro/imagens2") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/imagens2&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;id_pessoa=<?=$id_pessoa;?>">Obras: upload</a></li>
        <li id="aba_artistas_obras" <? if ($pagina=="financeiro/imagens2") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/imagens&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;id_pessoa=<?=$id_pessoa;?>">Obras: ordenação</a></li>
        <li id="aba_artistas_notas" <? if ($pagina=="financeiro/pessoa_notas") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/pessoa_notas&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;id_pessoa=<?=$id_pessoa;?>">Notas</a></li>
        <li id="aba_artistas_publicacao" <? if ($pagina=="financeiro/pessoa_web") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/pessoa_web&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;acao=e&amp;id_pessoa=<?=$id_pessoa;?>">Publica&ccedil;&atilde;o</a></li>
        <li id="aba_artistas_publicacao_miniaturas" <? if ($pagina=="financeiro/imagens_miniaturas") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/imagens_miniaturas&amp;id_pessoa=<?=$id_pessoa;?>">Miniaturas</a></li>
    </ul>
</div>

<? } ?>