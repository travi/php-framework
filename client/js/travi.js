var travi = (function () {
    "use strict";

    var loadTemplate = function (templateUrl, templateName) {
        var deferred = $.Deferred();

        $.get(templateUrl, function (template) {
            $.template(templateName, template);
            deferred.resolve();
        });

        return deferred.promise();
    },

    getStyleSheet = function (sheetUrl) {
        var link = document.createElement("link");

        link.setAttribute("rel", "stylesheet");
        link.setAttribute("type", "text/css");
        link.setAttribute("href", sheetUrl);

        document.getElementsByTagName("head")[0].appendChild(link);
    };

    return {
        loadTemplate    : loadTemplate,
        getStyleSheet   : getStyleSheet
    };
}());