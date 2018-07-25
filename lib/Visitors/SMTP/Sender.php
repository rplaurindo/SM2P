<?php

namespace SM2P\Visitors\SMTP;

use SM2P;

class Sender extends AbstractSMTP implements SM2P\AbstractVisitor {

    function visit(SM2P\Streaming $element) {
        return $element->sendCommand($this->smtp->getSender());
    }

}
