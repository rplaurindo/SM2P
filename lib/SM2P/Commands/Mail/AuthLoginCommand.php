<?php

namespace SM2P\Commands\Mail;

use
    SM2P\AbstractMailProtocolCommand,
    SM2P\StreamingReceiver
;

// Concrete Command
class AuthLoginCommand extends AbstractMailProtocolCommand {

    private $login;

    function __construct(StreamingReceiver $receiver, $login) {
        parent::__construct($receiver);

        $this->login = $login;
    }

    function execute() {
        return $this->receiver->sendCommand('AUTH LOGIN ' . base64_encode($this->login));
    }

}
