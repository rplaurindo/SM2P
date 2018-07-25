<?php

namespace SM2P\Visitors\Mail;

use SM2P;

class StartTLS implements SM2P\AbstractVisitor {

    function visit(SM2P\Streaming $element) {
        return $element->sendCommand('STARTTLS');
    }

}
