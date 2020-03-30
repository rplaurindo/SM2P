<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\AbstractMailProtocolCommand,
    SM2P\StreamingReceiver
;

class RecipientsCommand extends AbstractMailProtocolCommand {
    
    private $recipients;
    
    function __construct(StreamingReceiver $receiver, $recipients) {
        parent::__construct($receiver);
        
        $this->recipients = $recipients;
    }

    function execute() {
        $responses = [];
        
        foreach ($this->recipients as $recipient) {
            $responses[] = $this->receiver->sendCommand($recipient);
        }
        
        return implode("", $responses);
    }

}
