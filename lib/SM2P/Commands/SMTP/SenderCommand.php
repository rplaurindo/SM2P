<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\AbstractMailProtocolCommand,
    SM2P\Streaming
;

class SenderCommand extends AbstractMailProtocolCommand {
    
    private $sender;
    
    function __construct(Streaming $receiver, $sender) {
        parent::__construct($receiver);
        
        $this->sender = $sender;
    }

    function execute() {
        return $this->receiver->sendCommand($this->sender);
    }

}
