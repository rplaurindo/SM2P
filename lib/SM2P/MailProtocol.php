<?php

namespace SM2P;

class MailProtocol {

    private $login;
    
    private $password;
    
    function definesPassword($password) {
        $this->password = $password;
    }
    
    protected function getLogin() {
        return $this->login;
    }
    
    protected function getPassword() {
        return $this->password;
    }
    
    protected function definesLogin($login) {
        $this->login = $login;
    }

}
