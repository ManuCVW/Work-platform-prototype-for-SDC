<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WorkWise - Inicio</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Script EXCELL -->
    <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<!-- Librerias jsPDF y AutoTable para PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

    <!-- Tus estilos personalizados -->
    <link rel="stylesheet" href="../build/css/appwoo.css">
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

    <div class="container mt-5">
        <h1 class="mb-4">Mantenimiento de Créditos</h1>

        <form id="formCredito" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="cliente" class="form-label">Cliente</label>
                    <select id="cliente" class="form-select" required></select>
                </div>
                <div class="col-md-4">
                    <label for="monto" class="form-label">Monto</label>
                    <input type="number" id="monto" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="semanas" class="form-label">Semanas</label>
                    <input type="number" id="semanas" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="localidad" class="form-label">Localidad</label>
                    <select id="localidad" class="form-select" required></select>
                </div>
                <div class="col-md-6">
                    <label for="sector" class="form-label">Sector</label>
                    <select id="sector" class="form-select" required></select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Guardar Crédito</button>
        </form>

                <!-- Botones (derecha) -->
        <div class="col ms-auto text-end mb-2">
            <button class="btn btn-success btn-sm me-2" onclick="exportarCreditosExcel()">
                📄 Excel
            </button>
            <button id="imprimirCreditos" class="btn btn-danger btn-sm">
                🖨️ PDF
            </button>
        </div>

        <div class="alert alert-danger d-none" id="errorMessage"></div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Cliente</th>
                    <th>Monto</th>
                    <th>Semanas</th>
                    <th>Localidad</th>
                    <th>Sector</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tbodyCreditos"></tbody>
        </table>
    </div>


<script>
async function exportarCreditosExcel() {
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Creditos');

    worksheet.columns = [
        { header: 'Cliente', key: 'cliente', width: 25, style: { alignment: { horizontal: 'center' } } },
        { header: 'Monto', key: 'monto', width: 15, style: { alignment: { horizontal: 'center' } } },
        { header: 'Semanas', key: 'semanas', width: 15, style: { alignment: { horizontal: 'center' } } },
        { header: 'Localidad', key: 'localidad', width: 20, style: { alignment: { horizontal: 'center' } } },
        { header: 'Sector', key: 'sector', width: 20, style: { alignment: { horizontal: 'center' } } },
    ];

    const creditos = JSON.parse(localStorage.getItem('creditos') || '[]');
    creditos.forEach(c => {
        // Convertir monto y semanas a número si aplica
        c.monto = Number(c.monto);
        c.semanas = Number(c.semanas);
        worksheet.addRow(c);
    });

    worksheet.addTable({
        name: 'CreditosTable',
        ref: 'A1',
        headerRow: true,
        style: {
            theme: 'TableStyleMedium1',
            showRowStripes: true
        },
        columns: [
            { name: 'Cliente' },
            { name: 'Monto' },
            { name: 'Semanas' },
            { name: 'Localidad' },
            { name: 'Sector' }
        ],
        rows: creditos.map(c => [c.cliente, c.monto, c.semanas, c.localidad, c.sector])
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
    link.download = 'creditos.xlsx';
    link.click();
}

document.getElementById('imprimirCreditos').addEventListener('click', () => {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const creditos = JSON.parse(localStorage.getItem('creditos') || '[]');
    if (creditos.length === 0) {
        alert('No hay datos de créditos para imprimir.');
        return;
    }

    const fecha = new Date().toLocaleDateString();

    doc.setFontSize(12);
    doc.text('Créditos', doc.internal.pageSize.width / 2, 20, { align: 'center' });

    doc.autoTable({
        startY: 30,
        head: [['Cliente', 'Monto', 'Semanas', 'Localidad', 'Sector']],
        body: creditos.map(c => [c.cliente, c.monto, c.semanas, c.localidad, c.sector]),
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


    <script>
        function cambiarTema() {
            document.body.classList.toggle('tema-oscuro');
            const esTemaOscuro = document.body.classList.contains('tema-oscuro');
            localStorage.setItem('tema', esTemaOscuro ? 'oscuro' : 'claro');
        }

        window.onload = function () {
            const temaGuardado = localStorage.getItem('tema');
            if (temaGuardado === 'oscuro') {
                document.body.classList.add('tema-oscuro');
            }
            cargarSelects();
            cargarCreditos();
        }

        function cargarSelects() {
            const clientes = JSON.parse(localStorage.getItem('clientes') || '[]');
            const localidades = JSON.parse(localStorage.getItem('localidades') || '[]');
            const sectores = JSON.parse(localStorage.getItem('sectores') || '[]');

            const clienteSelect = document.getElementById('cliente');
            const localidadSelect = document.getElementById('localidad');
            const sectorSelect = document.getElementById('sector');

            clienteSelect.innerHTML = '';
            clientes.forEach(cliente => {
                const option = document.createElement('option');
                option.value = cliente.nombre;
                option.textContent = cliente.nombre;
                clienteSelect.appendChild(option);
            });

            localidadSelect.innerHTML = '';
            localidades.forEach(localidad => {
                const option = document.createElement('option');
                option.value = localidad.nombre;
                option.textContent = localidad.nombre;
                localidadSelect.appendChild(option);
            });

            sectorSelect.innerHTML = '';
            sectores.forEach(sector => {
                const option = document.createElement('option');
                option.value = sector.nombre;
                option.textContent = sector.nombre;
                sectorSelect.appendChild(option);
            });
        }

function cargarCreditos() {
    const tbody = document.getElementById('tbodyCreditos');
    tbody.innerHTML = '';
    try {
        const creditos = JSON.parse(localStorage.getItem('creditos') || '[]');
        creditos.forEach((credito, index) => {
            const monto = parseFloat(credito.monto) || 0;
            const color = monto > 0 ? 'text-danger' : 'text-success';

            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${credito.cliente}</td>
                <td class="${color}">${monto.toFixed(2)}</td>
                <td>${credito.semanas}</td>
                <td>${credito.localidad}</td>
                <td>${credito.sector}</td>
                <td>
                    <div class="input-group input-group-sm mb-1">
                        <input type="number" min="0" class="form-control" id="pago-${index}" placeholder="Pago" />
                        <button class="btn btn-success" onclick="registrarPago(${index})">✔</button>
                    </div>
                    <button class="btn btn-warning btn-sm me-1" onclick="editarCredito(${index})">Editar</button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarCredito(${index})">Eliminar</button>
                </td>
            `;
            tbody.appendChild(fila);
        });
    } catch (error) {
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = 'Error cargando créditos: ' + error.message;
        errorMessage.classList.remove('d-none');
    }
}

function registrarPago(index) {
    const input = document.getElementById(`pago-${index}`);
    const pago = parseFloat(input.value);
    if (isNaN(pago) || pago <= 0) {
        alert("Ingresa un pago válido.");
        return;
    }

    const creditos = JSON.parse(localStorage.getItem('creditos') || '[]');
    let monto = parseFloat(creditos[index].monto) || 0;
    monto -= pago;  // restar pago al monto directamente
    creditos[index].monto = monto.toFixed(2);
    localStorage.setItem('creditos', JSON.stringify(creditos));
    cargarCreditos();
}




        document.getElementById('formCredito').addEventListener('submit', function (e) {
            e.preventDefault();
            const cliente = document.getElementById('cliente').value.trim();
            const monto = parseFloat(document.getElementById('monto').value.trim());
            const semanas = parseInt(document.getElementById('semanas').value.trim());
            const localidad = document.getElementById('localidad').value.trim();
            const sector = document.getElementById('sector').value.trim();

            const creditos = JSON.parse(localStorage.getItem('creditos') || '[]');
            creditos.push({ cliente, monto, semanas, localidad, sector });
            localStorage.setItem('creditos', JSON.stringify(creditos));
            cargarCreditos();
            this.reset();
        });

        function editarCredito(index) {
            const creditos = JSON.parse(localStorage.getItem('creditos') || '[]');
            const credito = creditos[index];

            document.getElementById('cliente').value = credito.cliente;
            document.getElementById('monto').value = credito.monto;
            document.getElementById('semanas').value = credito.semanas;
            document.getElementById('localidad').value = credito.localidad;
            document.getElementById('sector').value = credito.sector;

            creditos.splice(index, 1);
            localStorage.setItem('creditos', JSON.stringify(creditos));
            cargarCreditos();
        }

        function eliminarCredito(index) {
            const creditos = JSON.parse(localStorage.getItem('creditos') || '[]');
            creditos.splice(index, 1);
            localStorage.setItem('creditos', JSON.stringify(creditos));
            cargarCreditos();
        }
    </script>


<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
