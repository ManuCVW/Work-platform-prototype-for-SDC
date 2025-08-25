<?php 
if(isset($_GET['name'])){
	include 'conexionBD.php';
	$query = "DROP TABLE ".$_GET['name'];
	mysqli_query($conexion, $query);
	$query = "DELETE FROM chats WHERE name = '".$_GET['name']."'";
	mysqli_query($conexion, $query);
}
?>