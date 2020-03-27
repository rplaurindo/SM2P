<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\SMTP\AbstractCommand,
    SM2P\SMTP\Receiver;

class HeaderCommand extends AbstractCommand {

    function __construct(Receiver $receiver) {
        parent::__construct($receiver);
    }

    function execute() {
        $this->receiver->sendCommand('DATA');
        $header = $this->receiver->getHeader();
        
        foreach($header as $key => $value) {
            $this->receiver->sendCommand("$key: $value");
        }
        
    }

}
