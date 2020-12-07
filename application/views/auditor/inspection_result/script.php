<script>
    $(document).ready(function() {
        $("li#auditor-inspection-section").addClass('menu-open');
        $("a#auditor-inspection-subject").addClass('active');
        $("a#auditor-calendar").addClass('active');

        
        alert('! อยู่ระหว่างการพัฒนา');

        $("#add-note-btn").click(function() {
            $("#create-note-modal").modal();
        });

        const getInspections = () => {
            console.log('Loading inspections...');
            return $.get({
                url: '<?= site_url('data_service/ajax_get_inspection') ?>',
                dataType: 'json'
            }).done(res => {
                console.log('Loading inspections complete');
            }).fail((jhr, status, error) => console.error((jhr, status, error)));
        };

        const drawinspections = async () => {
            let inspections = await getInspections();
            let option = '';
            inspections.forEach(r => {
                option +=`<option value="${r.INSPE_ID}">${r.INSPE_NAME}</option>`;
            });
            $("#create-note-form-inspections").html(option);
        };
        drawinspections();
    });
</script>