<?php

namespace SM2P\Commands\Mail;

use
    SM2P\AbstractMailProtocolCommand,
    SM2P\CommandReceiver
;

class PasswordCommand extends AbstractMailProtocolCommand {

    private $password;

    function __construct(CommandReceiver $receiver, $password) {
        parent::__construct($receiver);

        $this->password = $password;
    }

    function execute() {
        return $this->receiver->sendCommand(base64_encode($this->password));
    }

}
