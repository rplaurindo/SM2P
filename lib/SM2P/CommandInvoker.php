<?php

namespace SM2P;

class CommandInvoker {
    
    private $commands;
    
    private $history;
    
    function __construct() {
        $this->commands = [];
        $this->history = [];
    }

    function addsCommand(AbstractMailProtocolCommand $command) {
        $this->commands[]= $command;
        $this->history[]= $command;
    }
    
    function execute() {

       $lines = '';
       
        foreach($this->commands as $command) {
            $lines .= $command->execute();
        }
        
        $this->commands = [];
        
        return $lines;
    }

}
