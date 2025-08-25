<?php
include 'conexionBD.php';
$query = "SELECT * FROM chats";
$res = mysqli_query($conexion, $query);
while ($chat = mysqli_fetch_array($res)) {
	$users = explode(",", $chat["users"]);
	?>
<li class="item-list-chat" id="<?php echo $chat["name"]; ?>">
	<table>
	<tr>
	<td class="item-list-chat-name">
		<h2>
			<?php echo $chat["name"]; ?>
		</h2>
	</td>
	<td class="item-list-chat-users">
		<h3>
			Miembros del chat:
		</h3>
		<?php
		for ($i=1; $i < count($users)-1; $i++) { 
			$query = "SELECT id,nombre,email FROM usuarios WHERE id = ".$users[$i];
			$res2 = mysqli_query($conexion, $query);
			while($row2 = mysqli_fetch_array($res2)){ $dataUser = $row2; }
		?>
		<div class="item-list-chat-users-user">
			<span class="item-list-chat-users-user-name" id="<?php echo $dataUser["id"]."-name"; ?>">
				<?php echo $dataUser["nombre"]; ?>
			</span>
			<span class="item-list-chat-users-user-email" id="<?php echo $dataUser["id"]."-email"; ?>">
				<?php echo $dataUser["email"]; ?>
			</span>
			<input type="hidden" class="item-list-chat-users-user-<?php echo $chat["name"]; ?>" value="<?php echo $dataUser["id"]; ?>">
		</div>
		<?php

		}
		?>
	</td>
	<td class="item-list-chat-btn">
		<button id="chat-modify" onclick="chatModify('<?php echo $chat["name"]; ?>')">
			Modificar
		</button>
	</td>
	<td class="item-list-chat-btn">
		<button id="chat-delete" onclick="chatDelete('<?php echo $chat["name"]; ?>')">
			Eliminar
		</button>
	</td>
	</tr>
	</table>
</li>
	<?php
}
if(!isset($res)){
	?>
<div class="empty-chats">
	<h2>
		No hay chats que mostrar
	</h2>
</div>
	<?php
}
?>