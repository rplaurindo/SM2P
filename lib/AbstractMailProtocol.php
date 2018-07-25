<?php

namespace SM2P;

abstract class AbstractMailProtocol extends Streaming {

    private $login;
    private $password;

    function __construct($server, $port, array $options = []) {
        parent::__construct($server, $port, $options);
    }

    function setLogin($login) {
        $this->login = $login;
    }

    function setPassword($password) {
        $this->password = $password;
    }

}
