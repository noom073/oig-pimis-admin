<script>
    $(document).ready(function() {
        $("li#controller-user-headnav-manage-data").addClass('menu-open');
        $("li#controller-user-headnav-manage-data").children("a.nav-link").addClass('active');
        $("a#controller-user-inspection-option").addClass('active');


        let inspections = new Array();
        const putInspectionToSelect = () => { // PUT INSPECTIONS TO #inspection-list
            console.log('Loading inspections');
            $.get({
                url: '<?= site_url('data_service/ajax_get_inspection') ?>',
                dataType: 'json'
            }).done(res => {
                inspections = res;
                let option = '<option value="">โปรดระบุ</option>';
                res.forEach(r => {
                    option += `<option value="${r.INSPE_ID}">${r.INSPE_NAME}</option>`;
                });
                $('#inspection-list').html(option);
                console.log('Loading inspections complete');

            }).fail((jhr, status, error) => console.error(jhr, status, error));
        };
        putInspectionToSelect();


        const convertSubjectToTree = (subjects, parentID = '0') => {
            let items = subjects.filter(r => r.SUBJECT_PARENT_ID === parentID)
                .sort((a, b) => a.SUBJECT_ORDER - b.SUBJECT_ORDER)
                .map(item => {
                    let child = convertSubjectToTree(subjects, item.SUBJECT_ID);
                    if (child.length) {
                        item.child = child;
                    }
                    return item;
                });
            return items;
        };


        const treeView = (array, display = 'show', rowNumber = '') => {
            let classDisplay = display === 'show' ? 'd-block' : 'd-none child';
            let html = `<ul class="${classDisplay}">`;
            array.forEach((r, index) => {
                let disableQuestionBtn = `<button title="ไม่มีข้อคำถาม" class="btn btn-sm btn-secondary " disabled>ข้อคำถาม</button>`;
                let listQuestionBtn = `<a href="<?= site_url('controller_user/questions') ?>?subject_id=${r.SUBJECT_ID}" target="_blank" class="btn btn-sm btn-primary">ข้อคำถาม</a>`;
                let createBtn = `<button title="เพิ่มหัวข้อการตรวจ" class="btn btn-sm btn-outline-primary create-btn" data-subject-id="${r.SUBJECT_ID}" data-subject-level="${r.SUBJECT_LEVEL}">
                                <i class="fas fa-plus-circle"></i>
                            </button>`;
                let editBtn = `<button class="btn btn-sm btn-primary edit-btn" data-subject-id="${r.SUBJECT_ID}">แก้ไข</button>`;
                let deleteBtn = `<button class="btn btn-sm btn-danger delete-btn" data-subject-id="${r.SUBJECT_ID}">ลบ</button>`;
                let num = rowNumber === '' ? (index + 1) : rowNumber + '.' + (index + 1);
                if (r.child) {
                    html += `<li class="list-subject d-block my-1 mx-0 px-0 py-1">
                                <div class="d-flex">
                                    <span class="has-child">
                                        <i class="caret fas fa-angle-right text-primary"></i>
                                        ${num}. ${r.SUBJECT_NAME}
                                    </span>                             
                                    <div class="ml-auto">${createBtn} ${listQuestionBtn} ${editBtn} ${deleteBtn}</div>
                                </div>
                                ${treeView(r.child, 'none', num)}                            
                            </li>`;
                } else {
                    html += `<li class="list-subject d-block my-1 mx-0 px-0 py-1">
                                <div class="d-flex">
                                    <span>${num}. ${r.SUBJECT_NAME}</span>  
                                    <div class="ml-auto">${createBtn} ${listQuestionBtn} ${editBtn} ${deleteBtn}</div>
                                </div>
                            </li>`;
                }
            });
            html += `</ul>`;
            return html;
        };


        const getSubjects = inspectionID => {
            console.log('Loading Subjects');
            return $.post({
                url: '<?= site_url('data_service/ajax_get_subjects') ?>',
                data: {
                    inspectionID: inspectionID
                },
                dataType: 'json'
            }).done(res => console.log('Loading Subjects complete')).fail((jhr, status, error) => console.error(jhr, status, error));
        };


        let subjects = new Array();
        const drawSubjectList = async inspectionID => {
            subjects = await getSubjects(inspectionID);
            let treeArray = convertSubjectToTree(subjects);
            let html = treeView(treeArray);
            $("#tree").html(html);
        };


        $("#inspection-list").change(function() {
            let inspectionValue = $(this).val();
            drawSubjectList(inspectionValue);
            if (inspectionValue != '') {
                $(".subject-activity-btn").removeClass('invisible');
            } else {
                $(".subject-activity-btn").addClass('invisible');
            }
        });


        $(document).on('click', ".has-child", function() {
            let isUlCollapse = $(this).parent().siblings('ul').hasClass('d-none');
            if (isUlCollapse) {
                $(this).parent().siblings('ul.child').removeClass('d-none');
                $(this).children(".caret").removeClass("fa-angle-right text-primary");
                $(this).children(".caret").addClass("fa-angle-down text-success");
            } else {
                $(this).parent().siblings('ul.child').addClass('d-none');
                $(this).children(".caret").removeClass("fa-angle-down text-success");
                $(this).children(".caret").addClass("fa-angle-right text-primary");
            }
        });


        $("#add-subject").click(() => $("#create-subject-modal").modal());


        $("#create-subject-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let inspectionID = $("#inspection-list").val();
            let formData = thisForm.serialize() + `&inspectionID=${inspectionID}`;
            console.log(formData);
            $.post({
                url: '<?= site_url('controller_user/ajax_add_subject') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                if (res.status) {
                    $("#result-create-subject-form").prop('class', 'alert alert-success');
                    $("#result-create-subject-form").text(res.text);
                } else {
                    $("#result-create-subject-form").prop('class', 'alert alert-danger');
                    $("#result-create-subject-form").text(res.text);
                }
                let inspectionID = $("#inspection-list").val();
                drawSubjectList(inspectionID);
                setTimeout(() => {
                    $("#result-create-subject-form").prop('class', '');
                    $("#result-create-subject-form").text('');
                    thisForm.trigger('reset');
                }, 5000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        const getSubjectOne = subjectID => {
            console.log('Loading SubjectOne');
            return $.post({
                url: '<?= site_url('data_service/ajax_get_a_subject') ?>',
                data: {
                    subjectID: subjectID
                },
                dataType: 'json'
            }).done(res => console.log('Loading SubjectOne complete')).fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $(document).on('click', ".edit-btn", async function() {
            let subjectID = $(this).data('subject-id');
            let inspectionID = $("#inspection-list").val();
            let subjectDetail = await getSubjectOne(subjectID);

            let subjectParentOption = '<option value="0">ไม่ระบุ</option>';
            subjects.filter(r => r.SUBJECT_ID != subjectID)
                .sort((a, b) => a.SUBJECT_ORDER - b.SUBJECT_ORDER)
                .forEach(r => {
                    subjectParentOption += `<option value="${r.SUBJECT_ID}" ${subjectDetail.SUBJECT_PARENT_ID == r.SUBJECT_ID ? 'selected':''}>${r.SUBJECT_NAME}</option>`;
                });

            let inspection = inspections.filter(r => r.INSPE_ID == inspectionID);

            let inspectionParentOption = '';
            inspection.forEach(r => {
                inspectionParentOption = `<option value="${r.INSPE_ID}" selected>${r.INSPE_NAME}</option>`;
            });
            $("#edit-subject-form").data('subject-id', subjectID);
            $("#subject-name-edit-subject-form").val(subjectDetail.SUBJECT_NAME);
            $("#subject-parent-edit-subject-form").html(subjectParentOption);
            $("#inspection-id-edit-subject-form").html(inspectionParentOption);
            $("#subject-order-edit-subject-form").val(subjectDetail.SUBJECT_ORDER);
            $("#edit-subject-modal").modal();
        });


        $("#edit-subject-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let subjectID = thisForm.data('subject-id');
            let formData = thisForm.serialize() + `&subjectId=${subjectID}`;
            $.post({
                url: '<?= site_url('controller_user/ajax_update_subject') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                console.log(res);
                if (res.status) {
                    $("#result-edit-subject-form").prop('class', 'alert alert-success');
                    $("#result-edit-subject-form").text(res.text);
                } else {
                    $("#result-edit-subject-form").prop('class', 'alert alert-danger');
                    $("#result-edit-subject-form").text(res.text);
                }
                let inspectionID = $("#inspection-list").val();
                drawSubjectList(inspectionID);
                setTimeout(() => {
                    $("#result-edit-subject-form").prop('class', '');
                    $("#result-edit-subject-form").text('');
                }, 5000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        $("#collapse-subject-ul").click(() => {
            let caret = $(".child").parent('li.list-subject').children('div.d-flex').children('.has-child').children('.caret');
            $(".child").removeClass('d-none');
            caret.removeClass("fa-angle-right text-primary");
            caret.addClass("fa-angle-down text-success");
        });


        $("#show-subject-ul").click(() => {
            let caret = $(".child").parent('li.list-subject').children('div.d-flex').children('.has-child').children('.caret');
            $(".child").addClass('d-none');
            caret.removeClass("fa-angle-down text-success");
            caret.addClass("fa-angle-right text-primary");
        });


        $(document).on('click', ".create-btn", function() {
            let parentID = $(this).data('subject-id');
            let parentLevelID = $(this).data('subject-level');
            $("#create-sub-subject-form").data('parent-level-id', parentLevelID);

            let parentSubject = subjects.filter(r => r.SUBJECT_ID == parentID);
            let inspection = inspections.filter(r => r.INSPE_ID == $("#inspection-list").val());

            let parentSubjectOption = '';
            parentSubject.forEach(r => parentSubjectOption = `<option value="${r.SUBJECT_ID}">${r.SUBJECT_NAME}</option>`);
            $("#subject-parent-create-sub-subject-form").html(parentSubjectOption);

            let inspectionOption = '';
            inspection.forEach(r => inspectionOption = `<option value="${r.INSPE_ID}">${r.INSPE_NAME}</option>`);
            $("#inspection-create-sub-subject-form").html(inspectionOption);

            $("#add-sub-subject-modal").modal();
        });


        $("#create-sub-subject-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let subjectLevel = $(this).data('parent-level-id') + 1;
            let formData = thisForm.serialize() + `&subjectLevel=${subjectLevel}`;

            $.post({
                url: '<?= site_url('controller_user/ajax_add_sub_subject') ?>',
                data: formData,
                dataType: 'json'
            }).done(res => {
                console.log(res);
                if (res.status) {
                    $("#result-create-sub-subject-form").prop('class', 'alert alert-success');
                    $("#result-create-sub-subject-form").text(res.text);
                } else {
                    $("#result-create-sub-subject-form").prop('class', 'alert alert-danger');
                    $("#result-create-sub-subject-form").text(res.text);
                }
                let inspectionID = $("#inspection-list").val();
                drawSubjectList(inspectionID);
                setTimeout(() => {
                    $("#result-create-sub-subject-form").prop('class', '');
                    $("#result-create-sub-subject-form").text('');
                }, 5000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
            thisForm.trigger('reset');
        });


        $(document).on('click', ".delete-btn", function() {
            if (confirm('ยืนยันการลบหัวข้อ !')) {
                let subjectID = $(this).data('subject-id');
                console.log(subjectID);

                $.post({
                    url: '<?= site_url('controller_user/ajax_delete_subject') ?>',
                    data: {
                        subjectID: subjectID
                    },
                    dataType: 'json'
                }).done(res => {
                    console.log(res);
                    alert(res.text);
                    let inspectionID = $("#inspection-list").val();
                    drawSubjectList(inspectionID);
                }).fail((jhr, status, error) => console.error(jhr, status, error));
                return true;
            } else {
                return false;
            }
        });


        $("#create-inspection").click(function(){
            $("#create-inspection-modal").modal();
        });


        $("#create-inspection-form").submit(function(event){
            event.preventDefault();
            let thisForm = $(this);
            let formData = thisForm.serialize();

            $.post({
                url: '<?= site_url('controller_user/add_inspection')?>',
                data: formData,
                dataType: 'json'
            }).done(res=>{
                console.log(res);
                if (res.status) {
                    $("#result-create-inspection-form").prop('class', 'alert alert-success');
                    $("#result-create-inspection-form").text(res.text);
                    putInspectionToSelect();
                } else {
                    $("#result-create-inspection-form").prop('class', 'alert alert-danger');
                    $("#result-create-inspection-form").text(res.text);
                }
                setTimeout(() => {
                    $("#result-create-inspection-form").prop('class', '');
                    $("#result-create-inspection-form").text('');
                    thisForm.trigger('reset');
                }, 5000);
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        })
    });
</script>