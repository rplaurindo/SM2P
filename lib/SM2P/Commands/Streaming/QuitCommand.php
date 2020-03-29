<?php

namespace SM2P\Commands\Streaming;

use
    SM2P\AbstractMailProtocolCommand;

class QuitCommand extends AbstractMailProtocolCommand {

    function execute() {
        $this->receiver->sendCommand('QUIT');
    }

}
