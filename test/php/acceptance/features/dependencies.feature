Feature: Front-end Dependency Management
    In order to ensure that the proper static files are loaded to the browser
    As a consuming application
    I need the middle-end framework to manage the dependencies carefully

    Scenario:
        Given no dependencies are defined
        When page is rendered
        Then the dependencies lists should contain
            | js | css |
            |    |     |

    Scenario:
        Given "framework" defined as a dependency
        When page is rendered
        Then the dependencies lists should contain
            | js                                                        | css |
            | /resources/min/thirdparty/jquery/jquery.js                |     |
            | /resources/min/thirdparty/amplify/amplify.core.js         |     |
            | /resources/min/thirdparty/jsrender/jsrender.js            |     |
            | /resources/min/thirdparty/travi-core/js/travi-core.min.js |     |

    Scenario:
        Given environment is "local"
        And "framework" defined as a dependency
        When page is rendered
        Then the dependencies lists should contain
            | js                                                    | css | templates                                                          |
            | /resources/thirdparty/jquery/jquery.js                |     | /resources/thirdparty/travi-core/templates/enhancementVersion.tmpl |
            | /resources/thirdparty/amplify/amplify.core.js         |     |                                                                    |
            | /resources/thirdparty/jsrender/jsrender.js            |     |                                                                    |
            | /resources/thirdparty/travi-core/js/travi-core.min.js |     |                                                                    |
