<?php

namespace SM2P;

use
    Exception,
    ArrayObject
;

// Receiver
class StreamingReceiver {

    protected $streaming;
    
//     private $command;

    private $responses;
    
    private $server;
    
    private $responseLines;
    
    private $timeout = 10;

    function __construct($server, $port, array $options = []) {
        $this->server = $server;
        $this->resolvesOptions($options);
        
        $this->responses = new ArrayObject();
        
        try {
            $errNum = null;
            $errStr = null;
            
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
        
        $this->command = $command;
        
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

    function getResponseCode($index) {
        $responses = $this->responses->getArrayCopy()[0];
        if (array_key_exists($index, $responses)) {
            $responseLine = $responses[$index];
            if (strlen($responseLine) >= 4) {
                return substr($responseLine, 0, 3);
            }
        }
        
        return null;
    }
    
    function getResponses() {
        return $this->responses->getArrayCopy()[0];
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
        $response = fgets($this->streaming);
        
        if (isset($response) && gettype($response) === 'string' && strlen($response) > 1) {
//             removes EOL
            $resolvedResponse = substr($response, 0, strlen($response) - 1);
            
            if ($this->responses->offsetExists(0)) {
                $responses = $this->responses->offsetGet(0);
                $this->responses->offsetSet(0, array_merge($responses, explode("\n", $resolvedResponse)));
            } else {
                $this->responses->append(explode("\n", $resolvedResponse));
            }
            
        }
        
        return $response;
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
