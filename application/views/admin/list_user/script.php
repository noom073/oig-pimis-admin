<script>
    $(document).ready(function() {
        $("li#admin-manage-user-section").addClass('menu-open');
        $("a#admin-manage-user-subject").addClass('active');
        $("a#admin-list-user").addClass('active');


        let userTable = $("#table-user").DataTable({
            responsive: true,
            ajax: {
                url: '<?= site_url('admin/ajax_get_user_all') ?>',
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
                    data: 'EMAIL',
                },
                {
                    data: 'USER_ACTIVE',
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        if (data === 'y') {
                            return `<span class="text-success">Active</span>`;
                        } else {
                            return `<span class="text-warning">Inactive</span>`;
                        }
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        let editBtn = `<button class="btn btn-sm btn-primary update-user" data-user-id="${row.USER_ID}">แก้ไข</button>`;
                        let deleteBtn = `<button class="btn btn-sm btn-danger delete-user" data-user-id="${row.USER_ID}">ลบ</button>`;
                        return `${editBtn} ${deleteBtn}`;
                    }
                },
            ]
        });


        const getUserTypes = () => {
            console.log('Loading user type ' + new Date());
            return $.get({
                url: '<?= site_url('data_service/ajax_get_type_user') ?>',
                dataType: 'json',
            }).done(res => {
                if ($.isEmptyObject(res)) {
                    console.log('! EMPTY OBJECT');
                }
                console.log('Loading user type complete ' + new Date());
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        }


        const reloadUserTable = () => {
            console.log('Reloading user table ' + new Date());
            $.get({
                url: '<?= site_url('admin/ajax_get_user_all') ?>',
                dataType: 'json'
            }).done(res => {
                userTable.clear().draw();
                userTable.rows.add(res).draw();
                console.log('Reloading user table cpmplete ' + new Date());
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        }


        $("#add-user-btn").click(async function() {
            let type = await getUserTypes();
            let option = '';
            type.forEach(function(r) {
                option += `<option value="${r.TYPE_ID}">${r.TYPE_NAME}</option>`;
            });
            $("#type-user-create-user-form").html(option);
            $("#create-user-modal").modal();
        });


        $("#create-user-form").submit(function(event) {
            event.preventDefault();
            let formData = $(this).serialize();
            let thisForm = $(this);
            $.post({
                url: '<?= site_url('admin/ajax_add_user') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#result-create-user-form").prop('class', 'alert alert-success');
                    $("#result-create-user-form").text(res.text);
                    reloadUserTable();

                    setTimeout(() => {
                        $("#result-create-user-form").prop('class', '');
                        $("#result-create-user-form").text('');
                        thisForm.trigger('reset');
                    }, 5000);
                } else {
                    $("#result-create-user-form").prop('class', 'alert alert-danger');
                    $("#result-create-user-form").text(`! ${res.text}`);

                    setTimeout(() => {
                        $("#result-create-user-form").prop('class', '');
                        $("#result-create-user-form").text('');

                    }, 5000);
                }
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        $(document).on('click', ".delete-user", function() {
            let userID = $(this).data('user-id');
            if (confirm('ยืนยันการลบ ผู้ใช้')) {
                $.post({
                    url: '<?= site_url('admin/ajax_delete_user') ?>',
                    data: {
                        userID: userID
                    },
                    dataType: 'json'
                }).done(res => {
                    alert(res.user.text);
                    alert(res.privilege.text);
                    if (res.user.status) {
                        reloadUserTable();
                    }
                }).fail((jhr, status, error) => console.error(jhr, status, error));
                return true;
            } else {
                return false;
            }
        });


        const getUserDetail = function(userID) {
            console.log('Loading user detail ' + new Date());
            return $.post({
                url: '<?= site_url('admin/ajax_get_user_detail') ?>',
                data: {
                    userID: userID
                },
                dataType: 'json'
            }).done(res => {
                if ($.isEmptyObject(res)) {
                    console.log('! EMPTY OBJECT ' + new Date());
                }
                console.log('Loading user detail complete ' + new Date());
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        }


        $(document).on('click', ".update-user", async function() {
            let userID = $(this).data('user-id');
            let userDetail = await getUserDetail(userID);
            $("#title-update-user-form").val(userDetail.TITLE);
            $("#fname-update-user-form").val(userDetail.FIRSTNAME);
            $("#lname-update-user-form").val(userDetail.LASTNAME);
            let email = userDetail.EMAIL.split('@'); //GET EMAIL WITHOUT DOMAIN
            $("#email-update-user-form").val(email[0]);
            $("#act-update-user-form").val(userDetail.USER_ACTIVE);

            let userTypes = await getUserTypes();
            let option = '';
            userTypes.forEach(function(r) {
                option += `<option value="${r.TYPE_ID}">${r.TYPE_NAME}</option>`;
            });
            $("#type-update-user-form").html(option);
            $("#type-update-user-form").val(userDetail.USER_TYPE);

            $("#update-user-form").data('user-id', userID); // PASS USER-ID VALUE TO #update-user-form FOR USE AS KEY TO UPDATE USER ROW 
            $("#update-user-modal").modal(); // POPUP MODAL
        });


        $("#update-user-form").submit(function(event) {
            event.preventDefault();
            let formData = $(this).serialize();
            let userID = $(this).data('user-id');
            $.post({
                url: '<?= site_url('admin/ajax_update_user_detail') ?>',
                data: formData + `&userID=${userID}`,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#result-update-user-form").prop('class', 'alert alert-success');
                    $("#result-update-user-form").text(res.text);
                    reloadUserTable();

                    setTimeout(() => {
                        $("#result-update-user-form").prop('class', '');
                        $("#result-update-user-form").text('');
                    }, 5000);
                } else {
                    $("#result-update-user-form").prop('class', 'alert alert-danger');
                    $("#result-update-user-form").text(`! ${res.text}`);

                    setTimeout(() => {
                        $("#result-update-user-form").prop('class', '');
                        $("#result-update-user-form").text('');

                    }, 5000);
                }
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });
    });
</script>