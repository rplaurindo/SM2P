<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\AbstractMailProtocolCommand;

class RecipientsCommand extends AbstractMailProtocolCommand {

    function execute() {
        $responses = [];
        
        foreach ($this->receiver->getRecipients() as $recipient) {
            $responses[] = $this->receiver->sendCommand($recipient);
        }
        
        return implode("", $responses);
    }

}
