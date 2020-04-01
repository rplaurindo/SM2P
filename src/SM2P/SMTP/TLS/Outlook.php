<?php

namespace SM2P\Clients\SMTP\TLS;

use
    SM2P\CommandInvoker,
    SM2P\Commands\Mail,
    SM2P\Commands\SMTP\BodyCommand,
    SM2P\Commands\SMTP\EHLOCommand,
    SM2P\Commands\SMTP\HeaderCommand,
    SM2P\Commands\SMTP\RecipientsCommand,
    SM2P\Commands\SMTP\SenderCommand,
    SM2P\Commands\Streaming\QuitCommand,
    SM2P\SMTP,
    SM2P\CommandReceiver
;

// Client
class Outlook extends SMTP {
    
    private $streaming;

    private $commandInvoker;

    function __construct($sender, array $options = []) {
        parent::__construct($sender, $options);
        $this->streaming = new CommandReceiver('smtp.office365.com', 587, $options);
        
        $this->commandInvoker = new CommandInvoker();
    }

    function send() {
        $this->commandInvoker->addsCommand(new EHLOCommand($this->streaming));
        
        $this->commandInvoker->addsCommand(new Mail\StartTLSCommand($this->streaming));

        $this->commandInvoker->execute(function($response) {
             echo $response;
        });
        
//         the extension openssl should be enabled, otherwise that will give "timeout"
        if ($this->streaming->encryptConnection()) {
            echo "The connection stream has been encrypted.\n";
        }
        
        $this->commandInvoker->addsCommand(new EHLOCommand($this->streaming));

        $this->commandInvoker->addsCommand(new Mail\AuthLoginCommand($this->streaming, $this->getLogin()));
        $this->commandInvoker->addsCommand(new Mail\PasswordCommand($this->streaming, $this->getPassword()));
        
        $this->commandInvoker->addsCommand(new SenderCommand($this->streaming, $this->getSender()));
        $this->commandInvoker->addsCommand(new RecipientsCommand($this->streaming, $this->getRecipients()));
        
//         defines To, Content-Type and Subject
        $this->commandInvoker->addsCommand(new HeaderCommand($this->streaming, $this->getHeader()));

        $this->commandInvoker->addsCommand(new BodyCommand($this->streaming, $this->getBody()));

        $this->commandInvoker->addsCommand(new QuitCommand($this->streaming));

        $this->commandInvoker->execute(function($response) {
             echo $response;
        });

        $this->streaming->closesConnection();
        
        if ($this->streaming->getResponseCode(count($this->streaming->getResponses()) - 2) === '250') {
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
