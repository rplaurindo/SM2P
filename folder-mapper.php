<?php

$arr = array(
    'a' => array(
        'a.1' => array(
            'a.1.1' => 'a.1.1',
            'a.1.2' => 'a.1.2'
        ),
        'a.2' => array(
            'a.2.1' => 'a.2.1',
            'a.2.2' => 'a.2.2'
        )
    ),
    'b' => array(
        'b.1' => array(
            'b.1.1' => 'b.1.1',
            'b.1.2' => 'b.1.2'
        ),
        'b.2' => 'b.2'
    )
);

abstract class AbstractGraphSearch implements Iterator {

    private $queue;
    private $nextQueue;
    private $i;
    private $lastIndex;
    private $keys;
//    private $initialCollection;

    function __construct(array $collection) {
        $this->queue = $collection;
        $this->nextQueue = array();
    }

//    abstract function isLeafNode();
    abstract function isLeafNode($current);
    abstract function getChildNodes($current);
//    abstract function getChildNodes();
//    abstract function nextCollection();

    function key() {
        return $this->keys[$this->i];
    }

    function next() {
//        echo 'next' . "\n";
        $key = $this->keys[$this->i++];
        $item = $this->queue[$key];

        if (!$this->valid()) {
            $this->resetQueue();
            echo 'queue after reset: ' . json_encode($this->queue) . "\n" ;
//            só pode fazer rewind se houver algo em queue
            if (count($this->queue)) {
                // $this->rewind();
            }
        }

        return $item;
    }

    function current() {
//        echo 'current' . "\n";

        if ($this->valid()) {
            $current = $this->queue[$this->key()];
        } else {
            $current = $this->last();
        }

        if (!$this->isLeafNode($current)) {
            $this->putOnNextQueue($this->getChildNodes($current));
        }

        return $current;
    }

    function rewind() {
        $this->i = 0;
        $this->keys = array_keys($this->queue);
        $this->lastIndex = count($this->keys) - 1;
    }

    function valid() {
        return $this->i <= $this->lastIndex;
    }

    private function last() {
        return $this->queue[$this->keys[$this->lastIndex]];
    }

    private function putOnNextQueue($collection) {
        array_push($this->nextQueue, $collection);
//        echo 'next queue: ' . json_encode($this->nextQueue) . "\n";
    }

    private function resetQueue() {
        $nextQueue = new ArrayObject($this->nextQueue);
        $nextQueueCopy = $nextQueue->getArrayCopy();
        if (count($nextQueueCopy)) {
            $this->queue = $nextQueueCopy;
        } else {
            $this->queue = array();
        }
//        echo 'next queue: ' . json_encode($this->queue) . "\n";
        $this->nextQueue = array();
    }

}

class BFSArray extends AbstractGraphSearch {

    function __construct(array $collection) {
        parent::__construct($collection);
    }

//    function isLeafNode() {
    function isLeafNode($current) {
//        echo 'next queue: ' . json_encode(parent::current()) . "\n";
//        if (is_array(parent::current())) {
        if (is_array($current)) {
            return false;
        }

        return true;
    }

//    function getChildNodes() {
    function getChildNodes($current) {
//        use glob here
        return $current;
    }

}

$bfsArray = new BFSArray($arr);

foreach ($bfsArray as $key => $value) {
    echo 'key: ' . $key . "\n";
    echo 'value: ' . json_encode($value) . "\n";
//    echo 'is valid: ' . $bfsArray->valid() . "\n";
//    echo 'is valid: ' . $bfsArray->valid() . "\n";
}

//echo 'current: ' . json_encode($bfsArray->current()) . "\n";
//echo 'current: ' . json_encode($bfsArray->next()) . "\n";

//echo json_encode($bfsArray->next()) . "\n";
//echo json_encode($bfsArray->current()) . "\n";
//echo json_encode($bfsArray->next()) . "\n";

function BFSResearch(array $collection, $callback) {

    global $queue;
    global $putOnQueue;
    global $cleanQueue;

    $queue = array(
        array(
            'trace' => [],
            'collection' => $collection
        )
    );

    $cleanQueue = function () {
        global $queue;
        $queue = array();
    };

    $putOnQueue = function ($map) {
        global $queue;
        array_push($queue, $map);
    };

    $queueBlock = function (array $map, $callback) {
        $iteratorBlock = function ($value, $key, $trace, $callback) {
            global $putOnQueue;

            array_push($trace, $key);
            $callback($value, $key, $trace);

//            to break use "return ...;"
//            usar o glob aqui ou na iteração
            if (is_array($value)) {
                $putOnQueue(array(
                    'trace' => $trace,
                    'collection' => $value
                ));
            }
        };

        foreach ($map['collection'] as $key => $value) {
            $iteratorBlock($value, $key, $map['trace'], $callback);
        }
    };

    while (true) {
        if (count($queue)) {
            $queueArrayObject = new ArrayObject($queue);
            $cleanQueue();
            foreach ($queueArrayObject->getArrayCopy() as $map) {
                $queueBlock($map, $callback);
            }
        } else {
            break;
        }
    }

};

//$arr = [1, 2, 3, [4, 5, 6, [7, 8, 9]]];

//BFSresearch($arr, function ($value, $key, $trace) {
//    echo 'key: ' . $key . "\n";
//    echo 'value: ' . json_encode($value) . "\n";
//    echo 'path: ' . json_encode($trace) . "\n";
//    echo "------------------------\n";
//});
