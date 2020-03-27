<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\SMTP\AbstractCommand,
    SM2P\SMTP\Receiver;

class BodyCommand extends AbstractCommand {

    function __construct(Receiver $receiver) {
        parent::__construct($receiver);
    }

    function execute() {
        $lines = $this->receiver->sendCommand(PHP_EOL);
        $lines .= $this->receiver->sendCommand($this->receiver->getBody());
        $lines .= $this->receiver->sendCommand('.');
        
        return $lines;
    }

}
