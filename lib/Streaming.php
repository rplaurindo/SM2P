<?php

namespace SM2P;

use Exception;

class Streaming extends AbstractElement {

    protected $socket;
    private $server;
    private $lines;
    private $timeout = 10;

    function __construct($server, $port, array $options = []) {
        $this->resolveOptions($options);

        try {
            $this->socket = @fsockopen($server, $port, $errNum, $errStr, $this->timeout);
            if ($errNum) {
                throw new Exception($errStr);
            }
            $this->server = $server;
            $this->getResponse();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function accept(AbstractStreamingVisitor $visitor) {
        $visitor->visit($this);
    }

//    action
    function sendCommand($command, $hasManyLines = false) {
        fputs($this->socket, $command . PHP_EOL);
        if ($hasManyLines) {
            $this->eachLine();
            return $this->lines;
        }
        return $this->getResponse();
    }

    function encryptConnection($cryptoType = STREAM_CRYPTO_METHOD_TLS_CLIENT) {
        stream_socket_enable_crypto($this->socket, true, $cryptoType);
        $this->getResponse();
    }

    function closeConnection() {
        fclose($this->socket);
    }

    function getResponseCode() {
        if (strlen($this->lines) >= 4 && $this->lines[3] == " ") {
            return substr($this->lines, 0, 3);
        }
        return null;
    }

    function getServer() {
        return $this->server;
    }

    private function resolveOptions(array $options) {
        if (isset($options['timeout'])) {
            $this->timeout = $options['timeout'];
        }
    }

    protected function getResponse() {
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
