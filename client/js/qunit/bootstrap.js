var $testEnvironmentLoader = $.Deferred();

$.when(

    // Testing extensions
    $.getScript('/resources/shared/js/qunit/qunit.js'),
    $.getScript('/resources/shared/js/jquery/plugins/mockjax/jquery.mockjax.js'),
    $.getScript('/resources/shared/js/jquery/plugins/template/jquery.tmpl.js'),
//
//    // ProjectX files
//    $.getScript('js/projectx.js'),

    // DOM ready deferred object
    $.Deferred(function (deferred) {
        $(deferred.resolve);
    })

).done(function () {

    // Trigger any functions bound to this test environment loader
    $testEnvironmentLoader.resolve();

});

function remoteScripts() {
    $.getScript('http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.js');
    $.getScript('http://code.jquery.com/qunit/git/qunit.js');
}