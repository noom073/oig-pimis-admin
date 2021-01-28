<script>
    $(document).ready(function() {
        $("li#auditor-inspection-section").addClass('menu-open');
        $("a#auditor-inspection-subject").addClass('active');
        $("a#auditor-calendar").addClass('active');

        $("#add-note-btn").click(function() {
            $("#create-note-modal").modal();
        });


        let noteTable = $("#note-table").DataTable({
            ajax: {
                url: '<?= site_url('auditor/ajax_get_inspection_notes_list_by_team_plan_id') ?>',
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
                    data: 'INSPECTION_NAME',
                    className: 'text-center'
                },
                {
                    data: 'TIME_UPDATE',
                    className: 'text-center'
                },
                {
                    data: 'ROW_ID',
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        let detailBtn = `<button class="btn btn-sm btn-primary detail-btn" data-row-id="${data}">รายละเอียด</button>`;
                        let deleteBtn = `<button class="btn btn-sm btn-danger delete-btn" data-row-id="${data}">ลบ</button>`;
                        return `${detailBtn} ${deleteBtn}`;
                    }
                }
            ]
        });


        $("#create-note-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let teamPlanID = '<?= $teamPlan['ROW_ID'] ?>';
            let formData = thisForm.serialize() + `&teamPlanID=${teamPlanID}`;
            $.post({
                url: '<?= site_url('auditor/ajax_add_note_inspection_result') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                console.log(res);
                if (res.status) {
                    $("#create-note-form-result").prop('class', 'alert alert-success');
                    $("#create-note-form-result").text(res.text);
                    noteTable.ajax.reload();
                } else {
                    $("#create-note-form-result").prop('class', 'alert alert-danger');
                    $("#create-note-form-result").text(res.text);
                }
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        const getInspectionNoteDetail = rowID => {
            return $.post({
                url: '<?= site_url('auditor/ajax_get_inspection_note_detail') ?>',
                data: {
                    rowID: rowID
                },
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $(document).on('click', ".detail-btn", async function() {
            let rowID = $(this).data('row-id');
            let detail = await getInspectionNoteDetail(rowID);
            $("#edit-note-form-inspection-option").val(detail.INSPECTION_OPTION_ID);
            $("#edit-note-form-commander").val(detail.UNIT_COMMANDER);
            $("#edit-note-form-date").val(detail.DATE_INSPECT);
            $("#edit-note-form-auditee").val(detail.AUDITEE_NAME);
            $("#edit-note-form-auditee-position").val(detail.AUDITEE_POS);
            $("#edit-note-form-can-improve").text(detail.CAN_IMPROVE);
            $("#edit-note-form-failing").text(detail.FAILING);
            $("#edit-note-form-important-failing").text(detail.IMPORTANT_FAILING);
            $("#edit-note-form-commention").text(detail.COMMENTIONS);
            $("#edit-note-form-inspection-score").val(detail.INSPECTION_SCORE);
            $("#edit-note-form-working-score").val(detail.WORKING_SCORE);
            $("#edit-note-form").data('row-id', rowID);
            $("#edit-note-modal").modal();
        });


        $("#edit-note-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let rowID = thisForm.data('row-id');
            let formData = thisForm.serialize() + `&rowID=${rowID}`;
            $.post({
                url: '<?= site_url('auditor/ajax_update_inspection_note') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                console.log(res);
                if (res.status) {
                    $("#edit-note-form-result").prop('class', 'alert alert-success');
                    $("#edit-note-form-result").text(res.text);
                    noteTable.ajax.reload();
                } else {
                    $("#edit-note-form-result").prop('class', 'alert alert-danger');
                    $("#edit-note-form-result").text(res.text);
                }
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        $(document).on('click', ".delete-btn", async function() {
            if (confirm('ยืนยันการลบข้อมูล ?')) {
                let rowID = $(this).data('row-id');
                $.post({
                    url: '<?= site_url('auditor/ajax_delete_inspection_note') ?>',
                    data: {
                        rowID: rowID
                    },
                    dataType: 'json'
                }).done(res => {
                    console.log(res);
                    if (res.status) noteTable.ajax.reload();
                    alert(res.text);
                }).fail((jhr, status, error) => console.error(jhr, status, error));
            } else {

            }
        });

    });
</script>