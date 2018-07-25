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

        $this->commands->accepts2Each(new SMTP\EHLO($this));
        $this->commands->accepts2Each(new SMTP\Sender($this));
        $this->commands->accepts2Each(new SMTP\Recipients($this));
        $this->commands->accepts2Each(new SMTP\Header($this));

        $this->commands->accepts2Each(new SMTP\Body($this));
        if ($this->getResponseCode() == '250') {
            $sent = true;
        }

        $this->commands->accepts2Each(new Streaming\QUIT());

        $this->closeConnection();
        if ($sent) {
            return true;
        }
        return false;
    }

}
