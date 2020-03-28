<?php

namespace SM2P\SMTP\Clients\TLS;

use
    SM2P\CommandInvoker,
    SM2P\Commands\Mail,
    SM2P\Commands\SMTP,
    SM2P\Commands\Streaming,
    SM2P\SMTP\Receiver;

// Client
class Outlook {
    
    private $receiver;

    private $commandInvoker;

    function __construct($sender, array $options = []) {
        $this->receiver = new Receiver('smtp.office365.com', 587, $sender, $options);
        
        $this->commandInvoker = new CommandInvoker();
    }
    
    function definesPassword($password) {
        $this->receiver->definesPassword($password);
    }
    
    function addsTo($recipient, $name='') {
        $this->receiver->addsTo($recipient, $name);
    }
    
    function definesSubject($subject) {
        $this->receiver->definesSubject($subject);
    }
    
    function definesBody($body) {
        $this->receiver->definesBody($body);
    }

    function send() {
        $sent = false;
        
        $this->commandInvoker->addsCommand(new SMTP\EHLOCommand($this->receiver));
        
        $this->commandInvoker->addsCommand(new Mail\StartTLSCommand($this->receiver));

        echo $this->commandInvoker->execute();
        
//         the extension openssl should be enabled, otherwise that will give "timeout"
        if ($this->receiver->encryptConnection()) {
            echo "The connection stream has been encrypted.\n";
        }
        
        $this->commandInvoker->addsCommand(new SMTP\EHLOCommand($this->receiver));

        $this->commandInvoker->addsCommand(new Mail\AuthLoginCommand($this->receiver));
        $this->commandInvoker->addsCommand(new Mail\PasswordCommand($this->receiver));
        
        $this->commandInvoker->addsCommand(new SMTP\SenderCommand($this->receiver));
        $this->commandInvoker->addsCommand(new SMTP\RecipientsCommand($this->receiver));
        
//         defines To, Content-Type and Subject
        $this->commandInvoker->addsCommand(new SMTP\HeaderCommand($this->receiver));

        $this->commandInvoker->addsCommand(new SMTP\BodyCommand($this->receiver));
        
        if ($this->receiver->getLastResponseCode() === '250') {
            $sent = true;
        }

        $this->commandInvoker->addsCommand(new Streaming\QuitCommand($this->receiver));

        echo $this->commandInvoker->execute();

        $this->receiver->closesConnection();

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
