<?php

namespace SM2P\Visitors\SMTP;

use SM2P;

class Body extends AbstractSMTP implements SM2P\AbstractVisitor {

    function visit(SM2P\Streaming $element) {
        $element->sendCommand(PHP_EOL . $this->smtp->getBody());
        return $element->sendCommand('.');
    }

}
