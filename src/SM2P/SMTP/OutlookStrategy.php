<?php

namespace SM2P\SMTP;

use SM2P\AbstractSMTP;

class OutlookStrategy extends AbstractSMTP {

    function __construct($sender, array $options = []) {
        parent::__construct('smtp-mail.outlook.com', 587, $sender, $options);
    }

    function send() {
        $sent = false;

        $this->sendEHLO();

        $this->sendSTARTTLS();
        $this->encryptConnection();
        $this->sendEHLO();
        $this->sendLogin();
        $this->sendPassword();
        $this->sendSender();
        $this->sendRecipients();
        $this->sendHeader();
        $this->sendBody();
        if ($this->getResponseCode() == '250') {
            $sent = true;
        }
        $this->sendQUIT();
        $this->closeConnection();

        if ($sent) {
            return true;
        }
        return false;
    }

}
