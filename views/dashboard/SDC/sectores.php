<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WorkWise - Sectores</title>
    <!-- Script PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

    <!-- Script EXCELL -->
    <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tus estilos personalizados -->
    <link rel="stylesheet" href="../build/css/appwoo.css">

    <style>
        #tablaSectores {
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
    <h1 class="mb-4">Gestionar Sectores</h1>
    <div class="row align-items-center mb-4">
        <!-- Formulario (izquierda) -->
        <div class="col-auto">
            <form id="formSector" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input type="text" id="nombre" class="form-control form-control-sm" placeholder="Nombre" required>
                </div>
                <div class="col-auto">
                    <input type="text" id="numero" class="form-control form-control-sm" placeholder="Número" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    
        <!-- Botones (derecha) -->
        <div class="col ms-auto text-end">
            <button class="btn btn-success btn-sm me-2" onclick="exportarSectoresExcel()">
                📄 Excel
            </button>
            <button id="imprimirSectores" class="btn btn-danger btn-sm">
                🖨️ PDF
            </button>
        </div>
    </div>
    <!-- Tabla de sectores -->
    <div id="tablaSectores" class="table-responsive">
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

    <!-- Mensajes de error -->
    <div id="errorMessage" class="alert alert-danger mt-3 d-none small"></div>
</main>

<!-- Script EXCELL -->
<script>
async function exportarSectoresExcel() {
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Sectores');

    // Definir las columnas
    worksheet.columns = [
        { header: 'Nombre', key: 'nombre', width: 30, style: { alignment: { horizontal: 'center' } } },
        { header: 'Número', key: 'numero', width: 15, style: { alignment: { horizontal: 'center' } } }
    ];

    // Obtener los sectores desde localStorage
    const sectores = JSON.parse(localStorage.getItem('sectores') || '[]');
    sectores.forEach(sector => {
        // Verificar si el valor de 'numero' puede convertirse en un número válido
        const numeroConvertido = Number(sector.numero);
        if (!isNaN(numeroConvertido)) {
            // Solo asignar si es un número válido
            sector.numero = numeroConvertido;
        } else {
            // Si no es un número válido, dejarlo como está o asignar un valor predeterminado
            // sector.numero = 'Valor no válido'; // O lo que prefieras
        }

        worksheet.addRow(sector);
    });

    // Formato de la tabla con estilo gris y bordes
    worksheet.addTable({
        name: 'SectoresTable',
        ref: 'A1',
        headerRow: true,
        style: {
            theme: 'TableStyleMedium1', // Cambiado a un estilo gris
            showRowStripes: true
        },
        columns: [
            { name: 'Nombre' },
            { name: 'Número' }
        ],
        rows: sectores.map(s => [s.nombre, s.numero])  // Asegurarse de que 'numero' sea un número
    });

    worksheet.eachRow((row, rowNumber) => {
        row.eachCell((cell, colNumber) => {
            if (rowNumber === 1) { // Primer fila (encabezados)
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'D3D3D3' } }; // Gris claro
                cell.font = { bold: true, color: { argb: '000000' } }; // Negrita y texto negro
            }
        });
    });

    // Descargar el archivo
    const buffer = await workbook.xlsx.writeBuffer();
    const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'sectores.xlsx';
    link.click();
}
</script>


<!-- Script para PDF -->
<script>

document.getElementById('imprimirSectores').addEventListener('click', function () {
    const sectores = JSON.parse(localStorage.getItem('sectores') || '[]');
    if (sectores.length === 0) {
        alert('No hay datos de sectores para imprimir.');
        return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const fecha = new Date().toLocaleDateString();

    // Titulo más pequeño
    doc.setFontSize(12);
    doc.text('Sectores', doc.internal.pageSize.width / 2, 20, { align: 'center' });

    // Estilo de la tabla: compacto, similar a Excel
    doc.autoTable({
        startY: 30,
        head: [['Nombre', 'Número']],
        body: sectores.map(s => [s.nombre, s.numero]),

        styles: {
            fontSize: 8,
            cellPadding: 2,
            valign: 'middle',
            halign: 'center', // <- Esto centra el contenido horizontalmente
            overflow: 'linebreak'
        },
        headStyles: {
            fillColor: [220, 220, 220], // Color de fondo de la cabecera (gris claro)
            textColor: 0, // Color de texto oscuro
            fontStyle: 'bold', // Negrita para el encabezado
            halign: 'center' // Alineación horizontal de las celdas del encabezado
        },
        alternateRowStyles: {
            fillColor: [245, 245, 245] // Color alternativo de las filas para mejorar la legibilidad
        },
        theme: 'grid', // Línea de cuadrícula de la tabla
        margin: { top: 30, bottom: 20 },
        tableWidth: 'auto', // La tabla se ajusta automáticamente
    });

    // Pie de página con fecha
    const pageHeight = doc.internal.pageSize.height;
    const pageCount = doc.getNumberOfPages();
    doc.setPage(pageCount);
    doc.setFontSize(10);
    doc.text(`Fecha: ${fecha}`, doc.internal.pageSize.width / 2, pageHeight - 10, { align: 'center' });

    // Numeración de páginas
    doc.setFontSize(8);
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.text(`Página ${i} de ${pageCount}`, doc.internal.pageSize.width - 30, pageHeight - 10, { align: 'right' });
    }

        // Abrir directamente la impresión sin descargar ni ventana nueva
        doc.autoPrint();
        window.open(doc.output('bloburl'), '_blank');

});


</script>

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
}
</script>

<!-- Script para sectores -->
<script>
function cargarSectores() {
    const tbody = document.getElementById('tbodySectores');
    tbody.innerHTML = '';

    try {
        const sectores = JSON.parse(localStorage.getItem('sectores') || '[]');
        sectores.forEach((sector, index) => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${sector.nombre}</td>
                <td>${sector.numero}</td>
                <td>
                    <button class="btn btn-warning btn-sm me-1" onclick="editarSector(${index})">Editar</button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarSector(${index})">Eliminar</button>
                </td>
            `;
            tbody.appendChild(fila);
        });
    } catch (error) {
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = 'Error cargando sectores: ' + error.message;
        errorMessage.classList.remove('d-none');
    }
}

document.getElementById('formSector').addEventListener('submit', function(e) {
    e.preventDefault();
    const nombre = document.getElementById('nombre').value.trim();
    const numero = document.getElementById('numero').value.trim();
    const sectores = JSON.parse(localStorage.getItem('sectores') || '[]');

    sectores.push({ nombre, numero });
    localStorage.setItem('sectores', JSON.stringify(sectores));
    cargarSectores();
    this.reset();
});

function editarSector(index) {
    const sectores = JSON.parse(localStorage.getItem('sectores'));
    const sector = sectores[index];
    document.getElementById('nombre').value = sector.nombre;
    document.getElementById('numero').value = sector.numero;
    sectores.splice(index, 1);
    localStorage.setItem('sectores', JSON.stringify(sectores));
    cargarSectores();
}

function eliminarSector(index) {
    const sectores = JSON.parse(localStorage.getItem('sectores'));
    sectores.splice(index, 1);
    localStorage.setItem('sectores', JSON.stringify(sectores));
    cargarSectores();
}
</script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include_once __DIR__ . "/../../../chat-v3/index.php"; ?>