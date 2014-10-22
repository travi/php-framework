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
            | js                                                                    | css |
            | /resources/min/thirdparty/jquery/jquery.js                            |     |
            | /resources/min/thirdparty/travi-core/js/travi.js                      |     |
            | /resources/min/thirdparty/travi-core/js/travi/dependencies/loader.js  |     |
            | /resources/min/thirdparty/travi-core/js/travi/dependencies/checker.js |     |
            | /resources/min/thirdparty/amplify/amplify.core.js                     |     |
            | /resources/min/thirdparty/travi-core/js/travi/events.js               |     |
            | /resources/min/thirdparty/jsrender/jsrender.js                        |     |
            | /resources/min/thirdparty/travi-core/js/travi/templates.js            |     |
            | /resources/min/thirdparty/travi-core/js/travi/cookies.js              |     |
            | /resources/min/thirdparty/travi-core/js/travi/browserProxy.js         |     |
            | /resources/min/thirdparty/travi-core/js/travi/enhancements.js         |     |
            | /resources/min/thirdparty/travi-core/js/travi/framework.js            |     |

    Scenario:
        Given environment is "local"
        And "framework" defined as a dependency
        When page is rendered
        Then the dependencies lists should contain
            | js                                                                | css |
            | /resources/thirdparty/jquery/jquery.js                            |     |
            | /resources/thirdparty/travi-core/js/travi.js                      |     |
            | /resources/thirdparty/travi-core/js/travi/dependencies/loader.js  |     |
            | /resources/thirdparty/travi-core/js/travi/dependencies/checker.js |     |
            | /resources/thirdparty/amplify/amplify.core.js                     |     |
            | /resources/thirdparty/travi-core/js/travi/events.js               |     |
            | /resources/thirdparty/jsrender/jsrender.js                        |     |
            | /resources/thirdparty/travi-core/js/travi/templates.js            |     |
            | /resources/thirdparty/travi-core/js/travi/cookies.js              |     |
            | /resources/thirdparty/travi-core/js/travi/browserProxy.js         |     |
            | /resources/thirdparty/travi-core/js/travi/enhancements.js         |     |
            | /resources/thirdparty/travi-core/js/travi/framework.js            |     |
