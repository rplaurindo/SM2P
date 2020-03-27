<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\SMTP\AbstractCommand,
    SM2P\SMTP\Receiver;

class SenderCommand extends AbstractCommand {

    function __construct(Receiver $receiver) {
        parent::__construct($receiver);
    }

    function execute() {
        return $this->receiver->sendCommand($this->receiver->getSender());
    }

}
