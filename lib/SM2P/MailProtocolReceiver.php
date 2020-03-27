<?php

namespace SM2P;

use Exception;

class MailProtocolReceiver {
    
    private $lines;

    private $login;
    
    private $password;

    private $socket;
    
    private $server;
    
    private $timeout = 10;

    function __construct($server, $port, array $options = []) {
        $this->resolvesOptions($options);

        try {
            $errNum = null;
            $errStr = null;
            $this->server = $server;

            $this->socket = @stream_socket_client("$server:$port", $errNum, $errStr, $this->timeout);
            
            if (isset($errNum)) {
                throw new Exception($errStr);
            }

            $this->getResponse();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    function definesPassword($password) {
        $this->password = $password;
    }
    
    function getLogin() {
        return $this->login;
    }
    
    function getPassword() {
        return $this->password;
    }

//    action
    function sendCommand($command, array $options = []) {
        
        if (!array_key_exists('appendsEOL', $options)) {
            $options['appendsEOL'] = true;
        }
        
        if ($options['appendsEOL']) {
//             EOL = End Of Line
            $command .= PHP_EOL;
        }
        
//         echo "\n$command";
        fputs($this->socket, $command);
        
        if (!array_key_exists('hasManyLines', $options)) {
            $options['hasManyLines'] = false;
        }
        
        if ($options['hasManyLines']) {
            $this->eachLine();
//             eachLine already calls getResponse() to defines lines
            return $this->lines;
        }

        return $this->getResponse();
    }
    
    function getServer() {
        return $this->server;
    }
    
    protected function definesLogin($login) {
        $this->login = $login;
    }
    
    protected function encryptConnection($cryptoType = STREAM_CRYPTO_METHOD_TLS_CLIENT) {
        return stream_socket_enable_crypto($this->socket, true, $cryptoType);
    }
    
    protected function closesConnection() {
        return fclose($this->socket);
    }
    
    protected function getResponseCode() {
        if (strlen($this->lines) >= 4 && $this->lines[3] == " ") {
            return substr($this->lines, 0, 3);
        }
        return null;
    }

    private function resolvesOptions(array $options) {
        if (isset($options['timeout'])) {
            $this->timeout = $options['timeout'];
        }
    }

    private function getResponse() {
        $this->lines = fgets($this->socket);
        return $this->lines;
    }

    private function eachLine() {
        $lines = '';

        do {
            stream_set_timeout($this->socket, 1);
            $serverResponse = $this->getResponse();
            $lines .= $serverResponse;
        } while ($serverResponse !== false);

        $this->lines = $lines;
    }

}
