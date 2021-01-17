<script>
    $(document).ready(function() {
        $("li#controller-user-headnav-manage-data").addClass('menu-open');
        $("li#controller-user-headnav-manage-data").children("a.nav-link").addClass('active');
        $("a#controller-user-question-manage").addClass('active');


        const inspectionOptionsTable = $("#inspection-option-table").DataTable({
            data: [],
            columns: [{
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1,
                    className: 'text-center'
                },
                {
                    data: 'INSPECTION_NAME',
                    render: (data, type, row, meta) => `ชุดคำถาม ${data}`
                },
                {
                    data: 'OPTION_YEAR',
                    className: 'text-center'
                },
                {
                    data: 'ROW_ID',
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        let detailBtn = `<a href="<?= site_url('controller_user/subject') ?>?inspectionoption=${data}" class="btn btn-sm btn-primary">รายละเอียด</a>`;
                        let deleteBtn = `<button class="btn btn-sm btn-danger delete-inspection-option" data-inspection-option-id="${data}">ลบ</button>`;
                        return `${detailBtn} ${deleteBtn}`;
                    }
                }
            ]
        });

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
        putInspectionToSelect();


        const getInspectionOptions = inspectionID => {
            return $.post({
                url: '<?= site_url('data_service/ajax_get_inspection_options') ?>',
                data: {
                    inspectionID: inspectionID
                },
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $("#inspection-list").change(async function(event) {
            $("#fetch-inspection-option-loading").removeClass('invisible');
            let inspectionID = $(this).val();
            if (inspectionID !== '') {
                let inspection = inspections.filter(r => r.INSPE_ID == inspectionID);
                let inspectionOptions = await getInspectionOptions(inspectionID);
                inspectionOptionsTable.clear()
                    .rows.add(inspectionOptions)
                    .draw();
                $("#fetch-inspection-option-loading").addClass('invisible');
                $("#add-inspection-option").children('span').text(inspection[0].INSPE_NAME);
                $("#add-inspection-option").removeClass('invisible');
                $("#add-inspection-option").attr('disabled', false);
            } else {
                $("#add-inspection-option").addClass('invisible');
                $("#add-inspection-option").attr('disabled', true);
                $("#add-inspection-option").children('span').text('');
            }
        });


        $("#add-inspection-option").click(() => $("#create-inspection-option-modal").modal());


        $("#create-inspection-option-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let id = $("#inspection-list").val();
            let formData = thisForm.serialize() + `&inspectionID=${id}`;
            $.post({
                url: '<?= site_url('controller_user/ajax_add_inspection_option') ?>',
                data: formData,
                dataType: 'json'
            }).done(async res => {
                if (res.status) {
                    $("#result-create-inspection-option-form").prop('class', 'alert alert-success');
                    $("#result-create-inspection-option-form").text(res.text);
                    let data = $("#inspection-list").serialize();
                    let inspectionOptions = await getInspectionOptions(data);
                    inspectionOptionsTable.clear()
                        .rows.add(inspectionOptions)
                        .draw();
                } else {
                    $("#result-create-inspection-option-form").prop('class', 'alert alert-danger');
                    $("#result-create-inspection-option-form").text(res.text);
                }
                setTimeout(() => {
                    $("#result-create-inspection-option-form").prop('class', '');
                    $("#result-create-inspection-option-form").text('');
                }, 2500);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        $(document).on('click', ".delete-inspection-option", function() {
            let inspectionOptionID = $(this).data('inspection-option-id');
            console.log(inspectionOptionID);
            if (confirm('ยืนยันการลบ ชุดคำถาม')) {
                $.post({
                    url: '<?= site_url('controller_user/ajax_delete_inspection_option') ?>',
                    data: {
                        inspectionOptionID: inspectionOptionID
                    },
                    dataType: 'json'
                }).done(async res => {
                    console.log(res);
                    let text = res.checkInSubject.status ? '' : res.checkInSubject.text;
                    text += res.checkInAuditorScore.status ? '' : res.checkInAuditorScore.text;
                    if (res.status) {
                        $("#inspection-option-result").prop('class', 'alert alert-success');
                        $("#inspection-option-result").text('ลบข้อมูล สำเร็จ');
                        let inspectionID = $("#inspection-list").val();
                        let inspectionOptions = await getInspectionOptions(inspectionID);
                        inspectionOptionsTable.clear()
                            .rows.add(inspectionOptions)
                            .draw();
                    } else {
                        $("#inspection-option-result").prop('class', 'alert alert-danger');
                        $("#inspection-option-result").text(text);
                    }

                    setTimeout(() => {
                        $("#inspection-option-result").prop('class', 'invisible');
                        $("#inspection-option-result").text('');
                    }, 5000);
                }).fail((jhr, status, error) => console.error(jhr, status, error));
                return true;
            } else {
                return false;
            }
        });

    });
</script>