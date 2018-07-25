<?php

namespace SM2P\Visitors\Streaming;

use SM2P;

class QUIT implements SM2P\AbstractVisitor {

    function visit(SM2P\Streaming $element) {
        $element->sendCommand('QUIT');
    }

}
