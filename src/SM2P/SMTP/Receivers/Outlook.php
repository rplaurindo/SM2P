<?php

namespace SM2P\SMTP\Receivers;

use
    SM2P\CommandInvoker,
    SM2P\SMTP\Receiver;

// Client
class Outlook extends Receiver {

    private $commandInvoker;

    function __construct($sender, array $options = []) {
//         echo "\n$sender\n";
        parent::__construct('smtp.office365.com', 587, $sender, $options);

        $this->commandInvoker = new CommandInvoker($this);
    }

    function send() {
        $sent = false;
        
        echo $this->commandInvoker->send('EHLO');
        
        echo $this->commandInvoker->send('STARTTLS');
        
//         the extension openssl should be enabled, otherwise that will give "timeout"
        if ($this->encryptConnection()) {
            echo "The connection stream has been encrypted.\n";
        }
        
        echo $this->commandInvoker->send('EHLO');

        echo $this->commandInvoker->send('AUTH LOGIN');
        echo $this->commandInvoker->send('PASSWORD');
        
        echo $this->commandInvoker->send('SENDER');
        echo $this->commandInvoker->send('RECIPIENTS');
        
//         defines To, Content-Type and Subject
        echo $this->commandInvoker->send('HEADER');

        echo $this->commandInvoker->send('BODY');
        
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

// https://www.openssl.org/docs/man1.0.2/man1/s_client.html

// $ openssl s_client -starttls smtp -connect <smtp.address>:587 <-ign_eof | <<EOF>
// this command is necessary once cause' the connection starts already encrypted
// > EHLO smtp.office365.com

// encoded as base64
// > AUTH LOGIN login==
// encoded as base64
// > password

// encoded as base64
// > AUTH PLAIN \0login\0password

// > MAIL FROM:<>
// > RCPT TO:<>
// > DATA
// > To: <>
// > Content-Type: text/html; charset=UTF-8
// > Subject: Subject test
// >
// > Test of body
// > .
// > QUIT

// > [EOF]

// testar chamar o STARTTLS no mei da parada
