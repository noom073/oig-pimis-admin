<script>
    $(document).ready(function() {
        $("li#auditor-inspection-section").addClass('menu-open');
        $("a#auditor-inspection-subject").addClass('active');
        $("a#auditor-calendar").addClass('active');


        let summaryTable = $("#summary-table").DataTable({
            responsive: true,
            ajax: {
                url: '<?= site_url('auditor/ajax_get_summary') ?>',
                type: 'post',
                data: {
                    teamPlanID: '<?= $teamPlan['ROW_ID'] ?>'
                },
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    className: 'text-center',
                    render: (data, type, row, meta) => meta.row + 1
                },
                {
                    data: 'INSPECTION_NAME'
                },
                {
                    data: 'SCORE',
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        let dt = data === null ? '' : data;
                        return `<span class="text-success">${dt}</span>`
                    }
                },
                {
                    data: 'TIME_UPDATE'
                },
                {
                    data: 'ROW_ID',
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        let btn = '';
                        let userInspectionType = <?= json_encode($userInspectionType) ?>;
                        if (userInspectionType.includes(row.INSPECTION_ID)) {
                            if (data === null) {
                                btn = `<button class="btn btn-sm btn-primary create-summary-btn" data-inspection-option-id="${row.INSPECTION_OPTION_ID}">เพิ่ม</button>`;
                            } else {
                                let editBtn = `<button class="btn btn-sm btn-primary edit-btn" data-summary-id="${data}" data-inspection-option-id="${row.INSPECTION_OPTION_ID}">แก้ไข</button>`;
                                let deleteBtn = `<button class="btn btn-sm btn-danger delete-btn" data-summary-id="${data}">ลบ</button>`;
                                btn = `${editBtn} ${deleteBtn}`;
                            }
                        }

                        return btn;
                    }
                }
            ],
            initComplete: (settings, json) => {
                $("#loading-table").addClass('invisible');
            }
        });


        const getNoteByTeamIDAndInspectionOptionID = (teamPlanID, inspectionOptionID) => {
            return $.post({
                url: '<?= site_url('auditor/ajax_get_inspection_note_by_team_plan_id_n_inspection_option_id') ?>',
                data: {
                    teamPlanID: teamPlanID,
                    inspectionOptionID: inspectionOptionID
                },
                dataType: 'json'
            }).done(res => {
                console.log('Loading inspections complete');
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $(document).on('click', ".create-summary-btn", async function() {
            $("#create-summary-form-commention").html('');
            let inspectionOptionID = $(this).data('inspection-option-id');
            let teamPlanID = '<?= $teamPlan['ROW_ID'] ?>';
            let noteDetail = await getNoteByTeamIDAndInspectionOptionID(teamPlanID, inspectionOptionID);
            if ($.isEmptyObject(noteDetail) == false) {
                let summary = 'ข้อควรแก้ไข: \n' + noteDetail.CAN_IMPROVE + '\n\n' +
                    'ข้อบกพร่อง: \n' + noteDetail.FAILING + '\n\n' +
                    'ข้อบกพร่องสำคัญมาก: \n' + noteDetail.IMPORTANT_FAILING + '\n\n' +
                    'ข้อสังเกตจากการตรวจและข้อแนะนำ: \n' + noteDetail.COMMENTIONS;
                $("#create-summary-form-commention").html(summary);
            }
            let teamInspections = <?= json_encode($teamInspections) ?>;
            let inspection = teamInspections.filter(r => r.INSPECTION_OPTION_ID == inspectionOptionID)
                .reduce((acc, cur) => acc = cur);
            $("#create-summary-form-inspection-label").html(inspection.INSPECTION_NAME);
            $("#create-summary-form").data('inspection-option-id', inspectionOptionID);
            $("#create-summary-modal").modal()
        });


        const setAllScore = () => {
            let sumScore = +($("#sum-score-avg").text());
            let policyScore = +($("#policy-score-avg").text());
            let prepareScore = +($("#prepare-score-avg").text());
            let allScore = sumScore + policyScore + prepareScore;
            $("#all-score").text(allScore);
        };
        setAllScore();


        $("#create-summary-form").submit(function(event) {
            event.preventDefault();
            $("#loading-table").removeClass('invisible');
            let thisForm = $(this);
            let teamPlanID = '<?= $teamPlan['ROW_ID'] ?>';
            let inspectionOptionID = $(this).data('inspection-option-id');
            let formData = thisForm.serialize() + `&teamPlanID=${teamPlanID}&inspectionOptionID=${inspectionOptionID}`;
            $.post({
                url: '<?= site_url('auditor/ajax_add_summary') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#create-summary-result").prop('class', 'alert alert-success');
                    $("#create-summary-result").text(res.text);
                    summaryTable.ajax.reload(() => {
                        $("#loading-table").addClass('invisible');
                    });
                } else {
                    $("#create-summary-result").prop('class', 'alert alert-danger');
                    $("#create-summary-result").text(res.text);
                }

                setTimeout(() => {
                    $("#create-summary-result").prop('class', '');
                    $("#create-summary-result").text('');
                    thisForm.trigger('reset');
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        $("#set-plan-score").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let teamPlanID = thisForm.data('team-plan-id');
            let formData = thisForm.serialize() + `&teamPlanID=${teamPlanID}`;
            $.post({
                url: '<?= site_url('auditor/ajax_update_plan_score') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#set-plan-score-result").prop('class', 'alert alert-success');
                    $("#set-plan-score-result").text(res.text);
                    let policyScore = res.data.policyScore * 0.1;
                    let prepareScore = res.data.prepareScore * 0.1;
                    $("#policy-score-avg").text(policyScore.toFixed(2));
                    $("#prepare-score-avg").text(prepareScore.toFixed(2));
                    setAllScore();
                } else {
                    $("#set-plan-score-result").prop('class', 'alert alert-danger');
                    $("#set-plan-score-result").text(res.text);
                }

                setTimeout(() => {
                    $("#set-plan-score-result").prop('class', '');
                    $("#set-plan-score-result").text('');
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        const getsummaryDetail = summaryID => {
            console.log('Loading summary detail...');
            return $.post({
                url: '<?= site_url('auditor/ajax_get_summary_detail') ?>',
                data: {
                    summaryID: summaryID
                },
                dataType: 'json'
            }).done(res => {
                console.log('Loading summary detail complete');
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $(document).on('click', ".edit-btn", async function() {
            let summaryID = $(this).data('summary-id');
            let summaryData = await getsummaryDetail(summaryID);            
            $("#update-summary-form").data('summary-id', summaryID);
            let inspectionOptionID = $(this).data('inspection-option-id');
            let teamInspections = <?= json_encode($teamInspections) ?>;
            let inspection = teamInspections.filter(r => r.INSPECTION_OPTION_ID == inspectionOptionID)
                .reduce((acc, cur) => acc = cur);
            $("#update-summary-form-inspection-label").html(inspection.INSPECTION_NAME);
            $("#update-summary-comment").val(summaryData.COMMENTION);
            $("#update-summary-modal").modal();
        });


        $("#update-summary-form").submit(function(event) {
            event.preventDefault();
            $("#loading-table").removeClass('invisible');
            let thisForm = $(this);
            let summaryID = thisForm.data('summary-id');
            let formData = thisForm.serialize() + `&summaryID=${summaryID}`;
            $.post({
                url: '<?= site_url('auditor/ajax_update_summary') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#update-summary-result").prop('class', 'alert alert-success');
                    $("#update-summary-result").text(res.text);
                    summaryTable.ajax.reload(() => {
                        $("#loading-table").addClass('invisible');
                    });
                } else {
                    $("#update-summary-result").prop('class', 'alert alert-danger');
                    $("#update-summary-result").text(res.text);
                }

                setTimeout(() => {
                    $("#update-summary-result").prop('class', '');
                    $("#update-summary-result").text('');
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        $(document).on('click', '.delete-btn', function() {
            $("#loading-table").removeClass('invisible');
            let summaryID = $(this).data('summary-id');
            if (confirm('ยืนยันการลบข้อนี้')) {
                $.post({
                    url: '<?= site_url('auditor/ajax_delete_summary') ?>',
                    data: {
                        summaryID: summaryID
                    },
                    dataType: 'json'
                }).done(res => {
                    alert(res.text);
                    summaryTable.ajax.reload(() => {
                        $("#loading-table").addClass('invisible');
                    });
                }).fail((jhr, status, error) => console.error(jhr, status, error));
                return true;
            } else {
                return false;
            }
        });

    });
</script>