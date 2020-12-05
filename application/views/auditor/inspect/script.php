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
                                    <div class="question-name">- ${question.Q_NAME} ?</div>
                                    <div class="pl-5">
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="1">   
                                        <label class="text-success choice">Yes</label>
                                        &nbsp;&nbsp;                                   
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.5" checked>                                        
                                        <label class="text-info choice">N/A</label>
                                        &nbsp;&nbsp; 
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0">                                        
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
                        html += `<div class="pl-3 border-left my-2 question">
                                    <div class="question-name">- ${question.Q_NAME} ?</div>
                                    <div class="pl-5">
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="1">   
                                        <label class="text-success choice">Yes</label>
                                        &nbsp;&nbsp;                                   
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0.5" checked>                                        
                                        <label class="text-info choice">N/A</label>
                                        &nbsp;&nbsp; 
                                        <input class="auditor-score" type="radio" name="score-${question.Q_ID}" value="0">                                        
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


        const drawQuestionForm = async inspectionID => {
            let questions = await getQuestionsAndSubject(inspectionID);
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
            const questionAmount = $(".question-name").length;
            console.log(questionAmount);
            $("#result-auditor-score").removeClass('invisible');
            $("#total-auditor-score").text(`${score} คะแนน ; ทั้งหมด ${questionAmount} คะแนน`);
        };


        $(".inspect-list").click(async function() {
            let inspectionID = $(this).data('inspection-id');
            await drawQuestionForm(inspectionID);
            showScore();
            $(".inspect-list").removeClass('active');
            $(this).addClass('active');
            $("#auditor-inspect-form").data('inspection-id', inspectionID);
        });


        $(document).on('change', ".auditor-score", () => {
            showScore();
        });


        $(document).on('click', ".choice", function() {
            $(this).prev('input[type="radio"]').prop('checked', true);
            showScore();
        });


        $("#auditor-inspect-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let inspectionID = thisForm.data('inspection-id');
            let planID = '<?= $planID ?>';
            let formData = thisForm.serialize() + `&inspectionID=${inspectionID}&planID=${planID}`;
            console.log(formData);

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