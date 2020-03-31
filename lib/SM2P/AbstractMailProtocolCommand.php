<?php

namespace SM2P;

// Abstract Command
abstract class AbstractMailProtocolCommand {

    protected $receiver;

    function __construct(Streaming $receiver) {
        $this->receiver = $receiver;
    }

    abstract function execute();

}
