<?php

namespace Codeception\Module;

use Codeception\Lib\Interfaces\MultiSession;
use Codeception\Module;
use Codeception\TestCase;

/**
 * Created by PhpStorm.
 * User: willemv
 * Date: 15/08/14
 * Time: 15:16
 */
class SmtpModule extends Module implements MultiSession
{
    /**
     * @var \Net_SMTP
     */
    private $client;

    public function sendEmailBySmtp($from, $to, $subject, $body)
    {
        $this->_createSmtpClient();
        $this->client->connect();
        $this->client->mailFrom($from);
        $this->client->rcptTo($to);
        $this->client->data($body, array(
            'From' => $from,
            'To' => $to,
            'Subject' => $subject
        ));
        $this->client->disconnect();
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

    public function _setClient(\Net_SMTP $client)
    {
        $this->client = $client;
    }

    private function _createSmtpClient()
    {
        if (is_null($this->client)) {
            $this->client = new \Net_SMTP($this->config['host']);
        }
    }
}