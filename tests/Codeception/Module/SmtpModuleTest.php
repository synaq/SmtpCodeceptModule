<?php

namespace Codeception\Module;

use Codeception\Lib\ModuleContainer;
use Mockery as m;
use Tx\Mailer\Message;
use Tx\Mailer\SMTP;

/**
 * Created by PhpStorm.
 * User: willemv
 * Date: 15/08/14
 * Time: 16:09
 */
class SmtpModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SMTP|m\MockInterface
     */
    private $client;

    /**
     * @var SmtpModule
     */
    private $module;

    public function setUp()
    {
        /** @var ModuleContainer|m\MockInterface $container */
        $container = \Mockery::mock('Codeception\Lib\ModuleContainer');
        $this->client = \Mockery::mock('Tx\Mailer\SMTP');
        $this->module = new SmtpModule($container);
        $this->module->_setClient($this->client);
    }

    public function testSendEmailBySmtp()
    {
        $this->client->shouldReceive('send')->with(m::on(function($value) {
            $message = new Message();
            $message->setFrom("foo@bar.com", "foo@bar.com");
            $message->setTo("bar@foo.com", "bar@foo.com");
            $message->setSubject("Foo");
            $message->setBody("Bar");

            return ($message == $value);
        }));
        $this->module->sendEmailBySmtp("foo@bar.com", "bar@foo.com", "Foo", "Bar");
    }
}