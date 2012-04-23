/*!
 * slide-down navigation menu, primarily intended for mobile
 * Original author: @mtravi
 * Dual licensed under the MIT or GPL Version 2 licenses
 */

(function ($) {
    "use strict";

    // Create the defaults once
    var pluginName = 'slideNav',
        defaults = {
            propertyName: "value"
        };

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;

        this.options = $.extend({}, defaults, options);

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Plugin.prototype.init = function () {
        $(this.element).prepend('Navigation');
    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName,
                    new Plugin(this, options));
            }
        });
    };

}(jQuery));