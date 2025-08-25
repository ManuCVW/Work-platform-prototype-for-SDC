<div class="adminChat">
	<link rel="stylesheet" type="text/css" href="adminChat.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="adminChat.js"></script>
<?php
session_start();
if (!isset($_SESSION['id'])) {
	?>
<div class="error">
	<h2>No iniciado sesion</h2>
</div>
	<?php
}
else
{
include 'conexionBD.php';
$query = "SELECT * FROM usuarios WHERE id = ".$_SESSION['id'];
$res = mysqli_query($conexion, $query);
while($row = mysqli_fetch_array($res)){
	$user = $row;
}

if($user["tipoUsuario"] < 3){ //1-. Dueño, 2-. Admin, 3-. Trabajador
	//Mostrar Chats Habilitados (con su respectiva opcion de modificar y eliminar)
	//Apartado para agregar nuevo chat
	$query = "SELECT * FROM usuarios";
	$users = mysqli_query($conexion, $query);
	?>
	<!-- <h1 class="list-chat-title">
		Lista de chats
	</h1> -->
	<ul class="list-chat">
		<!--Aqui se inserta la actualizacion de los chats-->
	</ul>
	<button id="chat-new" onclick="chatCreate()">
		Crear un nuevo chat
	</button>
	<div class="blur pos-fixed" id="creating-chat" hidden>
		<div>
		<div class="box-creating-chat">
			<div>
				<h2>
					Nuevo grupo
				</h2>
			</div>
			<div>
				<label for="txt-chat-name-creating-chat">
					Nombre del chat:
				</label>
				<input type="text" name="txt-chat-name-creating-chat" id="txt-chat-name-creating-chat">
			</div>
			<div>
				<label for="select-name-creating-chat">

				</label>
				<select name="select-name-creating-chat" id="select-name-creating-chat">
					<?php
					while($allUser = mysqli_fetch_array($users)){
						?>
					<option value="<?php echo $allUser["id"]; ?>">
						<span>
							<?php echo $allUser["nombre"]; ?>
						</span>
						<span>
							<?php echo $allUser["email"]; ?>
						</span>
					</option>
						<?php
					}
					?>
				</select>
				<button id="accept-name-creating-chat" onclick="addUser(0)">
					Agregar
				</button>
			</div>
			<div id="list-names-creating-chat">
				<!--Insertar los nombres seleccionados-->
			</div>
			<div>
				<button id="cancel-creating-chat" onclick="cancelCreating()">
					Cancelar
				</button>
				<button id="send-creating-chat" onclick="acceptCreating()">
					Crear
				</button>
			</div>
		</div>
		</div>
	</div>

	<div class="blur pos-fixed" id="updating-chat" hidden>
		<div>
		<div class="box-updating-chat">
			<div>
				<h2>
					Nuevo grupo
				</h2>
			</div>
			<div>
				<label for="txt-chat-name-updating-chat">
					Nombre del chat:
				</label>
				<input type="text" name="txt-chat-name-updating-chat" id="txt-chat-name-updating-chat">
			</div>
			<div>
				<label for="select-name-updating-chat">

				</label>
				<select name="select-name-updating-chat" id="select-name-updating-chat">
					<?php
					$users = mysqli_query($conexion, $query);
					while($allUser = mysqli_fetch_array($users)){
						?>
					<option value="<?php echo $allUser["id"]; ?>">
						<span>
							<?php echo $allUser["nombre"]; ?>
						</span>
						<span>
							<?php echo $allUser["email"]; ?>
						</span>
					</option>
						<?php
					}
					?>
				</select>
				<button id="accept-name-updating-chat" onclick="addUser(1)">
					Agregar
				</button>
			</div>
			<div id="list-names-updating-chat">
				<!--Insertar los nombres seleccionados-->
			</div>
			<div>
				<button id="cancel-updating-chat" onclick="cancelUpdating()">
					Cancelar
				</button>
				<button id="send-updating-chat" onclick="acceptUpdating()">
					Actualizar
				</button>
			</div>
		</div>
		</div>
	</div>
	<?php
}
else{

	?>
<div class="error">
	<h2>No tienes acceso a este apartado</h2>
</div>
	<?php
}
}
?>
</div>