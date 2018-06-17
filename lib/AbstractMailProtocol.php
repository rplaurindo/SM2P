<?php

abstract class AbstractMailProtocol {

    private $login;
    private $password;

    private $socket;
    private $bufferSize = 120;
    private $timeout = 10;

    function __construct($server, $port, array $options = []) {
        $this->resolveOptions($options);
        $this->socket = @fsockopen($server, $port, $errNum, $errStr, $this->timeout);
        $this->eachLine();

        if ($errNum) {
            throw new Exception("$errNum error: $errstr." );
        }
    }

    function sendCommand($command) {
    	fputs($this->socket, $command . PHP_EOL, $this->bufferSize);
        return $this->getResponseCode();
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
        return $this->extractResponseCodeFrom($this->getResponse());
    }

    function closeConnection() {
        fclose($this->socket);
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

        return $lines;
    }

    private function extractResponseCodeFrom($response) {
        return substr($response, 0, 3);
    }

    private function getResponseCode() {
        return $this->extractResponseCodeFrom($this->eachLine());
    }

    private function getResponse() {
        return fgets($this->socket, $this->bufferSize);
    }

}
