<?php
	require("../config.php");
	/* ALUSTAN SESSIOONI */
	session_start();
		
	/* ÜHENDUS */
	$database = "if16_geithy";
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	
?>



