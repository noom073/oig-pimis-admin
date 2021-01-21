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
                    className: 'text-center'
                },
                {
                    data: 'POSITION',
                    className: 'text-center'
                },
                {
                    data: 'ADT_ID',
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        let detailBtn = `<a href="" class="btn btn-sm btn-primary team-detail">รายละเอียด</a>`;
                        let editBtn = `<button class="btn btn-sm btn-primary edit-auditor-team" data-row-id="${data}">แก้ไข</button>`;
                        let deleteBtn = `<button class="btn btn-sm btn-danger delete-auditor-team" data-row-id="${data}" disabled>ลบ</button>`;
                        return `${detailBtn} ${editBtn} ${deleteBtn}`;
                    }
                }
            ]
        });
    });
</script>