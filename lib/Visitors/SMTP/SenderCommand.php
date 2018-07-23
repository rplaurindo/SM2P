<?php

namespace SM2P\Commands\SMTP;

use SM2P\SMTP;

class SenderCommand extends SMTP\AbstractCommand {

    function __construct(SMTP\Receiver $receiver) {
        parent::__construct($receiver);
    }

    function execute() {
        return $this->receiver->sendCommand($this->receiver->getSender());
    }

}
