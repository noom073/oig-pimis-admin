<script>
    $(document).ready(function() {
        $("li#auditor-inspection-section").addClass('menu-open');
        $("a#auditor-inspection-subject").addClass('active');
        $("a#auditor-calendar").addClass('active');


        $("#edit-main-photo-btn").click(function() {
            let teamplanID = $(this).data('team-plan-id');
            $("#edit-main-photo-form").data('team-plan-id', teamplanID);
            $("#edit-main-photo").modal();
            console.log(teamplanID);
        });


        $("#edit-main-photo-form").submit(function(event) {
            event.preventDefault();
            let thisForm = $(this);
            let teamplanID = thisForm.data('team-plan-id');
            let formData = new FormData(this);
            formData.append('teamplanID', teamplanID);
            $.post({
                url: '<?= site_url('auditor/ajax_upload_main_photo') ?>',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false
            }).done(res => {
                // console.log(res);
                location.reload();
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        $(".delete-main-photo").click(function() {
            let photoID = $(this).data('photo-row-id');
            if (confirm('ยืนยันการลบภาพปก ?')) {
                $.post({
                    url: '<?= site_url('auditor/ajax_delete_main_photo') ?>',
                    data: {
                        rowID: photoID
                    },
                    dataType: 'json'
                }).done(res => {
                    // console.log(res);
                    $("#edit-main-photo-form-result").html('Loading...');
                    location.reload();
                }).fail((jhr, status, error) => console.error(jhr, status, error));
                return true;
            } else {
                return false;
            }
        });


        const getGalleryPhoto = (teamPlanID, inspectionOptionID) => {
            return $.post({
                url: '<?= site_url('auditor/ajax_get_gallery_photo') ?>',
                data: {
                    teamPlanID: teamPlanID,
                    inspectionOptionID: inspectionOptionID
                },
                dataType: 'json',
            }).done().fail((jhr, status, error) => console.error(jhr, status, error));
        };


        $(".edit-gallery-photo").click(async function() {
            let teamPlanID = $(this).data('team-plan-id');
            let inspectionOptionID = $(this).data('inspection-option-id');
            let galleryPhotos = await getGalleryPhoto(teamPlanID, inspectionOptionID);
            let html = '';
            let userInspectionType = JSON.parse('<?= json_encode($userInspectionType) ?>');

            $(".gallery-photo-label").html(galleryPhotos.inspectionOption.INSPECTION_NAME);

            if (userInspectionType.includes(galleryPhotos.inspectionOption.INSPECTION_ID)) {
                console.log(galleryPhotos);
                $("#edit-gallery-photo-form").data({
                    'team-plan-id': teamPlanID,
                    'inspection-option-id': inspectionOptionID
                });
                galleryPhotos.photos.forEach(r => {
                    html += `<div class="mb-3">
                                <img src="<?= base_url("assets/filesUpload/") ?>${r.PIC_PATH}" class="rounded mx-auto d-block" height="250" alt="${r.PIC_NAME}">
                                <div class="text-right">
                                    <button class="btn btn-sm btn-danger delete-gallery-photo" data-photo-row-id="${r.ROW_ID}">ลบ</button>
                                </div>
                            </div>`;
                });
                $(".gallery-photos").html(html);
                $("#edit-gallery-photo-modal").modal();
            } else {
                galleryPhotos.photos.forEach(r => {
                    html += `<div class="mb-3">
                                <img src="<?= base_url("assets/filesUpload/") ?>${r.PIC_PATH}" class="rounded mx-auto d-block" height="250" alt="${r.PIC_NAME}">
                            </div>`;
                });
                $(".gallery-photos").html(html);
                $("#view-gallery-photo-modal").modal();
            }
            return;
        });


        $("#edit-gallery-photo-form").submit(function(event) {
            event.preventDefault();
            let teamPlanID = $(this).data('team-plan-id');
            let inspectionOptionID = $(this).data('inspection-option-id');
            let formData = new FormData(this);
            formData.append('teamPlanID', teamPlanID);
            formData.append('inspectionOptionID', inspectionOptionID);
            $.post({
                url: '<?= site_url('auditor/ajax_upload_gallery_photo') ?>',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false
            }).done(res => {
                if (res.status) {
                    alert('Upload สำเร็จ');
                    location.reload();
                } else {
                    alert(res.error);
                }
            }).fail((jhr, status, error) => console.error(jhr, status, error));
        });


        $(document).on('click', ".delete-gallery-photo", function() {
            let thisElement = $(this);
            let photoID = thisElement.data('photo-row-id');
            if (confirm('ยืนยันการลบภาพปก ?')) {
                $.post({
                    url: '<?= site_url('auditor/ajax_delete_gallery_photo') ?>',
                    data: {
                        rowID: photoID
                    },
                    dataType: 'json'
                }).done(res => {
                    $("#edit-gallery-photo-form-result").html('');
                    location.reload();
                }).fail((jhr, status, error) => console.error(jhr, status, error));
                return true;
            } else {
                return false;
            }
        });

    });
</script>