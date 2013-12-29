Feature: Form View Object
  In order to simplify form rendering and processing
  As a developer
  I want the form object to abstract as much as possible for me

Scenario:
  Given "Field" with value ""
  When validation is checked
  Then "field" should report validation error "Field is required"

Scenario:
  Given "Field1" with value ""
  And "Field2" with value ""
  When validation is checked
  Then "field1" should report validation error "Field1 is required"
  And "field2" should report validation error "Field2 is required"