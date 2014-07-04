Feature: Trigger OR Event
  In order to allow tenant specific actions to be perform

  Background:
    Given the database is clean
    And there are the following "VivaBravoBundle:Customer":
      | email                                | forename | surname   |
      | john.smith@tests.vivait.co.uk        | John     | Smith     |
      | john.wright@tests.vivait.co.uk       | John     | Wright    |
      | fabian.poteincier@tests.vivait.co.uk | Fabien   | Potencier |
    And there are the following "VivaitInspectorBundle:Inspection":
      | eventName       | name       | voterType |
      | customer.update | Behat test | 2         |
    And there are the following "VivaitInspectorBundle:Condition\Expression":
      | expression                         | inspection       |
      | "customer.getForename() == 'John'" | @customer.update |
      | "customer.getSurname() == 'Smith'" | @customer.update |
    And there are the following "VivaitInspectorBundle:Action\SendEmail":
      | recipient             | sender             | message | inspection       |
      | lewiswright@gmail.com | lewis@vivait.co.uk | Test 2  | @customer.update |

  @mink:symfony2 @email
  Scenario: Trigger an 'Or' event when all conditions match
    Given
    When I trigger a 'customer.update' event on customer "John Smith"
    Then I should get an email on "lewiswright@gmail.com" with:
    """
    Test 2
    """

  @mink:symfony2 @email
  Scenario: Don't trigger an 'Or' event when no conditions match
    When I trigger a 'customer.update' event on customer "Fabien Potencier"
    Then I should not get an email on "lewiswright@gmail.com"

  @mink:symfony2 @email
  Scenario: Trigger an 'Or' event when not all conditions match
    When I trigger a 'customer.update' event on customer "John Wright"
    Then I should get an email on "lewiswright@gmail.com" with:
    """
    Test 2
    """