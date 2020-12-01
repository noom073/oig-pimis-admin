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


        const recursiveTree = data => {
            console.log(data);
            let html ='<ul>';
            data.forEach(r => {
                if (r.child) {
                    html += `<li>${r.SUBJECT_NAME} ${recursiveTree(r.child)} +</li>`;
                    console.log(r.SUBJECT_NAME, r.child);
                } else {
                    // console.log(r.SUBJECT_NAME, '--');   
                    html += `<li>${r.SUBJECT_NAME}</li>`;                 
                }
            });
            html +='</ul>';
            $("#xx").html(html);
        };


        const drawQuestionForm = async inspectionID => {
            let questions = await getQuestionsAndSubject(inspectionID);
            recursiveTree(questions);
        };


        $(".inspect").click(function() {
            let inspectionID = $(this).data('inspection-id');
            drawQuestionForm(inspectionID);
        });

    });
</script>