<?php

class SMTPStrategyContext {

	private $strategy;

    function __construct(AbstractSMTP $strategy) {
    	$this->strategy = $strategy;
    }

    function send() {
    	return $this->strategy->send();
    }

}
