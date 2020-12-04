<script>
    $(document).ready(function() {
        $("li#auditor-inspection-section").addClass('menu-open');
        $("a#auditor-inspection-subject").addClass('active');
        $("a#auditor-calendar").addClass('active');


        const getQuestionsAndSubject = (inspectionID) => {
            return $.post({
                url: '<?= site_url('data_service/ajax_get_questions_by_inspection') ?>',
                data: {
                    inspectionID: inspectionID
                },
                dataType: 'json'
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        const generateTreeView = (data, rowNum = '') => {
            let html = '<ul class="pl-2 border-left">';
            data.forEach((r, index) => {
                let number = `${rowNum}${index + 1}.`;
                if (r.child) {
                    let questions = '';
                    r.questions.forEach((question, index) => {
                        questions += `<div class="pl-3 border-left my-2 question">
                                    <div>- ${question.Q_NAME} ?</div>
                                    <div class="pl-5">
                                        <label class="text-success">Yes</label>
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="1">   
                                        &nbsp;&nbsp;                                   
                                        <label class="text-info">N/A</label>
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.5">                                        
                                        &nbsp;&nbsp; 
                                        <label class="text-danger">No</label>
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0">                                        
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
                        html += `<div class="pl-3 border-left my-2 question">
                                    <div>- ${question.Q_NAME} ?</div>
                                    <div class="pl-5">
                                        <label class="text-success">Yes</label>
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="1">   
                                        &nbsp;&nbsp;                                   
                                        <label class="text-info">N/A</label>
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.5">                                        
                                        &nbsp;&nbsp; 
                                        <label class="text-danger">No</label>
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0">                                        
                                    </div>
                                </div>`;
                    });
                    html += `</li>`;
                }
            });
            html += '</ul>';
            return html;
        };


        const drawQuestionForm = async inspectionID => {
            let questions = await getQuestionsAndSubject(inspectionID);
            let html = generateTreeView(questions);
            $("#form-questionaire").html(html);
        };


        $(".inspect").click(function() {
            let inspectionID = $(this).data('inspection-id');
            drawQuestionForm(inspectionID);
        });

        $(document).on('change', ".auditor-score", function() {
            let countScore = 0;
            $(".auditor-score:checked").each(function(el) {
                let score = (+$(this).val());
                countScore += score;
            });
            $("#result-auditor-score").removeClass('invisible');
            $("#total-auditor-score").text(countScore);
        });

    });
</script>