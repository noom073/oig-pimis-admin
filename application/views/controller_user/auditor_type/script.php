<script>
    $(document).ready(function() {
        $("li#controller-user-headnav-manage-data").addClass('menu-open');
        $("li#controller-user-headnav-manage-data").children("a.nav-link").addClass('active');
        $("a#controller-user-auditor-type").addClass('active');


        let inspections = new Array();
        const putInspectionToSelect = () => {
            /**
             * PUT INSPECTIONS TO SELECT 
             * #edit-auditor-type-form-inspection-type & #create-auditor-type-form-inspection-type
             * 
             *  */
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
                $('#create-auditor-type-form-inspection-type').html(option);
                $('#edit-auditor-type-form-inspection-type').html(option);
                console.log('Loading inspections complete');
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        };
        putInspectionToSelect();


        const auditorTypeTable = $("#auditor-type-table").DataTable({
            responsive: true,
            pageLength: 25,
            ajax: {
                url: '<?= site_url('data_service/ajax_get_auditor_types') ?>',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1,
                    className: 'text-center'
                },
                {
                    data: 'AUDITOR_POSITION'
                },
                {
                    data: 'INSPE_NAME',
                    render: (data, type, row, meta) => data == null ? '-' : data

                },
                {
                    data: 'ADT_T_ID',
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        let editBtn = `<button class="btn btn-sm btn-primary edit-auditor-type" data-auditor-type-id="${data}">แก้ไข</button>`;
                        let deleteBtn = `<button class="btn btn-sm btn-danger delete-auditor-type" data-auditor-type-id="${data}">ลบ</button>`;
                        return `${editBtn} ${deleteBtn}`;
                    }
                },
            ]
        });


        $("#create-auditor-type").click(() => $("#create-auditor-type-modal").modal());


        $("#create-auditor-type-form").submit(function(event) {
            event.preventDefault();
            let formData = $(this).serialize();
            $.post({
                url: '<?= site_url('controller_user/ajax_add_auditor_type') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#create-auditor-type-form-result").prop('class', 'alert alert-success');
                    $("#create-auditor-type-form-result").text(res.text);
                    auditorTypeTable.ajax.reload();
                } else {
                    $("#create-auditor-type-form-result").prop('class', 'alert alert-danger');
                    $("#create-auditor-type-form-result").text(res.text);
                }
                setTimeout(() => {
                    $("#create-auditor-type-form-result").prop('class', '');
                    $("#create-auditor-type-form-result").text('');
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        const getAuditorTypeDetail = id => {
            return $.post({
                url: '<?= site_url('controller_user/ajax_get_auditor_type_detail') ?>',
                data: {
                    auditorTypeID: id
                },
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $(document).on('click', ".edit-auditor-type", async function() {
            let auditorTypeID = $(this).data('auditor-type-id');
            let auditorTypeDetail = await getAuditorTypeDetail(auditorTypeID);
            $("#edit-auditor-type-form-name").val(auditorTypeDetail.AUDITOR_POSITION);
            $("#edit-auditor-type-form-inspection-type").val(auditorTypeDetail.INSPECTION_ID);
            $("#edit-auditor-type-form").data('auditor-type-id', auditorTypeID);
            $("#edit-auditor-type-modal").modal();
        });


        $("#edit-auditor-type-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let id = thisForm.data('auditor-type-id');
            let formData = thisForm.serialize() + `&auditorTypeID=${id}`;
            $.post({
                url: '<?= site_url('controller_user/ajax_update_auditor_type_detail') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#edit-auditor-type-form-result").prop('class', 'alert alert-success');
                    $("#edit-auditor-type-form-result").text(res.text);
                    auditorTypeTable.ajax.reload();
                } else {
                    $("#edit-auditor-type-form-result").prop('class', 'alert alert-danger');
                    $("#edit-auditor-type-form-result").text(res.text);
                }
                setTimeout(() => {
                    $("#edit-auditor-type-form-result").prop('class', '');
                    $("#edit-auditor-type-form-result").text('');
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        $(document).on('click', ".delete-auditor-type", function() {
            if (confirm('ยืนยันการลบข้อมูล ?')) {
                let auditorTypeID = $(this).data('auditor-type-id');
                $.post({
                    url: '<?= site_url('controller_user/ajax_delete_auditor_type') ?>',
                    data: {
                        auditorTypeID: auditorTypeID
                    },
                    dataType: 'json'
                }).done(res => {
                    if (res.status) {
                        alert(res.text);
                        auditorTypeTable.ajax.reload();
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