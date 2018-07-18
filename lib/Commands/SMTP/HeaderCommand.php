<?php

namespace SM2P\Commands\SMTP;

use SM2P\SMTP;

class HeaderCommand extends SMTP\AbstractCommand {

    function __construct(SMTP\Receiver $receiver) {
        parent::__construct($receiver);
    }

    function execute() {
        $this->receiver->sendCommand('DATA');
        $header = $this->receiver->getHeader();
        $header['To'] = substr($header['To'], 0, -2);
        array_walk($header, function($v, $k) {
            $this->receiver->sendCommand("$k: $v");
        });
    }

}
