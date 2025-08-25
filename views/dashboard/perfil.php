<?php include_once __DIR__  . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/cambiar-password" class="enlace">Cambiar Password</a>

    <form class="formulario" method="POST" action="/perfil">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input
                type="text"
                value="<?php echo $usuario->nombre; ?>"
                <?php if($_SESSION["tipoUsuario"] === "3" || $_SESSION["tipoUsuario"] === "2"): ?>
                    disabled
                <?php endif; ?>
                name="nombre"
                placeholder="Tu Nombre"
            />
        </div>
        <div class="campo">
            <label for="nombre">Email</label>
            <input
                type="email"
                value="<?php echo $usuario->email; ?>"
                <?php if($_SESSION["tipoUsuario"] === "3" || $_SESSION["tipoUsuario"] === "2"): ?>
                    disabled
                <?php endif; ?>
                name="email"
                placeholder="Tu Email"
            />
        </div>
       

        <div class="campo">
            <label for="tipoUsuario">Tipo</label>
            <input
                type="text"
                disabled
                value="<?php
                    switch($usuario->tipoUsuario){
                        case 1: echo 'Dueño'; break;
                        case 2: echo 'Administrador'; break;
                        case 3: echo 'Trabajador'; break;
                    }
                ?>"
                name="tipoUsuario"
            />
        </div>


        <?php if($_SESSION["tipoUsuario"] === "1"): ?>
           <input type="submit" value="Guardar Cambios">         
        <?php endif; ?>
        
    </form>
</div>

<?php include_once __DIR__ . "/../../chat-v3/index.php"; ?>
<?php include_once __DIR__  . '/footer-dashboard.php'; ?>

