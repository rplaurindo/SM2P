<?php

namespace SM2P\SMTP\Receivers;

use
    SM2P,
    SM2P\SMTP;

// Client
class Outlook extends SMTP\Receiver {

    private $commandInvoker;

    function __construct($sender, array $options = []) {
//         echo "\n$sender\n";
        parent::__construct('smtp.office365.com', 587, $sender, $options);
//         parent::__construct('smtp.gmail.com', 587, $sender, $options);

        $this->commandInvoker = new SM2P\CommandInvoker($this);
    }

    function send() {
        $sent = false;
        
        $this->commandInvoker->send('EHLO');
        
        $this->commandInvoker->send('STARTTLS');
        
//         the extension openssl should be enabled, otherwise that will give "timeout"
        if ($this->encryptConnection()) {
            echo "The connection stream has been encrypted.\n";
        }
        
//         echo "\n$this->lines";

//         desnecessário
//         $this->commandInvoker->send('EHLO');

        $this->commandInvoker->send('LOGIN');
        $this->commandInvoker->send('PASSWORD');
        
        $this->commandInvoker->send('SENDER');
        $this->commandInvoker->send('RECIPIENTS');
        
//         defines To, Content-Type and Subject
        $this->commandInvoker->send('HEADER');

        $this->commandInvoker->send('BODY');
        
//         echo "\nresponse: {$this->getResponse()}";
        
        if ($this->getResponseCode() === '250') {
            $sent = true;
        }

        $this->commandInvoker->send('QUIT');

        $this->closesConnection();

        if ($sent) {
            return true;
        }
        return false;
    }

}

// $ openssl s_client -starttls smtp -connect smtp.office365.com:587 -crlf <<EOF

// > EHLO smtp.office365.com
// > AUTH LOGIN login==
// > password=
// > MAIL FROM:<>
// > RCPT TO:<>
// > DATA
// > To: <>
// > Content-Type: text/html; charset=UTF-8
// > Subject: Test of subject
// >
// > Test of body
// > .
// > QUIT
// > EOF
