<?php

namespace SM2P\Visitors\SMTP;

use SM2P;

abstract class AbstractSMTP {

    protected $smtp;

    function __construct(SM2P\SMTP $smtp) {
        $this->smtp = $smtp;
    }

}
