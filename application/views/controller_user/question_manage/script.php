<script>
    $(document).ready(function() {
        $("li#controller-user-headnav-manage-data").addClass('menu-open');
        $("li#controller-user-headnav-manage-data").children("a.nav-link").addClass('active');
        $("a#controller-user-question-manage").addClass('active');


        const inspectionOPtionsTable = $("#inspection-option-table").DataTable({
            data: [],
            columns: [{
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1,
                    className: 'text-center'
                },
                {
                    data: 'INSPECTION_NAME'
                },
                {
                    data: 'OPTION_YEAR',
                    className: 'text-center'
                },
                {
                    data: 'ROW_ID',
                    className: 'text-center'
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


        const getInspectionOptions = formData => {
            return $.post({
                url: '<?= site_url('data_service/ajax_get_inspection_options') ?>',
                data: formData,
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $("#get-inspections-option-form").submit(async function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let formData = thisForm.serialize();
            $("#fetch-inspection-option-loading").removeClass('invisible');
            let inspectionOptions = await getInspectionOptions(formData);
            inspectionOPtionsTable.clear()
                .rows.add(inspectionOptions)
                .draw();
            $("#fetch-inspection-option-loading").addClass('invisible');
        });


        $("#inspection-list").change(function() {
            let inspectionID = $(this).val();
            if (inspectionID !== '') {
                let inspection = inspections.filter(r => r.INSPE_ID == inspectionID);
                console.log(inspection);
                $("#add-inspection-option").children('span').text(inspection[0].INSPE_NAME);
                $("#add-inspection-option").removeAttr('disabled');
            } else {
                $("#add-inspection-option").attr('disabled', true);
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
            }).done(res => {
                if (res.status) {
                    $("#result-create-inspection-option-form").prop('class', 'alert alert-success');
                    $("#result-create-inspection-option-form").text(res.text);
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

    });
</script>