<?php

namespace SM2P\Visitors\SMTP;

use SM2P;

class Recipients implements SM2P\AbstractSMTPVisitor {

    function visit(SM2P\SMTP $element) {
        global $responses;
        global $globalElement;
        $globalElement = $element;
        $responses = [];
        array_walk($element->getRecipients(), function($r) {
            global $responses;
            global $globalElement;
            array_push($responses, $globalElement->sendCommand($r));
        });
        return implode("", $responses);
    }

}
