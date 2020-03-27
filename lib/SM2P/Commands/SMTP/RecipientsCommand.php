<?php

namespace SM2P\Commands\SMTP;

use SM2P\SMTP;

class RecipientsCommand extends SMTP\AbstractCommand {

    function __construct(SMTP\Receiver $receiver) {
        parent::__construct($receiver);
    }

    function execute() {
        $responses = [];
        
        foreach ($this->receiver->getRecipients() as $recipient) {
            $responses[] = $this->receiver->sendCommand($recipient);
        }
        
        return implode("", $responses);
    }

}
