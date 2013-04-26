var config = module.exports;

config["framework tests"] = {
    extensions: [
        require("buster-jstestdriver")
    ],

    environment: 'browser',

    libs: [
        'test/js/stubs/*.js',

        'client/thirdparty/jquery/jquery.js',
        'client/thirdparty/jquery/plugins/validation/jquery.validate.js',
        'client/thirdparty/jquery/**/*.js'
    ],

    sources: [
        'client/js/travi.js',
        'client/js/travi/templates.js',
        'client/js/travi/location.js',
        'client/js/travi/events.js',
        'client/js/travi/pagination.js',
        'client/js/**/*.js'
    ],

    resources: [
        'client/templates/**/*.tmpl',
        'test/js/templates/**/*.tmpl'
    ],

    testHelpers: [
        'test/js/tools/common.js',
        'test/js/tools/referee-jstestdriver.js',
        'test/js/resources/bootstrap.js'
    ],

    tests: [
        'test/js/**/*.js'
    ]
};
