<?php

namespace SM2P\Commands\Mail;

use SM2P;

class PasswordCommand extends SM2P\AbstractMailProtocolCommand {

    private $password;

    function __construct(SM2P\MailProtocolReceiver $receiver) {
        parent::__construct($receiver);

        $this->password = $receiver->getPassword();
    }

    function execute() {
        return $this->receiver->sendCommand(base64_encode($this->password));
    }

}
