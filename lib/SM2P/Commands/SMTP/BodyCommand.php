<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\AbstractMailProtocolCommand;

class BodyCommand extends AbstractMailProtocolCommand {

    function execute() {
        $lines = $this->receiver->sendCommand(PHP_EOL, ['appendsEOL' => false]);
        $lines .= $this->receiver->sendCommand($this->receiver->getBody());
        $lines .= $this->receiver->sendCommand('.');
        
        return $lines;
    }

}
