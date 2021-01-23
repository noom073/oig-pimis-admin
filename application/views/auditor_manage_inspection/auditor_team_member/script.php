<script>
    $(document).ready(function() {
        $("li#auditor-manage-inspection-section").addClass('menu-open');
        $("a#auditor-manage-inspection-subject").addClass('active');
        $("a#auditor-manage-inspection-auditor-team").addClass('active');


        let auditorMemberTable = $("#auditor-member-table").DataTable({
            // responsive: true,
            ajax: {
                url: '<?= site_url('auditor_manage_inspection/ajax_get_team_member') ?>',
                data: {
                    teamID: <?= $team['ROW_ID'] ?>
                },
                type: 'post',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    className: 'text-center',
                    render: (data, type, row, meta) => meta.row + 1
                },
                {
                    data: null,
                    className: 'text-left',
                    render: (data, type, row, meta) => {
                        let html = `<div>
                                ${row.ADT_TITLE} ${row.ADT_FIRSTNAME} ${row.ADT_LASTNAME}<br/>
                                <small>${row.POSITION}</small>
                            </div>`;
                        return html;
                    }
                },
                {
                    data: 'AUDITOR_POSITION',
                    className: 'text-center'
                },
                {
                    data: 'ADT_ID',
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        let editBtn = `<button class="btn btn-sm btn-primary edit-auditor-member" data-row-id="${data}">แก้ไข</button>`;
                        let deleteBtn = `<button class="btn btn-sm btn-danger delete-auditor-member" data-row-id="${data}">ลบ</button>`;
                        return `${editBtn} ${deleteBtn}`;
                    }
                }
            ]
        });


        $("#add-auditor-member").click(() => $("#create-auditor-member-modal").modal());


        $("#create-auditor-member-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let formData = thisForm.serialize();
            $.post({
                url: '<?= site_url('auditor_manage_inspection/ajax_add_auditor_member') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#create-auditor-member-form-result").prop('class', 'alert alert-success');
                    $("#create-auditor-member-form-result").text(res.text);
                    auditorMemberTable.ajax.reload();
                } else {
                    $("#create-auditor-member-form-result").prop('class', 'alert alert-danger');
                    $("#create-auditor-member-form-result").text('');
                }

                setTimeout(() => {
                    $("#create-auditor-member-form-result").prop('class', '');
                    $("#create-auditor-member-form-result").text('');
                    thisForm.trigger('reset');
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        const getAuditorDetail = auditorID => {
            return $.post({
                url: '<?= site_url('auditor_manage_inspection/ajax_get_auditor_detail') ?>',
                data: {
                    auditorID: auditorID
                },
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $(document).on('click', ".edit-auditor-member", async function() {
            let auditorID = $(this).data('row-id');
            let auditorDetail = await getAuditorDetail(auditorID);
            $("#edit-auditor-member-form-title").val(auditorDetail.ADT_TITLE);
            $("#edit-auditor-member-form-first-name").val(auditorDetail.ADT_FIRSTNAME);
            $("#edit-auditor-member-form-last-name").val(auditorDetail.ADT_LASTNAME);
            $("#edit-auditor-member-form-position").val(auditorDetail.POSITION);
            $("#edit-auditor-member-form-idp").val(auditorDetail.ADT_IDP);
            $("#edit-auditor-member-form-auditor-team").val(auditorDetail.ADT_TEAM);
            $("#edit-auditor-member-form-auditor-type").val(auditorDetail.ADT_TYPE);
            $("#edit-auditor-member-form").data('row-id', auditorID);
            $("#edit-auditor-member-modal").modal();
        });


        $("#edit-auditor-member-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let auditorID = thisForm.data('row-id');
            let formData = thisForm.serialize() + `&auditorID=${auditorID}`;
            $.post({
                url: '<?= site_url('auditor_manage_inspection/ajax_update_auditor_detail') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#edit-auditor-member-form-result").prop('class', 'alert alert-success');
                    $("#edit-auditor-member-form-result").text(res.text);
                    auditorMemberTable.ajax.reload();
                } else {
                    $("#edit-auditor-member-form-result").prop('class', 'alert alert-danger');
                    $("#edit-auditor-member-form-result").text('');
                }

                setTimeout(() => {
                    $("#edit-auditor-member-form-result").prop('class', '');
                    $("#edit-auditor-member-form-result").text('');
                    thisForm.trigger('reset');
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        $(document).on('click', ".delete-auditor-member", function() {
            if (confirm('ยืนยันการลบสมาชิก ?')) {
                let auditorID = $(this).data('row-id');
                $.post({
                    url: '<?= site_url('auditor_manage_inspection/ajax_delete_auditor') ?>',
                    data: {
                        auditorID: auditorID
                    },
                    dataType: 'json'
                }).done(res => {
                    if (res.status) {
                        alert(res.text);
                        auditorMemberTable.ajax.reload();
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