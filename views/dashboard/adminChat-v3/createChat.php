<?php

include 'conexionBD.php';

if(isset($_GET['name']) && isset($_GET['users'])){

	$query = "INSERT INTO chats (name, users) VALUES ('".$_GET['name']."', '".$_GET['users']."')";
	mysqli_query($conexion, $query);

	$query = "CREATE TABLE ".$_GET['name']." (id_mensaje INT(20) NOT NULL AUTO_INCREMENT , id_usuario INT(10) NOT NULL , mensaje TEXT NOT NULL , PRIMARY KEY (id_mensaje)) ENGINE = InnoDB";
	mysqli_query($conexion, $query);
}
?>