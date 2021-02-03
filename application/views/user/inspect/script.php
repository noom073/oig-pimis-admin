<script>
    $(document).ready(function() {
        $("li#user-section").addClass('menu-open');
        $("a#user-inspection-subject").addClass('active');
        $("a#user-calendar").addClass('active');


        let teamPlanID = <?= $teamPlan['ROW_ID'] ?>;

        const getQuestionsAndSubject = (inspectionOptionID) => {
            return $.post({
                url: '<?= site_url('data_service/ajax_get_questions_and_score_user') ?>',
                data: {
                    inspectionOptionID: inspectionOptionID,
                    teamPlanID: teamPlanID
                },
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        let questionsAmount = 0;
        const generateTreeView = (data, rowNum = '') => {
            let html = '<ul class="pl-2 border-left">';
            data.forEach((r, index) => {
                let number = `${rowNum}${index + 1}.`;
                if (r.child) {
                    let questions = '';
                    r.questions.forEach((question, index) => {
                        let status = '';
                        if (question.SCORE == '1') status = '<span class="text-success">ดำเนินการ</span>';
                        else if (question.SCORE == '.5') status = '<span class="text-info">อยู่ระหว่างดำเนินการ</span>';
                        else if (question.SCORE == '0') status = '<span class="text-danger">ไม่ได้ดำเนินการ</span>';
                        else status = '<span>N/A</span>';

                        questionsAmount++;
                        questions += `<div class="pl-3 border-left my-2 question">
                                    <div class="question-name">- ${question.Q_NAME} ?</div>
                                    <div class="pl-5">
                                        <button type="button"
                                            class="btn btn-sm btn-primary user-evaluate" 
                                            data-question-id="${question.Q_ID}" 
                                            data-team-plan-id="${teamPlanID}">
                                            รายละเอียด
                                        </button>
                                        ${status}
                                    </div>
                                </div>`;
                    });

                    html += `<li>${number} ${r.SUBJECT_NAME} 
                                ${questions}
                                ${generateTreeView(r.child, number)}
                            </li>`;
                } else {
                    html += `<li class="pl-2 border-left">${number} ${r.SUBJECT_NAME}`;
                    r.questions.forEach((question, index) => {
                        let status = '';
                        if (question.SCORE == '1') status = '<span class="text-success">ดำเนินการ</span>';
                        else if (question.SCORE == '.5') status = '<span class="text-info">อยู่ระหว่างดำเนินการ</span>';
                        else if (question.SCORE == '0') status = '<span class="text-danger">ไม่ได้ดำเนินการ</span>';
                        else status = '<span>N/A</span>';

                        questionsAmount++;
                        html += `<div class="pl-3 border-left my-2 question">
                                    <div class="question-name">- ${question.Q_NAME} ?</div>
                                    <div class="pl-5">
                                        <button type="button"
                                            class="btn btn-sm btn-primary user-evaluate" 
                                            data-question-id="${question.Q_ID}" 
                                            data-team-plan-id="${teamPlanID}">
                                            รายละเอียด
                                        </button>
                                        ${status}
                                    </div>
                                </div>`;
                    });
                    html += `</li>`;
                }
            });
            html += '</ul>';
            return html;
        };


        const drawQuestionForm = async inspectionOptionID => {
            questionsAmount = 0; // reset ค่าจำนวนคำถาม
            $("#form-questionaire").html('');
            let questions = await getQuestionsAndSubject(inspectionOptionID);
            let html = generateTreeView(questions);
            $("#form-questionaire").html(html);
        };


        // const summaryScore = () => {
        //     let countScore = 0;
        //     $(".auditor-score:checked").each(function(el) {
        //         let score = (+$(this).val());
        //         countScore += score;
        //     });
        //     return countScore;
        // };


        // const showScore = () => {
        //     const score = summaryScore();
        //     $("#result-auditor-score").removeClass('invisible');
        //     $("#total-auditor-score").text(`${score} คะแนน ; ทั้งหมด ${questionsAmount} คะแนน`);
        // };


        $(".inspect-list").click(async function() {
            $("#form-loading").removeClass('d-none');
            let inspectionOptionID = $(this).data('inspection-option-id');
            await drawQuestionForm(inspectionOptionID);
            // showScore();
            $(".inspect-list").removeClass('active');
            $("#form-loading").addClass('d-none');
            $(this).addClass('active');
            $("#user-evaluate-form").data('inspection-option-id', inspectionOptionID);
            $("#evaluate-form").data('inspection-option-id', inspectionOptionID);
            $("#user-evaluate-form").removeClass('d-none');
        });


        // $(document).on('change', ".auditor-score", () => {
        //     showScore();
        // });


        // $(document).on('click', ".choice", function() {
        //     $(this).prev('input[type="radio"]').prop('checked', true);
        //     showScore();
        // });


        // $("#user-evaluate-form").submit(function(event) {
        //     $("#user-evaluate-form-submit").prop('disabled', true);
        //     event.preventDefault();
        //     let thisForm = $(this);
        //     let teamPlanID = thisForm.data('team-plan-id');
        //     let inspectionOptionID = thisForm.data('inspection-option-id');
        //     let planID = '<?= '' ?>';
        //     let formData = thisForm.serialize() + `&teamPlanID=${teamPlanID}&inspectionOptionID=${inspectionOptionID}`;

        //     $.post({
        //         url: '<?= site_url('auditor/ajax_auditor_add_inpect_score') ?>',
        //         data: formData,
        //         dataType: 'json'
        //     }).done(res => {
        //         console.log(res);
        //         if (res.status) {
        //             alert('บันทึกข้อมูลสำเร็จ');
        //             window.location.replace('<?= site_url('user/calendar') ?>');
        //         } else {
        //             alert('! บันทึกข้อมูลไม่สำเร็จ');
        //         }
        //     }).fail((jhr, status, error) => console.error(jhr, status, error));
        // });


        const getEvaluateData = (questionID, teamPlanID) => {
            return $.post({
                url: '<?= site_url('user/ajax_get_evaluate') ?>',
                data: {
                    questionID: questionID,
                    teamPlanID: teamPlanID
                },
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        const getFilesAttath = (questionID, teamPlanID) => {
            return $.post({
                url: '<?= site_url('user/ajax_get_files_attath') ?>',
                data: {
                    questionID: questionID,
                    teamPlanID: teamPlanID
                },
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $(document).on('click', ".user-evaluate", async function() {
            let title = $(this).parent().siblings('.question-name').text();
            let questionID = $(this).data('question-id');
            let teamPlanID = $(this).data('team-plan-id');
            let evaluateData = await getEvaluateData(questionID, teamPlanID);
            let filesAttath = await getFilesAttath(questionID, teamPlanID);
            if ($.isEmptyObject(evaluateData) == false) {
                $(`input:radio[name='evalValue'][value='${evaluateData.VALUE}']`).prop('checked', true);
            }

            let list = '';
            filesAttath.forEach((r, index) => {
                console.log(r);
                list += `<a target="blank" href="<?= base_url('assets/filesUpload/') ?>${r.FILES_PATH}" title="${r.FILE_NAME}">File. ${index+1}</a> <br>`;
            });
            $("#list-files").html(list);
            $("#evaluate-modal-label").text(title);
            $("#evaluate-form").data({
                questionID: questionID,
                teamPlanID: teamPlanID
            });
            $("#evaluate-modal").modal();
        });


        $("#evaluate-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            // let questionID = thisForm.data('questionID');
            // let teamPlanID = thisForm.data('teamPlanID');
            let formData = new FormData(this);
            formData.append('questionID', thisForm.data('questionID'));
            formData.append('teamPlanID', thisForm.data('teamPlanID'));
            formData.append('inspectionOptionID', thisForm.data('inspection-option-id'));
            $.post({
                url: '<?= site_url('user/ajax_set_evaluate') ?>',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
            }).done(res => {
                console.log(res);
                alert(res.update.text);
                let inspectionOptionID = thisForm.data('inspection-option-id');
                drawQuestionForm(inspectionOptionID);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });

    });
</script>