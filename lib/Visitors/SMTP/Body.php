<?php

namespace SM2P\Visitors\SMTP;

use SM2P;

class Body implements SM2P\AbstractSMTPVisitor {

    function visit(SM2P\SMTP $element) {
        $element->sendCommand(PHP_EOL . $element->getBody());
        return $element->sendCommand('.');
    }

}
