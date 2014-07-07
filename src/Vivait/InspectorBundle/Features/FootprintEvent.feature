Feature: Footprint Event
  In order to ensure data integrity and less involvement of users
  The system should automatically create footprints when user-specified events are triggered

  Background:
    Given the database is clean
    Given there are the following "VivaAuthBundle:User":
      | username  | fullname  | email                        | password | active |
      | TestUser1 | Test user | testuser1@bravo.vivait.co.uk | password | yes    |
    Given there are the following "VivaAuthBundle:Tenant":
      | code | tenant |
      | TE1  | Tenant |
    And there are the following "VivaBravoBundle:Customer":
      | @id | email                   | forename | surname |
      | c1  | john.smith@vivait.co.uk | John     | Smith   |
    And there are the following "VivaitInspectorBundle:Inspection":
      | eventName       | name       | voterType |
      | customer.update | Behat test | 2         |
    And there are the following "VivaitInspectorBundle:Condition\Expression":
      | expression                           | inspection       |
      | "customer.getName() == 'John Smith'" | @customer.update |
    And there are the following "VivaBravoBundle:FootprintType\Customer":
      | @id | customer |
      | ft1 | @c1      |
    And there are the following "VivaitFootprintBundle:Footprint\Email":
      | @id | contact | message | subject | fromuser   | fromtenant | footprinttype |
      | m1  | 0       | This is a test    | Testing | @TestUser1 | @TE1       | @ft1          |
    And there are the following "VivaitFootprintBundle:CannedMessage":
      | @id | category | name | description | message |
      | cm1 | Tests    | Test | Testing     | @m1     |
    And there are the following "VivaitInspectorBundle:Action\Footprint":
      | canned | inspection       |
      | @cm1   | @customer.update |

  @mink:symfony2 @email
  Scenario: Trigger an 'And' event when all conditions match
    Given
    When I trigger a 'customer.update' event on customer "John Smith"
    Then I should get an email on "john.smith@vivait.co.uk" with:
    """
    This is a test
    """