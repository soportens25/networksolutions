import './bootstrap';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');

  const calendar = new Calendar(calendarEl, {
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    locale: 'es',
    firstDay: 1,
    selectable: true,
    events: window.Laravel.eventsUrl, // ✅ CORRECTO

    select: function(info) {
      const title = prompt('Nombre del evento:');
      if (title && title.trim() !== '') {
        fetch(window.Laravel.eventsUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.Laravel.csrfToken
          },
          body: JSON.stringify({
            title: title,
            type: type,
            start: info.startStr,
            end: info.endStr
          })
        }).then(() => calendar.refetchEvents());
      }
    }
  });

  calendar.render();
});
