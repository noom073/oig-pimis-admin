<script>
    $(document).ready(function() {
        $("li#controller-user-headnav-manage-data").addClass('menu-open');
        $("li#controller-user-headnav-manage-data").children("a.nav-link").addClass('active');
        $("a#controller-user-question-manage").addClass('active');


        $(".close-window").click(() => window.close());


        $(".add-question").click(() => $("#add-question-modal").modal());


        const getQuestions = (subjectID) => {
            console.log('Loading questions');
            return $.post({
                url: '<?= site_url('data_service/ajax_get_questions') ?>',
                data: {
                    subjectID: subjectID
                },
                dataType: 'json'
            }).done(res => console.log('Loading questions complete')).fail((jhr, status, error) => console.error(jhr, status, error));
        };


        const drawQuestionsList = async () => {
            let subjectID = <?= $subject['SUBJECT_ID'] ?>;
            let questions = await getQuestions(subjectID);
            let html = '';
            questions.forEach((r, index) => {
                let editBtn = `<button class="btn btn-sm btn-primary edit-question-btn" data-question-id="${r.Q_ID}">แก้ไข</button>`;
                let deleteBtn = `<button class="btn btn-sm btn-danger delete-question-btn" data-question-id="${r.Q_ID}">ลบ</button>`;
                html += `<div class="d-flex mb-1">
                        <div>
                            <span>${index+1}.</span>
                            <span>${r.Q_NAME}</span>
                        </div>
                        <div class="ml-auto">${editBtn} ${deleteBtn}</div>
                    </div>`;
            });
            $("#question-list").html(html);
        };
        drawQuestionsList();


        $("#create-question-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let subjectID = thisForm.data('subject-id');
            let formData = thisForm.serialize() + `&subjectID=${subjectID}`;
            $.post({
                url: '<?= site_url('controller_user/ajax_add_question') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#result-create-question-form").prop('class', 'alert alert-success');
                    $("#result-create-question-form").text(res.text);
                    drawQuestionsList();
                } else {
                    $("#result-create-question-form").prop('class', 'alert alert-danger');
                    $("#result-create-question-form").text(res.text);
                }
                setTimeout(() => {
                    $("#result-create-question-form").prop('class', '');
                    $("#result-create-question-form").text('');
                    thisForm.trigger('reset');
                }, 5000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        const getAQuestion = questionID => {
            console.log('Loading question');
            return $.post({
                url: '<?= site_url('data_service/ajax_get_question') ?>',
                data: {
                    questionID: questionID
                },
                dataType: 'json'
            }).done(res => console.log('Loading question complete')).fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $(document).on('click', ".edit-question-btn", async function() {
            let questionID = $(this).data('question-id');
            let question = await getAQuestion(questionID);
            $("#question-name-edit-question-form").val(question.Q_NAME);
            $("#question-limit-score-edit-question-form").val(question.LIMIT_SCORE);
            $("#question-order-edit-question-form").val(question.Q_ORDER);
            $("#edit-question-form").data('question-id', questionID);
            $("#edit-question-modal").modal();
        });


        $("#edit-question-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let questionID = thisForm.data('question-id');
            let formData = thisForm.serialize() + `&questionID=${questionID}`;
            console.log('Updating question');
            $.post({
                url: '<?= site_url('controller_user/ajax_edit_question') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                console.log('Updating question complete');
                if (res.status) {
                    $("#result-edit-question-form").prop('class', 'alert alert-success');
                    $("#result-edit-question-form").text(res.text);
                    drawQuestionsList();
                } else {
                    $("#result-edit-question-form").prop('class', 'alert alert-danger');
                    $("#result-edit-question-form").text(res.text);
                }
                setTimeout(() => {
                    $("#result-edit-question-form").prop('class', '');
                    $("#result-edit-question-form").text('');
                    thisForm.trigger('reset');
                }, 5000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        $(document).on('click', ".delete-question-btn", function() {
            if (confirm('ยืนยันการลบคำถามข้อนี้ ?')) {
                let questionID = $(this).data('question-id');
                $.post({
                    url: '<?= site_url('controller_user/ajax_delete_question') ?>',
                    data: {
                        questionID: questionID
                    },
                    dataType: 'json'
                }).done(res => {
                    console.log(res);
                    alert(res.text);
                    drawQuestionsList();
                }).fail((jhr, status, error) => console.error(jhr, status, error));
                return true;
            } else {
                return false;
            }
        });
    });
</script>