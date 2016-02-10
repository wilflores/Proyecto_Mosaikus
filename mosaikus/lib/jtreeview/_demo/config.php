<?php
session_name('mosaikus');	
session_start();
        
        chdir('..');
        chdir('..');
        
        //echo dirname(__FILE__);
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))  ."/msks/includes/EnDecryptText.php");
	$EnDecryptText = new EnDecryptText();

	$BD= strtolower($EnDecryptText->Decrypt_Text($_SESSION[BaseDato]));
	//$LBD= $EnDecryptText->Decrypt_Text($CookLogginBD);
	//$PWDBD= $EnDecryptText->Decrypt_Text($CookPasswordDB);
	$IpBD= $EnDecryptText->Decrypt_Text($CookIPDB);

	$LBD= "adm_bd";
	$PWDBD= "672312";
        $LBD= "root";
	$PWDBD= "123456";


	//echo "ssss".$LBD;
	//echo "<br />ssss".$PWDBD;
	//echo "<br />ssss".$BD;


	//$link0 = mysql_connect("localhost","adm_bd","672312");

	//exit;
	// Database config & class
	$db_config = array(
		"servername"=> "localhost",
		"username"	=> "$LBD",
		"password"	=> "$PWDBD",
		"database"	=> "$BD"
	);
	if(extension_loaded("mysqli")) require_once("_inc/class._database_i.php");
	else require_once("_inc/class._database.php");

	// Tree class
	require_once("_inc/class.tree.php");
?>