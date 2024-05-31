<?
require_once("includes/conexao.unico.php");
require_once("includes/funcoes.php");
//include_once("includes/vcard/VCardIFL-PHP5.php");

if (pode_algum("vr", $_SESSION["permissao"])) {
	if ($acao=="") $acao= $_GET["acao"];
	
	if ($_GET["tipo_pessoa"]!="") $tipo_pessoa= $_GET["tipo_pessoa"];
	if ($_POST["tipo_pessoa"]!="") $tipo_pessoa= $_POST["tipo_pessoa"];
	
	if ($_GET["status_pessoa"]!="") $status_pessoa= $_GET["status_pessoa"];
	if ($_POST["status_pessoa"]!="") $status_pessoa= $_POST["status_pessoa"];
	if ($status_pessoa=="") $status_pessoa= 1;
	
	if ($_GET["pessoa_id_pessoa"]!="") $pessoa_id_pessoa= $_GET["pessoa_id_pessoa"];
	if ($_POST["pessoa_id_pessoa"]!="") $pessoa_id_pessoa= $_POST["pessoa_id_pessoa"];
	
	if ($_GET["id_pessoa"]!="") $id_pessoa= $_GET["id_pessoa"];
	if ($_POST["id_pessoa"]!="") $id_pessoa= $_POST["id_pessoa"];
	
	if ($_GET["esquema"]!="") $esquema= $_GET["esquema"];
	if ($_POST["esquema"]!="") $esquema= $_POST["esquema"];
	
	$result= mysql_query("select * from  contatos
								where id_empresa = '". $_SESSION["id_empresa"] ."'
								and   id_pessoa = '". $id_pessoa ."'
								and   status_contato <> '2'
								order by nome asc
								") or die(mysql_error());
								
	$linhas= mysql_num_rows($result);
	
	$vcard_arquivo="";
	//$vcard_tz = date("O");
	$vcard_rev = date("Y-m-d");
	
	while ($rs= mysql_fetch_object($result)) {
		
		// Start Vcard Scritp
		$vcard_arquivo .= "BEGIN:VCARD\r\n";
		$vcard_arquivo .= "VERSION:3.0\r\n";
		$vcard_arquivo .= "REV:" . $vcard_rev . "\r\n";
		//$vcard_arquivo .= "TZ:" . $vcard_tz . "\r\n";
	
		$vcard_arquivo .= "FN:" . $rs->nome ."\r\n";
		
		if ($rs->cargo != ""){
			$vcard_arquivo .= "ROLE:". $rs->cargo ."\r\n";
		}
		
		if ($rs->obs!=""){
			$vcard_arquivo .= "NOTE:" . $rs->obs . "\r\n";
		}
	
	   // vcard_w_mail
		if ($rs->email!=""){
			$vcard_arquivo .= "EMAIL;TYPE=INTERNET,PREF:" . $rs->email . "\r\n";
		}
		if ($rs->email_alternativo!=""){
			$vcard_arquivo .= "EMAIL;TYPE=INTERNET:" . $rs->email_alternativo . "\r\n";
		}
		
		$result_tel= mysql_query("select * from contatos_telefones
									where id_empresa = '". $_SESSION["id_empresa"] ."'
									and   id_contato = '". $rs->id_contato ."'
									order by id asc
									");
		
		while ($rs_tel= mysql_fetch_object($result_tel)) {
			
			switch($rs_tel->tipo) {
				case 1: $classe_tel= "VOICE,HOME"; break;
				case 2: $classe_tel= "VOICE,WORK"; break;
				case 3: $classe_tel= "VOICE,CELL"; break;
				case 4: $classe_tel= "FAX,WORK"; break;
				default: $classe_tel= "VOICE"; break;
			}
			
			$vcard_arquivo .= "TEL;TYPE=". $classe_tel .":" . $rs_tel->telefone . "\r\n";
		}
		
	   // vcard_uri
		if ($rs->site!=""){
			$vcard_arquivo .= "URL:" . $rs->site . "\r\n";
		}
	
		// vcard_h_uri
		if ($rs->blog != ""){
			$vcard_arquivo .= "URL;HOME:" . $rs->blog . "\r\n";
		}
	
		// End of Script Vcard
		$vcard_arquivo .= "END:VCARD\n\n";
	
	}

	$caminho= "uploads/vcards/";
	$arquivo= $caminho . "curador_vCard_". retira_acentos(pega_pessoa($_GET["id_pessoa"])) ."_". date("Y_m_d__H_i_s") .".vcf";
	
	$handel= @fopen($arquivo, "w");
	$write=@fwrite($handel, $vcard_arquivo, strlen($vcard_arquivo));
	@fclose($handel);
	
	header("Content-type: text/directory");
	header("Content-Disposition: attachment; filename=".$arquivo."");
	header("Pragma: public");
	print $vcard_arquivo;
	
}
?>