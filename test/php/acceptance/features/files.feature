Feature: Read/Write Files
  In order to manipulate files
  As the framework
  I need the ability to create, write, and read files

@wip
Scenario: Read the contents of a file
  Given the file "newFile.js" exists in "/home/travi/sandbox/resources/travi.org/optimized/js/"
  When the framework requests the the contents of the file
  Then the framework should receive the following string: "some text"

