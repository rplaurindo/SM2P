<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\AbstractMailProtocolCommand;

class HeaderCommand extends AbstractMailProtocolCommand {

    function execute() {
        $this->receiver->sendCommand('DATA');
        $header = $this->receiver->getHeader();
        
        foreach($header as $key => $value) {
            $this->receiver->sendCommand("$key: $value");
        }
        
    }

}
