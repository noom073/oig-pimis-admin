<!-- Select2 -->
<script src="<?= base_url('assets/admin_lte/plugins/select2/js/select2.full.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        $("li#admin-manage-user-section").addClass('menu-open');
        $("a#admin-manage-user-subject").addClass('active');
        $("a#admin-list-authorize").addClass('active');
        $("#privilege-form-result").text('Loading...');
        $(".select2").select2();


        const getAllUserType = () => {
            return $.get({
                url: '<?= site_url('data_service/ajax_get_all_user_types') ?>',
                dataType: 'json'
            }).done(res => {
                console.log('Loading getAllUserType complete');
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        };


        const drawUserTypeSelect = async () => {
            let userTypes = await getAllUserType();
            let userPrivileges = JSON.parse('<?= json_encode($userPrivileges) ?>');
            let types = userPrivileges.map(type => type.TYPE_NAME);
            let option = '';
            userTypes.forEach(r => {
                let checkselected = types.some(type => type == r.TYPE_NAME);
                let selected = checkselected === true ? 'selected' : '';
                option += `<option value="${r.TYPE_ID}" ${selected}>${r.TYPE_NAME_FULL}</option>`;
            });
            $("#user-privileges").html(option);
            $("#privilege-form-result").text('');
        };
        drawUserTypeSelect();


        $("#privileg-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let userID = thisForm.data('user-id');
            let formData = thisForm.serialize() + `&userID=${userID}`;
            $.post({
                url: '<?= site_url('admin/ajax_edit_privilage') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                console.log(res);
                $("#privilege-form-result").prop('class', 'alert alert-success');
                $("#privilege-form-result").text('บันทึกสำเร็จ');
                setTimeout(() => {
                    $("#privilege-form-result").prop('class', '');
                    $("#privilege-form-result").text(res.text);
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });
    });
</script>