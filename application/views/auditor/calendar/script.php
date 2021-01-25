<!-- fullCalendar 2.2.5 -->
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar/main.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar/locales/th.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar-daygrid/main.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar-timegrid/main.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar-interaction/main.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar-bootstrap/main.min.js') ?>"></script>

<script>
    $(document).ready(function() {
        $("li#auditor-inspection-section").addClass('menu-open');
        $("a#auditor-inspection-subject").addClass('active');
        $("a#auditor-calendar").addClass('active');

        const getCalendarData = () => {
            return $.get({
                url: '<?= site_url('data_service/ajax_inspection_data_calendar') ?>',
                dataType: 'json'
            });
        };


        const calendarEl = document.getElementById('calendar');
        const drawCalendar = async () => {
            let calendar = new FullCalendar.Calendar(calendarEl, {
                height: 650,
                locale: 'th',
                firstDay: 0,
                plugins: ['bootstrap', 'interaction', 'dayGrid', 'timeGrid'],
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                events: function(info, success, fail) {
                    $.get({
                        url: '<?= site_url('data_service/ajax_inspection_data_calendar') ?>',
                        dataType: 'json'
                    }).done(function(res) {
                        let events = res.map(r => {
                            return {
                                id: r.teamPlanID,
                                title: `${r.unitAcm} (${r.teamName})`,
                                start: r.dateStart,
                                end: r.dateEnd,
                                allDay: true,
                                url: '<?= site_url('auditor/inspection_list/?plan=') ?>' + r.planID,
                                backgroundColor: r.color,
                                borderColor: 'white'
                            };
                        });
                        success(events);
                    }).fail((jhr, status, error) => {
                        fail(error);
                        console.error(jhr, status, error);
                    });
                },
                eventClick: function(info) {
                    console.log(info);
                },
                loading: isLoading => {
                    if (isLoading) {
                        $("#load-calendar").prop('class', 'visible');
                    } else {
                        $("#load-calendar").prop('class', 'invisible');
                    }
                }
            });
            calendar.render();
        };
        drawCalendar();


    });
</script>