<?php

namespace SM2P\Commands\SMTP;

use
    SM2P\AbstractMailProtocolCommand;

class SenderCommand extends AbstractMailProtocolCommand {

    function execute() {
        return $this->receiver->sendCommand($this->receiver->getSender());
    }

}
