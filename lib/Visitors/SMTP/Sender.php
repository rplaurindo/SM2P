<?php

namespace SM2P\Commands\SMTP;

use SM2P;

class Sender implements SM2P\AbstractSMTPVisitor {

    function visit(SM2P\SMTP $element) {
        return $element->sendCommand($element->getSender());
    }

}
