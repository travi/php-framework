Feature: Front-end Dependency Management
    In order to ensure that the proper static files are loaded to the browser
    As a consuming application
    I need the middle-end framework to manage the dependencies carefully

    Scenario:
        Given no dependencies are defined
        When page is rendered
        Then the dependencies lists should contain
            | js | css | templates |
            |    |     |           |

    Scenario:
        Given "framework" defined as a dependency
        When page is rendered
        Then the dependencies lists should contain
            | js                                                      | css | templates                                                          |
            | /resources/min/thirdparty/jquery/jquery.js              |     | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/min/thirdparty/amplify/amplify.core.js       |     |                                                                    |
            | /resources/min/thirdparty/jsrender/jsrender.js          |     |                                                                    |
            | /resources/thirdparty/travi-core/dist/travi-core.min.js |     |                                                                    |

    Scenario:
        Given environment is "local"
        And "framework" defined as a dependency
        When page is rendered
        Then the dependencies lists should contain
            | js                                                      | css | templates                                                          |
            | /resources/thirdparty/jquery/jquery.js                  |     | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/thirdparty/amplify/amplify.core.js           |     |                                                                    |
            | /resources/thirdparty/jsrender/jsrender.js              |     |                                                                    |
            | /resources/thirdparty/travi-core/dist/travi-core.min.js |     |                                                                    |

    Scenario:
        Given environment is "local"
        And "form" defined as a dependency
        When page is rendered
        Then the dependencies lists should contain
            | js                                                                                | css                                                                                      | templates                                                          |
            | /resources/thirdparty/jquery/jquery.js                                            | http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.min.css | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/thirdparty/jquery-ui/jquery-ui.js                                      | /resources/thirdparty/travi-styles/dist/css/form/travi-form.css                          |                                                                    |
            | /resources/thirdparty/jquery-migrate/jquery-migrate.js                            |                                                                                          |                                                                    |
            | /resources/thirdparty/wymeditor/jquery.wymeditor.js                               |                                                                                          |                                                                    |
            | /resources/thirdparty/wymeditor/plugins/fullscreen/jquery.wymeditor.fullscreen.js |                                                                                          |                                                                    |
            | /resources/thirdparty/amplify/amplify.core.js                                     |                                                                                          |                                                                    |
            | /resources/thirdparty/jsrender/jsrender.js                                        |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-core/dist/travi-core.min.js                           |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-ui/dist/form.min.js                                   |                                                                                          |                                                                    |


    Scenario:
        Given environment is "local"
        And device has a "large" screen size
        And "dialog" defined as a dependency
        When page is rendered
        Then the dependencies lists should contain
            | js                                                                                | css                                                                                      | templates                                                          |
            | /resources/thirdparty/jquery/jquery.js                                            | http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.min.css | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/thirdparty/amplify/amplify.core.js                                     | /resources/thirdparty/travi-styles/dist/css/form/travi-form.css                          |                                                                    |
            | /resources/thirdparty/jsrender/jsrender.js                                        | /resources/thirdparty/travi-styles/dist/css/form/travi-form_d.css                        |                                                                    |
            | /resources/thirdparty/travi-core/dist/travi-core.min.js                           |                                                                                          |                                                                    |
            | /resources/thirdparty/jquery-ui/jquery-ui.js                                      |                                                                                          |                                                                    |
            | /resources/thirdparty/jquery-migrate/jquery-migrate.js                            |                                                                                          |                                                                    |
            | /resources/thirdparty/wymeditor/jquery.wymeditor.js                               |                                                                                          |                                                                    |
            | /resources/thirdparty/wymeditor/plugins/fullscreen/jquery.wymeditor.fullscreen.js |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-ui/dist/form.min.js                                   |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-ui/dist/dialog.min.js                                 |                                                                                          |                                                                    |

    Scenario:
        Given environment is "local"
        And device has a "small" screen size
        And "entityList" defined as a dependency
        When page is rendered
        Then the dependencies lists should contain
            | js                                                         | css                                                                       | templates                                                          |
            | /resources/thirdparty/jquery/jquery.js                     | /resources/thirdparty/travi-styles/dist/css/entities/travi-entities.css   | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/thirdparty/amplify/amplify.core.js              | /resources/thirdparty/travi-styles/dist/css/entities/travi-entities_m.css | /resources/thirdparty/travi-core/templates/entity-item.tmpl        |
            | /resources/thirdparty/jsrender/jsrender.js                 |                                                                           |                                                                    |
            | /resources/thirdparty/travi-core/dist/travi-core.min.js    |                                                                           |                                                                    |
            | /resources/thirdparty/jquery-form/jquery.form.js           |                                                                           |                                                                    |
            | /resources/thirdparty/travi-ui/js/pagination.js            |                                                                           |                                                                    |
            | /resources/thirdparty/travi-ui/js/entityList/pagination.js |                                                                           |                                                                    |

    Scenario:
        Given environment is "local"
        And device has a "large" screen size
        And "entityList" defined as a dependency
        When page is rendered
        Then the dependencies lists should contain
            | js                                                                                | css                                                                                      | templates                                                          |
            | /resources/thirdparty/jquery/jquery.js                                            | http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.min.css | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/thirdparty/amplify/amplify.core.js                                     | /resources/thirdparty/travi-styles/dist/css/form/travi-form.css                          | /resources/thirdparty/travi-core/templates/entity-item.tmpl        |
            | /resources/thirdparty/jsrender/jsrender.js                                        | /resources/thirdparty/travi-styles/dist/css/form/travi-form_d.css                        |                                                                    |
            | /resources/thirdparty/travi-core/dist/travi-core.min.js                           | /resources/thirdparty/travi-styles/dist/css/entities/travi-entities.css                  |                                                                    |
            | /resources/thirdparty/jquery-form/jquery.form.js                                  | /resources/thirdparty/travi-styles/dist/css/entities/travi-entities_d.css                |                                                                    |
            | /resources/thirdparty/travi-ui/js/pagination.js                                   |                                                                                          |                                                                    |
            | /resources/thirdparty/jquery-ui/jquery-ui.js                                      |                                                                                          |                                                                    |
            | /resources/thirdparty/jquery-migrate/jquery-migrate.js                            |                                                                                          |                                                                    |
            | /resources/thirdparty/wymeditor/jquery.wymeditor.js                               |                                                                                          |                                                                    |
            | /resources/thirdparty/wymeditor/plugins/fullscreen/jquery.wymeditor.fullscreen.js |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-ui/dist/form.min.js                                   |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-ui/dist/dialog.min.js                                 |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-ui/js/entityList/updates.js                           |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-ui/js/entityList/pagination.js                        |                                                                                          |                                                                    |

    Scenario: critical-js on initial site visit
        Given environment is "local"
        When page is rendered
        Then the critical list should contain
            | js                                                          |
            | /resources/thirdparty/travi-core/thirdparty/modernizr.js    |
            | /resources/thirdparty/travi-core/dist/travi-critical.min.js |

    Scenario: critical-js after initial page
        Given environment is "local"
        And device has a "large" screen size
        When page is rendered
        Then the critical list should contain
            | js                                                       |
            | /resources/thirdparty/travi-core/thirdparty/modernizr.js |

    Scenario: critical-js on production
        Given environment is "production"
        When page is rendered
        Then the critical list should contain
            | js                                                           |
            | /resources/min/thirdparty/travi-core/thirdparty/modernizr.js |
            | /resources/thirdparty/travi-core/dist/travi-critical.min.js  |

    Scenario: Component and uiDependency should not duplicate dependencies
        Given environment is "local"
        And device has a "large" screen size
        And "Gallery" included as a component
        And "videoPlayer" defined as a dependency
        When page is rendered
        Then the dependencies lists should contain
            | js                                                                      | css | templates                                                          |
            | https://cdn.sublimevideo.net/js/ws9xvgbm.js                             |     | /resources/templates/videoStage.tmpl                               |
            | /resources/js/video/player.js                                           |     | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/thirdparty/jquery/jquery.js                                  |     | /resources/templates/thumbnail.tmpl                                |
            | /resources/thirdparty/amplify/amplify.core.js                           |     | /resources/templates/previewPane.tmpl                              |
            | /resources/thirdparty/jsrender/jsrender.js                              |     |                                                                    |
            | /resources/thirdparty/travi-core/dist/travi-core.min.js                 |     |                                                                    |
            | /resources/js/photos/thumbnails.js                                      |     |                                                                    |
            | /resources/js/photos/carousel.js                                        |     |                                                                    |
            | /resources/shared/thirdparty/jquery/plugins/lightbox/jquery.lightbox.js |     |                                                                    |
            | /resources/js/photos/previewPane.js                                     |     |                                                                    |
            | /resources/shared/thirdparty/reflection/reflection.js                   |     |                                                                    |
            | /resources/js/photos/gallery.js                                         |     |                                                                    |

    Scenario: Component and file should not duplicate dependencies
        Given environment is "local"
        And device has a "large" screen size
        And "Gallery" included as a component
        And "gallery" defined as a dependency
        When page is rendered
        Then the dependencies lists should contain
            | js                                                                      | css | templates                                                          |
            | /resources/thirdparty/jquery/jquery.js                                  |     | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/thirdparty/amplify/amplify.core.js                           |     | /resources/templates/thumbnail.tmpl                                |
            | /resources/thirdparty/jsrender/jsrender.js                              |     | /resources/templates/videoStage.tmpl                               |
            | /resources/thirdparty/travi-core/dist/travi-core.min.js                 |     | /resources/templates/previewPane.tmpl                              |
            | /resources/js/photos/thumbnails.js                                      |     |                                                                    |
            | /resources/js/photos/carousel.js                                        |     |                                                                    |
            | https://cdn.sublimevideo.net/js/ws9xvgbm.js                             |     |                                                                    |
            | /resources/js/video/player.js                                           |     |                                                                    |
            | /resources/shared/thirdparty/jquery/plugins/lightbox/jquery.lightbox.js |     |                                                                    |
            | /resources/js/photos/previewPane.js                                     |     |                                                                    |
            | /resources/shared/thirdparty/reflection/reflection.js                   |     |                                                                    |
            | /resources/js/photos/gallery.js                                         |     |                                                                    |

    @wip
    Scenario: Sub-component should not be included separately when bundled with other component
        Given environment is "local"
        And "Form" included as a component
        And "datePicker" defined as a dependency
        When page is rendered
        Then the dependencies lists should contain
            | js                                                      | css                                                                                      | templates                                                          |
            | /resources/thirdparty/jquery/jquery.js                  | http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.min.css | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/thirdparty/jquery-ui/jquery-ui.js            | /resources/thirdparty/travi-styles/dist/css/form/travi-form.css                          |                                                                    |
            | /resources/thirdparty/amplify/amplify.core.js           |                                                                                          |                                                                    |
            | /resources/thirdparty/jsrender/jsrender.js              |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-core/dist/travi-core.min.js |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-ui/js/form/date.js          |                                                                                          |                                                                    |

    @wip
    Scenario: Sub-component should not be included separately when bundled with other component
        Given environment is "local"
        And "datePicker" defined as a dependency
        And "Form" included as a component
        When page is rendered
        Then the dependencies lists should contain
            | js                                                      | css                                                                                      | templates                                                          |
            | /resources/thirdparty/jquery/jquery.js                  | http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.min.css | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/thirdparty/jquery-ui/jquery-ui.js            | /resources/thirdparty/travi-styles/dist/css/form/travi-form.css                          |                                                                    |
            | /resources/thirdparty/amplify/amplify.core.js           |                                                                                          |                                                                    |
            | /resources/thirdparty/jsrender/jsrender.js              |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-core/dist/travi-core.min.js |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-ui/js/form/date.js          |                                                                                          |                                                                    |


    Scenario: Form component only needs a style sheet
        Given environment is "local"
        And "Form" included as a component
        When page is rendered
        Then the dependencies lists should contain
            | js | css                                                             | templates |
            |    | /resources/thirdparty/travi-styles/dist/css/form/travi-form.css |           |

    Scenario: Form with validations defined includes validation dependencies
        Given environment is "local"
        And "Form" included as a component
        And a field is required
        When page is rendered
        Then the dependencies lists should contain
            | js                                                          | css                                                                                      | templates                                                          |
            | /resources/thirdparty/jquery/jquery.js                      | http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.min.css | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/thirdparty/amplify/amplify.core.js               | /resources/thirdparty/travi-styles/dist/css/form/travi-form.css                          |                                                                    |
            | /resources/thirdparty/jsrender/jsrender.js                  |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-core/dist/travi-core.min.js     |                                                                                          |                                                                    |
            | /resources/thirdparty/jquery.validation/jquery.validate.js  |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-ui/js/form/validation/active.js |                                                                                          |                                                                    |

    Scenario: Email field should include validation dependencies
        Given environment is "local"
        And "Form" included as a component
        And a "Email" field is included
        When page is rendered
        Then the dependencies lists should contain
            | js                                                          | css                                                                                      | templates                                                          |
            | /resources/thirdparty/jquery/jquery.js                      | http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.min.css | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/thirdparty/amplify/amplify.core.js               | /resources/thirdparty/travi-styles/dist/css/form/travi-form.css                          |                                                                    |
            | /resources/thirdparty/jsrender/jsrender.js                  |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-core/dist/travi-core.min.js     |                                                                                          |                                                                    |
            | /resources/thirdparty/jquery.validation/jquery.validate.js  |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-ui/js/form/validation/active.js |                                                                                          |                                                                    |
