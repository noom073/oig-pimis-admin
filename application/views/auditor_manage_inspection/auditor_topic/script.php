<script>
    $(document).ready(function() {
        $("li#auditor-manage-inspection-section").addClass('menu-open');
        $("a#auditor-manage-inspection-subject").addClass('active');
        $("a#auditor-manage-inspection-auditor-team").addClass('active');


        let auditorTeamTable = $("#auditor-team-table").DataTable({
            responsive: true,
            ajax: {
                url: '<?= site_url('auditor_manage_inspection/ajax_get_auditor_team') ?>',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    className: 'text-center',
                    render: (data, type, row, meta) => meta.row + 1
                },
                {
                    data: 'TEAM_NAME',
                    className: 'text-center'
                },
                {
                    data: 'TEAM_YEAR',
                    className: 'text-center'
                },
                {
                    data: 'COLOR',
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        return `<button style="background-color:${data};" class="btn btn-sm"> </button>`;
                    }
                },
                {
                    data: 'ROW_ID',
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        let detailBtn = `<a href="<?= site_url('auditor_manage_inspection/auditor_team_member') ?>?team=${data}" class="btn btn-sm btn-primary team-detail">รายชื่อ</a>`;
                        let editBtn = `<button class="btn btn-sm btn-primary edit-auditor-team" data-row-id="${data}">แก้ไข</button>`;
                        let deleteBtn = `<button class="btn btn-sm btn-danger delete-auditor-team" data-row-id="${data}">ลบ</button>`;
                        return `${detailBtn} ${editBtn} ${deleteBtn}`;
                    }
                }
            ]
        });


        $("#add-auditor-team").click(() => $("#create-auditor-name-modal").modal());


        $("#create-auditor-name-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let formData = thisForm.serialize();
            $.post({
                url: '<?= site_url('auditor_manage_inspection/ajax_add_auditor_name') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#create-auditor-name-form-result").prop('class', 'alert alert-success');
                    $("#create-auditor-name-form-result").text(res.text);
                    auditorTeamTable.ajax.reload();
                } else {
                    $("#create-auditor-name-form-result").prop('class', 'alert alert-danger');
                    $("#create-auditor-name-form-result").text(res.text);
                }

                setTimeout(() => {
                    $("#create-auditor-name-form-result").prop('class', '');
                    $("#create-auditor-name-form-result").text('');
                    thisForm.trigger('reset');
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        const getTeamNameDetail = (id) => {
            return $.post({
                url: '<?= site_url('auditor_manage_inspection/ajax_get_a_auditor_name') ?>',
                data: {
                    rowID: id
                },
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $(document).on('click', ".edit-auditor-team", async function() {
            let rowID = $(this).data('row-id');
            let auditorTeam = await getTeamNameDetail(rowID);
            $("#edit-auditor-name-form").data('row-id', rowID);
            $("#edit-auditor-name-form-team-name").val(auditorTeam.TEAM_NAME);
            $("#edit-auditor-name-form-team-year").val(auditorTeam.TEAM_YEAR);
            $("#edit-auditor-name-form-team-color").val(auditorTeam.COLOR);
            $("#edit-auditor-name-modal").modal();
        });


        $("#edit-auditor-name-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let rowID = thisForm.data('row-id');
            let formData = thisForm.serialize() + `&rowID=${rowID}`;
            $.post({
                url: '<?= site_url('auditor_manage_inspection/ajax_update_auditor_name') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                console.log(res);
                if (res.status) {
                    $("#edit-auditor-name-form-result").prop('class', 'alert alert-success');
                    $("#edit-auditor-name-form-result").text(res.text);
                    auditorTeamTable.ajax.reload();
                } else {
                    $("#edit-auditor-name-form-result").prop('class', 'alert alert-danger');
                    $("#edit-auditor-name-form-result").text(res.text);
                }

                setTimeout(() => {
                    $("#edit-auditor-name-form-result").prop('class', '');
                    $("#edit-auditor-name-form-result").text('');
                    thisForm.trigger('reset');
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        $(document).on('click', ".delete-auditor-team", function() {
            let rowID = $(this).data('row-id');
            if (confirm('ยืนยันการลบ ?')) {
                $.post({
                    url: '<?= site_url('auditor_manage_inspection/ajax_delete_auditor_team') ?>',
                    data: {
                        auditorTeamID: rowID
                    },
                    dataType: 'json'
                }).done(res => {
                    if (res.status) {
                        alert(res.text);
                        auditorTeamTable.ajax.reload();
                    } else {
                        alert(res.text);                        
                    }
                }).fail((jhr, status, error) => console.error(jhr, status, error));
                return true;
            } else {
                return false;
            }
        });
    });
</script>