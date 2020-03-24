<?php
//Comprueba en la BD que el usuario no exista
	$servername = "localhost";
	$username = "qql267";
	$password = "Fabiete2";
	$db = "qql267.acquajet.com";

	$conexion = mysqli_connect($servername, $username, $password,$db);

	if ($conexion)
	{
	   
	}
	mysqli_close($conexion);  
?>