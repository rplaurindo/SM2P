<?php

namespace SM2P\Commands\SMTP;

use SM2P\SMTP;

class BodyCommand extends SMTP\AbstractCommand {

    function __construct(SMTP\Receiver $receiver) {
        parent::__construct($receiver);
    }

    function execute() {
        $this->receiver->sendCommand(PHP_EOL . $this->receiver->getBody());
        return $this->receiver->sendCommand('.', ['appendsEOL' => false]);
    }

}
