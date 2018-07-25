<?php

namespace SM2P\Visitors\SMTP;

use SM2P;

class Header extends AbstractSMTP implements SM2P\AbstractVisitor {

    function visit(SM2P\Streaming $element) {
        global $globalElement;
        $globalElement = $element;
        $element->sendCommand('DATA');
        $header = $this->smtp->getHeader();
        $header['To'] = substr($header['To'], 0, -2);
        array_walk($header, function($v, $k) {
            global $globalElement;
            $globalElement->sendCommand("$k: $v");
        });
    }

}
