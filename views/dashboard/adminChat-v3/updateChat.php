<?php

include 'conexionBD.php';

if(isset($_GET['name']) && isset($_GET['users']) && isset($_GET['nName'])){

	$query = "ALTER TABLE ".$_GET['name']." RENAME TO ".$_GET['nName'];
	mysqli_query($conexion, $query);

	$query = "UPDATE chats SET name = '".$_GET['nName']."', users = '".$_GET['users']."' WHERE name = '".$_GET['name']."'";
	mysqli_query($conexion, $query);
}
?>