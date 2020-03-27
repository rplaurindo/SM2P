<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\SMTP\AbstractCommand,
    SM2P\SMTP\Receiver;

class EHLOCommand extends AbstractCommand {

    function __construct(Receiver $receiver, $arguments = null) {
        parent::__construct($receiver);
    }

    function execute() {
        return $this->receiver->sendCommand("EHLO {$this->receiver->getServer()}", ['hasManyLines' => true]);
    }

}
