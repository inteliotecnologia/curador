<?php

/*
 * File:
 *      vbook.php
 *
 * Project:
 *      vCard PHP <http://vcardphp.sourceforge.net>
 *
 * Author:
 *      Frank Hellwig <frank@hellwig.org>
 *
 * Usage:
 *      http://host/path/vbook.php?file=contacts.vcf
 *      See the index.html file for additional parameters.
 */

require("includes/vcard/vcard.php");

$file= $targetFile;

if (file_exists($file)) insert_vcard_address_book($file, $_POST["id_empresa"], $_POST["id_pessoa"], $_POST["tipo_pessoa"], $_POST["id_usuario"]);
else echo "Arquivo não encontrado para importação.";

function insert_vcard_address_book($file, $id_empresa, $id_pessoa, $tipo_pessoa, $id_usuario) {
    
    $lines = file($file);
    
	if (!$lines) {
        exit("Can't read the vCard file: $file");
    }
    
	$cards = parse_vcards($lines);
    
	print_vcards($cards, $id_empresa, $id_pessoa, $tipo_pessoa, $id_usuario);
}

/**
 * Prints a set of vCards in two columns. The $categories_to_display is a
 * comma delimited list of categories.
 */   
function print_vcards(&$cards, $id_empresa, $id_pessoa, $tipo_pessoa, $id_usuario) {
    
    //echo "<p class='categories'>\nCategories: ";
    //echo join(', ', $categories_to_display);
    //echo "<br />\n</p>\n";

    $i = 0;
    foreach ($cards as $card_name => $card) {
        //if (!$card->inCategories($categories_to_display)) {
        //   continue;
        //}
       
        //echo "<p class='name'><strong>$card_name</strong></p>";
		
        // Add the categories (if present) after the name.
        $property = $card->getProperty('CATEGORIES');
        
		if ($property) {
            // Replace each comma by a comma and a space.
            $categories = $property->getComponents(',');
            $categories = join(', ', $categories);
            //echo "&nbsp;($categories)";
        }
        
		$nome_index= trim($card_name);
		
		$result_pre= mysql_query("select * from contatos
									where id_empresa='$id_empresa'
									and   id_pessoa='$id_pessoa'
									and   tipo_contato='$tipo_pessoa'
									and   nome_index='$nome_index'
									and   status_contato <> '2'
									") or die(mysql_error());
		$linhas_pre= mysql_num_rows($result_pre);
		
		if ($linhas_pre==0) {
			$result_insere= mysql_query("insert into contatos
											(id_empresa, id_pessoa, tipo_contato, nome_index, status_contato, vcard, id_usuario)
											values
											('$id_empresa', '$id_pessoa', '$tipo_pessoa', '$nome_index', '1', '1', '$id_usuario')
											");
			$id_contato= mysql_insert_id();
			$modo='i';
		}
		else {
			$rs_pre= mysql_fetch_object($result_pre);
			
			$id_contato= $rs_pre->id_contato;
			$modo='e';
			
			$result_limpa= mysql_query("delete from contatos_telefones
										where id_contato = '$id_contato'
										and   id_empresa = '$id_empresa'
										");
		}
		
        print_vcard($card, $modo, $id_contato, $id_empresa, $id_usuario);
		
    }
}

/**
 * Prints the vCard as HTML.
 */
function print_vcard($card, $modo, $id_contato, $id_empresa, $id_usuario) {
    $names = array('FN', 'TITLE', 'ORG', 'TEL', 'EMAIL', 'URL', 'ADR', 'BDAY', 'NOTE');

    $row = 0;

    foreach ($names as $name) {
        /*if (in_array_case($name, $hide)) {
            continue;
        }*/
		
        $properties = $card->getProperties($name);
		
		
		//echo "<strong>". $properties ."</strong><br />";
		
        if ($properties) {
            foreach ($properties as $property) {
                $show = true;
                $types = $property->params['TYPE'];
                if ($types) {
                    foreach ($types as $type) {
                        /*if (in_array_case($type, $hide)) {
                            $show = false;
                            break;
                        }*/
                    }
                }
				
                if ($show) {
                    $class = ($row++ % 2 == 0) ? "property-even" : "property-odd";
                    print_vcard_property($property, $class, $modo, $id_contato, $id_empresa, $id_usuario);
                }
            }
        }
    }
}

/**
 * Prints a VCardProperty as HTML.
 */
function print_vcard_property($property, $class, $modo, $id_contato, $id_empresa, $id_usuario) {
    $name = $property->name;
    $value = $property->value;
    
	switch ($name) {
        case 'ADR':
            $adr = $property->getComponents();
            $lines = array();
            for ($i = 0; $i < 3; $i++) {
                if ($adr[$i]) {
                    $lines[] = $adr[$i];
                }
            }
            $city_state_zip = array();
            for ($i = 3; $i < 6; $i++) {
                if ($adr[$i]) {
                    $city_state_zip[] = $adr[$i];
                }
            }
            if ($city_state_zip) {
                // Separate the city, state, and zip with spaces and add
                // it as the last line.
                $lines[] = join("&nbsp;", $city_state_zip);
            }
            // Add the country.
            if ($adr[6]) {
                $lines[] = $adr[6];
            }
            //$html = "(ADR) ". join("\n", $lines);
            break;
        case 'EMAIL':
            //$html = "(EMAIL) ". "<a href='mailto:$value'>$value</a>";
			
			$result_atualiza= mysql_query("update contatos set
											email= '$value'
											where id_contato = '$id_contato'
											");
													
            break;
        case 'URL':
            //$html = "(URL) ". "<a href='$value' target='_base'>$value</a>";
            break;
		case 'NOTE':
            //$html = "(NOTE) ". "$value";
			
			$result_atualiza= mysql_query("update contatos set
											obs= '$value'
											where id_contato = '$id_contato'
											");
											
            break;
        case 'BDAY':
            //$html = "(BDAY) ". "Birthdate: $value";
            break;
        case 'FN':			
			$result_atualiza= mysql_query("update contatos set
											nome= '$value'
											where id_contato = '$id_contato'
											");
			
			if ($modo=='i') $verbo= "Importando";
			elseif ($modo='e') $verbo= "Atualizando";
			
			$html = $verbo ." <strong>$value</strong>.......... OK <br />";
								
            break;
		case 'TEL':
            $components = $property->getComponents();
            $lines = array();
            foreach ($components as $component) {
                if ($component) {
                    $lines[] = $component;
                }
            }
            //$html = "(TEL) ". join("\n", $lines);
            break;
		default:
            $components = $property->getComponents();
            $lines = array();
            foreach ($components as $component) {
                if ($component) {
                    $lines[] = $component;
                }
            }
            //$html = "(ELSE) ". join("\n", $lines);
            break;
    }
	
    //echo "<p class='$class'>\n";
    echo nl2br(stripcslashes($html));
	
    $types = $property->params['TYPE'];
    if ($types) {
        $type = join(", ", $types);
		
		switch (strtolower($type)) {
			case 'work': $tipo_telefone_novo=2; break;
			case 'cell': $tipo_telefone_novo=3; break;
			default: $tipo_telefone_novo=2; break;
		}
		
		//echo " | ";
		
        //echo " (" . (strtolower($type)) . ")";
    }
	
	if ($name=='TEL') {
		
		if ($tipo_telefone_novo=="") $tipo_telefone_novo=2;
		
		$result6_tel= mysql_query("insert into contatos_telefones
								(id_empresa, id_contato, telefone, tipo, obs, vcard) values
								('". $id_empresa ."', '". $id_contato  ."', '". join("\n", $lines) ."',
								'". $tipo_telefone_novo ."', '', '1')
								") or die(mysql_error());
	}
	
    //echo "\n</p>\n";
}


/**
 * Parses a set of cards from one or more lines. The cards are sorted by
 * the N (name) property value. There is no return value. If two cards
 * have the same key, then the last card parsed is stored in the array.
 */
function parse_vcards(&$lines)
{
    $cards = array();
    $card = new VCard();
    while ($card->parse($lines)) {
        $property = $card->getProperty('N');
        if (!$property) {
            return "";
        }
        $n = $property->getComponents();
        $tmp = array();
        if ($n[3]) $tmp[] = $n[3];      // Mr.
        if ($n[1]) $tmp[] = $n[1];      // John
        if ($n[2]) $tmp[] = $n[2];      // Quinlan
        if ($n[4]) $tmp[] = $n[4];      // Esq.
        $ret = array();
        if ($n[0]) $ret[] = $n[0];
        $tmp = join(" ", $tmp);
        if ($tmp) $ret[] = $tmp;
        $key = join(", ", $ret);
        $cards[$key] = $card;
        // MDH: Create new VCard to prevent overwriting previous one (PHP5)
        $card = new VCard();
    }
    ksort($cards);
    return $cards;
}

// ----- Utility Functions -----

/**
 * Checks if needle $str is in haystack $arr but ignores case.
 */
function in_array_case($str, $arr)
{
    foreach ($arr as $s) {
        if (strcasecmp($str, $s) == 0) {
            return true;
        }
    }
    return false;
}

?>
