<?php

namespace SM2P\SMTP\Receivers;

use SM2P;
use SM2P\SMTP;
use SM2P\Commands;

// Client
class SeSeg extends SMTP\Receiver {

    private $commandInvoker;

    function __construct($sender, array $options = []) {
        parent::__construct('mail.seseg.rj.gov.br', 25, $sender, $options);

        $this->commandInvoker = new SM2P\CommandInvoker();
    }

    function send() {
        $sent = false;

        $this->commandInvoker->run(new Commands\SMTP\EHLOCommand($this));
        $this->commandInvoker->run(new Commands\SMTP\SenderCommand($this));
        $this->commandInvoker->run(new Commands\SMTP\RecipientsCommand($this));
        $this->commandInvoker->run(new Commands\SMTP\HeaderCommand($this));

        $this->commandInvoker->run(new Commands\SMTP\BodyCommand($this));
        if ($this->getResponseCode() == '250') {
            $sent = true;
        }

        $this->commandInvoker->run(new Commands\Streaming\QUITCommand($this));

        $this->closeConnection();

        if ($sent) {
            return true;
        }
        return false;
    }

}
