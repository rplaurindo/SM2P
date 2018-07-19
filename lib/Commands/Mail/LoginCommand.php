<?php

namespace SM2P\Commands\Mail;

use SM2P;

// Concrete Command
class LoginCommand extends SM2P\AbstractMailProtocolCommand {

    private $login;

    function __construct(SM2P\MailProtocolReceiver $receiver, $login) {
        parent::__construct($receiver);

        $this->login = $login;
    }

    function execute() {
        return $this->receiver->sendCommand('AUTH LOGIN ' . base64_encode($this->login));
    }

}
