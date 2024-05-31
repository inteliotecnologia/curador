<?
require_once("includes/conexao.php");
if (pode_algum("v", $_SESSION["permissao"])) {
	
?>
<div class="div_abas screen" id="aba_curadorias">
    <ul class="abas">
        <li id="aba_curadoria_artistas" <? if ($pagina=="financeiro/curadoria_passo1") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/curadoria_passo1&amp;acao=e&amp;id_projeto=<?=$id_projeto;?>&amp;id_curadoria=<?=$id_curadoria;?>">Montagem</a></li>
        <li id="aba_curadoria_preview" <? if ($pagina=="financeiro/curadoria_passo2") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/curadoria_passo2&amp;id_projeto=<?=$id_projeto;?>&amp;id_curadoria=<?=$id_curadoria;?>">Preview</a></li>
        <li id="aba_curadoria_final" <? if ($pagina=="financeiro/curadoria_passo3") { ?> class="atual" <? } ?>><a href="./?pagina=financeiro/curadoria_passo3&amp;id_projeto=<?=$id_projeto;?>&amp;id_curadoria=<?=$id_curadoria;?>">Final</a></li>
    </ul>
</div>

<? } ?>