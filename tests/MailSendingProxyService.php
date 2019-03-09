<?php

namespace Services;

use SM2P\Clients\SMTP;

class MailSendingProxyService {

    private $smtp;

    function __construct() {
        $this->smtp = new SMTP\SeSeg('suportesistemas@pmerj.rj.gov.br');
    }

    function addTo($recipient, $name = '') {
        $this->smtp->addTo($recipient, $name);
    }

    function setSubject($subject) {
        $this->smtp->setSubject($subject);
    }

    function setBody($body) {
        $this->smtp->setBody($body);
    }

    function send() {
        return $this->smtp->send();
    }

}
