(function () {
    "use strict";

    // use yuicompress (http://developer.yahoo.com/yui/compressor/) to generate openid.min.js

    jQuery(function () {
        jQuery('#openid_system_status').hide();

        jQuery('#openid_status_link').click(function () {
            jQuery('#openid_system_status').toggle();
            return false;
        });
    });

    function stylize_profilelink() {
        jQuery("#commentform a[href$='profile.php']").addClass('openid_link');
    }

    function add_openid_to_comment_form() {
        var html, label, children;

        jQuery('#commentform').addClass('openid');

        html = ' <a id="openid_enabled_link" href="http://openid.net">(OpenID Enabled)</a> ' +
                    '<div id="openid_text">' +
                        'If you have an OpenID, you may fill it in here.  If your OpenID provider provides ' +
                        'a name and email, those values will be used instead of the values here.  ' +
                        '<a href="http://openid.net/what/">Learn more about OpenID</a> or ' +
                        '<a href="http://openid.net/get/">find an OpenID provider</a>.' +
                    '</div> ';

        jQuery('#commentform #url').attr('maxlength', '100');
        label = jQuery('#commentform label[for=url]');
        children = jQuery(':visible', label);

        if (children.length > 0) {
            children.filter(':last').append(html);
        } else if (label.is(':hastext')) {
            label.append(html);
        } else {
            label.append(html);
        }

        // setup action
        jQuery('#openid_text').hide();
        jQuery('#openid_enabled_link').click(function () {
            jQuery('#openid_text').toggle(200);
            return false;
        });
    }
}());
