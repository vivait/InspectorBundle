Feature: Trigger Event
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
      | customer.update | Behat test | and       |
    And there are the following "VivaitInspectorBundle:Condition\Expression":
      | expression                         | inspection       |
      | "customer.getForename() == 'John'" | @customer.update |
      | "customer.getSurname() == 'Smith'" | @customer.update |
    And there are the following "VivaitInspectorBundle:Action\SendEmail":
      | recipient          | sender             | message | inspection       |
      | bravo@vivait.co.uk | lewis@vivait.co.uk | Test 1  | @customer.update |

  @mink:symfony2 @email
  Scenario: Trigger an 'And' event when all conditions match
    Given
    When I trigger a 'customer.update' event on customer "John Smith"
    Then I should get an email on "bravo@vivait.co.uk" with:
    """
    Test 1
    """

  @mink:symfony2 @email
  Scenario: Don't trigger an 'And' event when no conditions match
    When I trigger a 'customer.update' event on customer "Fabien Potencier"
    Then I should not get an email on "bravo@vivait.co.uk"

  @mink:symfony2 @email
  Scenario: Don't trigger an 'And' event when not all conditions match
    When I trigger a 'customer.update' event on customer "John Wright"
    Then I should not get an email on "bravo@vivait.co.uk"