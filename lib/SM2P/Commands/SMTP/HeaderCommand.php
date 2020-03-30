<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\AbstractMailProtocolCommand,
    SM2P\StreamingReceiver
;

class HeaderCommand extends AbstractMailProtocolCommand {
    
    private $header;
    
    function __construct(StreamingReceiver $receiver, $header) {
        parent::__construct($receiver);
        
        $this->header = $header;
    }

    function execute() {
        $this->receiver->sendCommand('DATA');
        
        foreach($this->header as $key => $value) {
            $this->receiver->sendCommand("$key: $value");
        }
        
    }

}
