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
                'client/js/**/*.js',
                'test/js/**/*.jstd'
            ],
            directives: {
                browser: true,
                predef: [
                    '$',
                    'jQuery',
                    'Modernizr',
                    'amplify',
                    'travi',

                    'sinon',
                    'assertEquals',
                    'assertSame',
                    'assertNotSame',
                    'assertString',
                    'assertObject',
                    'assertFunction',
                    'assert',
                    'assertFalse',
                    'assertTrue',
                    'expectAsserts'
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