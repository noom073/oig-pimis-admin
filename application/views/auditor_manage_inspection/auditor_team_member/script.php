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
                        let editBtn = `<button class="btn btn-sm btn-primary edit-auditor-team" data-row-id="${data}">แก้ไข</button>`;
                        let deleteBtn = `<button class="btn btn-sm btn-danger delete-auditor-team" data-row-id="${data}" disabled>ลบ</button>`;
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
                console.log(res);
                if (res.status) {
                    $("#create-auditor-member-form-result").prop('class', 'alert alert-success');
                    $("#create-auditor-member-form-result").text(res.text);
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

        
    });
</script>