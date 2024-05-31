<?
if (pode("v", $_SESSION["permissao"])) {
	
	if ($_GET["selecionado"]!="") $str= " and   selecionado = '". $_GET["selecionado"] ."' ";
	
	$result= mysql_query("select * from projetos
							where 1=1
							". $str ."
							and   status_projeto <> '0'
							order by ordem asc, data_projeto desc
							");
	
	/*$num= 1000;
	$total = mysql_num_rows($result);
	$num_paginas = ceil($total/$num);
	if ($_GET["num_pagina"]=="") $num_pagina= 0;
	else $num_pagina= $_GET["num_pagina"];
	$inicio = $num_pagina*$num;
	
	$result= mysql_query("select * from projetos
							where 1=1
							". $str ."
							and   status_projeto <> '2'
							order by ordem asc
							limit $inicio, $num
							");*/
	
?>
<script type="text/javascript" src="js/drag-n-drop/jquery-ui-1.7.1.custom.min.js"></script>

<script type="text/javascript">
	$().ready(function() {
		$(function() {
            $("#projetos").sortable({ opacity: 0.8, cursor: 'move', update: function() {
                var order = $(this).sortable("serialize") + '&chamada=atualizaOrdemProjetos'; 
                $.get("link.php", order, function(theResponse){
                    //$("#ordem_retorno").html(theResponse);
                }); 															 
            }								  
            });
        });
	});
	
</script>

<div id="tela_mensagens_acoes">
</div>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="tit tit_maleta">Ordenar projetos</h2>

<p>Arraste e solte para ordenar os projetos.</p>

<ul class="recuo_ordenar" id="projetos">
    <?
    $i=0;
    while ($rs= mysql_fetch_object($result)) {
    ?>
        <li id="linha_<?= $rs->id_projeto; ?>"><?= $rs->projeto_pt; ?></li>
    <? $i++; } ?>
</ul>

<? } ?>