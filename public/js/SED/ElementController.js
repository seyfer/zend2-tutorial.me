$(document).ready(function() {

    /**
     * если редактирование
     */
    elementController.load();

    /**
     * событие добавления аттрибута
     */
    $("#attribute_add").click(elementController.attributesAddEvent);

    /**
     * удаление аттрибута
     */
    $(".delAttr").click(elementController.delAttrClick);

});

var elementController = (function() {

    currentElementAttributeNum = 0;

    /**
     * удалить аттрибут
     * @returns {Boolean}
     */
    var delAttrClick = function() {

        $(this).parent().remove();
        return false;
    };

    /**
     * при загрузке для редактирования
     * @returns {undefined}
     */
    var load = function() {

        var attributes = $("#attributes > div").toArray();
//    console.log(attributes);

        if (attributes.length > 0)
        {
            var last = attributes.pop();
//        console.log(last.id);

            currentElementAttributeNum = parseInt(last.id.replace("attributes", ""));
            currentElementAttributeNum++;
        }
    };

    /**
     * добавление атррибутов на форму
     * @param {type} e
     * @returns {Boolean}
     */
    var attributesAddEvent = function(e) {

        e.preventDefault();

        var newAttrNameName = "attributes[" + currentElementAttributeNum + "][name]";
        var newAttrNameId = "attributes" + currentElementAttributeNum;

        var div = $("<div/>").attr({id: newAttrNameId,
            class: "form-group"});

        var nameLabel = $("<label/>")
                .attr({for : newAttrNameId})
                .html("Имя ");

        var name = $("<input/>")
                .attr({type: "text", name: newAttrNameName, id: newAttrNameId,
                    class: "form-control"});

        var newAttrValueName = "attributes[" + currentElementAttributeNum + "][value]";
        var newAttrValueId = "attributes" + currentElementAttributeNum;

        var valueLabel = $("<label/>")
                .attr({for : newAttrValueId})
                .html("Значение ");

        var value = $("<input/>")
                .attr({type: "text", name: newAttrValueName, id: newAttrValueId,
                    class: "form-control"});

        var delLink = $("<a/>").attr({href: "", class: "delAttr"}).html('удалить');
        delLink.click(delAttrClick);

        div.append(nameLabel, name, valueLabel, value, delLink, "<hr>");

        $("#attributes").append(div);

        currentElementAttributeNum++;

        return false;
    };

    return {
        load: load,
        attributesAddEvent: attributesAddEvent,
        delAttrClick: delAttrClick,
    };

})();