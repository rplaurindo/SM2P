<?php

namespace SM2P\Visitors\Mail;

use SM2P;

class StartTLS implements SM2P\AbstractMailProtocolVisitor {

    function visit(SM2P\MailProtocol $element) {
        return $element->sendCommand('STARTTLS');
    }

}
