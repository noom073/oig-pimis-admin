<script>
    $(document).ready(function() {
        $("li#auditor-inspection-section").addClass('menu-open');
        $("a#auditor-inspection-subject").addClass('active');
        $("a#auditor-calendar").addClass('active');


        const getQuestionsAndSubject = (inspectionOptionID) => {
            return $.post({
                url: '<?= site_url('data_service/ajax_get_questions_by_inspection') ?>',
                data: {
                    inspectionOptionID: inspectionOptionID
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
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="1" checked>   
                                        <label class="text-success choice">1</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;                                    
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.75">                                        
                                        <label class="text-info choice">0.75</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;  
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.50">                                        
                                        <label class="text-danger choice">0.50</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;  
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.25">                                        
                                        <label class="text-danger choice">0.25</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;  
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0">                                        
                                        <label class="text-danger choice">0</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;  
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="n">                                        
                                        <label class="choice">ไม่มีการตรวจ</label>
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
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="1" checked>   
                                        <label class="text-success choice">1</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;                                    
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.75">                                        
                                        <label class="text-info choice">0.75</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;  
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.50">                                        
                                        <label class="text-danger choice">0.50</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;  
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.25">                                        
                                        <label class="text-danger choice">0.25</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;  
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0">                                        
                                        <label class="text-danger choice">0</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;  
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="n">                                        
                                        <label class="choice">ไม่มีการตรวจ</label>
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


        $(".inspect-list").click(async function() {
            $("#form-loading").removeClass('d-none');
            let inspectionOptionID = $(this).data('inspection-option-id');
            await drawQuestionForm(inspectionOptionID);
            showScore();
            $(".inspect-list").removeClass('active');
            $("#form-loading").addClass('d-none');
            $(this).addClass('active');
            $("#auditor-inspect-form").data('inspection-option-id', inspectionOptionID);
            $("#auditor-inspect-form").removeClass('d-none');
        });


        $(document).on('change', ".auditor-score", () => {
            showScore();
        });


        $(document).on('click', ".choice", function() {
            $(this).prev('input[type="radio"]').prop('checked', true);
            showScore();
        });


        $("#auditor-inspect-form").submit(function(event) {
            $("#auditor-inspect-form-submit").prop('disabled', true);
            event.preventDefault();
            let thisForm = $(this);
            let teamPlanID = thisForm.data('team-plan-id');
            let inspectionOptionID = thisForm.data('inspection-option-id');
            let planID = '<?= '' ?>';
            let formData = thisForm.serialize() + `&teamPlanID=${teamPlanID}&inspectionOptionID=${inspectionOptionID}`;

            $.post({
                url: '<?= site_url('auditor/ajax_auditor_add_inpect_score') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                console.log(res);
                if (res.status) {
                    alert('บันทึกข้อมูลสำเร็จ');
                    window.location.replace('<?= site_url('auditor/calendar') ?>');
                } else {
                    alert('! บันทึกข้อมูลไม่สำเร็จ');
                }
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });

    });
</script>