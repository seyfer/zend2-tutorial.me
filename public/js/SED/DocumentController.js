$(document).ready(function() {

    /**
     * событие выбора документа, на основе которого делать версию
     */
    $("#versionParent").chosen().change(documentController.versionParentChange);

    /**
     * событие выбора сторон
     */
    $("#sides").chosen().change(documentController.sideSelectChangeEvent);

    /**
     * событие смены типа
     */
    $("#types0").change(documentController.typeSelectChangeEvent);

    /**
     * событие удаления файла
     */
    $(".deleteFile").click(documentController.deleteFileClick);

    /**
     * загрузка файла
     */
    $(".downloadFile").click(documentController.downloadFileClick);

    /**
     * диалог просмотра
     */
    $(".documentViewDialog").click(documentController.documentViewDialog);

    //при загрузке
    documentController.loadIfEdit();
});

/**
 * контроллер страницы документ
 * @type Function|_L30.Anonym$18
 */
var documentController = (function() {

    currentTypeRoles = [];
    lastLoadedTypes = [];
    currentTypeElements = [];

    var messages = (function() {
        return {
            emptyRoles: "На выбранный тип не назначены роли",
            deleteFile: "Вы уверены?",
            deleteFileFail: "Ошибка удаления файла"
        };
    })();

    /**
     * перейти на страницу создания версии
     * @param {type} evt
     * @param {type} params
     * @returns {Boolean|undefined}
     */
    var versionParentChange = function(evt, params) {

        var selected = params.selected;

        if (!selected || selected == 0 || selected == null) {
            return;
        }

        window.location.href = "/admin/sed/document/version/" + selected;

        return false;
    };

    /**
     * окно просмотра документа
     * @returns {undefined}
     */
    var documentViewDialog = function() {

        var $link = $(this);
        var url = $link.attr('href');
        var navbar = $(".navbar");

        console.log(navbar);

        var tag = $("<div></div>");
        tag.attr({"style": "z-index:2000"});
        $.ajax({
            url: url,
            success: function(data) {
                tag.html(data).dialog({
                    modal: true,
                    draggable: false,
                    resizable: false,
                    autoOpen: false,
                    title: $link.attr('title'),
                    width: $("body").css("width"),
                    height: parseInt($("body").css("height")) * 0.60,
                    position: ['center', 100]

                }).dialog('open');
            }
        });

//        alert($link.attr('title'));

        return false;
    };

    /**
     * загрузка документа
     * @returns {Boolean}
     */
    var downloadFileClick = function() {
        var fileId = parseInt($(this).attr("id").replace("file", ""));

        console.log("downloadFileClick", fileId);

        window.location.href = "/admin/sed/document/download/" + fileId;

        return false;
    }

    /**
     * удаление документа
     * @returns {Boolean}
     */
    var deleteFileClick = function() {

        if (confirm(messages.deleteFile)) {

            var fileId = parseInt($(this).attr("id").replace("file", ""));

            $.ajax({
                type: "POST",
                url: "/admin/sed/document/deletefile/",
                data: {fileId: fileId},
                dataType: "json",
                async: false
            }).done(function(response) {

                console.log("deleteFileClick", response);

                if (response != false && response != null) {
//                    alert(response);
                    $("#file" + response).parent().remove();
                } else {
                    alert(messages.deleteFileFail);
                }

            });
        }

        return false;
    };

    /**
     * обновление сторон
     * @param {type} evt
     * @param {type} params
     * @returns {undefined}
     */
    var sideSelectChangeEvent = function(evt, params)
    {
        console.log(evt, params);
        if (params.selected) {
            var sideId = params.selected;
            var sideText = $("#sides option[value=" + sideId + "]").text();

            console.log(sideId);

            var div = generateRoleSelect(sideId, sideText);
            $("#sides-inputs").append(div);
        }
        if (params.deselected) {
            var sideId = params.deselected;
            console.log(sideId);

            var idName = '#role_' + sideId;

            $(idName).parent().remove();
            console.log(idName, $(idName));
        }
    };

    /**
     * событие на смену типа
     * @param {type} evt
     * @returns {Boolean}
     */
    var typeSelectChangeEvent = function(evt) {
        console.log("typeSelectChangeEvent", evt, evt.target, $(this));

        lastLoadedTypes = [];
        currentTypeRoles = [];
        currentTypeElements = [];

        var selected = $(this).find("option:selected").val();
        var text = $(this).find("option:selected").text();
        var elementLevel = $(this).data("level");

        //удалить выбранные ниже, если сменился элемент выше
        $("#loaded-type-selects > div").filter(function() {
            return $(this).attr("data-level") > parseInt(elementLevel);
        }).remove();

        console.log("selected", selected, "elementLevel", elementLevel);

        //если выбран, загрузить следующий уровень
        if (selected) {
            loadTypeslevel(selected);
        }

        //если следующий уровень не пустой
        if (!checkLastLoadedTypesEmpty()) {
            var nextLevelSelect = generateTypeSelect(selected, text, elementLevel);
            nextLevelSelect.change(typeSelectChangeEvent);
            $('#loaded-type-selects').append(nextLevelSelect);
        }

        //загрузить роли
        if (selected) {
            loadRolesForType(selected);
        }

        if (!checkCurrentRolesEmpty())
        {
            //поставить ограничение на кол-во сторон
            //по ролям
            setSidesMaxOptions();
        }
        else {
            //если роли не загружены, сбросить селект сторон
            resetSidesSelect();
        }

        if (selected) {
            loadElementsForType(selected);
        }

        if (!checkCurrentElementsEmpty()) {
            $('#elements-inputs').html("");
            var inputs = generateElementInputs();
            $('#elements-inputs').append(inputs);
        }
        else {
            resetElementsDiv();
        }

        console.log("currentTypeRoles", currentTypeRoles);
    };

    /**
     * установить максимум сторон по кол-ву ролей
     * @returns {undefined}
     */
    var setSidesMaxOptions = function() {

        if (checkCurrentRolesEmpty())
        {
            return;
        }

        var maxOptions = currentTypeRoles.length;
        $("#sides").chosen('destroy').chosen({max_selected_options: maxOptions});

        console.log("maxOptions", maxOptions);
    };

    /**
     * проверить загружены роли или нет
     * @returns Boolean
     */
    var checkCurrentRolesEmpty = function() {
        return !currentTypeRoles || currentTypeRoles.length == 0;
    };

    /**
     * загружены ли типы
     * @returns Boolean
     */
    var checkLastLoadedTypesEmpty = function() {
        return !lastLoadedTypes || lastLoadedTypes.length == 0;
    };

    /**
     * элементы типа загружены
     * @returns Boolean
     */
    var checkCurrentElementsEmpty = function() {
        return !currentTypeElements || currentTypeElements.length == 0;
    };

    /**
     * сбросить выбор сторон и ролей
     * @returns {undefined}
     */
    var resetSidesSelect = function() {
        $("#sides").val("").trigger("chosen:updated");
        $("#sides-inputs").html("");
    };

    var resetElementsDiv = function() {
        console.log("resetElementsDiv");
        $('#elements-inputs').html("");
    };

    /**
     * роли для выбранного типа при загрузке
     * @returns {undefined}
     */
    var loadIfEdit = function() {
        console.log("loadIfEdit");

        var lastSelectValue;

        //если определен режим редактирования
        if (typeof docEdit != 'undefined')
        {
            //навесить событие на селекты типов
            var typeSelects = $('#loaded-type-selects > div > select');

            $.each(typeSelects, function(index, select) {
                $(select).parent().attr("data-level", index);
                $(select).change(typeSelectChangeEvent);

                lastSelectValue = $(select).val();

            });
            console.log("lastSelectValue", lastSelectValue);

            //проверить роли по последнему типу
            //вдруг удалены
            loadRolesForType(lastSelectValue);

            setSidesMaxOptions();

//        var selectedSides = $("#sides option:selected");
//        $.each(selectedSides, function(index, value) {
//            var div = generateRoleSelect($(value).val(), $(value).text());
//            $("#sides-inputs").append(div);
//        });
        }
    };

    /**
     * аякс запрос на получение ролей для типа
     * @param {type} typeId
     * @returns {undefined}
     */
    var loadRolesForType = function(typeId) {

        $.ajax({
            type: "POST",
            url: docTypeRolesUrl,
            data: {selected: typeId},
            dataType: "json",
            async: false
        }).done(function(response) {

            console.log("loadRolesForType", response);

            currentTypeRoles = response;

        });

    };

    /**
     *
     * @param {type} typeId
     * @returns {undefined}
     */
    var loadElementsForType = function(typeId) {

        $.ajax({
            type: "POST",
            url: docTypeElementsUrl,
            data: {selected: typeId},
            dataType: "json",
            async: false
        }).done(function(response) {

            console.log("loadElementsForType", response);

            currentTypeElements = response;

        });
    }

    /**
     * загрузка уровня селекта типа по родителю
     * @param {type} parent
     * @returns {unresolved}
     */
    var loadTypeslevel = function(parent) {

        console.log("loadTypeslevel", parent);

        $.ajax({
            type: "POST",
            url: docTypeLevelUrl,
            data: {level: parent},
            dataType: "json",
            async: false
        }).done(function(response) {

            console.log("loadTypeslevel", response);

            if (response && response != null)
            {
                lastLoadedTypes = response;
            }

            return response;
        });
    };

    /**
     * сгенерировать селекс с версткой bootstrap
     * @param {type} sideId
     * @param {type} sideText
     * @returns {jQuery}
     */
    var generateRoleSelect = function(sideId, sideText) {

        console.log(currentTypeRoles);
        //сбросить селект, если нет ролей для сторон
        if (checkCurrentRolesEmpty()) {
            alert(messages.emptyRoles);
            resetSidesSelect();
            return false;
        }

        var selectName = "role[" + sideId + "]";
        var selectId = "role_" + sideId;
        //метка селекта
        var sideLabel = $("<label/>").attr({for : selectName})
                .html("Роль для: " + sideText);
        //селект
        var roleSelect = $("<select/>").attr({name: selectName, id: selectId,
            class: "form-control"});

        //опции
        $.each(currentTypeRoles, function(key, oneRole) {

            console.log(roleSelect, oneRole);

            roleSelect.append($("<option/>", {
                value: oneRole.id,
                text: oneRole.name
            }));

        });

        var div = $('<div/>').attr({class: "form-group"}).append(sideLabel, roleSelect);
        return div;
    };

    /**
     * сгенерировать селект типов следующего уровня
     * @param {type} typeParentId
     * @param {type} typeParentText
     * @param {type} typeParentLevel
     * @returns {Boolean|jQuery}
     */
    var generateTypeSelect = function(typeParentId, typeParentText, typeParentLevel) {

        console.log(lastLoadedTypes);

        //сбросить селект, если нет ролей для сторон
        if (checkLastLoadedTypesEmpty()) {
            return false;
        }

        var p = $("<p/>").attr("class", "type_p");

        var selectName = "type[" + typeParentId + "]";
        var selectId = "types" + typeParentId;
        var selectLevel = typeParentLevel + 1;

        //метка селекта
        var typeLabel = $("<label/>")
                .attr({for : selectId})
                .html("Подтипы для: " + typeParentText);
        //селект
        var typeSelect = $("<select/>")
                .attr({name: selectName, id: selectId,
                    class: "form-control", 'data-level': selectLevel.toString()});

        //пустая опция
        typeSelect.append($("<option/>", {
            value: "",
            text: "---"
        }));

        //опции
        $.each(lastLoadedTypes, function(key, oneType) {

            console.log(typeSelect, oneType);

            typeSelect.append($("<option/>", {
                value: oneType.id,
                text: oneType.title
            }));

        });

        p.append(typeLabel, typeSelect);
        var div = $('<div/>')
                .attr({class: "form-group", 'data-level': selectLevel.toString()})
                .append(p);

        return div;
    };

    /**
     * генерация элементов
     * @returns {Array}
     */
    var generateElementInputs = function() {

        var inputs = [];
        $.each(currentTypeElements, function(index, element) {

            var div = $('<div/>').attr({class: "form-group documentElement"});
            var label = $('<label/>').attr({for : element.name}).html(element.name);

            var name = "element[" + element.id + "]";
            var input = $('<input/>').attr(
                    {type: element.type, name: name, id: element.name,
                        value: element.defaultValue, class: 'form-control'}
            );

            div.append(label, input);
            inputs.push(div);
        });

        return inputs;
    };

    /**
     * API
     */
    return {
        sideSelectChangeEvent: sideSelectChangeEvent,
        typeSelectChangeEvent: typeSelectChangeEvent,
        loadIfEdit: loadIfEdit,
        deleteFileClick: deleteFileClick,
        downloadFileClick: downloadFileClick,
        documentViewDialog: documentViewDialog,
        versionParentChange: versionParentChange
    };

})();