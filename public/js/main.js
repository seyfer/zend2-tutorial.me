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

    $(function() {
        $("a.ui-button").button();
    });

//    console.log($("a.ui-button").button());
//
//    $(function() {
//        $("input[type=submit], a, button")
//                .button()
//                .click(function(event) {
//                    event.preventDefault();
//                });
//    });

});