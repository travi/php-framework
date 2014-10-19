Feature: Front-end Dependency Management
  In order to ensure that the proper static files are loaded to the browser
  As a consuming application
  I need the middle-end framework to manage the dependencies carefully

Scenario:
  When page is rendered
  Then the dependencies lists should contain
    | js | css |
    |    |     |