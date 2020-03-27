<?php

namespace SM2P\Commands\SMTP;

use SM2P\SMTP;

class EHLOCommand extends SMTP\AbstractCommand {

    function __construct(SMTP\Receiver $receiver, $arguments = null) {
        parent::__construct($receiver);
    }

    function execute() {
        return $this->receiver->sendCommand("EHLO {$this->receiver->getServer()}", ['hasManyLines' => true]);
    }

}
