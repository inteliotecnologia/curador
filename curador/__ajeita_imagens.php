<?php

$result= mysql_query("select * from imagens
						where tipo_imagem = '". $_GET["tipo_imagem"] ."'
						and   id_externo = '". $_GET["id_externo"] ."'
						and   site = '1'
						");
while ($rs= mysql_fetch_object($result)) {
	//cria_imagem_site(1, $rs->id_imagem);
	
	echo "Imagem ". $rs->id_imagem ." processada! <br />";
	
	echo "<img src='link.php?chamada=imagemSite&id=". $rs->id_imagem ."&site=1'>";
}


?>