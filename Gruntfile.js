/*global module*/
module.exports = function (grunt) {
    'use strict';

    grunt.loadNpmTasks('grunt-jslint');
    grunt.loadNpmTasks('grunt-bower');
    grunt.loadNpmTasks('grunt-contrib-clean');

    // Project configuration.
    grunt.initConfig({
        clean: ['client/css'],

        jslint: {
            files: [
                'grunt.js',
                'client/js/**/*.js'
            ],
            directives: {
                browser: true,
                predef: [
                    '$',
                    'jQuery',
                    'Modernizr',
                    'travi'
                ]
            },
            options: {
                errorsOnly: true
            }
        },

        bower: {
            dev: {}
        }
    });

    // Default task.
    grunt.registerTask('default', 'jslint');

};