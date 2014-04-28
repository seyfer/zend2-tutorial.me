$(document).ready(function() {

    $(".navbar-toggle").on("click", function(e) {
        $("#menu").toggle();
    });

    $(".chosen-select").chosen();

    $(".btn-danger").click(function() {
        if (!confirm("Вы уверены ???")) {
            return false;
        }
    });

});