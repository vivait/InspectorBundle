Feature: Queue Inspection
  In order to allow a grace before inspections are performed
  The system should allow inspections to be performed at set times

  Background:
    Given the database is clean
    And there are the following "VivaBravoBundle:Customer":
      | email                                | forename | surname   |
      | fabian.poteincier@tests.vivait.co.uk | Fabien   | Potencier |
    And there are the following "VivaitInspectorBundle:Inspection":
      | eventName       | name               | voterType |
      | customer.update | Delayed inspection | and       |
    And there are the following "VivaitInspectorBundle:Condition\Expression":
      | expression                           | inspection       |
      | "customer.getForename() == 'Fabien'" | @customer.update |
    And there are the following "VivaitInspectorBundle:Condition\RelativeTime":
      | expression | inspection       |
      | 10 seconds | @customer.update |
    And there are the following "VivaitInspectorBundle:Action\SendEmail":
      | recipient          | sender             | message | inspection       |
      | bravo@vivait.co.uk | lewis@vivait.co.uk | Test 1  | @customer.update |

  @mink:symfony2 @email
  Scenario: Trigger a delayed event that is no longer true when executed
    When I trigger a 'customer.update' event on customer "Fabien Potencier"
    And I update the name of customer "Fabien Potencier" to "Taylor" "Otwell"
    Then I should not get an email on "bravo@vivait.co.uk"

  @mink:symfony2 @email
  Scenario: Trigger a delayed event that is valid after it's triggered but before it's executed
    When I trigger a 'customer.update' event on customer "Fabien Potencier"
    And I update the name of customer "Fabien Potencier" to "Fabien" "Otwell"
    Then I should get an email on "bravo@vivait.co.uk" with:
    """
    Test 1
    """