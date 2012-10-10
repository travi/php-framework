(function (travi) {
    "use strict";

    var TEMPLATE_DIRECTORY = '/templates/',
        TEMPLATE_EXT = '.tmpl',
        loadedTemplates = {};

    $.views.helpers({
        toLower: function (string) {
            return string.toLowerCase();
        }
    });

    function loadTemplate(templateName, templateUrl) {
        var deferred = new $.Deferred(),
            promise = deferred.promise();

        loadedTemplates[templateName] = promise;

        $.ajax({
            url: templateUrl,
            type: 'get',
            dataType: 'text',
            success: function (template) {
                $.templates(templateName, template);
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

    function preLoadTemplates(templates, pathToSingleTemplate) {
        if ('string' === typeof templates) {
            return getTemplate(templates, pathToSingleTemplate);
        } else {
            var templateName;

            for (templateName in templates) {
                if (templates.hasOwnProperty(templateName)) {
                    getTemplate(templateName, templates[templateName]);
                }
            }
        }
    }

    function render(data, templateName) {
        return $.render[templateName](data || {});
    }

    function renderTemplate(templateName, data, callback) {
        if (callback) {
            getTemplate(templateName).then(function () {
                callback(render(data, templateName));
            });
        } else {
            return render(data, templateName);
        }
    }

    function init() {
        loadedTemplates = {};
    }

    travi.namespace('templates', {
        get: getTemplate,
        preLoad: preLoadTemplates,
        render: renderTemplate,
        init: init
    });
}(travi));