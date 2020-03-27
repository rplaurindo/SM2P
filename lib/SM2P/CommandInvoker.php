<?php

namespace SM2P;

use
    SM2P\Commands\Mail,
    SM2P\Commands\SMTP,
    SM2P\Commands\Streaming;

class CommandInvoker {

    private $receiver;

    function __construct(MailProtocolReceiver $receiver) {
        $this->receiver = $receiver;
    }

    function send($command) {

        switch($command) {
            case 'EHLO':
                $command = new SMTP\EHLOCommand($this->receiver);
                break;
            case 'STARTTLS':
                $command = new Mail\StartTLSCommand($this->receiver);
                break;
            case 'AUTH LOGIN':
                $command = new Mail\AuthLoginCommand($this->receiver);
                break;
            case 'PASSWORD':
                $command = new Mail\PasswordCommand($this->receiver);
                break;
            case 'SENDER':
                $command = new SMTP\SenderCommand($this->receiver);
                break;
            case 'RECIPIENTS':
                $command = new SMTP\RecipientsCommand($this->receiver);
                break;
            case 'HEADER':
                $command = new SMTP\HeaderCommand($this->receiver);
                break;
            case 'BODY':
                $command = new SMTP\BodyCommand($this->receiver);
                break;
            case 'QUIT':
                $command = new Streaming\QUITCommand($this->receiver);
        }

        return $command->execute();
    }

}
