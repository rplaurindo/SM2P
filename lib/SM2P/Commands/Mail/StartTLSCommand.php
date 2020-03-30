<?php

namespace SM2P\Commands\Mail;

use
    SM2P\AbstractMailProtocolCommand
;

class StartTLSCommand extends AbstractMailProtocolCommand {

    function execute() {
        return $this->receiver->sendCommand('STARTTLS');
    }

}
