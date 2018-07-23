<?php

namespace SM2P;

class MailProtocols {

    private $elements;

    function __construct() {
        $this->elements = [];
    }

    function add(AbstractElement $element) {
        array_push($this->elements, $element);
    }

//    accept
    function accepts2EachMailProtocol(AbstractMailProtocolVisitor $visitor) {
        foreach ($this->elements as $element) {
            $element->accept($visitor);
        }
    }

}
