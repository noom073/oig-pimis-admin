<script>
    $(document).ready(function() {
        $("li#admin-manage-user-section").addClass('menu-open');
        $("a#admin-manage-user-subject").addClass('active');
        $("a#admin-list-authorize").addClass('active');


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
                        let text = '';
                        data.forEach((r, index, arr) => {
                            if (index == arr.length - 1) {
                                text += `${r.TYPE_NAME_FULL}`;
                            } else {
                                text += `${r.TYPE_NAME_FULL}, `;
                            }
                        });
                        return text;
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        let editBtn = `<a href="<?= site_url('admin/user_authorize') ?>?userID=${row.USER_ID}" class="btn btn-sm btn-primary update-user">แก้ไข</a>`;
                        return `${editBtn}`;
                    }
                },
            ]
        });


    });
</script>