<?
if (pode("r", $_SESSION["permissao"])) {
	if ($_GET["letra"]!="") $letra= $_GET["letra"];
	if ($_POST["letra"]!="") $letra= $_POST["letra"];
	
	if ($_GET["tipo_contato"]!="") $tipo_contato= $_GET["tipo_contato"];
	if ($_POST["tipo_contato"]!="") $tipo_contato= $_POST["tipo_contato"];
	
	if ($_GET["id_pessoa"]!="") $id_pessoa= $_GET["id_pessoa"];
	if ($_POST["id_pessoa"]!="") $id_pessoa= $_POST["id_pessoa"];
	
	if ($id_pessoa!="") $subtitulo= pega_pessoa($id_pessoa);
	else $subtitulo= pega_tipo_contato_plural($tipo_contato);
?>
<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="tit tit_agenda">Contatos: <?= $subtitulo; ?></h2>

<!--
<div id="legenda">
	<ul>
    	<li class="preto">Residencial</li>
        <li class="azul">Comercial</li>
        <li class="verde">Celular</li>
        <li class="vermelho">Fax</li>
        <li class="cinza">Outros</li>
    </ul>
</div>
-->

<? if ($_POST["geral"]!="1") { ?>
<div class="div_abas screen div_abas_contatos" id="aba_letras">
    <ul class="abas">
        <li id="aba_letra_tudo" <? if ($letra=="") echo "class=\"atual\""; ?>><a href="javascript:void(0);" onclick="atribuiAbaAtual('aba_letra_tudo', 'aba_letras'); ajaxLink('conteudo_interno', 'carregaPagina&amp;pagina=contatos/contato_listar&amp;tipo_contato=<?= $tipo_contato; ?>&amp;letra=');">Tudo</a></li>
		<? for ($i='a'; $i!="aa"; $i++) { ?>
        <li id="aba_letra_<?=$i;?>" <? if ($i==$letra) echo "class=\"atual\""; ?>><a href="javascript:void(0);" onclick="atribuiAbaAtual('aba_letra_<?=$i;?>', 'aba_letras'); ajaxLink('conteudo_interno', 'carregaPagina&amp;pagina=contatos/contato_listar&amp;tipo_contato=<?= $tipo_contato; ?>&amp;letra=<?= $i; ?>');"><?= strtoupper($i); ?></a></li>
        <? } ?>
    </ul>
</div>
<? } ?>

<div id="conteudo_interno">
    <? require_once("_contatos/__contato_listar.php"); ?>
</div>

<? } ?>