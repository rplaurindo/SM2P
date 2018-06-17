<?php

abstract class AbstractSMTP extends AbstractMailProtocol {

    private $server;

	private $sender;
    // private $cc;
    // private $bcc;
    private $body;
    private $recipients = [];

    private $header = array(
        'To' => '',
        'Content-Type' => 'text/html'
    );

    function __construct($server, $port, $sender, array $options = []) {
        parent::__construct($server, $port, $options);

        $this->server = $server;
        $this->setLogin($sender);
    	$this->sender = "MAIL FROM:<$sender>";
    }

    abstract function send();

    function addTo($recipient, $name = '') {
    	$this->header['To'] .= ($name ? "$name " : "") . "<$recipient>, ";
    	$this->setRecipient($recipient);
    }

    function setSubject($subject) {
        $this->header['Subject'] = $subject;
    }

    function setBody($body) {
        $this->body = $body;
    }

    protected function sendEHLO() {
    	return $this->sendCommand("EHLO $this->server");
    }

    protected function sendSender() {
    	$this->sendCommand($this->sender);
    }

    protected function sendRecipients() {
        array_walk($this->recipients, function($r) {
            $this->sendCommand($r);
        });
    }

    protected function sendHeader() {
        $this->sendCommand('DATA');
        $this->header['To'] = substr($this->header['To'], 0, -2);
        array_walk($this->header, function($v, $k) {
            $this->sendCommand("$k: $v");
        });
    }

    protected function sendBody() {
    	$this->sendCommand(PHP_EOL . utf8_decode($this->body));
        return $this->sendCommand('.');
    }

    private function setRecipient($recipient) {
        array_push($this->recipients, "RCPT TO:<$recipient>");
    }

}