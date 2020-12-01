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
            let html = '<ul class="pl-2">';
            data.forEach((r, index) => {
                let number = `${rowNum}${index + 1}.`;
                if (r.child) {
                    html += `<li>${number} ${r.SUBJECT_NAME} ${generateTreeView(r.child, number)}</li>`;
                } else {
                    html += `<li>${number} ${r.SUBJECT_NAME}`;
                    r.questions.forEach((question, index) => {
                        html += `<div class="pl-3 d-flex my-2 question">
                                    <div>${number+(index+1)}. ${question.Q_NAME} ?</div>
                                    <div class="ml-auto">
                                        <input type="radio" name="" id="">
                                        <input type="radio" name="" id="">
                                        <input type="radio" name="" id="">
                                        <div>
                                            <input type="file" name="" id="">
                                        </div>
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
            $("#xx").html(html);
        };


        $(".inspect").click(function() {
            let inspectionID = $(this).data('inspection-id');
            drawQuestionForm(inspectionID);
        });

    });
</script>