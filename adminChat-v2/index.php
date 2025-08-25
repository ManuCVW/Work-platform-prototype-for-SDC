<!--EL INDEX ES SOLO PARA TESTEO-->
<!--PARA ASIGNAR UNA ID A LA SESION-->
<!DOCTYPE html>
<?php
//true if test
if(false){
session_start();
if(isset($_POST['name'])){
	$_SESSION['id'] = $_POST['name'];
	header("location: adminChat.php");
}

include_once __DIR__ . '/conexionBD.php';
$query = 'SELECT * FROM usuarios';
$res = mysqli_query($conexion, $query);
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Test chat</title>
</head>
<body>
	<form method="post">
		<label>Seleccionar un usuario para la sesión:</label>
		<?php
		while($row = mysqli_fetch_array($res)){
			?>
		<input type="radio" name="name" value="<?php echo $row['id'];?>" id="<?php echo $row['nombre'];?>">
		<label for="<?php echo $row['nombre'];?>"><?php echo $row['nombre'];?></label>
			<?php
		}
		?>
		<input type="submit" value="Aceptar">
	</form>
</body>
</html>
<?php
}
else{
	include_once __DIR__ . '/adminChat.php';
}
?>