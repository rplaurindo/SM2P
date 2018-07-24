<?php

namespace SM2P\Commands\Streaming;

use SM2P;

class QUIT implements SM2P\AbstractStreamingVisitor {

    function visit(SM2P\Streaming $element) {
        $element->sendCommand('QUIT');
    }

}
