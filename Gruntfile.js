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
                errorsOnly: true,
                checkstyle: 'config/build/logs/jslint.xml'
            }
        },

        sass: {
            dist: {
                files: [
                    {
                        expand: true,
                        cwd: 'client/scss',
                        src: ['**/*.scss'],
                        dest: 'client/css/',
                        ext: '.css'
                    }
                ]
            }
        },

        bower: {
            dev: {}
        }
    });

    // Default task.
    grunt.registerTask('default', 'jslint');
    grunt.registerTask('compile', ['sass']);

};