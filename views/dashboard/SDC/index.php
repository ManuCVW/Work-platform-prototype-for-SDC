<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>WorkWise - Inicio</title>

  <!-- Evitar flash blanco si estaba en tema oscuro -->
  <script>
    if (localStorage.getItem('tema') === 'oscuro') {
      document.documentElement.classList.add('tema-oscuro');
    }
  </script>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../build/css/appwoo.css">
    <style>

        .cerrar {
            background-color: var(--color-boton);
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-decoration: none;
        }

            .cerrar:hover {
                background-color: var(--color-boton-hover);
            }
    </style>
</head>
<body>

<header>
    <a href="index">
        <div class="workwise-logo">
            <div class="logo-icon">WW</div>
        </div>
    </a>
        <div class="workwise-logo">
        <span class="logo-text">WorkWise</span>
    </div>
    
    <nav>
        <a href="sectores">Sectores</a>
        <a href="localidades">Localidades</a>
        <a href="clientes">Clientes</a>
        <a href="creditos">Créditos</a>
        <a href="abonos">Abonos</a>
        <a href="reportes">Reportes</a>
        <a href="configuracion">Configuración</a>
    </nav>

    <div class="botones">
        <button onclick="cambiarTema()">Tema</button>
        <a class="cerrar" href="/logout">Cerrar sesión</a>
    </div>
</header>


<main>
    <h1>Bienvenido a WorkWise</h1>
    <p>Selecciona una opción del menú para comenzar.</p>
</main>



<script>
    function cerrarSesion() {
        // Aquí puedes limpiar datos si es necesario, por ejemplo:
        // localStorage.clear();
        // Redirigir al login
        window.location.href = 'login.html';
    }
</script>



<!-- Script para cambiar tema -->
<script>
    function cambiarTema() {
        const html = document.documentElement;
        // Alternar la clase 'tema-oscuro' en el body
        html.classList.toggle('tema-oscuro');
        // Verificar si el tema oscuro está activado
        const esTemaOscuro = html.classList.contains('tema-oscuro');
        // Guardar la preferencia en localStorage
        localStorage.setItem('tema', esTemaOscuro ? 'oscuro' : 'claro');
    }

    window.onload = function() {
        // Recuperar el tema guardado desde localStorage
        const temaGuardado = localStorage.getItem('tema');
        // Si se ha guardado un tema oscuro, activarlo
        if (temaGuardado === 'oscuro') {
            document.documentElement.classList.add('tema-oscuro');
        }
    }
</script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php include_once __DIR__ . "/../../../chat-v3/index.php"; ?>