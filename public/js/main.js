$(document).ready(function() {

    $(".navbar-toggle").on("click", function(e) {
        $("#menu").toggle();
    });

    if ($(".chosen-select").length > 0)
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

    /*
     * jQuery File Upload Plugin JS Example 8.9.1
     * https://github.com/blueimp/jQuery-File-Upload
     *
     * Copyright 2010, Sebastian Tschan
     * https://blueimp.net
     *
     * Licensed under the MIT license:
     * http://www.opensource.org/licenses/MIT
     */

    /* global $, window */

    $(function() {
        'use strict';

        // Initialize the jQuery File Upload widget:
        $('#fileupload').fileupload({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: '/uploads/processjquery/',
        }).bind('fileuploadstop', function (e, data) {
            console.log(data)
        }).bind('fileuploaddone', function (e, data) {
            console.log(data)
        });

        // Enable iframe cross-domain access via redirect option:
//        $('#fileupload').fileupload(
//                'option',
//                'redirect',
//                window.location.href.replace(
//                        /\/[^\/]*$/,
//                        '/cors/result.html?%s'
//                        )
//                );


        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
//            method: 'POST'
        }).always(function() {
            $(this).removeClass('fileupload-processing');
        }).done(function(result) {
            $(this).fileupload('option', 'done')
                    .call(this, $.Event('done'), {result: result});

            console.log("done", result);
        }).error(function(data) {
            console.log("error", data);
        }).fail(function(data) {
            console.log("fail", data);
        });


    });

});