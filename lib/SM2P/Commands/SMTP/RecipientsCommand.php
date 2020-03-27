<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\SMTP\AbstractCommand,
    SM2P\SMTP\Receiver;

class RecipientsCommand extends AbstractCommand {

    function __construct(Receiver $receiver) {
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
