<?
require_once("includes/conexao.php");
if (pode_algum("r", $_SESSION["permissao"])) {
	if ($acao=="") $acao= $_GET["acao"];
	
	if ($_GET["id_contato"]!="") $id_contato= $_GET["id_contato"];
	if ($_POST["id_contato"]!="") $id_contato= $_POST["id_contato"];
	
	if ($_GET["esquema"]!="") $esquema= $_GET["esquema"];
	if ($_POST["esquema"]!="") $esquema= $_POST["esquema"];
?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="tit tit_jarro">Contatos</h2>

<?
include("_contatos/__contato_interno_abas.php");
?>
    
<fieldset>
    <legend><a href="javascript:void(0);" rel="ef">Notas</a></legend>
    
    <div id="ef" class="fieldset_inner aberto">
        <div id="conteudo_form">
            <? require_once("_financeiro/__pessoa_nota_form.php"); ?>
        </div>
    </div>
</fieldset>
<br />


<h2 class="tit tit_nota">Notas</h2>

<?
$result_nota= mysql_query("select * from pessoas_notas
                                where id_empresa = '". $_SESSION["id_empresa"] ."'
                                and   id_pessoa = '". $id_contato ."'
                                and   status_nota <> '2'
                                and   tipo_nota = 'c'
                                order by data_nota desc, id_pessoa_nota desc
                                ") or die(mysql_error());
$linhas_nota= mysql_num_rows($result_nota);

if ($linhas_nota==0) echo "Nenhuma nota cadastrada.";
else {
	$result_nota_count_0= mysql_query("select * from pessoas_notas
		                                where id_empresa = '". $_SESSION["id_empresa"] ."'
		                                and   id_pessoa = '". $id_contato ."'
		                                and   status_nota <> '2'
		                                and   tipo_nota = 'c'
		                                and   avaliacao = '0'
		                                ") or die(mysql_error());
    $linhas_nota_count_0= mysql_num_rows($result_nota_count_0);
    
    $result_nota_count_1= mysql_query("select * from pessoas_notas
		                                where id_empresa = '". $_SESSION["id_empresa"] ."'
		                                and   id_pessoa = '". $id_contato ."'
		                                and   status_nota <> '2'
		                                and   tipo_nota = 'c'
		                                and   avaliacao = '1'
		                                ") or die(mysql_error());
    $linhas_nota_count_1= mysql_num_rows($result_nota_count_1);
?>

<div class="lateral_titulo">
	<div class="flutuar_esquerda"><?= $linhas_nota_count_0; ?></div> <div class="thumb thumb_0"></div> <div class="flutuar_esquerda">|</div> <div class="flutuar_esquerda"><?= $linhas_nota_count_1; ?></div> <div class="thumb thumb_1"></div>
</div>

<table id="tabela" class="tablesorter" cellspacing="0" width="100%">
<thead>
<tr>
  <th width="11%" align="left" valign="bottom">Data</th>
  <th width="55%" align="left" valign="bottom">Nota</th>
  <th width="12%" align="left" valign="bottom">Avaliação</th>
  <th width="11%" align="left" valign="bottom">Por</th>
  <th width="11%" align="left" valign="bottom">A&ccedil;&otilde;es</th>
</tr>
</thead>
<tbody>
<?
$i=0;
while ($rs_nota= mysql_fetch_object($result_nota)) {
?>
<tr id="linha_<?=$rs_nota->id_pessoa_nota;?>" class="linha_<?= $rs_nota->avaliacao;?>">
    <td valign="top"><?= desformata_data($rs_nota->data_nota); ?></td>
    <td valign="top"><?= nl2br($rs_nota->nota); ?></td>
    <td valign="top">
		<div class="thumb thumb_<?= $rs_nota->avaliacao;?>"></div>
    </td>
    <td valign="top"><?= primeira_palavra(pega_nome_usuario($rs_nota->id_usuario)); ?></td>
    <td valign="top">
        <a href="./?pagina=contatos/contato_notas&amp;origem=<?= $origem; ?>&amp;acao=e&amp;id_contato=<?= $rs_nota->id_pessoa; ?>&amp;id_pessoa_nota=<?= $rs_nota->id_pessoa_nota; ?>">
            <img border="0" src="images/ico_lapis.png" alt="Edita" /></a>
        |
        <a href="javascript:apagaLinha('notaExcluir', <?=$rs_nota->id_pessoa_nota;?>);" onclick="return confirm('Tem certeza que deseja excluir?');">
            <img border="0" src="images/ico_lixeira.png" alt="Status" /></a>
    </td>
</tr>
<?
    $i++;
}
?>
</tbody>
</table>
<? } ?>

    

<? } ?>