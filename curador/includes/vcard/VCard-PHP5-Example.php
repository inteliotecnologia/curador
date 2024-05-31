<?php
/**
 +-------------------------------------------------------------------------
 +---------------------->> In The Name Of Allah <<-------------------------
 +-------------------------------------------------------------------------
 | Class VCardIFL version 0.0.1 (for php 5)
 | Script For Create VCard  IFLashLord varsion 0.0.1
 | Vcard Create Online [VCard Creator Full] Example
 | Author  Behrouz Pooladrag  (IFLashLord) <Me [at] IFLashLord [dot] Com>
 | Email bugs/suggestions to  Me [at] iflashlord.com
 | Copyright (c) 2008 By Behrouz Pooladrag ,IFLashLord Co.
 +-------------------------------------------------------------------------
 | This script has been created and released under
 | the GNU GPL and is free to use and redistribute
 | only if this copyright statement is not removed
 +-------------------------------------------------------------------------
 +--------------| Contact 2 Behrouz Pooladrag |----------------------------
 | Email : Me [ at ] IFLashLord [dot] Com
 | WebSite : http://www.IFLashLord.Com
 | Yahoo : BehrouzPC [at] yahoo.Com
 | G-Mail : FLashLordX [at] gmail.Com
 | Mobile : +98 913 12 777 14
 +-------------------------------------------------------------------------
 | (Zakate Elame Nasher Aan Ast )
 +-------------------------------------------------------------------------
**/




             ///
          //****//
      //************//
  //*******************//
//***** FULL EXAMPLE *****//
  //*******************//
     //************//
         //****//
           ///


// setup array Data
$dataArray=array(
	"fileName"=>"vcardx", //file name
	"saveTo"=>"upload",  //upload dir
	
	"vcard_birtda"=>"1367-05-05",              
	"vcard_f_name"=>"Behrouz",
	"vcard_s_name"=>"IFLashLord",                 
	"vcard_uri"=>"http://wp.iflashlord.com",
	"vcard_nickna"=>"IranFLashLord",
	"vcard_note"=>"this is a vcard note!",
	"vcard_cellul"=>"+9891300000",
	"vcard_compan"=>"IFLashLord Studio Web Design Company.",
	"vcard_p_pager"=>"pagerNumber",
	
	"vcard_h_addr"=>"Address of Live Position",
	"vcard_h_city"=>"ShahinShahr",
	"vcard_h_coun"=>"Iran",
	"vcard_h_fax"=>"Faxnumber",
	"vcard_h_mail"=>"HomeMail <me@iflashlord.com>",
	"vcard_h_phon"=>"Home Phone 0312 000000",
	"vcard_h_zip"=>"ZipCode1234567890",
	"vcard_h_uri"=>"Home URi http://www.iflashlord.com",
	
	"vcard_w_addr"=>"Address of Work Position",
	"vcard_w_city"=>"Esfahan",
	"vcard_w_coun"=>"Iran",
	"vcard_w_fax"=>"WorkFax",
	"vcard_w_mail"=>"Work Mail <info@iflashlord.com>",
	"vcard_w_phon"=>"+98311 000000",
	"vcard_w_role"=>"Web Designer & Programer",
	"vcard_w_titl"=>"IFLashLord [TITLE]",
	"vcard_w_zip"=>"WorkZipCode1234567890",
	"vcard_w_uri"=>"Work URi http://about.iflashlord.com"
);

include_once("VCardIFL-PHP5.php");

$vcard=new VCardIFL($dataArray);

//Create Vcart By Your Setup or array
$vcard->createVcard(); 

// Download force your vcard
$vcard->DownloadVcard(); 

//parameter Random name Upload is false
//$vcard->SaveVcard(); 

?>