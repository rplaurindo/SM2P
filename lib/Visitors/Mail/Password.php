<?php

namespace SM2P\Visitors\Mail;

use SM2P;

class Password implements SM2P\AbstractVisitor {

    private $password;

    function __construct($password) {
        $this->password = $password;
    }

    function visit(SM2P\Streaming $element) {
        return $element->sendCommand(base64_encode($this->password));
    }

}
