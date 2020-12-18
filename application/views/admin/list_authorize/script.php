<!-- Select2 -->
<script src="<?= base_url('assets/admin_lte/plugins/select2/js/select2.full.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        $("li#admin-manage-user-section").addClass('menu-open');
        $("a#admin-manage-user-subject").addClass('active');
        $("a#admin-list-authorize").addClass('active');


        $("select").select2();


        let userTable = $("#table-user").DataTable({
            responsive: true,
            ajax: {
                url: '<?= site_url('admin/ajax_list_user_and_privileges') ?>',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    className: 'text-center',
                    render: (data, type, row, meta) => meta.row + 1
                },
                {
                    data: null,
                    render: (data, type, row, meta) => {
                        let title = row.TITLE == null ? '' : row.TITLE;
                        let fName = row.FIRSTNAME == null ? '' : row.FIRSTNAME;
                        let lName = row.LASTNAME == null ? '' : row.LASTNAME;
                        return `${title} ${fName}  ${lName}`;
                    }
                },
                {
                    data: 'EMAIL'
                },
                {
                    data: 'PRIVILEGES',
                    render: (data, type, row, meta) => {
                        console.log(data);
                        let text = '';
                        data.forEach(r => {
                            text += `${r.TYPE_NAME} `;
                        });
                        return text;
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        let editBtn = `<button class="btn btn-sm btn-primary update-user" data-user-id="${row.USER_ID}">แก้ไข</button>`;
                        return `${editBtn}`;
                    }
                },
            ]
        });


    });
</script>