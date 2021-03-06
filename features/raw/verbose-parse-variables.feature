Feature: Developer runs patch:up command in verbose mode
  As a Developer
  I want to list what actions would have been executed
  for various configuration files.

  Scenario: Parsing just one variable
    Given the file "stamp.yml" contains:
      """
      actions:
        -
          name: parse_variable_from_file
          parameters:
            regex:    '/"version" *: *"(?P<version>[^"]+)"/'
            filename: 'metadata.json'
      """
    And the file "metadata.json" contains:
      """
      {
        "name": "gajdaw-php_phars",
        "version": "50.1021.173",
        "author": "gajdaw"
      }
      """
    When I run command "raw:run" in verbose mode
    Then the output should contain:
      """
      parse_variable_from_file["filename"="metadata.json"]["version"="50.1021.173"]
      """

  Scenario: Parsing variables
    Given the file "stamp.yml" contains:
      """
      actions:
        -
          name: parse_variable_from_file
          parameters:
            regex:    '/"version": "(?P<version>[^"]+)"/'
            filename: 'metadata.json'
        -
          name: parse_variable_from_file
          parameters:
            regex:    '/"(?P<AUTHOR>[^"]+)": "gajdaw"/'
            filename: 'metadata.json'
      """
    And the file "metadata.json" contains:
      """
      {
        "name": "gajdaw-php_phars",
        "version": "50.1021.173",
        "author": "gajdaw"
      }
      """
    When I run command "raw:run" in verbose mode
    Then the output should contain:
      """
      parse_variable_from_file["filename"="metadata.json"]["version"="50.1021.173"]
      parse_variable_from_file["filename"="metadata.json"]["AUTHOR"="author"]
      """
