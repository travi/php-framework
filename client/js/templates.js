(function () {
    "use strict";

    var TEMPLATE_DIRECTORY = '/templates/',
        TEMPLATE_EXT = '.tmpl',
        loadedTemplates = {};

    function loadTemplate(templateName, templateUrl) {
        var deferred = new $.Deferred(),
            promise = deferred.promise();

        loadedTemplates[templateName] = promise;

        $.ajax({
            url: templateUrl,
            type: 'get',
            dataType: 'text',
            success: function (template) {
                $.template(templateName, template);
                deferred.resolve();
            }
        });

        return promise;
    }

    function getTemplate(templateName, templateUrl) {
        if (loadedTemplates[templateName]) {
            return loadedTemplates[templateName];
        } else {
            return loadTemplate(
                templateName,
                templateUrl || TEMPLATE_DIRECTORY + templateName + TEMPLATE_EXT
            );
        }
    }

    function renderTemplate(templateName, data) {
        return $.render(data, templateName);
    }

    function init() {
        loadedTemplates = {};
    }

    travi.namespace("templates", {
        get: getTemplate,
        preLoad: getTemplate,
        render: renderTemplate,
        init: init
    });
}());