<?php

namespace SM2P\Commands\Mail;

use SM2P;

// Concrete Command
class STARTTLSCommand extends SM2P\AbstractMailProtocolCommand {

    function __construct(SM2P\MailProtocolReceiver $receiver) {
        parent::__construct($receiver);
    }

    function execute() {
        return $this->receiver->sendCommand('STARTTLS');
    }

}
