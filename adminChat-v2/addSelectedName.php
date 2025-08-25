<?php
include 'conexionBD.php';

if(isset($_GET['id']) && isset($_GET['view'])){
	$query = "SELECT * FROM usuarios WHERE id = ".$_GET['id'];
	$res = mysqli_query($conexion, $query);
	while($row = mysqli_fetch_array($res)){ $user = $row; }
	?>
<div class="user-action-chat" id="user-action-chat-<?php echo $_GET['id']; ?>">
	<span>
		<?php
		echo $user["nombre"];
		?>
	</span>
	<button onclick="deleteUser(<?php echo $_GET['id']; ?>, <?php echo $_GET['view']; ?>)">
		eliminar
	</button>
</div>
	<?php
}
?>