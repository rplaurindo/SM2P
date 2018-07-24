<?php

namespace SM2P\Visitors\Mail;

use SM2P;

class Password implements SM2P\AbstractMailProtocolVisitor {

    private $password;

    function __construct($password) {
        $this->password = $password;
    }

    function visit(SM2P\MailProtocol $element) {
        return $element->sendCommand(base64_encode($this->password));
    }

}
