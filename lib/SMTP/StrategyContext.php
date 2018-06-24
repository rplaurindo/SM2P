<?php

namespace SM2P\SMTP;

use SM2P\AbstractSMTP;

class StrategyContext {

	private $strategy;

    function __construct(AbstractSMTP $strategy) {
    	$this->strategy = $strategy;
    }

    function send() {
    	return $this->strategy->send();
    }

}