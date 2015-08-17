<?php

namespace Codeception\Module;

use Codeception\Lib\Interfaces\MultiSession;
use Codeception\Module;
use Codeception\TestCase;
use Tx\Mailer\Message;
use Tx\Mailer\SMTP;

/**
 * Created by PhpStorm.
 * User: willemv
 * Date: 15/08/14
 * Time: 15:16
 */
class SmtpModule extends Module implements MultiSession
{
    /**
     * @var SMTP
     */
    private $client;

    public function sendEmailBySmtp($from, $to, $subject, $body)
    {
        $this->_createSmtpClient();

        $message = new Message();
        $message->setFrom($from, $from);
        $message->setTo($to, $to);
        $message->setSubject($subject);
        $message->setBody($body);

        $this->client->send($message);
    }

    /**
     * @param TestCase $test
     */
    public function _before(TestCase $test) {
        $this->_initializeSession();
    }

    public function _initializeSession()
    {
        $this->client = null;
    }

    public function _backupSession()
    {
        return [
            'client'    => $this->client
        ];
    }

    public function _loadSession($data)
    {
        foreach ($data as $key => $val) {
            $this->$key = $val;
        }
    }

    public function _closeSession($data)
    {
        unset($data);
    }

    public function _setClient(SMTP $client)
    {
        $this->client = $client;
    }

    private function _createSmtpClient()
    {
        if (is_null($this->client)) {
            $this->client = new SMTP();
            $this->client->setServer($this->config['host'], $this->config['port']);
        }
    }
}