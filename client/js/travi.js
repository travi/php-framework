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
        },

        namespace = function (ns, provided) {
            var object = this,
                levels = ns.split("."),
                levelCount = levels.length,
                i;

            for (i = 0; i < levelCount; i += 1) {
                if (typeof object[levels[i]] === "undefined") {
                    if (i === levelCount - 1) {
                        object[levels[i]] = provided || {};
                    } else {
                        object[levels[i]] = {};
                    }
                }

                object = object[levels[i]];
            }

            return object;
        },

        constants = (function () {
            var ownProp = Object.prototype.hasOwnProperty,
                constants = {};

            function set(key, value) {
                if (!ownProp.call(constants, key)) {
                    constants[key] = value;
                }
            }

            function get(key) {
                return constants[key];
            }

            return {
                set: set,
                get: get
            };
        }());

    return {
        loadTemplate    : loadTemplate,
        getStyleSheet   : getStyleSheet,
        namespace       : namespace,
        constants       : constants,
        enableConstants : constants
    };
}());