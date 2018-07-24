<?php

namespace SM2P\Commands\SMTP;

use SM2P;

class Header implements SM2P\AbstractSMTPVisitor {

    function visit(SM2P\SMTP $element) {
        global $globalElement;
        $globalElement = $element;
        $element->sendCommand('DATA');
        $header = $element->getHeader();
        $header['To'] = substr($header['To'], 0, -2);
        array_walk($header, function($v, $k) {
            global $globalElement;
            $globalElement->sendCommand("$k: $v");
        });
    }

}
