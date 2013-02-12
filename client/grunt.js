module.exports = function (grunt) {

    // Project configuration.
    grunt.initConfig({
        lint:{
            all:['grunt.js', 'js/**/*.js']
        },
        jshint: {
            options: {
                browser: true
            },
            globals: {
                $: true,
                Modernizr: true
            }
        }
    });

    // Default task.
    grunt.registerTask('default', 'lint');

};