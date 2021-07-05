<script>
    $(document).ready(function() {
        $("li#auditor-inspection-section").addClass('menu-open');
        $("a#auditor-inspection-subject").addClass('active');
        $("a#auditor-calendar").addClass('active');


        const getQuestionsAndSubject = (inspectionOptionID) => {
            return $.post({
                url: '<?= site_url('data_service/ajax_get_questions_and_score') ?>',
                data: {
                    inspectionOptionID: inspectionOptionID,
                    teamPlanID: '<?= $teamPlan['ROW_ID'] ?>'
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
                        questionsAmount += (+question.LIMIT_SCORE);
                        // -------------------- BACKUP INPUT SCORE BY RADIO -------------------
                        // questions += `<div class="pl-3 border-left my-2 question">
                        //             <div>- ${question.Q_NAME} ?</div>
                        //             <div class="pl-5">
                        //                 <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="1" ${question.SCORE == '1' ? 'checked':''}>   
                        //                 <label class="text-success choice">1</label>
                        //                 &nbsp;&nbsp;                                   
                        //                 <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.75" ${question.SCORE == '0.75' ? 'checked':''}>                                        
                        //                 <label class="text-info choice">0.75</label>
                        //                 &nbsp;&nbsp; 
                        //                 <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.5" ${question.SCORE == '0.5' ? 'checked':''}>                                        
                        //                 <label class="text-danger choice">0.50</label>
                        //                 &nbsp;&nbsp; 
                        //                 <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.25" ${question.SCORE == '0.25' ? 'checked':''}>                                        
                        //                 <label class="text-danger choice">0.25</label>
                        //                 &nbsp;&nbsp; 
                        //                 <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0" ${question.SCORE == '0' ? 'checked':''}>                                        
                        //                 <label class="text-danger choice">0</label>
                        //                 &nbsp;&nbsp;&nbsp;&nbsp;  
                        //                 <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="n" ${question.SCORE == 'n' ? 'checked':''}>                                        
                        //                 <label class="choice">ไม่มีการตรวจ</label>
                        //             </div>
                        //         </div>`;
                        // END ------------------- BACKUP INPUT SCORE BY RADIO -------------------
                        let textMaxDesc = (question.LIMIT_SCORE === null) ? 'ไม่ได้ระบุ' : question.LIMIT_SCORE;
                        questions += `<div class="pl-3 border-left my-2 question">
                                    <div>- ${question.Q_NAME} ?</div>
                                    <div class="pl-5 col-2">
                                        <input class="auditor-score form-control" type="number" name="score-${question.Q_ID}" value="${question.SCORE}" max="${question.LIMIT_SCORE}" min="0" step="0.01">   
                                        <small class="text-danger">คะแนนสูงสุดไม่เกิน ${textMaxDesc}</small>
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
                        questionsAmount += (+question.LIMIT_SCORE);
                        // -------------------- BACKUP INPUT SCORE BY RADIO -------------------
                        // html += `<div class="pl-3 border-left my-2 question">
                        //             <div>- ${question.Q_NAME} ?</div>
                        //             <div class="pl-5">
                        //                 <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="1" ${question.SCORE == '1' ? 'checked':''}>   
                        //                 <label class="text-success choice">1</label>
                        //                 &nbsp;&nbsp;                                   
                        //                 <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.75" ${question.SCORE == '0.75' ? 'checked':''}>                                        
                        //                 <label class="text-info choice">0.75</label>
                        //                 &nbsp;&nbsp; 
                        //                 <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.5" ${question.SCORE == '0.5' ? 'checked':''}>                                        
                        //                 <label class="text-danger choice">0.50</label>
                        //                 &nbsp;&nbsp; 
                        //                 <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.25" ${question.SCORE == '0.25' ? 'checked':''}>                                        
                        //                 <label class="text-danger choice">0.25</label>
                        //                 &nbsp;&nbsp; 
                        //                 <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0" ${question.SCORE == '0' ? 'checked':''}>                                        
                        //                 <label class="text-danger choice">0</label>
                        //                 &nbsp;&nbsp;&nbsp;&nbsp;  
                        //                 <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="n" ${question.SCORE == 'n' ? 'checked':''}>                                        
                        //                 <label class="choice">ไม่มีการตรวจ</label>
                        //             </div>
                        //         </div>`;
                        // END ------------------- BACKUP INPUT SCORE BY RADIO -------------------
                        let textMaxDesc = (question.LIMIT_SCORE === null) ? 'ไม่ได้ระบุ' : question.LIMIT_SCORE;
                        html += `<div class="pl-3 border-left my-2 question">
                                    <div>- ${question.Q_NAME} ?</div>
                                    <div class="pl-5 col-2">
                                        <input class="auditor-score form-control" type="number" name="score-${question.Q_ID}" value="${question.SCORE}" max="${question.LIMIT_SCORE}" min="0" step="0.01">   
                                        <small class="text-danger">คะแนนสูงสุดไม่เกิน ${textMaxDesc}</small>
                                    </div>
                                </div>`;
                    });
                    html += `</li>`;
                }
            });
            html += '</ul>';
            return html;
        };


        const summaryScore = () => {
            let countScore = 0;
            $(".auditor-score").each(function(el) {
                let score = 0;
                if ($(this).val().trim() == '') {
                    score = (+$(this).prop('max'));
                } else {
                    score = (+$(this).val());
                }
                countScore += score;
            });
            return countScore;
        };


        const showScore = () => {
            const score = summaryScore();
            $("#result-auditor-score").removeClass('invisible');
            $("#total-auditor-score").text(`${score} คะแนน ; ทั้งหมด ${questionsAmount} คะแนน`);
        };


        const drawQuestionForm = async inspectionOptionID => {
            questionsAmount = 0; // reset ค่าจำนวนคำถาม
            let questions = await getQuestionsAndSubject(inspectionOptionID);
            let inspectionID = questions.inspectionID;
            let html = generateTreeView(questions.array);
            $("#form-loading").addClass('d-none');
            $("#form-questionaire").html(html);
            $("#auditor-inspect-form").removeClass('d-none');
            showScore();
            let userInspectionType = JSON.parse('<?= json_encode($userInspectionType) ?>');
            if (userInspectionType.includes(inspectionID)) {
                $("#form-questionaire-submit").removeClass('d-none');
            }
        };


        $(document).on('change', ".auditor-score", () => {
            showScore();
        });


        $(document).on('click', ".choice", function() {
            $(this).prev('input[type="radio"]').prop('checked', true);
            showScore();
        });


        let inspectionOptionID = '<?= $inspectionOption['ROW_ID'] ?>';
        drawQuestionForm(inspectionOptionID);


        $("#auditor-inspect-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let teamPlanID = <?= $teamPlan['ROW_ID'] ?>;
            let formData = thisForm.serialize() + `&teamPlanID=${teamPlanID}`;
            $.post({
                url: '<?= site_url('auditor/ajax_update_inspect_score') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                console.log(res);
                $("#form-questionaire-result").prop('class', 'alert alert-success');
                $("#form-questionaire-result").text('บันทึกสำเร็จ');

                setTimeout(() => {
                    $("#form-questionaire-result").prop('class', '');
                    $("#form-questionaire-result").text('');
                }, 3000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });
    });
</script>