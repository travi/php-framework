/*global module*/
module.exports = function (grunt) {
    'use strict';

    grunt.loadNpmTasks('grunt-jslint');

    // Project configuration.
    grunt.initConfig({
        lint: {
            all: ['grunt.js', 'js/**/*.js']
        },

        jshint: {
            options: {
                browser: true
            },
            globals: {
                $: true,
                Modernizr: true
            }
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