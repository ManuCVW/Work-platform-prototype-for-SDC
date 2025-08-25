<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WorkWise - Localidades</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tus estilos personalizados -->
    <link rel="stylesheet" href="../build/css/appwoo.css">
<!-- Scripts exportacion Localidades -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <style>
        #tablaLocalidades {
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
    <h1 class="mb-4">Gestionar Localidades</h1>

    <!-- Formulario de localidades -->
    <form id="formLocalidad" class="row g-2 align-items-center mb-4">
        <div class="col-auto">
            <input type="text" id="nombre" class="form-control form-control-sm" placeholder="Nombre" required>
        </div>
        <div class="col-auto">
            <input type="text" id="numero" class="form-control form-control-sm" placeholder="Número" required>
        </div>
        <div class="col-auto">
            <select id="sector" class="form-control form-control-sm" required>
                <!-- Los sectores se llenarán dinámicamente -->
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
        </div>
    </form>
        <!-- Botones exportacion -->
    <div class="col ms-auto text-end mb-3">
        <button class="btn btn-success btn-sm me-2" onclick="exportarLocalidadesExcel()">
            📄 Excel
        </button>
        <button id="imprimirLocalidades" class="btn btn-danger btn-sm">
            🖨️ PDF
        </button>
    </div>

    <!-- Tabla de localidades -->
    <div id="tablaLocalidades" class="table-responsive">
        <table class="table table-striped table-sm">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Número</th>
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


<script>
async function exportarLocalidadesExcel() {
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Localidades');

    worksheet.columns = [
        { header: 'Nombre', key: 'nombre', width: 30, style: { alignment: { horizontal: 'center' } } },
        { header: 'Número', key: 'numero', width: 15, style: { alignment: { horizontal: 'center' } } },
        { header: 'Sector', key: 'sector', width: 30, style: { alignment: { horizontal: 'center' } } },
    ];

    const localidades = JSON.parse(localStorage.getItem('localidades') || '[]');
    localidades.forEach(loc => {
        // Convertir numero a numero real si es posible
        const numeroConvertido = Number(loc.numero);
        if (!isNaN(numeroConvertido)) loc.numero = numeroConvertido;
        worksheet.addRow(loc);
    });

    worksheet.addTable({
        name: 'LocalidadesTable',
        ref: 'A1',
        headerRow: true,
        style: {
            theme: 'TableStyleMedium1',
            showRowStripes: true
        },
        columns: [
            { name: 'Nombre' },
            { name: 'Número' },
            { name: 'Sector' }
        ],
        rows: localidades.map(l => [l.nombre, l.numero, l.sector])
    });

    worksheet.eachRow((row, rowNumber) => {
        row.eachCell((cell) => {
            if (rowNumber === 1) {
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'D3D3D3' } };
                cell.font = { bold: true, color: { argb: '000000' } };
            }
        });
    });

    const buffer = await workbook.xlsx.writeBuffer();
    const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'localidades.xlsx';
    link.click();
}

document.getElementById('imprimirLocalidades').addEventListener('click', () => {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const localidades = JSON.parse(localStorage.getItem('localidades') || '[]');
    if (localidades.length === 0) {
        alert('No hay datos de localidades para imprimir.');
        return;
    }

    const fecha = new Date().toLocaleDateString();

    doc.setFontSize(12);
    doc.text('Localidades', doc.internal.pageSize.width / 2, 20, { align: 'center' });

    doc.autoTable({
        startY: 30,
        head: [['Nombre', 'Número', 'Sector']],
        body: localidades.map(l => [l.nombre, l.numero, l.sector]),
        styles: {
            fontSize: 8,
            cellPadding: 2,
            valign: 'middle',
            halign: 'center',
            overflow: 'linebreak'
        },
        headStyles: {
            fillColor: [220, 220, 220],
            textColor: 0,
            fontStyle: 'bold',
            halign: 'center'
        },
        alternateRowStyles: {
            fillColor: [245, 245, 245]
        },
        theme: 'grid',
        margin: { top: 30, bottom: 20 },
        tableWidth: 'auto',
    });

    const pageHeight = doc.internal.pageSize.height;
    const pageCount = doc.getNumberOfPages();

    doc.setPage(pageCount);
    doc.setFontSize(10);
    doc.text(`Fecha: ${fecha}`, doc.internal.pageSize.width / 2, pageHeight - 10, { align: 'center' });

    doc.setFontSize(8);
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.text(`Página ${i} de ${pageCount}`, doc.internal.pageSize.width - 30, pageHeight - 10, { align: 'right' });
    }

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
    cargarLocalidades();
}
</script>

<!-- Script para localidades -->
<script>
function cargarSectores() {
    const sectoresSelect = document.getElementById('sector');
    try {
        const sectores = JSON.parse(localStorage.getItem('sectores') || '[]');
        
        // Ordenar sectores alfabéticamente por nombre
        sectores.sort((a, b) => a.nombre.localeCompare(b.nombre));
        
        sectoresSelect.innerHTML = ''; // Limpiar opciones previas
        sectores.forEach(sector => {
            const option = document.createElement('option');
            option.value = sector.nombre; // Usamos el nombre del sector como valor
            option.textContent = sector.nombre;
            sectoresSelect.appendChild(option);
        });
    } catch (error) {
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = 'Error cargando sectores: ' + error.message;
        errorMessage.classList.remove('d-none');
    }
}

function cargarLocalidades() {
    const tbody = document.getElementById('tbodyLocalidades');
    tbody.innerHTML = '';

    try {
        const localidades = JSON.parse(localStorage.getItem('localidades') || '[]');
        localidades.forEach((localidad, index) => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${localidad.nombre}</td>
                <td>${localidad.numero}</td>
                <td>${localidad.sector}</td>
                <td>
                    <button class="btn btn-warning btn-sm me-1" onclick="editarLocalidad(${index})">Editar</button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarLocalidad(${index})">Eliminar</button>
                </td>
            `;
            tbody.appendChild(fila);
        });
    } catch (error) {
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = 'Error cargando localidades: ' + error.message;
        errorMessage.classList.remove('d-none');
    }
}

document.getElementById('formLocalidad').addEventListener('submit', function(e) {
    e.preventDefault();
    const nombre = document.getElementById('nombre').value.trim();
    const numero = document.getElementById('numero').value.trim();
    const sector = document.getElementById('sector').value;

    const localidades = JSON.parse(localStorage.getItem('localidades') || '[]');
    localidades.push({ nombre, numero, sector });
    localStorage.setItem('localidades', JSON.stringify(localidades));
    cargarLocalidades();
    this.reset();
});

function editarLocalidad(index) {
    const localidades = JSON.parse(localStorage.getItem('localidades'));
    const localidad = localidades[index];
    document.getElementById('nombre').value = localidad.nombre;
    document.getElementById('numero').value = localidad.numero;
    document.getElementById('sector').value = localidad.sector;
    localidades.splice(index, 1);
    localStorage.setItem('localidades', JSON.stringify(localidades));
    cargarLocalidades();
}

function eliminarLocalidad(index) {
    const localidades = JSON.parse(localStorage.getItem('localidades'));
    localidades.splice(index, 1);
    localStorage.setItem('localidades', JSON.stringify(localidades));
    cargarLocalidades();
}
</script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include_once __DIR__ . "/../../../chat-v3/index.php"; ?>