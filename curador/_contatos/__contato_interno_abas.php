<?
require_once("includes/conexao.php");
if (pode_algum("r", $_SESSION["permissao"])) {
	if ($_GET["id_origem"]!="") $id_origem= $_GET["id_origem"];
	if ($_POST["id_origem"]!="") $id_origem= $_POST["id_origem"];
	
	if ($_GET["id_contato"]!="") $id_contato= $_GET["id_contato"];
	if ($_POST["id_contato"]!="") $id_contato= $_POST["id_contato"];
?>

<div class="div_abas screen" id="aba_artistas">
    <ul class="abas">
        <li id="aba_artistas_dados" <? if ($pagina=="contatos/contato") { ?> class="atual" <? } ?>><a href="./?pagina=contatos/contato&amp;acao=e&amp;id_contato=<?=$id_contato;?>&amp;id_origem=<?=$id_origem;?>">Dados</a></li>
        
        <li id="aba_artistas_notas" <? if ($pagina=="contatos/contato_notas") { ?> class="atual" <? } ?>><a href="./?pagina=contatos/contato_notas&amp;id_contato=<?=$id_contato;?>&amp;id_origem=<?=$id_origem;?>">Notas</a></li>
        
    </ul>
</div>

<? } ?>