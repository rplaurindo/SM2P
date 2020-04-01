<?php

namespace SM2P;

// Abstract Command
abstract class AbstractMailProtocolCommand {

    protected $receiver;

    function __construct(CommandReceiver $receiver) {
        $this->receiver = $receiver;
    }

    abstract function execute();

}
