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
            | /resources/thirdparty/jquery.validation/jquery.validate.js                        |                                                                                          |                                                                    |
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
            | /resources/thirdparty/jquery.validation/jquery.validate.js                        |                                                                                          |                                                                    |
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
            | /resources/thirdparty/jquery.validation/jquery.validate.js                        |                                                                                          |                                                                    |
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
