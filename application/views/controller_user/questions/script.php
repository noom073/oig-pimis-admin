<script>
    $(document).ready(function() {
        $("li#controller-user-headnav-manage-data").addClass('menu-open');
        $("li#controller-user-headnav-manage-data").children("a.nav-link").addClass('active');
        $("a#controller-user-inspection").addClass('active');


        $(".close-window").click(function(event) {
            console.log(event);
            window.close();
        });
    });
</script>