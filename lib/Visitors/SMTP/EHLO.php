<?php

namespace SM2P\Visitors\SMTP;

use SM2P;

// Concrete Visitor
class EHLO extends AbstractSMTP implements SM2P\AbstractVisitor {

//    overriding (should have same signature and inherited are not accepted as type). For overloading must be in the same class
    function visit(SM2P\Streaming $element) {
        return $element->sendCommand("EHLO {$this->smtp->getServer()}", true);
    }

}
