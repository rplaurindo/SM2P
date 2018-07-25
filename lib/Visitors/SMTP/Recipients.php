<?php

namespace SM2P\Visitors\SMTP;

use SM2P;

class Recipients extends AbstractSMTP implements SM2P\AbstractVisitor {

    function visit(SM2P\Streaming $element) {
        global $responses;
        global $globalElement;
        $globalElement = $element;
        $responses = [];
        array_walk($this->smtp->getRecipients(), function($r) {
            global $responses;
            global $globalElement;
            array_push($responses, $globalElement->sendCommand($r));
        });
        return implode("", $responses);
    }

}
