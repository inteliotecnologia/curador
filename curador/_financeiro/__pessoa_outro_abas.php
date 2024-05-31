<?
require_once("includes/conexao.php");
if (pode_algum("r", $_SESSION["permissao"])) {
	
?>

<div class="div_abas screen" id="aba_pessoas">
    <ul class="abas">
        <li id="aba_artistas_dados" <? if ($pagina=="financeiro/pessoa") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/pessoa&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;acao=e&amp;id_pessoa=<?=$id_pessoa;?>">Dados</a></li>
        <li id="aba_artistas_contatos" <? if (($pagina=="financeiro/pessoa_contatos") || ($pagina=="contatos/contato_importar")) { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/pessoa_contatos&amp;tipo_contato=<?=$tipo_pessoa;?>&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;id_pessoa=<?=$id_pessoa;?>&amp;origem=2">Contatos</a></li>
    </ul>
</div>

<? } ?>