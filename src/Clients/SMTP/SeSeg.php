<?php

namespace SM2P\Clients\SMTP;

use SM2P;
use SM2P\Visitors\Streaming;
use SM2P\Visitors\SMTP;

// Client
class SeSeg extends SM2P\SMTP {

    private $commands;

    function __construct($sender, array $options = []) {
        parent::__construct('mail.seseg.rj.gov.br', 25, $sender, $options);

        $this->commands = new SM2P\Transmissions();
        $this->commands->add($this);
    }

    function send() {
        $sent = false;

        $this->commands->accepts2Each(new SMTP\EHLO());
//        $this->commandInvoker->send('EHLO');
        $this->commands->accepts2Each(new SMTP\Sender());
//        $this->commandInvoker->send('SENDER');
        $this->commands->accepts2Each(new SMTP\Recipients());
//        $this->commandInvoker->send('RECIPIENTS');
        $this->commands->accepts2Each(new SMTP\Header());
//        $this->commandInvoker->send('HEADER');
//
        $this->commands->accepts2Each(new SMTP\Body());
//        $this->commandInvoker->send('BODY');
        if ($this->getResponseCode() == '250') {
            $sent = true;
        }

        $this->commands->accepts2Each(new Streaming\QUIT());
//        $this->commandInvoker->send('QUIT');

        $this->closeConnection();
        if ($sent) {
            return true;
        }
        return false;
    }

}
