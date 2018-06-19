<?php

namespace SM2P;

abstract class AbstractMailProtocol {

    private $login;
    private $password;

    private $socket;
    private $lines;
    private $bufferSize = 120;
    private $timeout = 10;

    function __construct($server, $port, array $options = []) {
        $this->resolveOptions($options);
        $this->socket = @fsockopen($server, $port, $errNum, $errStr, $this->timeout);
        $this->eachLine();

        if ($errNum) {
            throw new Exception("$errNum error: $errStr." );
        }
    }

    protected function sendCommand($command) {
    	fputs($this->socket, $command . PHP_EOL, $this->bufferSize);
        $this->eachLine();
        return $this->lines;
    }

    function sendSTARTTLS() {
    	return $this->sendCommand('STARTTLS');
    }

    function encryptConnection($cryptoType = STREAM_CRYPTO_METHOD_TLS_CLIENT) {
        stream_socket_enable_crypto($this->socket, true, $cryptoType);
        $this->eachLine();
    }

    function setLogin($login) {
        $this->login = $login;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function sendLogin() {
        return $this->sendCommand('AUTH LOGIN ' . base64_encode($this->login));
    }

    function sendPassword() {
        return $this->sendCommand(base64_encode($this->password));
    }

    // function sendPlainAuth($user, $password) {
    // 	return $this->sendCommand('AUTH PLAIN ' .
    //         base64_encode("\\0$user\\0$password"));
    // }

    function sendQUIT() {
        fputs($this->socket, 'QUIT' . PHP_EOL, $this->bufferSize);
        return $this->getResponse();
    }

    function closeConnection() {
        fclose($this->socket);
    }

    private function getResponse() {
        $this->lines = fgets($this->socket, $this->bufferSize);
        return $this->lines;
    }

    private function resolveOptions(array $options) {
        if (isset($options['bufferSize'])) {
            $this->bufferSize = $options['bufferSize'];
        }

        if (isset($options['timeout'])) {
            $this->timeout = $options['timeout'];
        }
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

    function getResponseCode() {
        if (strlen($this->lines) >= 4 && $this->lines[3] == " ") {
            return substr($this->lines, 0, 3);
        }
        return null;
    }

}
