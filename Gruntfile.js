/*global module*/
module.exports = function (grunt) {
    'use strict';

    grunt.loadNpmTasks('grunt-jslint');
    grunt.loadNpmTasks('grunt-bower');
    grunt.loadNpmTasks('grunt-contrib-clean');

    // Project configuration.
    grunt.initConfig({
        lint: {
            all: ['grunt.js', 'js/**/*.js']
        },

        jslint: {
            files: ['grunt.js', 'js/**/*.js'],
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
        }
    });

    // Default task.
    grunt.registerTask('default', 'jslint');

};