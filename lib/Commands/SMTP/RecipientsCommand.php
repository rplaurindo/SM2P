<?php

namespace SM2P\Commands\SMTP;

use SM2P\SMTP;

class RecipientsCommand extends SMTP\AbstractCommand {

    function __construct(SMTP\Receiver $receiver) {
        parent::__construct($receiver);
    }

    function execute() {
        global $responses;
        $responses = [];
        array_walk($this->receiver->getRecipients(), function($r) {
            global $responses;
            array_push($responses, $this->receiver->sendCommand($r));
        });
        return implode("", $responses);
    }

}
