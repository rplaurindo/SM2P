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

//$arr = [1, 2, 3, [4, 5, 6, [7, 8, 9]]];

abstract class AbstractGraphSearch implements Iterator {

    private $queue;
    private $nextQueue;
    private $i;
    private $lastIndex;
    private $keys;
    private $ordered2Stop;

    function __construct(array $collection) {
        $this->ordered2Stop = false;
        $this->queue = $collection;
        $this->nextQueue = array();
    }

    abstract function isLeafNode($current);
    abstract function getChildNodes($current);

    function key() {
        return $this->keys[$this->i];
    }

    function rewind() {
        $this->keys = array_keys($this->queue);
        if (!$this->ordered2Stop) {
            $this->i = 0;
            $this->lastIndex = count($this->keys) - 1;
        }
    }

    function valid() {
        return $this->i <= $this->lastIndex;
    }

    function current() {

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

    function next() {
        $this->i++;

        if (!$this->valid()) {
            if (!$this->ordered2Stop && $this->hasNextQueue()) {
                $this->rewind();
            }
        } else {
            $key = $this->keys[$this->i];
            $item = $this->queue[$key];
            return $item;
        }
    }

    function stop() {
        $this->ordered2Stop = true;
        $this->i = $this->lastIndex + 1;
    }

    private function putOnNextQueue($collection) {
        $keys = array_keys($collection);

        foreach ($keys as $key) {
            $this->nextQueue[$key] = $collection[$key];
        }
    }

    private function last() {
        return $this->queue[$this->keys[$this->lastIndex]];
    }

    private function hasNextQueue() {
        $nextQueue = new ArrayObject($this->nextQueue);
        $nextQueueCopy = $nextQueue->getArrayCopy();
        if (count($nextQueueCopy)) {
            $this->queue = $nextQueueCopy;
            $this->nextQueue = array();
            return true;
        }

        $this->queue = array();

        return false;
    }

}

class BFSArray extends AbstractGraphSearch {

    function __construct(array $collection) {
        parent::__construct($collection);
    }

    function isLeafNode($current) {
        if (is_array($current)) {
            return false;
        }

        return true;
    }

    function getChildNodes($current) {
        return $current;
    }

}

$bfsArray = new BFSArray($arr);

foreach ($bfsArray as $key => $value) {
    echo 'key: ' . $key . "\n";
    echo 'value: ' . json_encode($value) . "\n";
//    $bfsArray->stop();
//    break;
}
