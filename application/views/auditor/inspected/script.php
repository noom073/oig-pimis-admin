<script>
    $(document).ready(function() {
        $("li#auditor-inspection-section").addClass('menu-open');
        $("a#auditor-inspection-subject").addClass('active');
        $("a#auditor-calendar").addClass('active');


        const getQuestionsAndSubject = (inspectionID) => {
            return $.post({
                url: '<?= site_url('data_service/ajax_get_questions_and_score') ?>',
                data: {
                    inspectionID: inspectionID,
                    plan: '<?= $planID ?>'
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
                        questionsAmount++;
                        questions += `<div class="pl-3 border-left my-2 question">
                                    <div>- ${question.Q_NAME} ?</div>
                                    <div class="pl-5">
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="1" ${question.SCORE == '1' ? 'checked':''}>   
                                        <label class="text-success choice">Yes</label>
                                        &nbsp;&nbsp;                                   
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.5" ${question.SCORE == '.5' ? 'checked':''}>                                        
                                        <label class="text-info choice">N/A</label>
                                        &nbsp;&nbsp; 
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0" ${question.SCORE == '0' ? 'checked':''}>                                        
                                        <label class="text-danger choice">No</label>
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
                        questionsAmount++;
                        html += `<div class="pl-3 border-left my-2 question">
                                    <div>- ${question.Q_NAME} ?</div>
                                    <div class="pl-5">
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="1" ${question.SCORE == '1' ? 'checked':''}>   
                                        <label class="text-success choice">Yes</label>
                                        &nbsp;&nbsp;                                   
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.5" ${question.SCORE == '.5' ? 'checked':''}>                                        
                                        <label class="text-info choice">N/A</label>
                                        &nbsp;&nbsp; 
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0" ${question.SCORE == '0' ? 'checked':''}>                                        
                                        <label class="text-danger choice">No</label>
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
            $(".auditor-score:checked").each(function(el) {
                let score = (+$(this).val());
                countScore += score;
            });
            return countScore;
        };


        const showScore = () => {
            const score = summaryScore();
            $("#result-auditor-score").removeClass('invisible');
            $("#total-auditor-score").text(`${score} คะแนน ; ทั้งหมด ${questionsAmount} คะแนน`);
        };


        const drawQuestionForm = async inspectionID => {
            questionsAmount = 0; // reset ค่าจำนวนคำถาม
            let questions = await getQuestionsAndSubject(inspectionID);
            let html = generateTreeView(questions);
            $("#form-loading").addClass('d-none');
            $("#form-questionaire").html(html);
            showScore();
        };


        $(document).on('change', ".auditor-score", () => {
            showScore();
        });


        $(document).on('click', ".choice", function() {
            $(this).prev('input[type="radio"]').prop('checked', true);
            showScore();
        });


        let inspectionID = '<?= $inspection['INSPE_ID'] ?>';
        drawQuestionForm(inspectionID);
    });
</script>