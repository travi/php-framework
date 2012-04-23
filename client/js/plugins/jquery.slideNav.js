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
            toggleClass: "navToggle"
        };

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;

        this.options = $.extend({}, defaults, options);

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    function toggleMenu(e) {
        e.preventDefault();
        $(e.target).parent().find(' > ul').slideToggle();
    }

    Plugin.prototype.init = function () {
        var $this = $(this.element);

        $this.prepend('<a class="' + this._defaults.toggleClass + '">' +
            'Navigation' +
            '<span class="ui-icon ui-icon-triangle-1-s"/>' +
            '</a>');
        $this.find('.' + this._defaults.toggleClass).on('click', toggleMenu);
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