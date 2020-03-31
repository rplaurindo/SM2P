<?php

namespace SM2P;

// Abstract Command
abstract class AbstractMailProtocolCommand {

    protected $receiver;

    function __construct(StreamingReceiver $receiver) {
        $this->receiver = $receiver;
    }

    abstract function execute();

}
