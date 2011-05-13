var $testEnvironmentLoader = $.Deferred();

$.when(

    // Testing extensions

    //Local
//    $.getScript('/resources/shared/js/qunit/qunit.js'),
//    $.getScript('/resources/shared/js/jquery/plugins/mockjax/jquery.mockjax.js'),
//    $.getScript('/resources/shared/js/jquery/plugins/template/jquery.tmpl.js'),

    //Remote
    $.getScript('http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.js'),
    $.getScript('http://code.appendto.com/plugins/jquery-mockjax/jquery.mockjax.js'),
    $.getScript('http://code.jquery.com/qunit/git/qunit.js'),

    // DOM ready deferred object
    $.Deferred(function (deferred) {
        $(deferred.resolve);
    })

).done(function () {

    // Trigger any functions bound to this test environment loader
    $testEnvironmentLoader.resolve();

});