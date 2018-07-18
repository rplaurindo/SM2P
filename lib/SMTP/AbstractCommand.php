<?php

namespace SM2P\SMTP;

use SM2P;

abstract class AbstractCommand extends SM2P\AbstractMailProtocolCommand {

    protected $receiver;

    function __construct(Receiver $receiver) {
        parent::__construct($receiver);

        $this->receiver = $receiver;
    }

}
