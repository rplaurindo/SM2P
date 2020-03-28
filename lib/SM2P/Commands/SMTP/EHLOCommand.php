<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\AbstractMailProtocolCommand;

class EHLOCommand extends AbstractMailProtocolCommand {

    function execute() {
        return $this->receiver->sendCommand("EHLO {$this->receiver->getServer()}", ['hasManyLines' => true]);
    }

}
