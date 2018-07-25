<?php

namespace SM2P\Visitors\Mail;

use SM2P;

class Login implements SM2P\AbstractVisitor  {

    private $login;

    function __construct($login) {
        $this->login = $login;
    }

    function visit(SM2P\Streaming $element) {
        return $element->sendCommand('AUTH LOGIN ' . base64_encode($this->login));
    }

}
