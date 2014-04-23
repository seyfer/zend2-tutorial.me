$(document).ready(function() {

    /**
     * удаление аттрибута
     */
    $("#regDate").change(registrationController.changeRegDate);

});

var registrationController = (function() {

    var regDate = $("#regDate");

    regDate.datepicker({
//        showButtonPanel: true,
        dateFormat: "yy-mm-dd"
    });

    /**
     * получить новый номер
     * @returns {undefined}
     */
    var changeRegDate = function() {

        var maskId = $("#maskId").html();
        var documentId = $("#documentId").html();
        var regDate = $(this).val();

        var messages = {
            serialGetFail: "Не удалось расчитать номер"
        };

        console.log(regDate);

        $.ajax({
            type: "GET",
            url: "/admin/sed/document/registration/getfeautureserial/",
            data: {maskId: maskId, documentId: documentId, regDate: regDate},
            dataType: "json",
            async: false
        }).done(function(response) {

            console.log("changeRegDate", response);

            if (response != false && response != null) {
//                alert(response);
                $("#serialNumberFeauture").html(response);
            } else {
                alert(messages.serialGetFail);
            }

        });
    };

    /**
     * API
     */
    return {
        changeRegDate: changeRegDate
    };

})();