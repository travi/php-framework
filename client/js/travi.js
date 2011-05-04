var travi = (function () {
    "use strict";

    var loadTemplate = function (templateUrl, templateName) {
        var deferred = $.Deferred();

        $.get(templateUrl, function (template) {
            $.template(templateName, template);
            deferred.resolve();
        });

        return deferred.promise();
    };

    return {
        loadTemplate : loadTemplate
    };
}());