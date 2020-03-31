<?php

namespace SM2P;

class SMTP extends MailProtocol {

    private $sender;
    
    // private $cc;
    
    // private $bcc;

    private $body;
    
    private $header;

    private $recipients = [];

    function __construct($sender, array $options = []) {
        $this->recipients = [];
        $this->header = [
            'To' => '',
            'Content-Type' => 'text/html; charset=UTF-8'
        ];

        $this->definesLogin($sender);
        $this->sender = "MAIL FROM:<$sender>";
        $this->definesFrom($sender);
    }

    function addsTo($recipient, $name = '') {
        if (empty($this->header['To'])) {
            $this->header['To'] .= ($name ? "$name " : "") . "<$recipient>";
        } else {
            $this->header['To'] .= ', ' . ($name ? "$name " : "") . "<$recipient>";
        }
        
        $this->definesRecipient($recipient);
    }

    function definesSubject($subject) {
        $this->header['Subject'] = $subject;
    }
    
    function definesBody($body) {
        $this->body = $body;
    }

    protected function getSender() {
        return $this->sender;
    }

    protected function getRecipients() {
        return $this->recipients;
    }

    protected function getHeader() {
        return $this->header;
    }

    protected function getBody() {
        return $this->body;
    }
    
    protected function definesFrom($sender, $name = '') {
        $this->header['From'] = ($name ? $name : "") . "<$sender>";
    }

    private function definesRecipient($recipient) {
        $this->recipients[]= "RCPT TO:<$recipient>";
    }

}
