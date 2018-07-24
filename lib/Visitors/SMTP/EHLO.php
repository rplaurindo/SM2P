<?php

namespace SM2P\Visitors\SMTP;

use SM2P;

class EHLO implements SM2P\AbstractSMTPVisitor {

    function visit(SM2P\SMTP $element) {
        return $element->sendCommand("EHLO {$element->getServer()}", true);
    }

}
