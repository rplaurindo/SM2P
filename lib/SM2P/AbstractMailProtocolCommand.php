<?php

namespace SM2P;

abstract class AbstractMailProtocolCommand {

    protected $receiver;

    function __construct(MailProtocolReceiver $receiver) {
        $this->receiver = $receiver;
    }

    abstract function execute();

}
