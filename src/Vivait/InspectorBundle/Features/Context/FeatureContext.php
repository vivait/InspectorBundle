<?php

namespace Vivait\InspectorBundle\Features\Context;

use Behat\Behat\Event\ScenarioEvent;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\Symfony2Extension\Driver\KernelDriver;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;

use Behat\Behat\Context\BehatContext,
  Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
  Behat\Gherkin\Node\TableNode;

use Viva\BravoBundle\Entity\Customer;
use Viva\BravoBundle\Exception\ExistingOfferException;
use Vivait\BehatAliceLoader\AliceContext;
use Vivait\InspectorBundle\Service\Inspection\RegisterInspection;

//
// Require 3rd-party libraries here:
//
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
 */
class FeatureContext extends MinkContext
  implements KernelAwareInterface
{
    use KernelDictionary;

    private $parameters;
    /**
     * @var SwiftMailCollector
     */
    private $collector;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;

        $this->useContext('fixtures', new AliceContext($parameters));
        $this->useContext('fixtures', new AliceContext($parameters));
    }

    /**
     * @return EntityManagerInterface
     */
    private function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @Transform /^customer "(.*)"$/
     */
    public function castNameToCustomer($name)
    {
        return $this->getManager()->getRepository('VivaBravoBundle:Customer')->getByFullName($name);
    }

    /**
     * @When /^I trigger a \'([^\']*)\' event on (customer [^']+)$/
     * @When /^I trigger an \'([^\']*)\' event on (customer [^']+)$/
     */
    public function iTriggerAnEventOnACustomer($event, Customer $customer)
    {
//        $this->registerInspections();

        /* @var EventDispatcher $dispatcher */
        $dispatcher = $this->getContainer()->get('event_dispatcher');
        $dispatcher->dispatch($event, new CustomerEvent($customer));
    }

    /**
     * @Given /^I should get an email on "(?P<email>[^"]+)" with:$/
     * @Given /^I should get an email on "(?P<email>[^"]+)"$/
     */
    public function iShouldGetAnEmail($email, PyStringNode $text = null)
    {
        foreach ($this->collector->getMessages() as $message) {
            // Checking the recipient email and the X-Swift-To
            // header to handle the RedirectingPlugin.
            // If the recipient is not the expected one, check
            // the next mail.
            $correctRecipient = array_key_exists(
              $email,
              $message->getTo()
            );
            $headers          = $message->getHeaders();

            $correctXToHeader = false;
            if ($headers->has('X-Swift-To')) {
                $correctXToHeader = array_key_exists(
                  $email,
                  $headers->get('X-Swift-To')->getFieldBodyModel()
                );
            }

            if ((!$correctRecipient && !$correctXToHeader)) {
                continue;
            }

            if ($text === null) {
                return true;
            }

            try {
                // checking the content
                return \assertContains(
                  $text->getRaw(),
                  $message->getBody()
                );
            } catch (\PHPUnit_Framework_AssertionFailedError $e) {
                $error = sprintf(
                  'An email has been found for "%s" but without ' .
                  'the text "%s".',
                  $email,
                  $text->getRaw()
                );
            }
        }

        if (empty($error)) {
            $error = sprintf('Email to %s not sent', $email);
        }

        throw new ExpectationException($error, $this->getSession());
    }

    /**
     * @BeforeScenario @email
     */
    public function listenToEmails()
    {
        $this->collector = new SwiftMailCollector();
        $this->getContainer()->get('swiftmailer.mailer.default.transport.eventdispatcher')->bindEventListener(
          $this->collector
        );
    }

    /**
     * @AfterScenario @email
     */
    public function clearEmails()
    {
        $this->collector = null;
    }

    /**
     * @Given /^I should not get an email on "(?P<email>[^"]+)" with:$/
     * @Given /^I should not get an email on "(?P<email>[^"]+)"$/
     */
    public function iShouldNotGetAnEmail($email, PyStringNode $text = null)
    {
        try {
            $this->iShouldGetAnEmail($email, $text);
            throw new ExpectationException(sprintf('Not expecting email to %s', $email), $this->getSession());
        }
        catch (ExpectationException $e) {
            // Do nothing
        }
    }

    /**
     * @Given /^I update the name of (customer [^']+) to "([^"]*)" "([^"]*)"$/
     */
    public function iUpdateTheNameOfCustomerTo(Customer $customer, $first_name, $last_name)
    {
        $customer->setForename($first_name);
        $customer->setSurname($last_name);

        $this->iTriggerAnEventOnACustomer('customer.update', $customer);
    }
}
