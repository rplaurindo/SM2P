<?php

namespace SM2P\Commands\Mail;

use
    SM2P\AbstractMailProtocolCommand,
    SM2P\MailProtocolReceiver;

// Concrete Command
class AuthLoginCommand extends AbstractMailProtocolCommand {

    private $login;

    function __construct(MailProtocolReceiver $receiver) {
        parent::__construct($receiver);

        $this->login = $receiver->getLogin();
    }

    function execute() {
        return $this->receiver->sendCommand('AUTH LOGIN ' . base64_encode($this->login));
    }

}
