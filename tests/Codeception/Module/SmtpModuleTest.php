<?php

namespace Codeception\Module;

use Codeception\Lib\ModuleContainer;
use Mockery as m;

/**
 * Created by PhpStorm.
 * User: willemv
 * Date: 15/08/14
 * Time: 16:09
 */
class SmtpModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Net_SMTP|m\MockInterface
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
        $this->client = \Mockery::mock('Net_SMTP');
        $this->module = new SmtpModule($container);
        $this->module->_setClient($this->client);
    }

    public function testSendEmailBySmtp()
    {
        $this->client->shouldReceive('connect');
        $this->client->shouldReceive('mailFrom');
        $this->client->shouldReceive('rcptTo');
        $this->client->shouldReceive('data');
        $this->client->shouldReceive('disconnect');

        $this->module->sendEmailBySmtp("foo@bar.com", "bar@foo.com", "Foo", "Bar");
    }
}