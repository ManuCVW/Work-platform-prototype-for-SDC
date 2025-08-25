<?php include_once __DIR__  . '/header-dashboard.php';?>


    <?php if(count($proyectos) === 0 ) { ?>
        <p class="no-proyectos">No Hay Proyectos Aún 
            <?php if($_SESSION["tipoUsuario"] < 3 ): ?>
                <a href="/crear-proyecto">Comienza creando uno</a>
            <?php endif; ?>
        </p>
    <?php } else { ?>
        <ul class="listado-proyectos">
            <?php foreach($proyectos as $proyecto) {?>
                <li class="proyecto">
                    <a href="/proyecto?id=<?php echo $proyecto->url; ?>">
                        
                        <?php echo $proyecto->nombre; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>

    <?php include_once __DIR__ . "/../../chat-v3/index.php"; ?>

<?php include_once __DIR__  . '/footer-dashboard.php'; ?>