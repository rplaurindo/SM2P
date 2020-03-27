<?php

namespace SM2P\SMTP;

use
    SM2P\AbstractMailProtocolCommand;

abstract class AbstractCommand extends AbstractMailProtocolCommand {

    protected $receiver;

    function __construct(Receiver $receiver) {
        parent::__construct($receiver);

        $this->receiver = $receiver;
    }

}
