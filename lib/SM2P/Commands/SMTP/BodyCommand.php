<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\AbstractMailProtocolCommand,
    SM2P\StreamingReceiver
;

class BodyCommand extends AbstractMailProtocolCommand {
    
    private $body;
    
    function __construct(StreamingReceiver $receiver, $body) {
        parent::__construct($receiver);
        
        $this->body = $body;
    }

    function execute() {
        $lines = $this->receiver->sendCommand(PHP_EOL, ['appendsEOL' => false]);
        $lines .= $this->receiver->sendCommand($this->body);
        $lines .= $this->receiver->sendCommand('.');
        
        return $lines;
    }

}
