<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WorkWise - Sectores y Localidades</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tus estilos personalizados -->
    <link rel="stylesheet" href="../build/css/appwoo.css">

    <style>
        #tablaSectores, #tablaLocalidades {
            max-height: 400px;
            overflow-y: auto;
        }
        table {
            font-size: 0.9rem;
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
        <a href="reportes">Datos</a>
        <a href="configuracion">Perfil</a>
    </nav>

    <div class="botones">
        <button onclick="cambiarTema()">Tema</button>
        <button onclick="cerrarSesion()">Cerrar sesión</button>
    </div>
</header>

<main class="container mt-3">
    <h1 class="mb-4">Gestionar Datos</h1>


    <!-- Botones de gestión -->
    <div class="mb-4">
        <button onclick="descargarDatos()" class="btn btn-info btn-sm me-2">Descargar Datos</button>
        <button onclick="cargarDatos()" class="btn btn-success btn-sm me-2">Cargar Datos</button>
        <button onclick="eliminarDatos()" class="btn btn-danger btn-sm">Eliminar Datos</button>
    </div>

    <!-- Tablas para Sectores y Localidades -->
    <div id="tablaSectores" class="table-responsive">
        <h3>Sectores</h3>
        <table class="table table-striped table-sm">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Número</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tbodySectores">
                <!-- Sectores cargados aparecerán aquí -->
            </tbody>
        </table>
    </div>

    <div id="tablaLocalidades" class="table-responsive">
        <h3>Localidades</h3>
        <table class="table table-striped table-sm">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Sector</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tbodyLocalidades">
                <!-- Localidades cargadas aparecerán aquí -->
            </tbody>
        </table>
    </div>

    <!-- Mensajes de error -->
    <div id="errorMessage" class="alert alert-danger mt-3 d-none small"></div>
</main>

<!-- Script para tema -->
<script>
function cambiarTema() {
    document.body.classList.toggle('tema-oscuro');
    const esTemaOscuro = document.body.classList.contains('tema-oscuro');
    localStorage.setItem('tema', esTemaOscuro ? 'oscuro' : 'claro');
}

window.onload = function() {
    const temaGuardado = localStorage.getItem('tema');
    if (temaGuardado === 'oscuro') {
        document.body.classList.add('tema-oscuro');
    }
    cargarSectores();
    cargarLocalidades();
}
</script>

<!-- Script para Sectores y Localidades -->
<script>
function cargarSectores() {
    const tbody = document.getElementById('tbodySectores');
    tbody.innerHTML = '';
    const sectores = JSON.parse(localStorage.getItem('sectores') || '[]');
    sectores.forEach((sector, index) => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${sector.nombre}</td>
            <td>${sector.numero}</td>
            <td>

            </td>
        `;
        tbody.appendChild(fila);
    });
}

function cargarLocalidades() {
    const tbody = document.getElementById('tbodyLocalidades');
    tbody.innerHTML = '';
    const localidades = JSON.parse(localStorage.getItem('localidades') || '[]');
    localidades.forEach((localidad, index) => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${localidad.nombre}</td>
            <td>${localidad.sector}</td>
            <td>

            </td>
        `;
        tbody.appendChild(fila);
    });
}




// Funciones de carga, descarga y eliminación de datos
function descargarDatos() {
    const datos = {
        sectores: localStorage.getItem('sectores'),
        localidades: localStorage.getItem('localidades'),
        clientes: localStorage.getItem('clientes'),
        creditos: localStorage.getItem('creditos')
    };

    const blob = new Blob([JSON.stringify(datos)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'datos.json';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}


function cargarDatos() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.json';

    input.onchange = (event) => {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            try {
                const datos = JSON.parse(e.target.result);

                if (datos.sectores) localStorage.setItem('sectores', datos.sectores);
                if (datos.localidades) localStorage.setItem('localidades', datos.localidades);
                if (datos.clientes) localStorage.setItem('clientes', datos.clientes);
                if (datos.creditos) localStorage.setItem('creditos', datos.creditos);

                if (typeof cargarSectores === 'function') cargarSectores();
                if (typeof cargarLocalidades === 'function') cargarLocalidades();
                if (typeof cargarClientes === 'function') cargarClientes();
                if (typeof cargarCreditos === 'function') cargarCreditos();
            } catch (error) {
                alert('Error al cargar los datos');
            }
        };

        reader.readAsText(file);
    };

    input.click();
}


function eliminarDatos() {
    if (confirm('¿Estás seguro de eliminar todos los datos?')) {
        localStorage.removeItem('sectores');
        localStorage.removeItem('localidades');
        localStorage.removeItem('clientes');
        localStorage.removeItem('creditos');

        if (typeof cargarSectores === 'function') cargarSectores();
        if (typeof cargarLocalidades === 'function') cargarLocalidades();
        if (typeof cargarClientes === 'function') cargarClientes();
        if (typeof cargarCreditos === 'function') cargarCreditos();
    }
}

</script>

</body>
</html>

<?php include_once __DIR__ . "/../../../chat-v3/index.php"; ?>