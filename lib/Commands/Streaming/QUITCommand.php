<?php

namespace SM2P\Commands\Streaming;

use SM2P;

class QUITCommand extends SM2P\AbstractMailProtocolCommand {

    function __construct(SM2P\MailProtocolReceiver $receiver) {
        parent::__construct($receiver);
    }

    function execute() {
        $this->receiver->sendCommand('QUIT');
    }

}
