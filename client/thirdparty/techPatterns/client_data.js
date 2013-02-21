/*
 Script Name: Your Computer Information
 Author: Harald Hope, Website: http://TechPatterns.com/
 Script Source URI: http://TechPatterns.com/downloads/browser_detection.php
 Version 1.0.4
 Copyright (C) 20 October 2004

 This script is free software; you can redistribute it and/or
 modify it under the terms of the GNU Lesser General Public
 License as published by the Free Software Foundation; either
 version 2.1 of the License, or (at your option) any later version.

 This script is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 Lesser General Public License for more details.

 Lesser GPL license text:
 http://www.gnu.org/licenses/lgpl.txt

 This script requires the Full Featured Browser Detection and the Javascript Cookies scripts
 to function.
 You can download them here.
 http://TechPatterns.com/downloads/browser_detection_php_ar.txt
 http://TechPatterns.com/downloads/javascript_cookies.txt
 */

/*
 If your page is XHMTL 1 strict, you have to
 put this code into a js library file or your
 page will not validate
 */
function client_data(info) {
    if (info == 'width') {
        width = (screen.width) ? screen.width : '';
        height = (screen.height) ? screen.height : '';
        width_height_html = width + " x " + height + " pixels";
        var text = (width && height) ? width_height_html : '';
        return text;
    }
    else if (info == 'js') {
        return 'JavaScript is enabled.';
    }
    else if (info == 'cookies') {
        expires = '';
        Set_Cookie('cookie_test', 'it_worked', expires, '', '', '');
        if (Get_Cookie('cookie_test')) {
            string = 'Cookies are enabled';
        }
        else {
            string = 'Cookies are disabled';
        }
        return string;
    }
}