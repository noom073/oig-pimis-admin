<!-- fullCalendar 2.2.5 -->
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar/main.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar/locales/th.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar-daygrid/main.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar-timegrid/main.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar-interaction/main.min.js') ?>"></script>
<script src="<?= base_url('assets/admin_lte/plugins/fullcalendar-bootstrap/main.min.js') ?>"></script>
<!-- Select2 -->
<script src="<?= base_url('assets/admin_lte/plugins/select2/js/select2.full.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        $("li#auditor-manage-inspection-section").addClass('menu-open');
        $("a#auditor-manage-inspection-subject").addClass('active');
        $("a#auditor-manage-inspection-set-paln").addClass('active');
        $(".select2").select2();


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
                option += `<option value="${r.NPRT_UNIT}" title="${r.NPRT_NAME}">${r.NPRT_ACM}</option>`;
            });
            $("#nprt-units").html(option);
        });


        const getAuditorTeams = () => {
            return $.get({
                url: '<?= site_url('auditor_manage_inspection/ajax_get_auditor_team') ?>',
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        const setAuditorTeamOptionSelect = async () => {
            let auditorTeams = await getAuditorTeams();
            let option = '';
            auditorTeams.forEach(r => {
                option += `<option value="${r.ROW_ID}">${r.TEAM_NAME} (${r.TEAM_YEAR})</option>`;
            });
            $("#auditor-team").html(option);
        };


        const createPlanModalCall = (info) => {
            $("#date-start").val(info.startStr);
            $("#date-end").val(info.endStr);
            setAuditorTeamOptionSelect();
            $("#create-plan-modal").modal();
        };


        const calendarEl = document.getElementById('calendar');
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
                        return {
                            id: r.teamPlanID,
                            title: `${r.unitAcm} (${r.teamName})`,
                            start: r.dateStart,
                            end: r.dateEnd,
                            allDay: true,
                            // url: '<?= site_url('auditor/inspection_list/?plan=') ?>' + r.planID,
                            backgroundColor: r.color,
                            borderColor: 'white'
                        };
                    });
                    console.log(events);
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
        // calendar.refetchEvents();


        $("#create-plan-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let formData = thisForm.serialize();
            $.post({
                url: '<?= site_url('auditor_manage_inspection/ajax_add_plan') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                console.log(res);
                if (res.status) {
                    $("#create-plan-form-result").prop('class', 'alert alert-success');
                    $("#create-plan-form-result").text(res.text);
                    calendar.refetchEvents();
                } else {
                    $("#create-plan-form-result").prop('class', 'alert alert-danger');
                    $("#create-plan-form-result").text(res.text);
                }
                setTimeout(() => {
                    $("#create-plan-form-result").prop('class', '');
                    $("#create-plan-form-result").text('');
                    thisForm.trigger('reset');
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });
    });
</script>