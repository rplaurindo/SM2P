<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\AbstractMailProtocolCommand,
    SM2P\CommandReceiver
;

class HeaderCommand extends AbstractMailProtocolCommand {
    
    private $header;
    
    function __construct(CommandReceiver $receiver, $header) {
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
