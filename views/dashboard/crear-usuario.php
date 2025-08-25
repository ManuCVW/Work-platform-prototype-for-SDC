
<?php 
    include_once __DIR__  . '/header-dashboard.php';  
    
?>

    <div class="contenedor-sm">

        <?php if($_GET["resultado"] === "1"  ) :?>
            <div class="alerta exito">Se han enviado indicaciones al correo del usuario</div>
        <?php endif;?>

        <div class="contenedor crear">
            <h1 class="uptask">WorkWise</h1>

            <div class="contenedor-sm">
        <?php include_once __DIR__ .'/../templates/alertas.php'; ?>

        <form class="formulario" method="POST" action="/crear-usuario">

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                    type="text"
                    id="nombre"
                    placeholder="Nombre"
                    name="nombre"
                    value="<?php echo $usuario->nombre; ?>"
                />
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Email"
                    name="email"
                    value="<?php echo $usuario->email; ?>"
                />
            </div>

            <div class="campo">
                <label for="tipoUsuario">Tipo</label>
                <input 
                    type="number"
                    
                    <?php if($_SESSION["tipoUsuario"] === "1"): ?>
                        min="2"
                        max="4"
                    <?php endif; ?>

                    <?php if($_SESSION["tipoUsuario"] === "2"): ?>
                        min="3"
                        max="4"
                    <?php endif; ?>
                    
                    id="tipoUsuario"
                    placeholder="Tipo de Usuario"
                    name="tipoUsuario"
                    value="<?php echo $usuario->tipoUsuario; ?>"
                />
            </div>

            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Password"
                    name="password"
                />
            </div>

            <div class="campo">
                <label for="password2">Repetir Password</label>
                <input 
                    type="password"
                    id="password2"
                    placeholder="Repite el Password"
                    name="password2"
                />
            </div>

            <input type="submit" class="boton" value="Crear Nuevo Usuario">
        </form>
    </div> <!--.contenedor-sm -->
</div>
    </div>

<?php include_once __DIR__ . "/../../chat-v3/index.php"; ?>

<?php include_once __DIR__  . '/footer-dashboard.php'; ?>