<?php

namespace SM2P;

class CommandInvoker {

    function run(AbstractMailProtocolCommand $command) {
        return $command->execute();
    }

}
