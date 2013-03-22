/*global module*/
module.exports = function (grunt) {
    'use strict';

    grunt.loadNpmTasks('grunt-jslint');
    grunt.loadNpmTasks('grunt-bower');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-copy');

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
                checkstyle: 'build/logs/jslint.xml'
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
        },

        copy: {
            cssImages: {
                files: [
                    {
                        expand: true,
                        cwd: 'client/scss/icons',
                        src: ['*'],
                        dest: 'client/css/icons/'
                    }
                ]
            }
        }
    });

    // Default task.
    grunt.registerTask('default', 'jslint');
    grunt.registerTask('compile', ['sass', 'copy:cssImages']);

};