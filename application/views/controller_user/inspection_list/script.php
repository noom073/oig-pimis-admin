<script>
    $(document).ready(function() {
        $("li#controller-user-headnav-manage-data").addClass('menu-open');
        $("li#controller-user-headnav-manage-data").children("a.nav-link").addClass('active');
        $("a#controller-user-inspection").addClass('active');


        let inspections = new Array();
        const putInspectionToSelect = () => { // PUT INSPECTIONS TO #inspection-list
            console.log('Loading inspections');
            $.get({
                url: '<?= site_url('data_service/ajax_get_inspection') ?>',
                dataType: 'json'
            }).done(res => {
                inspections = res;
                let option = '<option value="">โปรดระบุ</option>';
                res.forEach(r => {
                    option += `<option value="${r.INSPE_ID}">${r.INSPE_NAME}</option>`;
                });
                $('#inspection-list').html(option);
                console.log('Loading inspections complete');

            }).fail((jhr, status, error) => console.error(jhr, status, error));
        };

        const inspectionTable = $("#inspection-table").DataTable({
            pageLength: 25,
            ajax: {
                url: '<?= site_url('data_service/ajax_get_inspection') ?>',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1,
                    className: 'text-center'
                },
                {
                    data: 'INSPE_NAME'
                },
                {
                    data: 'INSPE_ID',
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        let editBtn = `<button class="btn btn-sm btn-primary edit-inspection" data-inspection-id="${data}">แก้ไข</button>`;
                        let deleteBtn = `<button class="btn btn-sm btn-danger delete-inspection" data-inspection-id="${data}">ลบ</button>`;
                        return `${editBtn} ${deleteBtn}`;
                    }
                },
            ]
        });


        $("#create-inspection").click(function() {
            $("#create-inspection-modal").modal();
        });


        $("#create-inspection-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let formData = thisForm.serialize();

            $.post({
                url: '<?= site_url('controller_user/add_inspection') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                console.log(res);
                if (res.status) {
                    $("#result-create-inspection-form").prop('class', 'alert alert-success');
                    $("#result-create-inspection-form").text(res.text);
                    inspectionTable.ajax.reload();
                } else {
                    $("#result-create-inspection-form").prop('class', 'alert alert-danger');
                    $("#result-create-inspection-form").text(res.text);
                }
                setTimeout(() => {
                    $("#result-create-inspection-form").prop('class', '');
                    $("#result-create-inspection-form").text('');
                    thisForm.trigger('reset');
                }, 5000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        const getInspection = (inspectionID) => {
            return $.post({
                url: '<?= site_url('data_service/ajax_get_a_inspection') ?>',
                data: {
                    inspectionID: inspectionID
                },
                dataType: 'json'
            }).done(res => {}).fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $(document).on('click', ".edit-inspection", async function() {
            let inspectionID = $(this).data('inspection-id');
            let inspection = await getInspection(inspectionID);
            console.log(inspection);
            $("#inspection-name-edit-inspection-form").val(inspection.INSPE_NAME);
            $("#inspection-order-edit-inspection-form").val(inspection.ORDER_NUM);
            $("#edit-inspection-form").data('inspection-id', inspectionID);
            $("#edit-inspection-modal").modal();
        });


        $("#edit-inspection-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let inspectionID = thisForm.data('inspection-id');
            let formData = thisForm.serialize() + `&inspectionID=${inspectionID}`;
            $.post({
                url: '<?= site_url('controller_user/ajax_update_inspection') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                console.log(res);
                if (res.status) {
                    $("#result-edit-inspection-form").prop('class', 'alert alert-success');
                    $("#result-edit-inspection-form").text(res.text);
                    inspectionTable.ajax.reload();
                } else {
                    $("#result-edit-inspection-form").prop('class', 'alert alert-danger');
                    $("#result-edit-inspection-form").text(res.text);
                }
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });

        $(document).on('click', ".delete-inspection", function() {
            if (confirm('ยืนยันการลบสายการตรวจ')) {
                let inspectionID = $(this).data('inspection-id');
                $.post({
                    url: '<?= site_url('controller_user/ajax_delete_inspection') ?>',
                    data: {
                        inspectionID: inspectionID
                    },
                    dataType: 'json'
                }).done(res => {
                    console.log(res);
                    alert(res.text);
                    inspectionTable.ajax.reload();
                }).fail((jhr, status, error) => console.error(jhr, status, error));
                return true;
            } else {
                return false;
            }
        });
    });
</script>