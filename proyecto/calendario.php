<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario & Recordatorios - Tuortox</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <div class="dashboard-wrapper">
        <h1 class="dashboard-title">Calendario & Recordatorios</h1>

        <div class="main-container">
            <!-- Panel Izquierdo: Calendario -->
            <div class="calendar-card">
                <div class="calendar-header">
                    <h2 id="month-year">Junio 2026</h2>
                    <div class="calendar-nav">
                        <button onclick="cambiarMes(-1)">&lt;</button>
                        <button onclick="cambiarMes(1)">&gt;</button>
                    </div>
                </div>
                
                <div class="weekdays">
                    <div>DO</div><div>LU</div><div>MA</div><div>MI</div><div>JU</div><div>VI</div><div>SA</div>
                </div>
                
                <div class="days-grid" id="days-container">
                    <!-- Se genera dinámicamente con JS -->
                </div>
            </div>

            <!-- Panel Derecho: Formulario y Agenda -->
            <div class="sidebar">
                <div class="form-card">
                    <h3>Nuevo Recordatorio</h3>
                    <div class="input-group">
                        <input type="text" id="reminder-text" placeholder="¿Qué tienes que recordar?">
                    </div>
                    <div class="input-group">
                        <input type="date" id="reminder-date">
                    </div>
                    <button class="btn-submit" onclick="agregarRecordatorio()">Guardar Recordatorio</button>
                </div>

                <div class="agenda-section">
                    <h3>Lista de Agenda</h3>
                    <div class="agenda-list" id="agenda-container">
                        <!-- Se carga dinámicamente con JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let fechaActual = new Date(2026, 5, 1); 
        let recordatorios = JSON.parse(localStorage.getItem('recordatorios')) || [];

        const meses = [
            "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
        ];

        function renderizarCalendario() {
            const año = fechaActual.getFullYear();
            const mes = fechaActual.getMonth();

            document.getElementById('month-year').textContent = `${meses[mes]} ${año}`;

            const primerDiaIndex = new Date(año, mes, 1).getDay();
            const ultimoDia = new Date(año, mes + 1, 0).getDate();

            const container = document.getElementById('days-container');
            container.innerHTML = '';

            for (let i = 0; i < primerDiaIndex; i++) {
                const divVacio = document.createElement('div');
                divVacio.classList.add('day', 'empty');
                container.appendChild(divVacio);
            }

            for (let dia = 1; dia <= ultimoDia; dia++) {
                const divDia = document.createElement('div');
                divDia.classList.add('day');
                divDia.textContent = dia;

                const mesFormateado = String(mes + 1).padStart(2, '0');
                const diaFormateado = String(dia).padStart(2, '0');
                const fechaString = `${año}-${mesFormateado}-${diaFormateado}`;

                const tieneEvento = recordatorios.some(r => r.fecha === fechaString);
                if (tieneEvento) {
                    divDia.classList.add('has-event');
                }

                divDia.onclick = () => {
                    document.querySelectorAll('.day').forEach(d => d.classList.remove('active'));
                    divDia.classList.add('active');
                    document.getElementById('reminder-date').value = fechaString;
                };

                container.appendChild(divDia);
            }
        }

        function cambiarMes(direccion) {
            fechaActual.setMonth(fechaActual.getMonth() + direccion);
            renderizarCalendario();
        }

        function renderizarAgenda() {
            const container = document.getElementById('agenda-container');
            container.innerHTML = '';

            recordatorios.sort((a, b) => new Date(a.fecha) - new Date(b.fecha));

            recordatorios.forEach((rec, index) => {
                const item = document.createElement('div');
                item.classList.add('agenda-item');

                item.innerHTML = `
                    <div class="agenda-item-info">
                        <span class="agenda-date">${rec.fecha}</span>
                        <span class="agenda-text">${rec.texto}</span>
                    </div>
                    <button class="btn-delete" onclick="eliminarRecordatorio(${index})">×</button>
                `;
                container.appendChild(item);
            });
        }

        function agregarRecordatorio() {
            const texto = document.getElementById('reminder-text').value.trim();
            const fecha = document.getElementById('reminder-date').value;

            if (!texto || !fecha) {
                alert('Por favor, ingresa una descripción y selecciona una fecha.');
                return;
            }

            recordatorios.push({ texto, fecha });
            localStorage.setItem('recordatorios', JSON.stringify(recordatorios));

            document.getElementById('reminder-text').value = '';
            renderizarCalendario();
            renderizarAgenda();
        }

        function eliminarRecordatorio(index) {
            recordatorios.splice(index, 1);
            localStorage.setItem('recordatorios', JSON.stringify(recordatorios));
            renderizarCalendario();
            renderizarAgenda();
        }

        renderizarCalendario();
        renderizarAgenda();
    </script>
</body>
</html>
