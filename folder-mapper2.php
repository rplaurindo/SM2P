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

BFSresearch($arr, function ($value, $key, $trace) {
    echo 'key: ' . $key . "\n";
    echo 'value: ' . json_encode($value) . "\n";
    echo 'path: ' . json_encode($trace) . "\n";
    echo "------------------------\n";
});
