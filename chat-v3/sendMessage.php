<?php
if(isset($_GET['name']) && isset($_GET['id']) && isset($_GET['msg'])){
	include 'conexionBD.php';

	$query = "INSERT INTO ".$_GET['name']." (id_mensaje, id_usuario, mensaje) VALUES (NULL, '".$_GET['id']."', '".$_GET['msg']."')";
	mysqli_query($conexion, $query);
}
?>