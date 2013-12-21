module.exports = function (config) {
    config.set({
        files: [
            'node_modules/karma-jstd-adapter/jstd-adapter.js',

            'bower_components/jquery/jquery.js',
            'bower_components/jquery-ui/ui/jquery-ui.js',

            'client/js/plugins/jquery.menubar.js',

            'test/js/resources/bootstrap.js',

            'test/js/plugins/**/*.jstd'
        ],

        browsers: ['PhantomJS']
    });
};