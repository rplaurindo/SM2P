<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\AbstractMailProtocolCommand,
    SM2P\Streaming
;

class BodyCommand extends AbstractMailProtocolCommand {
    
    private $body;
    
    function __construct(Streaming $receiver, $body) {
        parent::__construct($receiver);
        
        $this->body = $body;
    }

    function execute() {
        $responseLines = $this->receiver->sendCommand(PHP_EOL, ['appendsEOL' => false]);
        $responseLines .= $this->receiver->sendCommand($this->body);
        $responseLines .= $this->receiver->sendCommand('.');
        
        return $responseLines;
    }

}
