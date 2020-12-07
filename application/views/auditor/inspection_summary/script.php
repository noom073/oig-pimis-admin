<script>
    $(document).ready(function() {
        $("li#auditor-inspection-section").addClass('menu-open');
        $("a#auditor-inspection-subject").addClass('active');
        $("a#auditor-calendar").addClass('active');


        let summaryTable = $("#summary-table").DataTable({
            ajax: {
                url: '<?= site_url('auditor/ajax_get_summary') ?>',
                type: 'post',
                data: {
                    planID: '<?= $planID ?>'
                },
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    className: 'text-center',
                    render: (data, type, row, meta) => meta.row + 1
                },
                {
                    data: 'INSPE_NAME'
                },
                {
                    data: 'SCORE',
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        let dt = data === null ? '' : data;
                        return `<span class="text-success">${dt}</span>`
                    }
                },
                {
                    data: 'TIME_UPDATE'
                },
                {
                    data: 'ROW_ID',
                    className: 'text-center',
                    render: (data, type, row, meta) => {
                        let btn = `<button class="btn btn-sm btn-primary edit-btn" data-summary-id="${data}">แก้ไข</button>`;
                        return btn;
                    }
                }
            ],
            initComplete: (settings, json) => {
                $("#loading-table").addClass('invisible');
            }
        });


        const getInspections = () => {
            console.log('Loading inspections...');
            return $.get({
                url: '<?= site_url('data_service/ajax_get_inspection') ?>',
                dataType: 'json'
            }).done(res => {
                console.log('Loading inspections complete');
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $("#create-summary-btn").click(async function() {
            let inspections = await getInspections();
            let option = '';
            inspections.forEach(r => {
                option += `<option value="${r.INSPE_ID}">${r.INSPE_NAME}</option>`;
            });
            $("#create-summary-inspections").html(option);
            $("#create-summary-modal").modal();
        });


        const setAllScore = () => {
            let sumScore = +($("#sum-score-avg").text());
            let policyScore = +($("#policy-score-avg").text());
            let prepareScore = +($("#prepare-score-avg").text());
            let allScore = sumScore + policyScore + prepareScore;
            $("#all-score").text(allScore);
        };
        setAllScore();


        $("#create-summary-form").submit(function(event) {
            event.preventDefault();
            $("#loading-table").removeClass('invisible');
            let thisForm = $(this);
            let planID = thisForm.data('plan-id');
            let formData = thisForm.serialize() + `&planID=${planID}`;
            $.post({
                url: '<?= site_url('auditor/ajax_add_summary') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#create-summary-result").prop('class', 'alert alert-success');
                    $("#create-summary-result").text(res.text);
                    summaryTable.ajax.reload(() => {
                        $("#loading-table").addClass('invisible');
                    });
                } else {
                    $("#create-summary-result").prop('class', 'alert alert-danger');
                    $("#create-summary-result").text(res.text);
                }

                setTimeout(() => {
                    $("#create-summary-result").prop('class', '');
                    $("#create-summary-result").text('');
                    thisForm.trigger('reset');
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        $("#set-plan-score").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let planID = thisForm.data('plan-id');
            let formData = thisForm.serialize() + `&plan=${planID}`;
            $.post({
                url: '<?= site_url('auditor/ajax_update_plan_score') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#set-plan-score-result").prop('class', 'alert alert-success');
                    $("#set-plan-score-result").text(res.text);
                    let policyScore = res.data.policyScore * 0.1;
                    let prepareScore = res.data.prepareScore * 0.1;
                    $("#policy-score-avg").text(policyScore.toFixed(2));
                    $("#prepare-score-avg").text(prepareScore.toFixed(2));
                    setAllScore();
                } else {
                    $("#set-plan-score-result").prop('class', 'alert alert-danger');
                    $("#set-plan-score-result").text(res.text);
                }

                setTimeout(() => {
                    $("#set-plan-score-result").prop('class', '');
                    $("#set-plan-score-result").text('');
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        const getsummaryDetail = summaryID => {
            console.log('Loading summary detail...');
            return $.post({
                url: '<?= site_url('auditor/ajax_get_summary_detail') ?>',
                data: {
                    summaryID: summaryID
                },
                dataType: 'json'
            }).done(res => {
                console.log('Loading summary detail complete');
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $(document).on('click', ".edit-btn", async function() {
            let summaryID = $(this).data('summary-id');
            let summaryData = await getsummaryDetail(summaryID);
            let inspections = await getInspections();
            let option = '';
            inspections.forEach(r => {
                option += `<option value="${r.INSPE_ID}">${r.INSPE_NAME}</option>`;
            });
            $("#update-summary-form").data('summary-id', summaryID);
            $("#update-summary-inspections").html(option);
            $("#update-summary-inspections").val(summaryData.INSPECTION_ID);
            $("#update-summary-comment").val(summaryData.COMMENTION);
            $("#update-summary-modal").modal();
        });


        $("#update-summary-form").submit(function(event) {
            event.preventDefault();
            $("#loading-table").removeClass('invisible');
            let thisForm = $(this);
            let summaryID = thisForm.data('summary-id');
            let formData = thisForm.serialize() + `&summaryID=${summaryID}`;
            $.post({
                url: '<?= site_url('auditor/ajax_update_summary') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#update-summary-result").prop('class', 'alert alert-success');
                    $("#update-summary-result").text(res.text);
                    summaryTable.ajax.reload(() => {
                        $("#loading-table").addClass('invisible');
                    });
                } else {
                    $("#update-summary-result").prop('class', 'alert alert-danger');
                    $("#update-summary-result").text(res.text);
                }

                setTimeout(() => {
                    $("#update-summary-result").prop('class', '');
                    $("#update-summary-result").text('');
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });

    });
</script>