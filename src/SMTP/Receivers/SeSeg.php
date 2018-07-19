<?php

namespace SM2P\SMTP\Receivers;

use SM2P;
use SM2P\SMTP;

// Client
class SeSeg extends SMTP\Receiver {

    private $commandInvoker;

    function __construct($sender, array $options = []) {
        parent::__construct('mail.seseg.rj.gov.br', 25, $sender, $options);

        $this->commandInvoker = new SM2P\CommandInvoker($this);
    }

    function send() {
        $sent = false;

        $this->commandInvoker->send('EHLO');
        $this->commandInvoker->send('SENDER');
        $this->commandInvoker->send('RECIPIENTS');
        $this->commandInvoker->send('HEADER');

        $this->commandInvoker->send('BODY');
        if ($this->getResponseCode() == '250') {
            $sent = true;
        }

        $this->commandInvoker->send('QUIT');

        $this->closeConnection();

        if ($sent) {
            return true;
        }
        return false;
    }

}
