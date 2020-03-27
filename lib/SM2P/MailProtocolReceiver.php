<?php

namespace SM2P;

use Exception;

class MailProtocolReceiver {
    
    protected $lines;

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
            
//             $stream_context = stream_context_create(
//                 [
//                     'ssl' => [
//                         'allow_self_signed' => true,
//                     ]
                    
//                 ]
//             );
            
//             $this->socket = @fsockopen($server, $port, $errNum, $errStr, $this->timeout);

//             $this->socket = @fsockopen("tls://$server", $port, $errNum, $errStr, $this->timeout);
            
//             $this->socket = @stream_socket_client("$server:$port", $errNum, $errStr, $this->timeout, STREAM_CLIENT_CONNECT, $stream_context);

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
            fputs($this->socket, $command . PHP_EOL);
            echo "\n$command". PHP_EOL;
        } else {
            echo "\n$command";
            fputs($this->socket, $command);
        }
        
        if (!array_key_exists('hasManyLines', $options)) {
            $hasManyLines = false;
        } else {
            $hasManyLines = $options['hasManyLines'];
        }
        
        if ($hasManyLines) {
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
        fclose($this->socket);
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
