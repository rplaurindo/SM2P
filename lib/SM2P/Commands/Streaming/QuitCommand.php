<?php

namespace SM2P\Commands\Streaming;

use
    SM2P\AbstractMailProtocolCommand,
    SM2P\MailProtocolReceiver;

class QuitCommand extends AbstractMailProtocolCommand {

    function __construct(MailProtocolReceiver $receiver) {
        parent::__construct($receiver);
    }

    function execute() {
        $this->receiver->sendCommand('QUIT');
    }

}
