<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\AbstractMailProtocolCommand,
    SM2P\Streaming
;

class RecipientsCommand extends AbstractMailProtocolCommand {
    
    private $recipients;
    
    function __construct(Streaming $receiver, $recipients) {
        parent::__construct($receiver);
        
        $this->recipients = $recipients;
    }

    function execute() {
        $responseLines = '';
        
        foreach ($this->recipients as $recipient) {
            $responseLines .= $this->receiver->sendCommand($recipient);
        }
        
        return $responseLines;
    }

}
