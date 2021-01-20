<!-- fullCalendar 2.2.5 -->
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar/main.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar/locales/th.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar-daygrid/main.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar-timegrid/main.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar-interaction/main.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar-bootstrap/main.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        $("li#auditor-manage-inspection-section").addClass('menu-open');
        $("a#auditor-manage-inspection-subject").addClass('active');
        $("a#auditor-manage-inspection-set-paln").addClass('active');


        let units = new Array();
        const getNprtUnit = () => {
            $.get({
                url: '<?= site_url('data_service/ajax_get_nprt_units') ?>',
                dataType: 'json'
            }).done(res => {
                units = res;
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        };
        getNprtUnit();


        $("#search-units").blur(function() {
            let unit = units.filter(r => r.NPRT_ACM.includes($(this).val()))
                .sort((a, b) => a.NPRT_UNIT - b.NPRT_UNIT);
            let option = '';
            unit.forEach(r => {
                option += `<option value="${r.NPRT_UNIT}">${r.NPRT_ACM}</option>`;
            });
            $("#nprt-units").html(option);
        });


        const createPlanModalCall = (info) => {
            let dayEnd = (info.endStr.substring(8) - 1).toString();
            dayEnd = dayEnd.length == 1 ? "0" + dayEnd : dayEnd;
            let dateEnd = info.endStr.substring(0,8)+dayEnd;
            $("#date-start").val(info.startStr);
            $("#date-end").val(dateEnd);
            $("#create-plan-modal").modal();
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
                    right: 'dayGridMonth'
                },
                themeSystem: 'bootstrap',
                events: function(info, success, fail) {
                    $.get({
                        url: '<?= site_url('data_service/ajax_inspection_data_calendar') ?>',
                        dataType: 'json'
                    }).done(function(res) {
                        let events = res.map(r => {
                            let bgColor = r.squad == 1 ? '#007bff' : '#00A170';
                            return {
                                title: r.unitAcm,
                                start: r.dateStart,
                                end: r.dateEnd,
                                // url: '<?= site_url('auditor/inspection_list/?plan=') ?>' + r.planID,
                                backgroundColor: bgColor,
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
                },
                selectable: true,
                select: (info) => {
                    createPlanModalCall(info);
                }
            });
            calendar.render();
        };
        drawCalendar();
    });
</script>