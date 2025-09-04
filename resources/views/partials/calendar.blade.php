<style>
    @keyframes highlightPulse {

        0%,
        100% {
            background-color: #fef3c7;
        }

        50% {
            background-color: #ff9361;
        }
    }

    .fc-day-today {
        animation: highlightPulse 3s infinite;
    }

    .fc a {
        text-decoration: none;
        color: inherit;
    }
</style>

<!-- Contenedor -->
<div class="1px-4 sm:px-6 lg:px-8 my-8" style="max-width: 100%;">
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-300 overflow-hidden">
        <h2 class="text-xl text-black font-semibold tracking-wide text-center py-4">
            📅 Calendario de Agendamientos
        </h2>
        <div id="calendar" class="p-6 bg-gray-50 min-h-[600px]"></div>
    </div>
</div>

<!-- Modal -->
<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex justify-center items-center"
    role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md relative">
        <button onclick="closeModal()"
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 focus:outline-none">✕</button>
        <h3 id="modal-title" class="text-lg font-semibold mb-4">Crear nuevo evento</h3>
        <form id="eventForm" class="space-y-4">
            <div>
                <label for="eventTitle" class="block text-sm font-medium text-gray-700">Título</label>
                <input type="text" id="eventTitle" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-orange-500 focus:border-orange-500" />
            </div>

            <div>
                <label for="eventType" class="block text-sm font-medium text-gray-700">Tipo</label>
                <select id="eventType"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="evento">Evento</option>
                    <option value="tarea">Tarea</option>
                </select>
            </div>

            <div>
                <label for="eventStart" class="block text-sm font-medium text-gray-700">Inicio</label>
                <input type="datetime-local" id="eventStart" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-orange-500 focus:border-orange-500" />
            </div>

            <div>
                <label for="eventEnd" class="block text-sm font-medium text-gray-700">Fin</label>
                <input type="datetime-local" id="eventEnd" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-orange-500 focus:border-orange-500" />
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancelar</button>
                <button type="submit"
                    class="px-4 py-2 bg-orange-500 text-black rounded-md hover:bg-orange-600">Crear</button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>

<script>
    window.Laravel = {
        csrfToken: '{{ csrf_token() }}',
        eventsUrl: '{{ url('api/events') }}'
    };

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('eventModal');
        const form = document.getElementById('eventForm');
        const titleInput = document.getElementById('eventTitle');
        const typeSelect = document.getElementById('eventType');
        const startInput = document.getElementById('eventStart');
        const endInput = document.getElementById('eventEnd');

        const toDatetimeLocal = (str) => new Date(str).toISOString().slice(0, 16);

        // Configuración común de calendario
        function crearCalendario(selectorId) {
            const el = document.getElementById(selectorId);

            return new FullCalendar.Calendar(el, {
                themeSystem: 'standard',
                initialView: 'dayGridMonth',
                locale: 'es',
                firstDay: 1,
                height: 'auto',
                selectable: true,
                nowIndicator: true,
                dayMaxEvents: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: window.Laravel.eventsUrl,
                eventClassNames: function(arg) {
                    return [
                        'rounded-md', 'text-white', 'px-2', 'py-1', 'text-sm',
                        arg.event.extendedProps.type === 'tarea' ? 'bg-blue-500' :
                        'bg-green-500'
                    ];
                },
                eventContent: function(arg) {
                    const title = arg.event.title;
                    const type = arg.event.extendedProps.type === 'tarea' ? '📝 Tarea' :
                        '📌 Evento';
                    const time = arg.timeText;

                    return {
                        html: `
                            <div class="text-sm leading-snug">
                                <div class="font-semibold">${title}</div>
                                <div class="text-xs opacity-90">${type}</div>
                                <div class="text-xs">${time}</div>
                            </div>
                        `
                    };
                },
                eventDidMount: function(info) {
                    const type = info.event.extendedProps.type === 'tarea' ? 'Tarea' : 'Evento';
                    const start = info.event.start.toLocaleString();
                    const end = info.event.end ? info.event.end.toLocaleString() : '';

                    tippy(info.el, {
                        content: `
                            <strong>${info.event.title}</strong><br>
                            Tipo: ${type}<br>
                            Inicio: ${start}<br>
                            ${end ? `Fin: ${end}<br>` : ''}
                        `,
                        allowHTML: true,
                        theme: 'light-border',
                    });
                },
                select: function(info) {
                    startInput.value = toDatetimeLocal(info.startStr);
                    endInput.value = toDatetimeLocal(info.endStr);
                    openModal();
                }
            });
        }

        // Instanciar ambos calendarios
        const calendarA = crearCalendario('calendar');
        const calendarB = crearCalendario('calendar1');

        function renderWhenVisible(calendar, containerId) {
            const el = document.getElementById(containerId);
            const waitForVisibility = () => {
                const isVisible = el.offsetParent !== null && el.offsetHeight > 0;
                if (isVisible) {
                    calendar.render();
                } else {
                    requestAnimationFrame(waitForVisibility);
                }
            };
            waitForVisibility();
        }

        renderWhenVisible(calendarA, 'calendar');
        renderWhenVisible(calendarB, 'calendar1');

        // Crear evento desde formulario
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const title = titleInput.value.trim();
            const type = typeSelect.value;
            const start = startInput.value;
            const end = endInput.value;

            if (!title || !start || !end) return;

            fetch(window.Laravel.eventsUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken
                },
                body: JSON.stringify({
                    title,
                    type,
                    start,
                    end
                })
            }).then(() => {
                calendarA.refetchEvents();
                calendarB.refetchEvents();
                closeModal();
                form.reset();
            }).catch(err => {
                console.error('Error al guardar el evento:', err);
                alert('Error al crear el evento.');
            });
        });

        // Cierre del modal
        window.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });

        modal.addEventListener('click', e => {
            if (e.target === modal) closeModal();
        });
    });

    function openModal() {
        document.getElementById('eventModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('eventModal').classList.add('hidden');
    }
</script>
