<?php

namespace SM2P;

use
    Exception
;

// Receiver
class StreamingReceiver {

    protected $streaming;
    
    private $server;
    
    private $responseLines;
    
    private $timeout = 10;

    function __construct($server, $port, array $options = []) {
        $this->resolvesOptions($options);
        
        try {
            $errNum = null;
            $errStr = null;
            $this->server = $server;
            
            $this->streaming = @stream_socket_client("$server:$port", $errNum, $errStr, $this->timeout);
            
            if (isset($errNum)) {
                throw new Exception($errStr);
            }
            
            $this->getResponse();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
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
        fputs($this->streaming, $command);
        
        if (!array_key_exists('hasManyLines', $options)) {
            $options['hasManyLines'] = false;
        }
        
        if ($options['hasManyLines']) {
            $this->eachLine();
//             eachLine already calls getResponse() to defines lines
            return $this->responseLines;
        }
        
        return $this->getResponse();
    }

    function encryptConnection($cryptoType = STREAM_CRYPTO_METHOD_TLS_CLIENT) {
        return stream_socket_enable_crypto($this->streaming, true, $cryptoType);
    }

    function closesConnection() {
        fclose($this->streaming);
    }

    function getLastResponseCode() {
        if (strlen($this->responseLines) >= 4 && $this->responseLines[3] == " ") {
            return substr($this->responseLines, 0, 3);
        }
        
        return null;
    }

    function getServer() {
        return $this->server;
    }

    private function resolvesOptions(array $options) {
        if (isset($options['timeout'])) {
            $this->timeout = $options['timeout'];
        }
    }

    private function getResponse() {
        $this->responseLines = fgets($this->streaming);
        return $this->responseLines;
    }

    private function eachLine() {
        $lines = '';

        do {
            stream_set_timeout($this->streaming, 1);
            $serverResponse = $this->getResponse();
            $lines .= $serverResponse;
        } while ($serverResponse !== false);

        $this->responseLines = $lines;
    }

}
