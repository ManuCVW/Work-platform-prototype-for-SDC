<?php
if(isset($_GET['id'])){
	include 'conexionBD.php';
	$query = 'SELECT * FROM usuarios WHERE id = ' . $_GET['id'];
	$res = mysqli_query($conexion, $query);
	while($row = mysqli_fetch_array($res)){$user = $row;}
	//$query = 'SELECT * FROM chats WHERE users LIKE "%%,'.$_GET["id"].',%%"';
	$query = "SELECT * FROM chats WHERE users LIKE '%%,".$_GET['id'].",%%'";
	$res = mysqli_query($conexion, $query);
	while($row = mysqli_fetch_array($res)){
?>
<button class="selection-chat" id="<?php echo $row["name"]; ?>" onClick="selectChat('<?php echo $row["name"]; ?>')">
	<?php echo $row["name"]; ?>
</button>
<?php
}

mysqli_close($conexion);
}
else{
?>
<p>No iniciado sesión...</p>
<?php

}
?>