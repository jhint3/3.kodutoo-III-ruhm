<?php 
	// functions.php
	require("/home/jooshint/config.php");
	
	// et saab kasutada $_SESSION muutujaid
	// kõigis failides mis on selle failiga seotud
	session_start(); 
	
	/* ÜHENDUS */
	$database = "if16_jooshint_3";
	$mysqli = new mysqli($serverHost, $serverUsername,  $serverPassword, $database);

	
?>