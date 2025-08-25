<?php
session_start();
// SOLO TESTEO
//------------------------------------------
//if(!isset($_SESSION['id'])){
//	header("location: index.php");
//}
//------------------------------------------

include 'conexionBD.php';
if(isset($_SESSION["id"])){
	$query = 'SELECT * FROM usuarios WHERE id = ' . $_SESSION['id'];
	$res = mysqli_query($conexion, $query);
	while($row = mysqli_fetch_array($res)){$user = $row;}
}
?>
<div class="chat" <?php if(isset($_SESSION['id'])){ echo 'id="'.$_SESSION['id'].'"'; } ?>>
	<link rel="stylesheet" type="text/css" href="../build/css/appalan.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="../build/js/chatalan.js"></script>
	<div class="sending-chat">
		<div class="top-sending-chat">
			<b id="name-sending-chat">
				sdsdfkjghfjkdgfkjhg
			</b>
			<button id="hide-sending-chat">
				<img src="../build/img/arrow.png">
			</button>
			<button id="close-sending-chat">
				<img src="../build/img/closeIcon.png">
			</button>
		</div>
		<div class="content-sending-chat">

		</div>
		<div class="message-sending-chat">
			<table>
				<tr>
					<th>
						<input type="text" name="txt-send-chat" id="txt-send-chat">
					</th>
					<th>
						<button id="btn-send-chat">
							<b>></b>
						</button>
					</th>
				</tr>
			</table>
		</div>
	</div>
	<div class="box-chat" isactive="false">
		<button type="button" id="img-chat">
			<img src="../build/img/chatIcon.png">
		</button>
		<div class="top-box-chat">
			<b>
				Chats
			</b>
			<button id="close-chat">
				<img src="../build/img/closeIcon.png">
			</button>
		</div>
		<div class="content-box-chat">
		</div>
	</div>
</div>