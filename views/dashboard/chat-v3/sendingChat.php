<?php
if(isset($_GET['name']) && isset($_GET['id'])){
	include 'conexionBD.php';
	$query = 'SELECT * FROM '.$_GET['name'];
	$messages = mysqli_query($conexion, $query);
	$query = 'SELECT * FROM usuarios WHERE id = "'.$_GET['id'].'"';
	$res = mysqli_query($conexion, $query);
	while($row = mysqli_fetch_array($res)){$user = $row;}
	while($message = mysqli_fetch_array($messages)){
?>
<div class="<?php echo $user["id"]==$message["id_usuario"] ? "msg msg-self" : "msg"; ?>" id="<?php echo $message["id_mensaje"]; ?>">
	<div class="msg-header">
		<?php
		$query = 'SELECT nombre FROM usuarios WHERE id = "'.$message["id_usuario"].'"';
		$res2 = mysqli_query($conexion, $query);
		while($row = mysqli_fetch_array($res2)){$user2 = $row;}
		?>
		<b><?php echo $user2["nombre"]; ?></b>
	</div>
	<div class="msg-text">
		<p><?php echo $message["mensaje"]; ?></p>
	</div>
</div>
<?php
}

mysqli_close($conexion);
}
else{
?>
<p>Error en la base de datos</p>
<?php

}
?>