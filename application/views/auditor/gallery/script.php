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
            console.log(photoID);
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

    });
</script>