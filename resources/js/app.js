import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';
import './alerts';

document.addEventListener('alpine:init', () => {
    Alpine.data('disponibilidadCalendar', (soloLectura = false) => ({
        calendar: null,

        init() {
            this.calendar = new Calendar(this.$refs.calendarEl, {
                plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
                initialView: 'timeGridWeek',
                locale: esLocale,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek',
                },
                height: 'auto',
                slotMinTime: '06:00:00',
                slotMaxTime: '22:00:00',
                selectable: !soloLectura,
                selectMirror: true,
                selectOverlap: false,
                eventOverlap: false,
                events: (info, success, failure) => {
                    this.$wire.eventos(info.startStr, info.endStr).then(success).catch(failure);
                },
                eventDidMount: (info) => {
                    const { docente, equipo, asignatura, estado } = info.event.extendedProps;
                    if (docente) {
                        const estadoTexto = estado === 'pendiente' ? ' (pendiente de confirmación)' : '';
                        info.el.title = `${equipo} · ${asignatura}${estadoTexto}\nSolicitado por: ${docente}`;
                    }
                },
                select: (info) => {
                    if (soloLectura) {
                        return;
                    }

                    Livewire.dispatch('abrir-modal-solicitud', {
                        fecha: info.startStr.slice(0, 10),
                        horaInicio: info.startStr.slice(11, 16),
                        horaFin: info.endStr.slice(11, 16),
                    });

                    this.calendar.unselect();
                },
            });

            this.calendar.render();

            Livewire.on('solicitud-creada', () => {
                this.calendar.refetchEvents();
            });
        },
    }));
});
