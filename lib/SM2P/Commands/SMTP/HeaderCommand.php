<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\AbstractMailProtocolCommand,
    SM2P\Streaming
;

class HeaderCommand extends AbstractMailProtocolCommand {
    
    private $header;
    
    function __construct(Streaming $receiver, $header) {
        parent::__construct($receiver);
        
        $this->header = $header;
    }

    function execute() {
        $responseLines = $this->receiver->sendCommand('DATA');
        
        foreach($this->header as $key => $value) {
            $responseLines .= $this->receiver->sendCommand("$key: $value");
        }
        
        return $responseLines;
    }

}
