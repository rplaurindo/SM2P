<?php

namespace SM2P;

class Transmissions {

    protected $elements;

    function __construct() {
        $this->elements = [];
    }

    function add(AbstractElement $element) {
        array_push($this->elements, $element);
    }

//    accept
    function accepts2Each(AbstractVisitor $visitor) {
        foreach ($this->elements as $element) {
            $element->accept($visitor);
        }
    }

}
