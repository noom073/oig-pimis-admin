<script>
    $(document).ready(function() {
        $("li#auditor-manage-inspection-section").addClass('menu-open');
        $("a#auditor-manage-inspection-subject").addClass('active');
        $("a#auditor-manage-inspection-auditor-team").addClass('active');


        let auditorTeamTable = $("#auditor-team-table").DataTable({
            // responsive: true,
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
                    data: 'ROW_ID',
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        return 1;
                    }
                }
            ]
        });
    });
</script>