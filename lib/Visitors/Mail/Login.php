<?php

namespace SM2P\Visitors\Mail;

use SM2P;

// Concrete Visitor
class Login implements SM2P\AbstractMailProtocolVisitor  {

    private $login;

    function __construct($login) {
        $this->login = $login;
    }

    function visit(SM2P\MailProtocol $element) {
        $element->sendCommand('AUTH LOGIN ' . base64_encode($this->login));
    }

}
