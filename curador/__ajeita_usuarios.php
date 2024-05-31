<?php

$result= mysql_query("select * from usuarios
						");
while ($rs= mysql_fetch_object($result)) {
	//cria_imagem_site(1, $rs->id_imagem);
	
	$result1= mysql_query("insert into pessoas
							(nome_rz, apelido_fantasia, tipo, id_empresa)
							values
							('$rs->nome', '$rs->nome', 'f', '1')
							");
	$id_pessoa= mysql_insert_id();
				
	$result2= mysql_query("insert into pessoas_tipos
							(id_pessoa, tipo_pessoa, status_pessoa, id_empresa)
							values
							('$id_pessoa', 'u', '1', '1')
							");
							
	$result2_5= mysql_query("insert into enderecos
							(id_pessoa)
							values
							('$id_pessoa')
							");
	
	$result3= mysql_query("update usuarios set
							id_pessoa= '$id_pessoa'
							where id_usuario= '$rs->id_usuario'
							");
	
	echo "Usuário ". $rs->nome ." migrado! <br />";
}


?>