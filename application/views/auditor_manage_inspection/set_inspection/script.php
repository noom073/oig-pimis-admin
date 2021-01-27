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


        const getPlan = planID => {
            return $.post({
                url: '<?= site_url('data_service/ajax_get_a_plan') ?>',
                data: {
                    planID: planID
                },
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        const getTeamPlan = teamPlanID => {
            return $.post({
                url: '<?= site_url('data_service/ajax_get_a_team_plan') ?>',
                data: {
                    teamPlanID: teamPlanID
                },
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        const getInspectionOptions = () => {
            return $.post({
                url: '<?= site_url('data_service/ajax_get_inspection_options') ?>',
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        const getTeamInspection = teamPlanID => {
            return $.post({
                url: '<?= site_url('data_service/ajax_get_team_inspection') ?>',
                data: {
                    teamPlanID: teamPlanID
                },
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        const updateInspaectionModal = async (teamPlanID, groupID) => {
            let planDetail = await getPlan(groupID);
            let teamPlanDetail = await getTeamPlan(teamPlanID);
            let auditorTeams = await getAuditorTeams();
            let inspectionOptions = await getInspectionOptions();
            let teamPlanInspection = await getTeamInspection(teamPlanID);
            let option = '';

            units.forEach(r => {
                option += `<option value="${r.NPRT_UNIT}" title="${r.NPRT_NAME}">${r.NPRT_ACM}</option>`;
            });
            $("#edit-team-inspection-form-nprt-units").html(option);

            option = '';
            auditorTeams.forEach(r => {
                option += `<option value="${r.ROW_ID}">${r.TEAM_NAME} (${r.TEAM_YEAR})</option>`;
            });
            $("#edit-team-inspection-form-auditor-team").html(option);

            option = '';
            inspectionOptions.forEach(r => {
                option += `<option value="${r.ROW_ID}">${r.INSPECTION_NAME} (${r.OPTION_YEAR})</option>`;
            });
            $("#edit-team-inspection-form-inspection").html(option);

            let inspectionOptionVal = teamPlanInspection.map(r => r.INSPECTION_OPTION_ID);
            $("#edit-team-inspection-form-nprt-units").val(planDetail.INS_UNIT);
            $("#edit-team-inspection-form-date-start").val(planDetail.INS_DATE);
            $("#edit-team-inspection-form-date-end").val(planDetail.FINISH_DATE);
            $("#edit-team-inspection-form-auditor-team").val(teamPlanDetail.TEAM_ID);
            $("#edit-team-inspection-form-inspection").val(inspectionOptionVal);
            $("#edit-team-inspection-form").data('teamPlanID', teamPlanDetail.ROW_ID);
            $("#edit-team-inspection-modal").modal();
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
                            groupId: r.planID,
                            title: `${r.unitAcm} (${r.teamName})`,
                            start: r.dateStart,
                            end: r.dateEnd,
                            allDay: true,
                            // url: '<?= site_url('auditor/inspection_list/?plan=') ?>' + r.planID,
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
                updateInspaectionModal(info.event.id, info.event.groupId);
            },
            loading: isLoading => {
                if (isLoading) {
                    $("#load-calendar").prop('class', 'visible');
                } else {
                    $("#load-calendar").prop('class', 'invisible');
                }
            },
            selectable: true
        });
        calendar.render();


        $("#edit-team-inspection-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let teamPlanID = thisForm.data('teamPlanID');
            let formData = thisForm.serialize() + `&teamPlanID=${teamPlanID}`;
            $.post({
                url: '<?= site_url('auditor_manage_inspection/ajax_update_team_inspection') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                console.log(res);
                alert('บันทึกข้อมูลเรียบร้อย');
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });
    });
</script>