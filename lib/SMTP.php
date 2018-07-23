<?php

namespace SM2P;

// Concrete Element
class SMTP extends MailProtocol {

    private $sender;
    // private $cc;
    // private $bcc;

    private $body;

    private $recipients = [];

    private $header = array(
        'To' => '',
        'Content-Type' => 'text/html; charset=UTF-8'
    );

    function __construct($server, $port, $sender, array $options = []) {
        parent::__construct($server, $port, $options);

        $this->setLogin($sender);
        $this->sender = "MAIL FROM:<$sender>";
        $this->setFrom($sender);
    }

    function setFrom($sender, $name = '') {
        $this->header['From'] = ($name ? $name : "") . "<$sender>";
    }

    function addTo($recipient, $name = '') {
        $this->header['To'] .= ($name ? "$name " : "") . "<$recipient>, ";
        $this->setRecipient($recipient);
    }

    function setSubject($subject) {
        $this->header['Subject'] = $subject;
    }

    function getSender() {
        return $this->sender;
    }

    function getRecipients() {
        return $this->recipients;
    }

    function getHeader() {
        return $this->header;
    }

    function setBody($body) {
        $this->body = $body;
    }

    function getBody() {
        return $this->body;
    }

    private function setRecipient($recipient) {
        array_push($this->recipients, "RCPT TO:<$recipient>");
    }

}
