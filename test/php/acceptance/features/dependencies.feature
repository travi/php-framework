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
            | js                                                          | css | templates                                                          |
            | /resources/min/thirdparty/jquery/jquery.js                  |     | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/min/thirdparty/amplify/amplify.core.js           |     |                                                                    |
            | /resources/min/thirdparty/jsrender/jsrender.js              |     |                                                                    |
            | /resources/min/thirdparty/travi-core/dist/travi-core.min.js |     |                                                                    |

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
        And device has a "small" screen size
        And "dialog" defined as a dependency
        When page is rendered
        Then the dependencies lists should contain
            | js                                                         | css                                                                                      | templates                                                          |
            | /resources/thirdparty/jquery/jquery.js                     | http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.min.css | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/thirdparty/amplify/amplify.core.js              |                                                                                          |                                                                    |
            | /resources/thirdparty/jsrender/jsrender.js                 |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-core/dist/travi-core.min.js    |                                                                                          |                                                                    |
            | /resources/thirdparty/jquery-ui/jquery-ui.js               |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-ui/js/form/validationMapper.js |                                                                                          |                                                                    |
            | /resources/thirdparty/travi-ui/dist/dialog.min.js          |                                                                                          |                                                                    |

    Scenario:
        Given environment is "local"
        And device has a "small" screen size
        And "entityList" defined as a dependency
        When page is rendered
        Then the dependencies lists should contain
            | js                                                      | css | templates                                                          |
            | /resources/thirdparty/jquery/jquery.js                  |     | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/thirdparty/amplify/amplify.core.js           |     | /resources/thirdparty/travi-core/templates/entity-item.tmpl        |
            | /resources/thirdparty/jsrender/jsrender.js              |     |                                                                    |
            | /resources/thirdparty/travi-core/dist/travi-core.min.js |     |                                                                    |
            | /resources/thirdparty/jquery-form/jquery.form.js        |     |                                                                    |
            | /resources/thirdparty/travi-ui/js/pagination.js         |     |                                                                    |
            | /resources/thirdparty/travi-ui/js/entityList.js         |     |                                                                    |
