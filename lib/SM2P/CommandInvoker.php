<?php

namespace SM2P;

// Invoker
class CommandInvoker {
    
    private $commands;
    
    private $history;
    
    function __construct() {
        $this->commands = [];
        $this->history = [];
        $this->executedCommandHistory = [];
    }

    function addsCommand(AbstractMailProtocolCommand $command) {
        $this->commands[]= $command;
        $this->history[]= $command;
    }
    
    function execute($callback = null) {

       $lines = '';
//        pode ser testado se houve sucesso no comandamento, caso não tenha havido sair do foreach e o comando que falhou será guardado,
//        caso em que poderá ser tentado executá-lo novamente
       if(isset($callback)) {
           foreach($this->commands as $command) {
               $this->executedCommandHistory[]= $command;
               
               $responseLine = $command->execute();
               
               $lines .= $responseLine;
               
               $callback($responseLine);
           }
       } else {
           foreach($this->commands as $command) {
               $lines .= $command->execute();
           }
       }
        
        $this->commands = [];
        
        return $lines;
    }

}
